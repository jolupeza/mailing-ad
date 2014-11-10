<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaigns extends MY_Controller
{

	/**
	* Cargar la vista index
	*
	* @access public
	*/
	public function display()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('view_campaigns')) {
			show_error('¡Acceso restringido!');
		}

		$this->template->set('_title', $this->lang->line('cms_general_title_campaigns'));
		$this->template->set('_active', 'campaigns');
		$this->template->add_js('view', 'campaigns/script');
		$this->template->render('campaigns/index');
	}

	/**
	* Método que vía ajax obtiene y muestra las campañas
	*
	* @access public
	* @param  $status 		Indicamos el status (confirmado o no confirmado)
	* @param  $sort_by 		Indicamos a través de que campo se ordena
	* @param  $sort_order 	Indicamos si se ordena ascendente o descendentemente
	* @param  $search 		Indicamos si se pasa un parámetro de búsqueda
	* @param  $offset 		Indicamos desde que registro obtenemos la lista
	*/
	public function displayAjax($status = 'all', $sort_by = 'id', $sort_order = 'desc', $search = 'all', $offset = 0)
	{
		$limit = 10;
		$total = 0;
		$this->load->model('Campaigns_model');

		$result = $this->Campaigns_model->getAll($status, $limit, $offset, $sort_by, $sort_order, $search);
		$campaigns = $result['data'];
		$total = $result['num_rows'];

		if (count($campaigns) > 0) {
			$this->template->set('_campaigns', $campaigns);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/campaigns/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 8;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_num_total', $this->Campaigns_model->countRows(array(0, 1)));
		$this->template->set('_active', $this->Campaigns_model->countRows(array(1)));
		$this->template->set('_no_active', $this->Campaigns_model->countRows(array(0)));
		$this->template->set('_trush', $this->Campaigns_model->countRows(array(2)));

		$this->template->set('_status', $status);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->renderAjax('campaigns/displayAjax');
	}

	/**
	* Método para añadir una lista de correo
	*
	* @access public
	*/
	public function add()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('create_campaigns')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Campaigns_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required|callback_name_check'
				),
				array(
					'field'		=>	'email_sender',
					'label'		=>	'lang:cms_general_label_email_sender',
					'rules'		=>	'trim|valid_email'
				),
				array(
					'field'		=>	'email_sender',
					'label'		=>	'lang:cms_general_label_email_reply',
					'rules'		=>	'trim|valid_email'
				),
				array(
					'field'		=>	'subject',
					'label'		=>	'lang:cms_general_label_subject',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'news',
					'label'		=>	'lang:cms_general_title_newsletters',
					'rules'		=>	'callback_select_check'
				),
				array(
					'field'		=>	'lists',
					'label'		=>	'lang:cms_general_title_lists',
					'rules'		=>	'required'
				)
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->helper('functions');

				$name = $this->input->post('name');
				$sender = $this->input->post('sender');
				$email_sender = $this->input->post('email_sender');
				$email_reply = $this->input->post('email_reply');
				$subject = $this->input->post('subject');
				$submit_at = $this->input->post('submit_at');
				$news = $this->input->post('news');
				$lists = $this->input->post('lists');
				$list = $this->input->post('list');
				$status = $this->input->post('status');

				if ($lists == '2') {
					if (!$list) {
						$this->template->set_flash_message(array('error' => $this->lang->line('error_select_list')));
						redirect('admin/campaigns/add');
					}
				}

				// Generar id para mailgun
				$code = randomString(5);

				$mailgun = self::getMailgun();
				$result = $mailgun->post($this->config->item('mailgun_domain') . '/campaigns', array(
					'name'			=>	$name,
					'id'			=>	$code
				))->http_response_body;

				if ($result->message == 'Campaign created') {
					$data = array(
						'name'			=>	$name,
						'mailgun_id'	=>	$code,
						'sender'		=>	(isset($sender) && !empty($sender)) ? $sender : $this->config->item('cms_sender'),
						'email_sender'	=>	(isset($email_sender) && !empty($email_sender)) ? $email_sender : $this->config->item('cms_email_sender'),
						'email_reply'	=>	(isset($email_reply) && !empty($email_reply)) ? $email_reply : $this->config->item('cms_email_reply'),
						'subject'		=>	$subject,
						'newsletter_id'	=>	$news,
						'lists_opt'		=>	$lists,
						'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
						'submit_at'		=>	$submit_at,
						'created'		=>	$this->user->id,
						'created_at'	=>	date('Y-m-d H:i:s')
					);

					$last_id = $this->Campaigns_model->add(NULL, $data);

					// Agregar las listas de email a las que pertenece la campaña
					if ($lists == '1') {
						$rows = $this->Campaigns_model->get('mailing_lists', 'id', array('status' => 1));
						foreach ($rows as $row) {
							$this->Campaigns_model->add('campaign_lists', array(
								'campaign_id'		=>	$last_id,
								'list_id'			=>	$row->id,
								'created'			=>	$this->user->id,
								'created_at'		=>	date('Y-m-d H:i:s')
							));
						}
					} elseif ($lists == '2') {
						foreach ($list as $row) {
							$this->Campaigns_model->add('campaign_lists', array(
								'campaign_id'		=>	$last_id,
								'list_id'			=>	$row,
								'created'			=>	$this->user->id,
								'created_at'		=>	date('Y-m-d H:i:s')
							));
						}
					}

					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					redirect('admin/campaigns/edit/' . $last_id);
				} else {
					$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
				}
			}
		}

		// Traemos la lista de boletínes activos
		$news = $this->Campaigns_model->get('newsletters', 'id, name', array('status' => 1));
		if (count($news) > 0) {
			$this->template->set('_news', $news);
		}

		// Traemos las listas de email activas
		$lists = $this->Campaigns_model->get('mailing_lists', 'id, name', array('status' => 1));
		if (count($lists) > 0) {
			$this->template->set('_lists', $lists);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'campaigns/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_add_campaign'));
		$this->template->set('_active', 'campaigns');
		$this->template->set('_token', $this->user->token());
		$this->template->render('campaigns/add');
	}

	/**
	* Método que verifica si el nombre ingresado a la lista ya existe
	*
	* @access public
	* @param  $name 		Nombre de la lista a ingresar
	* @return boolean 		Si no existe devuelve TRUE caso contrario FALSE
	*/
	public function name_check($name)
	{
		$this->load->model('Campaigns_model');
		if ($this->Campaigns_model->get(NULL, 'id', array('name' => $name))) {
			$this->form_validation->set_message('name_check', $this->lang->line('error_name_check'));
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* Método que verifica si se seleccionó algún elemento
	*
	* @access public
	* @param  $value 		Valor del select
	* @return boolean 		Si no existe devuelve TRUE caso contrario FALSE
	*/
	public function select_check($value)
	{
		if ($value <= 0) {
			$this->lang->load('error');
			$this->form_validation->set_message('select_check', $this->lang->line('error_select_check'));
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* Editamos los datos de la campaña
	*
	* @access public
	* @param  $id 			Id de campaña a editar
	*/
	public function edit($id)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('edit_any_campaigns')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Campaigns_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'email_sender',
					'label'		=>	'lang:cms_general_label_email_sender',
					'rules'		=>	'trim|valid_email'
				),
				array(
					'field'		=>	'email_sender',
					'label'		=>	'lang:cms_general_label_email_reply',
					'rules'		=>	'trim|valid_email'
				),
				array(
					'field'		=>	'subject',
					'label'		=>	'lang:cms_general_label_subject',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'news',
					'label'		=>	'lang:cms_general_title_newsletters',
					'rules'		=>	'callback_select_check'
				),
				array(
					'field'		=>	'lists',
					'label'		=>	'lang:cms_general_title_lists',
					'rules'		=>	'required'
				)
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->helper('functions');
				$this->lang->load('error');

				$name = $this->input->post('name');
				$sender = $this->input->post('sender');
				$email_sender = $this->input->post('email_sender');
				$email_reply = $this->input->post('email_reply');
				$subject = $this->input->post('subject');
				$submit_at = $this->input->post('submit_at');
				$news = $this->input->post('news');
				$lists = $this->input->post('lists');
				$list = $this->input->post('list');
				$status = $this->input->post('status');

				$row = $this->Campaigns_model->get(NULL, 'mailgun_id, name', array('id' => $id));

				// Verificamos que en caso se haya seleccionado elegir listas hayamos seleccionado alguna lista
				if ($lists == '2') {
					if (!$list) {
						$this->template->set_flash_message(array('error' => $this->lang->line('error_select_list')));
						redirect('admin/campaigns/edit/' . $id);
					}
				}

				// Verificamos que el nuevo nombre no este ya registrado
				if ($name != $row[0]->name) {
					if ($this->Campaigns_model->get(NULL, 'id', array('name' => $name))) {
						$this->template->set_flash_message(array('error' => $this->lang->line('error_name_check')));
		        		redirect('admin/lists/edit/' . $id);
					} else {
						// Actualizamos el nombre a la campaña en mailgun
						$mailgun = self::getMailgun();
						$result = $mailgun->put($this->config->item('mailgun_domain') . '/campaigns/' . $row[0]->mailgun_id, array(
							'name'			=>	$name
						))->http_response_body;

						if ($result->message != 'Campaign updated') {
							$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
							redirect('admin/campaigns/edit/' . $id);
						}
					}
				}

				$data = array(
					'name'			=>	$name,
					'sender'		=>	(isset($sender) && !empty($sender)) ? $sender : $this->config->item('cms_sender'),
					'email_sender'	=>	(isset($email_sender) && !empty($email_sender)) ? $email_sender : $this->config->item('cms_email_sender'),
					'email_reply'	=>	(isset($email_reply) && !empty($email_reply)) ? $email_reply : $this->config->item('cms_email_reply'),
					'subject'		=>	$subject,
					'newsletter_id'	=>	$news,
					'lists_opt'		=>	$lists,
					'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
					'submit_at'		=>	$submit_at,
					'modified'		=>	$this->user->id,
					'modified_at'	=>	date('Y-m-d H:i:s')
				);

				$lists_now = $this->Campaigns_model->get('campaign_lists', 'list_id', array('campaign_id' => $id));

				if ($this->Campaigns_model->edit(NULL, array('id' => $id), $data)) {
					// Agregar las listas de email a las que pertenece la campaña
					if ($lists == '1') {
						$rows = $this->Campaigns_model->get('mailing_lists', 'id', array('status' => 1));

						// Eliminamos las relaciones previas
						if (count($lists_now) > 0) {
							foreach ($lists_now as $list) {
								$this->Campaigns_model->delete('campaign_lists', $where = array('campaign_id' => $id, 'list_id' => $list->list_id));
							}
						}

						foreach ($rows as $row) {
							$this->Campaigns_model->add('campaign_lists', array(
								'campaign_id'		=>	$id,
								'list_id'			=>	$row->id,
								'created'			=>	$this->user->id,
								'created_at'		=>	date('Y-m-d H:i:s')
							));
						}
					} elseif ($lists == '2') {
						foreach ($lists_now as $row) {
							if (!in_array($row->list_id, $list)) {
								$this->Campaigns_model->delete('campaign_lists', array('campaign_id' => $id, 'list_id' => $row->list_id));
							}
						}

						foreach ($list as $row) {
							$arr = array();
							foreach ($lists_now as $value) {
								$arr[] = $value->list_id;
							}

							if (!in_array($row, $arr)) {
								$this->Campaigns_model->add('campaign_lists', array(
									'campaign_id'		=>	$id,
									'list_id'			=>	$row,
									'created'			=>	$this->user->id,
									'created_at'		=>	date('Y-m-d H:i:s')
								));
							}
						}
					}
					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				} else {
					$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
				}
			}
		}

		$fields = 'id, name, sender, email_sender, email_reply, subject, newsletter_id, lists_opt, status, submit_at';
		$row = $this->Campaigns_model->get(NULL, $fields, array('id' => (int)$id));

		if ($row) {
			$this->template->set('_camp', $row[0]);
		}

		// Traemos la lista de newsletters activos
		$news = $this->Campaigns_model->get('newsletters', 'id, name', array('status' => 1));
		if (count($news) > 0) {
			$this->template->set('_news', $news);
		}

		// Traemos la lista de mailing activos
		$lists = $this->Campaigns_model->get('mailing_lists', 'id, name', array('status' => 1));
		if (count($lists) > 0) {
			$this->template->set('_lists', $lists);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'campaigns/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_campaign'));
		$this->template->set('_active', 'campaigns');
		$this->template->set('_token', $this->user->token());
		$this->template->render('campaigns/edit');
	}

	/**
	* Enviar correo de la campaña
	*
	* @access public
	* @param  $id 			Id de campaña a enviar
	*/
	public function sendCampaign($id)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('send_campaigns')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Campaigns_model');

		$fields = 'id, mailgun_id, name, sender, email_sender, email_reply, subject, newsletter_id, lists_opt, status, submit_at, created_at';
		$row = $this->Campaigns_model->get(NULL, $fields, array('id' => (int)$id));

		if ($row) {
			$this->template->set('_camp', $row[0]);
		}

		// Traemos la lista de newsletters activos
		$news = $this->Campaigns_model->get('campaigns', 'name', array('id' => $row[0]->newsletter_id));
		if (count($news) > 0) {
			$this->template->set('_news', $news[0]);
		}

		// Traemos la listas de correos a la que pertenece la campaña
		$lists = $this->Campaigns_model->get('campaign_lists', 'list_id', array('campaign_id' => $id));
		$arr_lists = array();
		if (count($lists) > 0) {
			foreach ($lists as $list) {
				$nameList = $this->Campaigns_model->get('mailing_lists', 'name', array('id' => $list->list_id));
				if (count($nameList) > 0) {
					$arr_lists[] = $nameList[0]->name;
				}
			}
		}

		if (count($arr_lists) > 0) {
			$this->template->set('_lists', $arr_lists);
		}

		// Stats de campaña
		$mailgun = self::getMailgun();
		$stats = $mailgun->get($this->config->item('mailgun_domain') . '/campaigns/' . $row[0]->mailgun_id . '/stats')->http_response_body;
		if (count($stats) > 0) {
			$this->template->set('stats', $stats);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'campaigns/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_send_campaign'));
		$this->template->set('_token', $this->user->token());
		$this->template->render('campaigns/sendCampaign');
	}

	/**
	* Método que cambia algún valor de la campaña vía ajax
	*
	* @access public
	* @param  $id 			Id del template
	* @param  $campo 		Campo a cambiar
	* @param  $value 		Valor a cambiar
	*/
	public function action()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		if ((int)$id > 0) {
			$this->load->model('Campaigns_model');

			$data = array(
				'status'		=>	$status,
				'modified'		=> 	$this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')
			);

			if ($this->Campaigns_model->edit(NULL, array('id' => $id), $data)) {
				echo TRUE;
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}
	}


	/**
	* Método para eliminar una lista de correo de la base de datos
	*
	* @access public
	* @param  $id 			Id de la lista de correo
	*/
	public function delete($id = 0)
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Campaigns_model');
			$camp = $this->Campaigns_model->get(NULL, 'mailgun_id', array('id' => $id));

			if (count($camp) > 0) {
				// Eliminamos campaña de mailgun
				$mailgun = self::getMailgun();
				$result = $mailgun->delete($this->config->item('mailgun_domain') . '/campaigns/' . $camp[0]->mailgun_id)->http_response_body;

				if ($result->message === 'Campaign deleted') {
					if ($this->Campaigns_model->delete(NULL, array('id' => $id))) {
						// Eliminamos relaciones con las listas
						$lists = $this->Campaigns_model->get('campaign_lists', 'list_id', array('campaign_id' => $id));

						if (count($lists)) {
							foreach ($lists as $list) {
								$this->Campaigns_model->delete('campaign_lists', array('campaign_id' => $id, 'list_id' => $list->list_id));
							}
						}
						echo TRUE;
					} else {
						echo FALSE;
					}
				} else {
					echo FALSE;
				}
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}

	}

	/**
	* Método para obtener las listas de mail a la que pertenece la campaña
	*
	* @access public
	* @param  $id 			Id de  la campaña
	*/
	public function getLists()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Campaigns_model');
			$rows = $this->Campaigns_model->get('campaign_lists', 'list_id', array('campaign_id' => $id));
			if (count($rows) > 0) {
				echo json_encode($rows);
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}
	}

	public function send()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$mailgun = self::getMailgun();
			$this->load->model('Campaigns_model');
			$fields = 'name, mailgun_id, sender, email_sender, email_reply, subject, newsletter_id, status, submit_at';
			$camp_data = $this->Campaigns_model->get(NULL, $fields, array('id' => $id));

			// Obtener lista de correos habilitadas para la campaña
			$lists = $this->Campaigns_model->get('campaign_lists', 'list_id', array('campaign_id' => $id));

			// Boletín
			$newsletter = $this->Campaigns_model->get('newsletters', 'guid_path', array('id' => $camp_data[0]->newsletter_id));
			$content = file_get_contents($newsletter[0]->guid_path . 'index.html');

			$check_email = array();
			if (count($lists) > 0) {
				$members = array();
				foreach ($lists as $list) {
					// Obtener los miembros de cada lista a través de mailgun
					$address = $this->Campaigns_model->get('mailing_lists', 'address', array('id' => $list->list_id));
					$members = $mailgun->get('lists/' . $address[0]->address . '/members');

					if ($members->http_response_body->total_count > 0) {
						foreach ($members->http_response_body->items as $member) {
							$respon = $mailgun->sendMessage($this->config->item('mailgun_domain'), array(
								'from'    		=> 	$camp_data[0]->sender . ' <' . $camp_data[0]->email_sender . '>',
								//'from'    		=> 	$camp_data[0]->email->sender,
								'to'      		=> 	$member->name . ' <' . $member->address . '>',
								//'to'      		=> 	$member->address,
								//'cc'      	=> 	'baz@example.com',
								//'bcc'     	=> 	'bar@example.com',
								'subject' 		=>	$camp_data[0]->subject,
								//'text'    	=> 'Testing some Mailgun awesomness!',
								'html'    		=> 	$content,
								'o:campaign'	=>	$camp_data[0]->mailgun_id,
								'h:Reply-To'	=>	$camp_data[0]->email_reply,
								'o:tracking'	=>	true,
							))->http_response_body;

							if ($respon->message != 'Queued. Thank you') {
								$check_email[] = $member->address;
							}
						}
					}
				}
			}

			echo json_encode($check_email);
		}
	}

}

/* End of file lists.php */
/* Location: ./application/modules/admin/controllers/campaigns.php */ ?>