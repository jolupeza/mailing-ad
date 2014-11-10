<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lists extends MY_Controller
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

		if (!$this->user->has_permission('view_lists')) {
			show_error('¡Acceso restringido!');
		}

		$this->template->set('_title', $this->lang->line('cms_general_title_lists'));
		$this->template->set('_active', 'lists');
		$this->template->add_js('view', 'lists/script');
		$this->template->render('lists/index');
	}

	/**
	* Método que vía ajax obtiene y muestra la lista de mailing
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
		$this->load->model('Lists_model');

		$result = $this->Lists_model->getAll($status, $limit, $offset, $sort_by, $sort_order, $search);
		$lists = $result['data'];
		$total = $result['num_rows'];

		if (count($lists) > 0) {
			$this->template->set('_lists', $lists);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/lists/displayAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 8;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_num_total', $this->Lists_model->countRows(array(0, 1)));
		$this->template->set('_active', $this->Lists_model->countRows(array(1)));
		$this->template->set('_no_active', $this->Lists_model->countRows(array(0)));
		$this->template->set('_trush', $this->Lists_model->countRows(array(2)));

		$this->template->set('_status', $status);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->renderAjax('lists/displayAjax');
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

		if (!$this->user->has_permission('create_lists')) {
			show_error('¡Acceso restringido!');
		}

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required|callback_name_check'
				)
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->model('Lists_model');
				$this->load->helper('functions');

				$name = $this->input->post('name');
				$description = $this->input->post('description');
				$access_level = $this->input->post('access_level');
				$status = $this->input->post('status');
				$slug = slug($name);

				$mailgun = self::getMailgun();
				$result = $mailgun->post('lists', array(
					'address'		=>	$slug . '@' . $this->config->item('mailgun_domain'),
					'name'			=>	$name,
					'description'	=>	$description,
					'access_level'	=>	$access_level
				))->http_response_body;

				if ($result->message === 'Mailing list has been created') {
					$data = array(
						'name'			=>	$this->input->post('name'),
						'address'		=>	slug($name) . '@' . $this->config->item('mailgun_domain'),
						'description'	=>	$description,
						'access_level'	=>	$access_level,
						'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
						'created'		=>	$this->user->id,
						'created_at'	=>	date('Y-m-d H:i:s')
					);

					$last_id = $this->Lists_model->add(NULL, $data);

					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					redirect('admin/lists/edit/' . $last_id);
				} else {
					$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
				}
			}
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'lists/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_add_list'));
		$this->template->set('_active', 'lists');
		$this->template->set('_token', $this->user->token());
		$this->template->render('lists/add');
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
		$this->load->model('Lists_model');
		if ($this->Lists_model->get(NULL, 'id', array('name' => $name))) {
			$this->form_validation->set_message('name_check', $this->lang->line('error_name_check'));
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* Editamos los datos de una lista de correo
	*
	* @access public
	* @param  $id 			Id de la lista a editar
	*/
	public function edit($id)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('edit_any_lists')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Lists_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->helper('functions');

				$name = $this->input->post('name');
				$description = $this->input->post('description');
				$access_level = $this->input->post('access_level');
				$status = $this->input->post('status');

				$list_now = $this->Lists_model->get(NULL, 'name, address', array('id' => $id));

				$slug = '';
				if ($name != $list_now[0]->name) {
					if($this->Lists_model->get(NULL, 'id', array('name' => $name))) {
						$this->lang->load('error');
						$this->template->set_flash_message(array('error' => $this->lang->line('error_name_check')));
		        		redirect('admin/lists/edit/' . $id);
					} else {
						$slug = slug($name);
					}
				}

				$mailgun = self::getMailgun();
				$result = $mailgun->put('lists/' . $list_now[0]->address, array(
					'address'		=>	(!empty($slug)) ? $slug . '@' . $this->config->item('mailgun_domain') : $list_now[0]->address,
					'name'			=>	$name,
					'description'	=>	$description,
					'access_level'	=>	$access_level
				));

				$data = array(
					'name'			=>	$name,
					'address'		=>	(!empty($slug)) ? $slug . '@' . $this->config->item('mailgun_domain') : $list_now[0]->address,
					'description'	=>	$description,
					'access_level'	=>	$access_level,
					'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
					'modified'		=>	$this->user->id,
					'modified_at'	=>	date('Y-m-d H:i:s')
				);

				if ($this->Lists_model->edit(NULL, array('id' => $id), $data)) {
					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				}
			}
		}

		$fields = 'id, name, description, access_level, status';
		$row = $this->Lists_model->get(NULL, $fields, array('id' => (int)$id));

		if ($row) {
			$this->template->set('_list', $row);
		}

		$this->load->helper('form');
		$this->template->add_css('base', 'bootstrap/css/bootstrap-switch.min');
		$this->template->add_js('base', 'bootstrap/bootstrap-switch.min');
		$this->template->add_js('view', 'lists/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_list'));
		$this->template->set('_active', 'lists');
		$this->template->set('_token', $this->user->token());
		$this->template->render('lists/edit');
	}

	/**
	* Método que cambia algún valor del template vía ajax
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
			$this->load->model('Lists_model');

			$data = array(
				'status'		=>	$status,
				'modified'		=> 	$this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')
			);

			if ($this->Lists_model->edit(NULL, array('id' => $id), $data)) {
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
			$this->load->model('Lists_model');

			// Verificamos que no tengamos suscriptores
			$sus = $this->Lists_model->get('subscriber_lists', 'subscriber_id', array('list_id' => $id));

			if (!$sus) {
				$list = $this->Lists_model->get(NULL, 'address', array('id' => $id));

				if (count($list) > 0) {
					$mailgun = self::getMailgun();
					$result = $mailgun->delete('lists/' . $list[0]->address)->http_response_body;

					if ($result->message === 'Mailing list has been removed') {
						if ($this->Lists_model->delete(NULL, array('id' => $id))) {
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
		} else {
			echo FALSE;
		}

	}

}

/* End of file lists.php */
/* Location: ./application/modules/admin/controllers/lists.php */ ?>