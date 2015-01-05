<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscribers extends MY_Controller
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

		if (!$this->user->has_permission('view_subs')) {
			show_error('¡Acceso restringido!');
		}

		$this->template->set('_title', $this->lang->line('cms_general_title_subscribers'));
		$this->template->set('_active', 'subscribers');
		$this->template->add_js('view', 'subscribers/script');
		$this->template->render('subscribers/index');
	}

	/**
	* Método que vía ajax obtiene y muestra la lista de suscriptores
	*
	* @access public
	* @param  $status 		Indicamos el status (confirmado o no confirmado)
	* @param  $sort_by 		Indicamos a través de que campo se ordena
	* @param  $sort_order 	Indicamos si se ordena ascendente o descendentemente
	* @param  $search 		Indicamos si se pasa un parámetro de búsqueda
	* @param  $offset 		Indicamos desde que registro obtenemos la lista
	*/
	public function displayAjax($status = 'all', $sort_by = 'id', $sort_order = 'desc', $list = 'all', $limit = 10, $search = 'all',  $offset = 0)
	{
		$total = 0;
		$this->load->model('Subscribers_model');
		$this->load->helper('form');

		if ($search != 'all') {
			$like = array('name' => urldecode($search));
		} else {
			$like = '';
		}

		$result = $this->Subscribers_model->getAll($status, $limit, $offset, $sort_by, $sort_order, $list, $like);
		$subs = $result['data'];
		$total = $result['num_rows'];

		if (count($subs) > 0) {
			$this->template->set('_subs', $subs);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/subscribers/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $list . '/' . $limit . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 10;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		// Enviamos a la vista la lista de correos existentes
		$fields = 'id, name';
		$lists = $this->Subscribers_model->get('mailing_lists', $fields);

		if ($lists) {
			$this->template->set('_lists', $lists);
		}

		$this->template->set('_num_total', $this->Subscribers_model->countRows(array(0, 1)));
		$this->template->set('_active', $this->Subscribers_model->countRows(array(1)));
		$this->template->set('_no_active', $this->Subscribers_model->countRows(array(0)));
		$this->template->set('_trush', $this->Subscribers_model->countRows(array(2)));

		$this->template->set('_status', $status);
		$this->template->set('_total', $total);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_list', $list);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->set('_offset', $offset);
		$this->template->renderAjax('subscribers/displayAjax');
	}

	/**
	* Método para añadir un suscriptor
	*
	* @access public
	*/
	public function add()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('create_subs')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Subscribers_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'email',
					'label'		=>	'lang:cms_general_label_email',
					'rules'		=>	'trim|required|valid_email|callback_email_check'
				),
				array(
					'field'		=>	'lists',
					'label'		=>	'lang:cms_general_title_lists',
					'rules'		=>	'required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$name = $this->input->post('name');
				$email = $this->input->post('email');
				//$company = $this->input->post('company');
				//$address = $this->input->post('address');
				$status = $this->input->post('status');
				//$date_birth = $this->input->post('date_birth');
				$lists = $this->input->post('lists');

				$mailgun = self::getMailgun();
				$mailgunValidate = self::getMailgunValidate();
				$mailgunOptIn = $mailgun->OptInHandler();

				$validate = $mailgunValidate->get('address/validate', array('address' => $email))->http_response_body;

				/*
				$vars = array(
					'company'		=>	$company,
					'direccion'		=>	$address,
					'date_birth'	=>	$date_birth
				);*/

				//$vars = json_encode($vars);

				if ($validate->is_valid) {
					if (count($lists) > 0) {
						foreach ($lists as $list) {
							$row = $this->Subscribers_model->get('mailing_lists' , 'address', array('id' => $list));
							$mailgun->post('lists/' . $row[0]->address . '/members', array(
								'address'		=>	$email,
								'name'			=>	$name,
								//'vars'			=>	$vars,
								'subscribed'	=>	'yes'
							));
						}
					}

					$data = array(
						'name'			=>	$name,
						'email'			=>	$email,
						//'company'		=>	$company,
						//'address'		=>	$this->input->post('address'),
						'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
						'active'		=>	1,
						//'date_birth'	=>	$this->input->post('date_birth'),
						'created'		=>	$this->user->id,
						'created_at'	=>	date('Y-m-d H:i:s')
					);

					$last_id = $this->Subscribers_model->add(NULL, $data);

					if (is_integer($last_id) && $last_id > 0) {
						if (count($lists) > 0) {
							foreach ($lists as $l) {
								$data = array(
									'subscriber_id'		=>	$last_id,
									'list_id'			=>	$l,
									'created'			=>	$this->user->id,
									'created_at'		=>	date('Y-m-d H:i:s')
								);

								$this->Subscribers_model->add('subscriber_lists', $data);
							}
						}

						$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
						redirect('admin/subscribers/edit/' . $last_id);
					} else {
						$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
					}
				} else {
					$this->template->add_message(array('error' => $this->lang->line('error_email_valid')));
				}
			}
		}

		// Enviamos a la vista la lista de correos existentes
		$fields = 'id, name';
		$lists = $this->Subscribers_model->get('mailing_lists', $fields);

		if ($lists) {
			$this->template->set('_lists', $lists);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'subscribers/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_add_subscriber'));
		$this->template->set('_active', 'subscribers');
		$this->template->set('_token', $this->user->token());
		$this->template->render('subscribers/add');
	}

	/**
	* Método para agregar múltiples
	*
	* @access public
	*/
	public function addMulti()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('create_subs')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Subscribers_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->lang->load('upload');

			if (isset($_FILES['source']) && $_FILES['source']['name'] != '' && $_FILES['source']['error'] === 0) {
				// Verificar que archivo subido sea de tipo txt
				if ($_FILES['source']['type'] != 'text/plain') {
					$this->template->set_flash_message(array('error' => $this->lang->line('upload_invalid_filetype')));
					redirect('admin/subscribers/addMulti');
				}

				// Verificar que archivo subido sea menor a 10 MB
				if ($_FILES['source']['size'] > 10485760) {
					$this->template->set_flash_message(array('error' => $this->lang->line('upload_invalid_filesize')));
					redirect('admin/subscribers/addMulti');
				}

				$this->load->library('form_validation');

				$rules = array(
					array(
						'field'		=>	'lists',
						'label'		=>	'lang:cms_general_title_lists',
						'rules'		=>	'required'
					),
				);

				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() === TRUE) {
					$lists = $this->input->post('lists');

					$mailgun = self::getMailgun();
					$mailgunValidate = self::getMailgunValidate();
					$mailgunOptIn = $mailgun->OptInHandler();

					// Trajamos con archivo subido verificar que sea de tipo txt
					$txt = fopen($_FILES['source']['tmp_name'], "r");

					$email_error = array();
					$email_ok = array();

					while(!feof($txt)) {
						$line = fgets($txt);
						$cols = explode(',', $line);

						$email = trim($cols[1]);
						$name = trim($cols[0]);

						// Validamos email tanto para nuevo como para actualizar
						$validate = $mailgunValidate->get("address/validate", array('address' => $email))->http_response_body;

						if (!$validate->is_valid) {
							$email_error[] = $email;
							continue;
						}

						// En caso el email a insertar ya se encuentre regitrado no lo inserta (Verificar que hacer en este caso) Verificar la lista de suscripción \
						if (!$this->email_check($cols[1])) {
							continue; // Debemos actualizar los datos del suscriptor

							/* Activar código en caso se require actualizar la información del suscriptor
							// Obtenemos lista de email a las que pertenece el suscriptor
							$lists_now = $this->Subscribers_model->get('subscriber_lists', 'list_id', array('subscriber_id' => $id));

							$sub_now = $this->Subscribers_model->get(NULL, 'email', array('id' => $id));

							$mailgun = self::getMailgun();
							$mailgunValidate = self::getMailgunValidate();
							$mailgunOptIn = $mailgun->OptInHandler();

							$validate = $mailgunValidate->get('address/validate', array('address' => $email))->http_response_body;

							$vars = array(
								'company'		=>	$company,
								'direccion'		=>	$address,
								'date_birth'	=>	$date_birth
							);

							$vars = json_encode($vars);

							if ($validate->is_valid) {
								// Verificamos que email ingresado no se encuentre ya registrado
								if ($sub_now[0]->email != $email ) {
									if (!$this->email_check($email)) {
										$this->template->set_flash_message(array('error' => $this->lang->line('error_email_check')));
										redirect('admin/subscribers/edit/' . $id);
									}
								}

								$lists_arr = array();

								if (count($lists_now) > 0) {
									foreach ($lists_now as $l) {
										$lists_arr[] = $l->list_id;
									}
								}

								// Verificamos que lista anterior no esta en lista actual
								if (count($lists_arr) > 0) {
									foreach ($lists_arr as $l) {
										// Si no esta eliminamos de mailgun y de nuestra base de datos
										if (!in_array($l, $lists)) {
											$row = $this->Subscribers_model->get('mailing_lists' , 'address', array('id' => $l));

											$mailgun->delete('lists/' . $row[0]->address . '/members/' . $sub_now[0]->email);
											$this->Subscribers_model->delete('subscriber_lists', array('subscriber_id' => $id, 'list_id' => $l));
										}
									}
								}

								// Verificamos si lista actual esta en lista anterior
								foreach ($lists as $value) {
									$row = $this->Subscribers_model->get('mailing_lists' , 'address', array('id' => $value));

									// Si no esta lo agregamos a mailgun y a nuestra base de datos
									if (!in_array($value, $lists_arr)) {
										$data = array(
											'subscriber_id'		=>	$id,
											'list_id'			=>	$value,
											'modified'			=>	$this->user->id,
											'modified_at'		=>	date('Y-m-d H:i:s')
										);

										$mailgun->post('lists/' . $row[0]->address . '/members', array(
											'address'		=>	$email,
											'name'			=>	$name,
											'vars'			=>	$vars,
											'subscribed'	=>	'yes'
										));

										$this->Subscribers_model->add('subscriber_lists', $data);
									} else {
										$mailgun->put('lists/' . $row[0]->address . '/members/' . $sub_now[0]->email, array(
											'address'		=>	$email,
											'name'			=>	$name,
											'vars'			=>	$vars,
											'subscribed'	=>	'yes'
										));
									}
								}

								$data = array(
									'name'			=>	$name,
									'email'			=>	$email,
									'company'		=>	$company,
									'address'		=>	$address,
									'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
									'date_birth'	=>	$this->input->post('date_birth'),
									'modified'		=>	$this->user->id,
									'modified_at'	=>	date('Y-m-d H:i:s')
								);

								if ($this->Subscribers_model->edit(NULL, array('id' => $id), $data)) {
									$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
								}
							} else {
								$this->template->set_flash_message(array('error' => $this->lang->line('error_email_valid')));
							} */
						} else {
							/*
							$date_birth = str_replace("\r\n", "", $cols[4]);

							$vars = array(
								'company'		=>	trim($cols[2]),
								'direccion'		=>	trim($cols[3]),
								'date_birth'	=>	trim($date_birth)
							);
							$vars = json_encode($vars);*/

							if (count($lists) > 0) {
								foreach ($lists as $list) {
									$row = $this->Subscribers_model->get('mailing_lists' , 'address', array('id' => $list));
									$mailgun->post('lists/' . $row[0]->address . '/members', array(
										'address'		=>	$email,
										'name'			=>	$name,
										//'vars'			=>	$vars,
										'subscribed'	=>	'yes'
									));
								}
							}

							//$date_birth = new DateTime($date_birth);

							$data = array(
								'name'			=>	$name,
								'email'			=>	$email,
								//'company'		=>	trim($cols[2]),
								//'address'		=>	trim($cols[3]),
								'status'		=>	1,
								'active'		=>	1,
								//'date_birth'	=>	$date_birth->format('Y-m-d'),
								'created'		=>	$this->user->id,
								'created_at'	=>	date('Y-m-d H:i:s')
							);

							$last_id = $this->Subscribers_model->add(NULL, $data);

							if (is_integer($last_id) && $last_id > 0) {
								$email_ok[]	= $email;

								if (count($lists) > 0) {
									foreach ($lists as $l) {
										$data = array(
											'subscriber_id'		=>	$last_id,
											'list_id'			=>	$l,
											'created'			=>	$this->user->id,
											'created_at'		=>	date('Y-m-d H:i:s')
										);

										$this->Subscribers_model->add('subscriber_lists', $data);
									}
								}
							}
						}
					}

					if ($email_error && count($email_error) > 0) {
						$this->template->set('errorEmail', $email_error);
					}

					if ($email_ok && count($email_ok) > 0) {
						$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					}
				}
			} else {
				$this->template->add_message(array('error' => $this->lang->line('upload_no_file_selected')));
			}
		}

		// Enviamos a la vista la lista de correos existentes
		$fields = 'id, name';
		$lists = $this->Subscribers_model->get('mailing_lists', $fields);

		if ($lists) {
			$this->template->set('_lists', $lists);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'subscribers/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_load_multiple'));
		$this->template->set('_active', 'subscribers');
		$this->template->set('_token', $this->user->token());
		$this->template->render('subscribers/addMulti');
	}

	/**
	* Editamos los datos de un suscriptor
	*
	* @access public
	* @param  $id 			Id del suscriptor a editar
	*/
	public function edit($id)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('edit_any_subs')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Subscribers_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'email',
					'label'		=>	'lang:cms_general_label_email',
					'rules'		=>	'trim|required|valid_email'
				),
				array(
					'field'		=>	'lists',
					'label'		=>	'lang:lang:cms_general_title_lists',
					'rules'		=>	'required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$name = $this->input->post('name');
				$email = $this->input->post('email');
				$company = $this->input->post('company');
				$address = $this->input->post('address');
				$status = $this->input->post('status');
				$date_birth = $this->input->post('date_birth');
				$lists = $this->input->post('lists');

				// Obtenemos lista de email a las que pertenece el suscriptor
				$lists_now = $this->Subscribers_model->get('subscriber_lists', 'list_id', array('subscriber_id' => $id));

				$sub_now = $this->Subscribers_model->get(NULL, 'email', array('id' => $id));

				$mailgun = self::getMailgun();
				$mailgunValidate = self::getMailgunValidate();
				$mailgunOptIn = $mailgun->OptInHandler();

				$validate = $mailgunValidate->get('address/validate', array('address' => $email))->http_response_body;

				$vars = array(
					'company'		=>	$company,
					'direccion'		=>	$address,
					'date_birth'	=>	$date_birth
				);

				$vars = json_encode($vars);

				if ($validate->is_valid) {
					// Verificamos que email ingresado no se encuentre ya registrado
					if ($sub_now[0]->email != $email ) {
						if (!$this->email_check($email)) {
							$this->template->set_flash_message(array('error' => $this->lang->line('error_email_check')));
							redirect('admin/subscribers/edit/' . $id);
						}
					}

					$lists_arr = array();

					if (count($lists_now) > 0) {
						foreach ($lists_now as $l) {
							$lists_arr[] = $l->list_id;
						}
					}

					// Verificamos que lista anterior no esta en lista actual
					if (count($lists_arr) > 0) {
						foreach ($lists_arr as $l) {
							// Si no esta eliminamos de mailgun y de nuestra base de datos
							if (!in_array($l, $lists)) {
								$row = $this->Subscribers_model->get('mailing_lists' , 'address', array('id' => $l));

								$mailgun->delete('lists/' . $row[0]->address . '/members/' . $sub_now[0]->email);
								$this->Subscribers_model->delete('subscriber_lists', array('subscriber_id' => $id, 'list_id' => $l));
							}
						}
					}

					// Verificamos si lista actual esta en lista anterior
					foreach ($lists as $value) {
						$row = $this->Subscribers_model->get('mailing_lists' , 'address', array('id' => $value));

						// Si no esta lo agregamos a mailgun y a nuestra base de datos
						if (!in_array($value, $lists_arr)) {
							$data = array(
								'subscriber_id'		=>	$id,
								'list_id'			=>	$value,
								'modified'			=>	$this->user->id,
								'modified_at'		=>	date('Y-m-d H:i:s')
							);

							$mailgun->post('lists/' . $row[0]->address . '/members', array(
								'address'		=>	$email,
								'name'			=>	$name,
								'vars'			=>	$vars,
								'subscribed'	=>	'yes'
							));

							$this->Subscribers_model->add('subscriber_lists', $data);
						} else {
							$mailgun->put('lists/' . $row[0]->address . '/members/' . $sub_now[0]->email, array(
								'address'		=>	$email,
								'name'			=>	$name,
								'vars'			=>	$vars,
								'subscribed'	=>	'yes'
							));
						}
					}

					$data = array(
						'name'			=>	$name,
						'email'			=>	$email,
						'company'		=>	$company,
						'address'		=>	$address,
						'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
						'date_birth'	=>	$this->input->post('date_birth'),
						'modified'		=>	$this->user->id,
						'modified_at'	=>	date('Y-m-d H:i:s')
					);

					if ($this->Subscribers_model->edit(NULL, array('id' => $id), $data)) {
						$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
					}
				} else {
					$this->template->set_flash_message(array('error' => $this->lang->line('error_email_valid')));
				}
			}
		}

		$fields = 'id, name, email, address, company, date_birth, status';
		$row = $this->Subscribers_model->get(NULL, $fields, array('id' => (int)$id));

		if ($row) {
			$this->template->set('_subs', $row);
		}

		// Mandamos a la vista la lista de correos a las que pertenece el suscriptor actual
		$result = $this->Subscribers_model->get('subscriber_lists', 'list_id', array('subscriber_id' => $id));

		if ($result) {
			$lists_array = array();
			foreach ($result as $value) {
				$lists_array[] = $value->list_id;
			}
			$this->template->set('_list_subs', $lists_array);
		}

		// Enviamos a la vista la lista de correos existentes
		$fields = 'id, name';
		$lists = $this->Subscribers_model->get('mailing_lists', $fields);

		if ($lists) {
			$this->template->set('_lists', $lists);
		}

		$this->load->helper('form');
		$this->template->add_css('base', 'bootstrap/css/bootstrap-datetimepicker.min');
		$this->template->add_css('base', 'bootstrap/css/bootstrap-switch.min');
		$this->template->add_js('base', 'bootstrap/moment');
		$this->template->add_js('base', 'bootstrap/bootstrap-datetimepicker.min');
		$this->template->add_js('base', 'bootstrap/bootstrap-switch.min');
		$this->template->add_js('view', 'subscribers/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_subscriber'));
		$this->template->set('_active', 'subscribers');
		$this->template->set('_token', $this->user->token());
		$this->template->render('subscribers/edit');
	}

	/**
	* Método que verifica si el email ingresado ya existe
	*
	* @access public
	* @param  $email 		Email a revisar
	* @return boolean 		Si no existe devuelve TRUE caso contrario FALSE
	*/
	public function email_check($email)
	{
		$this->load->model('Subscribers_model');
		if ($this->Subscribers_model->get(NULL, 'id', array('email' => $email))) {
			$this->form_validation->set_message('email_check', $this->lang->line('error_email_check'));
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* Método que cambia algún valor vía ajax
	*
	* @access public
	* @param  $id 			Id del subscriber
	*/
	public function action()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		if ((int)$id > 0) {
			$this->load->model('Subscribers_model');

			$data = array(
				'status'		=>	$status,
				'modified'		=> 	$this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')
			);

			if ($this->Subscribers_model->edit(NULL, array('id' => $id), $data)) {
				echo TRUE;
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}
	}


	/**
	* Método para eliminar un subscriptor de la base de datos
	*
	* @access public
	* @param  $id 			Id del subscriptor
	*/
	public function delete($id = 0)
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Subscribers_model');
			$lists= $this->Subscribers_model->get('subscriber_lists', 'list_id', array('subscriber_id' => $id));
			$member = $this->Subscribers_model->get(NULL, 'email', array('id' => $id));

			if (count($lists) > 0) {
				if ($this->Subscribers_model->delete(NULL, array('id' => $id))) {
					// Borrar de la tabla subscriber_lists
					if ($this->Subscribers_model->delete('subscriber_lists', array('subscriber_id' => $id))) {
						$mailgun = self::getMailgun();

						if (count($lists) > 0) {
							foreach ($lists as $list) {
								$row = $this->Subscribers_model->get('mailing_lists', 'address', array('id' => $list->list_id));
								$result = $mailgun->delete('lists/' . $row[0]->address . '/members/' . $member[0]->email);
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
}

/* End of file subscribers.php */
/* Location: ./application/modules/admin/controllers/subscribers.php */