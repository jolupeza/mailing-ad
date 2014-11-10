<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller
{
	public function display()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_access')) {
			show_error('¡Acceso restringido!');
		}

		if (!$this->user->has_permission('admin_users')) {
			show_error('¡Acceso restringido!');
		}

		$this->template->set('_title', $this->lang->line('cms_general_title_users'));
		$this->template->set('_active', 'users');
		$this->template->add_js('view', 'users/script');
		$this->template->render('users/index');
	}

	public function displayAjax($role = 0, $status = 'all', $sort_by = 'user', $sort_order="asc", $search = 'all', $offset = 0)
	{
		$limit = 10;
		$total = 0;
		$this->load->model('Users_model');
		$this->load->helper('functions');
		$result = $this->Users_model->getAll($role, $status, $limit, $offset, $sort_by, $sort_order, $search);
		$users = $result['data'];
		$total = $result['num_rows'];

		if (sizeof($users) > 0) {
			$this->template->set('_users', $users);

			if ($total > $limit) {
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/users/displayAjax/' . $role . '/' .$status . '/' . $sort_by . '/' . $sort_order . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 9;
				$this->pagination->initialize($config);
				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$roles = $this->Users_model->get('roles', 'id, role');
		if ($roles && count($roles) > 0) {
			$this->template->set('_roles', $roles);
		}

		$this->template->set('_status', $status);
		$this->template->set('_role', $role);
		$this->template->set('_num_total', $this->Users_model->countRows(array(0, 1)));
		$this->template->set('_active', $this->Users_model->countRows(array(1)));
		$this->template->set('_no_active', $this->Users_model->countRows(array(0)));
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->renderAjax('users/displayAjax');
	}

	/**
	* Método para añadir un usuario
	*
	* @access public
	*/
	public function add()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_access')) {
			show_error('¡Acceso restringido!');
		}

		if (!$this->user->has_permission('admin_users')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Users_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');
			$rules = array(
				array(
					'field'	=>	'name',
					'label'	=>	'lang:cms_general_label_name',
					'rules'	=>	'trim|required'
				),
				array(
					'field'	=>	'user',
					'label'	=>	'lang:cms_general_label_user',
					'rules'	=>	'trim|required|alpha_dash|max_length[30]|callback_username_check'
				),
				array(
					'field'	=>	'email',
					'label'	=>	'lang:cms_general_label_email',
					'rules'	=>	'trim|required|valid_email|callback_email_check'
				),
				array(
					'field'	=>	'password',
					'label'	=>	'lang:cms_general_label_password',
					'rules'	=>	'trim|required|min_length[6]|max_length[30]'
				),
				array(
					'field'	=>	'repassword',
					'label'	=>	'lang:cms_general_label_repassword',
					'rules'	=>	'trim|required|min_length[6]|matches[password]|max_length[30]'
				),
				array(
					'field'	=>	'role',
					'label'	=>	'lang:cms_general_label_role',
					'rules'	=>	'trim|required|callback_select_check'
				),
			);

			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() === TRUE) {
				$this->load->helper(array('path', 'functions'));
				$this->load->library('encrypt');
				$name = $this->input->post('name');
				$user = $this->input->post('user');
				$email = $this->input->post('email');
				$password = $this->encrypt->password($this->input->post('password'), 'sha256');
				$role = $this->input->post('role');
				$status = $this->input->post('status');
				$avatar = $this->input->post('avatar');
				$data = array(
					'name'	=>	$name,
					'email'	=>	$email,
					'user'	=>	$user,
					'password'	=>	$password,
					'role'	=>	$role,
					'status'	=>	(isset($status) && !empty($status)) ? $status : 0,
					'avatar'	=> $avatar,
					'created'	=>	$this->user->id,
					'created_at'	=>	date('Y-m-d H:i:s')
				);

				$last_id = $this->Users_model->add(NULL, $data);
				if (is_integer($last_id) && $last_id > 0) {
					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					redirect('admin/users/edit/' . $last_id);
				} else {
					$this->lang->load('error');
					$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
				}
			}
		}

		// Obtenemos la lista de roles
		$roles = $this->Users_model->get('roles', 'id, role');
		if ($roles && count($roles) > 0) {
			$this->template->set('_roles', $roles);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'users/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_add_user'));
		$this->template->set('_active', 'users');
		$this->template->set('_token', $this->user->token());
		$this->template->render('users/add');
	}

	/**
	* Método que verifica si username ingresado ya existe
	*
	* @access public
	* @param $username Username
	* @return boolean Si no existe devuelve TRUE caso contrario FALSE
	*/
	public function username_check($username)
	{
		$this->load->model('Users_model');
		$user = $this->Users_model->get(NULL, 'id', array('user' => $username));
		if ($user && count($user) > 0) {
			$this->form_validation->set_message('username_check', $this->lang->line('user_error_username_exists'));
		return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* Método que verifica si email ingresado ya existe
	*
	* @access public
	* @param $email Email
	* @return boolean Si no existe devuelve TRUE caso contrario FALSE
	*/
	public function email_check($email)
	{
		$this->load->model('Users_model');
		if ($this->Users_model->get(NULL, 'id', array('email' => $email))) {
			$this->form_validation->set_message('email_check', $this->lang->line('user_error_email_exists'));
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	* Editamos los datos de customer
	*
	* @access public
	* @param $id Id de cliente a editar
	*/
	public function edit($id)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_access')) {
			show_error('¡Acceso restringido!');
		}

		if (!$this->user->has_permission('admin_users') || !$this->user->has_permission('view_user_profiles')) {
			show_error('¡Acceso restringido!');
		}

		if ($this->user->role > 1) {
			if ($this->user->role != $id) {
				show_error('¡Acceso restringido!');
			}
		}

		$this->load->model('Users_model');
		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');
			$rules = array(
				array(
					'field'	=>	'name',
					'label'	=>	'lang:cms_general_label_name',
					'rules'	=>	'trim|required'
				),
				array(
					'field'	=>	'user',
					'label'	=>	'lang:cms_general_label_user',
					'rules'	=>	'trim|required|alpha_dash|max_length[30]'
				),
				array(
					'field'	=>	'email',
					'label'	=>	'lang:cms_general_label_email',
					'rules'	=>	'trim|required|valid_email'
				),
			);

			if ($this->user->has_permission('admin_users')) {
				$rules[] = array(
					'field'	=>	'role',
					'label'	=>	'lang:cms_general_label_role',
					'rules'	=>	'trim|required|callback_select_check'
				);
			}

			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() === TRUE) {
				$this->load->helper(array('path', 'functions'));
				$this->lang->load('error');
				$name = $this->input->post('name');
				$user = $this->input->post('user');
				$email = $this->input->post('email');
				$role = $this->input->post('role');
				$status = $this->input->post('status');
				$avatar = $this->input->post('avatar');
				$row = $this->Users_model->get(NULL, 'user, email, role, status', array('id' => $id));

				if (!($this->user->has_permission('admin_users') && $this->user->has_permission('view_user_profiles'))) {
					$status = $row[0]->status;
					$role = $row[0]->role;
				}

				// Verificamos que el username sea diferente al que ya se tiene registrado y ver si el nuevo ya existe
				if ($user != $row[0]->user) {
					if ($this->Users_model->get(NULL, 'id', array('user' => $user))) {
						$this->template->add_message(array('error' => $this->lang->line('user_error_username_exists')));
					} else if ($email != $row[0]->email) {
						if ($this->Users_model->get(NULL, 'id', array('email' => $email))) {
							$this->template->add_message(array('error' => $this->lang->line('user_error_email_exists')));
						} else {
							$data = array(
								'name'			=>	$name,
								'email'			=>	$email,
								'user'			=>	$user,
								'role'			=>	$role,
								'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
								'avatar'		=> $avatar,
								'modified'		=>	$this->user->id,
								'modified_at'	=>	date('Y-m-d H:i:s')
							);

							if ($this->Users_model->edit(NULL, array('id' => $id), $data)) {
								$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
							} else {
								$this->template->add_message(array('error' => $this->lang->line('error_message_general')));
							}
						}
					} else {
						$data = array(
							'name'		=>	$name,
							'user'		=>	$user,
							'role'		=>	$role,
							'status'	=>	(isset($status) && !empty($status)) ? $status : 0,
							'avatar'	=> $avatar,
							'modified'	=>	$this->user->id,
							'modified_at'	=>	date('Y-m-d H:i:s')
						);

						if ($this->Users_model->edit(NULL, array('id' => $id), $data)) {
							$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
						} else {
							$this->template->add_message(array('error' => $this->lang->line('error_message_general')));
						}
					}
				} else if ($email != $row[0]->email) {
					if ($this->Users_model->get(NULL, 'id', array('email' => $email))) {
						$this->template->add_message(array('error' => $this->lang->line('user_error_email_exists')));
					} else {
						$data = array(
							'name'	=>	$name,
							'email'	=>	$email,
							'role'	=>	$role,
							'status'	=>	(isset($status) && !empty($status)) ? $status : 0,
							'avatar'	=> $avatar,
							'modified'	=>	$this->user->id,
							'modified_at'	=>	date('Y-m-d H:i:s')
						);

						if ($this->Users_model->edit(NULL, array('id' => $id), $data)) {
							$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
						} else {
							$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
						}
					}
				} else {
					$data = array(
						'name'	=>	$name,
						'role'	=>	$role,
						'status'	=>	(isset($status) && !empty($status)) ? $status : 0,
						'avatar'	=> $avatar,
						'modified'	=>	$this->user->id,
						'modified_at'	=>	date('Y-m-d H:i:s')
					);

					if ($this->Users_model->edit(NULL, array('id' => $id), $data)) {
						$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
					} else {
						$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
					}
				}
			}
		}

		$fields = 'name, email, user, role, status, avatar';
		$row = $this->Users_model->get(NULL, $fields, array('id' => (int)$id));
		if ($row) {
			$this->template->set('_user', $row[0]);
		}

		// Obtenemos la lista de roles
		$roles = $this->Users_model->get('roles', 'id, role');
		if ($roles && count($roles) > 0) {
			$this->template->set('_roles', $roles);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'users/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_edit_user'));
		$this->template->set('_active', 'users');
		$this->template->set('_token', $this->user->token());
		$this->template->render('users/edit');
	}

	/**
	* Método que verifica si se seleccionó algún elemento
	*
	* @access public
	* @param $value Valor del select
	* @return boolean Si no existe devuelve TRUE caso contrario FALSE
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

	public function login()
	{
		if (!$this->user->is_logged_in()) {
			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');
				$rules = array(
					array(
						'field'	=>	'user',
						'label'	=>	'lang:cms_general_label_user',
						'rules'	=>	'trim|required|alpha_dash|max_length[30]'
					),
					array(
						'field'	=>	'password',
						'label'	=>	'lang:cms_general_label_password',
						'rules'	=>	'required|max_length[30]'
					),
				);

				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() === TRUE) {
					if ($this->user->login($this->input->post('user'), $this->input->post('password')) === TRUE) {
						// Editamos el campo last_login para indicar cual ha sido su último logueo.
						$this->load->model('Users_model');
						$this->session->set_userdata('user_id', $this->user->id);
						$this->Users_model->edit(NULL, array('id'=> $this->user->id), array('last_login' => date('Y-m-d H:i:s')));
						redirect('admin/dashboard');
					}
					$this->template->add_message(array('error' => $this->user->errors()));
				}
			}

			$this->load->helper('form');
			$this->template->set('_title', $this->lang->line('cms_general_title_login'));
			$this->template->set('_notmp', TRUE);
			$this->template->set('_token', $this->user->token());
			$this->template->render('users/login');
		} else {
			redirect('admin/dashboard');
		}
	}

	public function logout()
	{
		if ($this->user->is_logged_in()) {
			$this->session->sess_destroy();
		}
		redirect('admin');
	}

	/**
	* Método que cambia algún valor del usuario vía ajax
	*
	* @access public
	* @param $id Id del usuario
	* @param $campo Campo a cambiar
	* @param $value Valor a cambiar
	*/
	public function action()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		if ((int)$id > 0) {
			$this->load->model('Users_model');
			$data = array(
				'status'	=>	$status,
				'modified'	=> $this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')
			);

			if ($this->Users_model->edit(NULL, array('id' => $id), $data)) {
				echo TRUE;
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}
		exit;
	}

	/**
	* Método para eliminar un usuario
	*
	* @access public
	* @param $id Id del usuario
	*/
	public function delete()
	{
		$id = $this->input->post('id');
		if ((int)$id > 0) {
			$this->load->model('Users_model');
			$user = $this->Users_model->get(NULL, 'avatar', array('id' => $id));

			if (count($user) > 0) {
				if ($this->Users_model->delete(NULL, array('id' => $id))) {
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

		exit;
	}
}
/* End of file users.php */
/* Location: ./application/controllers/users.php */ ?>
