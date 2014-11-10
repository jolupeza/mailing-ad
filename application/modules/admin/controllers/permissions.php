<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends MY_Controller
{
	/**
	* Permite entrar a la administración de perfiles de usuario
	*
	* @access private
	* @param $sort_by 			Indicamos el campo por el que queremos ordenar
	* @param $sort_order 		Indicamos si ordenamos ascendente o descendentemente
	* @param $search 			Indicamos texto a buscar
	*/
	public function index($sort_by = 'id', $sort_order = 'desc', $search = 'all', $offset = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_access')) {
			show_error('¡Acceso restringido!');
		}

		if (!$this->user->has_permission('admin_permissions')) {
			show_error('¡Acceso restringido!');
		}

		$limit = 10;
		$total = 0;
		$this->load->model('Permissions_model');

		$result = $this->Permissions_model->getAll($limit, $offset, $sort_by, $sort_order, $search);
		$roles = $result['data'];
		$total = $result['num_rows'];

		if (count($roles) > 0) {
			$this->template->set('_roles', $roles);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/permissions/index/' . $sort_by . '/' . $sort_order . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 7;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->set('_title', $this->lang->line('cms_general_label_roles'));
		$this->template->set('_active', 'permissions');
		$this->template->add_js('view', 'permissions/script');
		$this->template->render('permissions/index');
	}

	/**
	* Método para añadir un nuevo proyecto
	*
	* @access public
	*/
	public function add()
	{
		if ($this->user->is_logged_in()) {
			if ($this->user->has_permission('admin_access')) {
				if ($this->user->has_permission('view_user_profiles')) {
					$this->load->model('Permissions_model');
					$this->lang->load('error');

					if ($this->input->post('token') == $this->session->userdata('token')) {
						$this->load->library('form_validation');

						$rules = array(
							array(
								'field'		=>	'role',
								'label'		=>	'lang:cms_general_label_role',
								'rules'		=>	'trim|required'
							)
						);

						$this->form_validation->set_rules($rules);

						if ($this->form_validation->run() === TRUE) {
							$this->load->helper('functions');

							$role = $this->input->post('role');
							$status = $this->input->post('status');

							$data = array(
								'role'			=>	$role,
								'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
								'created'		=>	$this->user->id,
								'created_at'	=>	date('Y-m-d H:i:s')
							);

							$last_id = $this->Permissions_model->add(NULL, $data);

							if (is_integer($last_id) && $last_id > 0) {
								$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
								redirect('admin/permissions/edit/' . $last_id);
							} else {
								$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
							}
						}
					}

					$this->load->helper('form');
					$this->template->add_js('view', 'permissions/script');
					$this->template->set('_title', $this->lang->line('cms_general_label_add_role'));
					$this->template->set('_active', 'permissions');
					$this->template->set('_token', $this->user->token());
					$this->template->render('permissions/add');
				} else {
					show_error('¡Acceso restringido!');
				}
			} else {
				show_error('¡Acceso restringido!');
			}
		} else {
			redirect('admin');
		}
	}

	/**
	* Editamos los datos de un acta
	*
	* @access public
	* @param  $id 			Id de proyecto a editar
	*/
	public function edit($id)
	{
		if ($this->user->is_logged_in()) {
			if ($this->user->has_permission('admin_access')) {
				if ($this->user->has_permission('view_user_profiles')) {
					$this->load->model('Permissions_model');

					if ($this->input->post('token') == $this->session->userdata('token')) {
						$this->load->library('form_validation');

						$rules = array(
							array(
								'field'		=>	'role',
								'label'		=>	'lang:cms_general_label_role',
								'rules'		=>	'trim|required'
							),
						);

						$this->form_validation->set_rules($rules);

						if ($this->form_validation->run() === TRUE) {
							$this->lang->load('error');

							$role = $this->input->post('role');
							$status = $this->input->post('status');

							$data = array(
								'role'			=>	$role,
								'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
								'modified'		=>	$this->user->id,
								'modified_at'	=>	date('Y-m-d H:i:s')
							);

							if ($this->Permissions_model->edit(NULL, array('id' => $id), $data)) {
								$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
							} else {
								$this->template->set_flash_message(array('error' => $this->lang->line('error_message_general')));
							}
						}
					}

					$fields = 'id, role, status';
					$row = $this->Permissions_model->get(NULL, $fields, array('id' => (int)$id));

					if ($row) {
						$this->template->set('_role', $row[0]);
					}

					$this->load->helper('form');
					$this->template->add_js('view', 'permissions/script');
					$this->template->set('_title', $this->lang->line('cms_general_label_edit_role'));
					$this->template->set('_active', 'permissions');
					$this->template->set('_token', $this->user->token());
					$this->template->render('permissions/edit');
				} else {
					show_error('¡Acceso restringido!');
				}
			} else {
				show_error('¡Acceso restringido!');
			}
		} else {
			redirect('admin');
		}
	}


	public function perms($offset = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_access')) {
			show_error('¡Acceso restringido!');
		}

		if (!$this->user->has_permission('admin_permissions')) {
			show_error('¡Acceso restringido!');
		}

		$limit = 20;
		$total = 0;
		$this->load->model('Permissions_model');

		$result = $this->Permissions_model->getSpecial('permissions', 'id, title, name', array(), array(), $offset, $limit);
		$permissions = $result['data'];
		$total = $result['num_rows'];

		if (count($permissions) > 0) {
			$this->template->set('_permissions', $permissions);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/permissions/perms');
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 4;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_limit', $limit);
		$this->template->set('_title', $this->lang->line('cms_general_label_permissions'));
		$this->template->set('_active', 'permissions');
		$this->template->add_js('view', 'permissions/script');
		$this->template->render('permissions/perms');
	}

	public function permsRole($id = 0, $offset = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_access')) {
			show_error('¡Acceso restringido!');
		}

		if (!$this->user->has_permission('admin_permissions')) {
			show_error('¡Acceso restringido!');
		}

		$limit = 20;
		$total = 0;
		$this->load->model('Permissions_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
            $values = array_keys($_POST);
            $replace = array();
            $eliminar = array();

            for ($i = 0; $i < count($values); $i++) {
                if (substr($values[$i], 0, 5) == 'perm_') {
                    // Permite verificar que el id del permiso tenga dos dígitos
                    if (strstr(substr($values[$i], -2), '_')) {
                        $id_permiso = substr($values[$i], -1);
                    } else {
                        $id_permiso = substr($values[$i], -2);
                    }

                    if ($_POST[$values[$i]] == 'x') {
                        $eliminar[] = array(
                            'role' => $id,
                            'permiso' => $id_permiso
                        );
                    } else {
                        if ($_POST[$values[$i]] == 1) {
                            $v = 1;
                        } else {
                            $v = 0;
                        }
                        $replace[] = array(
                            'role' => $id,
                            'permiso' => $id_permiso,
                            'valor' => $v
                        );
                    }
                }
            }

            for ($i = 0; $i < count($eliminar); $i++) {
                $this->Permissions_model->eliminarPermisoRole($eliminar[$i]['role'], $eliminar[$i]['permiso']);
            }

            for ($i = 0; $i < count($replace); $i++) {
                $this->Permissions_model->editarPermisoRole($replace[$i]['role'], $replace[$i]['permiso'], $replace[$i]['valor']);
            }
		}

		$permissions = $this->Permissions_model->getPermisosRole($id);
		$data = array_slice($permissions, $offset, $limit);
		$total = count($permissions);

		if (count($permissions) > 0) {
			$this->template->set('_permissions', $data);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/permissions/permsRole/' . $id);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 5;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}


		$this->template->set('_limit', $limit);
		$this->load->helper('form');
		$role = $this->Permissions_model->get('roles', 'role', array('id' => $id));
		$this->template->set('_role', $role[0]);
		$this->template->set('_title', $this->lang->line('cms_general_label_permissions'));
		$this->template->set('_active', 'permissions');
		$this->template->set('_token', $this->user->token());
		$this->template->add_js('view', 'permissions/script');
		$this->template->render('permissions/permsRole');
	}

	/**
	* Método que cambia status del role vía ajax
	*
	* @access public
	* @param  $id 			Id del role
	* @param  $status 		Status del role
	*/
	public function action()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		if ((int)$id > 0) {
			$this->load->model('Permissions_model');

			$data = array(
				'status'		=>	$status,
				'modified'		=> 	$this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')
			);

			if ($this->Permissions_model->edit(NULL, array('id' => $id), $data)) {
				echo TRUE;
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}
	}

	/**
	* Método para eliminar un role de la base de datos
	*
	* @access public
	* @param  $id 			Id del role
	*/
	public function delete()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Permissions_model');

			if ($this->Permissions_model->delete(NULL, array('id' => $id))) {
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
	* Método para eliminar un role de la base de datos
	*
	* @access public
	* @param  $id 			Id del role
	*/
	public function delPerm()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Permissions_model');

			if ($this->Permissions_model->delete('permissions', array('id' => $id))) {
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
	* Verificamos si la clave ingresada para el permiso ya existe
	*
	* @access public
	*/
	public function verifyKey()
	{
		$key = $this->input->post('name');

		if (strlen($key) > 0) {
			$this->load->model('Permissions_model');
			if ($this->Permissions_model->get('permissions', 'id', array('name' => $key))) {
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
	* Agregar permiso vía ajax
	*
	* @access public
	*/
	public function addPerm()
	{
		$title = $this->input->post('title');
		$name = $this->input->post('name');

		if (strlen($title) > 0 && strlen($name) > 0) {
			$this->load->model('Permissions_model');
			$data = array(
				'title'			=>	$title,
				'name'			=>	$name,
				'created'		=>	$this->user->id,
				'created_at'	=>	date('Y-m-d H:i:s')
			);
			$this->Permissions_model->add('permissions', $data);
			echo TRUE;
		} else {
			echo FALSE;
		}
		exit;
	}

	/**
	* Método que devuelve los datos de un permiso
	*
	* @access public
	*/
	public function getPerm()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Permissions_model');
			$perm = $this->Permissions_model->get('permissions', 'title, name', array('id' => $id));

			if ($perm && count($perm) > 0) {
				echo json_encode($perm[0]);
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}

		exit;
	}

	/**
	* Método editar datos de un permiso
	*
	* @access public
	*/
	public function editPerm()
	{
		$id = $this->input->post('id');
		$title = $this->input->post('title');
		$name = $this->input->post('name');

		if ((int)$id > 0){
			$this->load->model('Permissions_model');
			$data = array(
				'title'			=>	$title,
				'name'			=>	$name,
				'modified'		=>	$this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')

			);

			if ($this->Permissions_model->edit('permissions', array('id' => $id), $data)) {
				$perm = $this->Permissions_model->get('permissions', 'title, name', array('id' => $id));
				if ($perm && count($perm) > 0) {
					echo json_encode($perm[0]);
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

/* End of file permissions.php */
/* Location: ./application/modules/admin/controllers/permissions.php */