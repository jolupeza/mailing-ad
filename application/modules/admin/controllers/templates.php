<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templates extends MY_Controller
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

		if (!$this->user->has_permission('view_tmp_email')) {
			show_error('¡Acceso restringido!');
		}

		$this->template->set('_title', $this->lang->line('cms_general_title_templates'));
		$this->template->set('_active', 'templates');
		$this->template->add_js('view', 'templates/script');
		$this->template->render('templates/index');
	}

	/**
	* Método que vía ajax obtiene y muestra las plantillas email disponibles
	*
	* @access public
	* @param  $sort_by 		Indicamos a través de que campo se ordena
	* @param  $sort_order 	Indicamos si se ordena ascendente o descendentemente
	* @param  $search 		Indicamos si se pasa un parámetro de búsqueda
	* @param  $offset 		Indicamos desde que registro obtenemos la lista
	*/
	public function displayAjax($sort_by = 'id', $sort_order = 'desc', $search = 'all',  $offset = 0)
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		$limit = 10;
		$total = 0;
		$this->load->model('Templates_model');
		$this->load->helper('form');

		$result = $this->Templates_model->getAll($limit, $offset, $sort_by, $sort_order, $search);
		$templates = $result['data'];
		$total = $result['num_rows'];

		if (count($templates) > 0) {
			$this->template->set('_templates', $templates);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/templates/displayAjax/' . $sort_by . '/' . $sort_order . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 7;

				$this->pagination->initialize($config);

				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_total', $total);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->renderAjax('templates/displayAjax');
	}

	/**
	* Método para añadir plantilla email
	*
	* @access public
	*/
	public function add()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('create_tmp_email')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Templates_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			if ($_FILES['source']['name'] != '') {
				$this->load->library('form_validation');

				$rules = array(
					array(
						'field'		=>	'name',
						'label'		=>	'lang:cms_general_label_name',
						'rules'		=>	'trim|required|callback_name_check'
					),
				);

				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run($this) === TRUE) {
					$this->load->helper(array('path', 'functions', 'directory'));
					$this->load->library(array('upload', 'image_lib'));
					$status = $this->input->post('status');

					// Subimos el archivo html
					$template = $this->input->post('name');
					$slug = slug($template);

					$dir = set_realpath('./mailing/' . $slug . '/');
					//echo $dir; exit;

					// Creamos el directorio en caso de no existir
					if(!is_dir($dir)){
					    mkdir($dir, 0777);
					    chmod($dir, 0777);
					}

					$config['upload_path'] = './mailing/' . $slug;
			        $config['allowed_types'] = 'html';
			        $config['file_name'] = 'index';
			        $config['max_size'] = '1024';
			        $this->upload->initialize($config);

			        $file_html = '';
			        if (!$this->upload->do_upload('source')) {
			        	// Eliminamos directorio creado
			        	if (is_dir($dir)) {
			        		rmdir($dir);
			        	}

			        	$this->template->set_flash_message(array('error' => $this->upload->display_errors()));
			        	redirect('admin/templates/add');
			        } else {
			        	$file_html = $this->upload->data();

			        	// Vemos si existe imagen destacada y generamos los thumbnails configurados
			        	$featured = $this->input->post('featured');
			        	if (!empty($featured)) {
				        	$featured_arr = explode('/', $featured);
				        	$name_featured = $featured_arr[count($featured_arr) - 1];

				        	$dir_thumb = directory_map('./ad-content/thumbs/');

				        	if ($this->config->item('cms_thumbnail_size_w') > 0 OR $this->config->item('cms_thumbnail_size_h') > 0) {
				        		if (!in_array($name_featured . '-' . $this->config->item('cms_thumbnail_size_w') . 'x' . $this->config->item('cms_thumbnail_size_h'), $dir_thumb)) {
				        			create_thumbnail($name_featured, $this->config->item('cms_thumbnail_size_w'), $this->config->item('cms_thumbnail_size_h'), $this->config->item('cms_thumbnail_crop'));
				        		}
				        	}

				        	if ($this->config->item('cms_medium_size_w') > 0 OR $this->config->item('cms_medium_size_h') > 0) {
				        		if (!in_array($name_featured . '-' . $this->config->item('cms_medium_size_w') . 'x' . $this->config->item('cms_medium_size_h'), $dir_thumb)) {
				        			create_thumbnail($name_featured, $this->config->item('cms_medium_size_w'), $this->config->item('cms_medium_size_h'), $this->config->item('cms_thumbnail_crop'));
				        		}
				        	}

				        	if ($this->config->item('cms_large_size_w') > 0 OR $this->config->item('cms_large_size_h') > 0) {
				        		if (!in_array($name_featured . '-' . $this->config->item('cms_large_size_w') . 'x' . $this->config->item('cms_large_size_h'), $dir_thumb)) {
				        			create_thumbnail($name_featured, $this->config->item('cms_large_size_w'), $this->config->item('cms_large_size_h'), $this->config->item('cms_thumbnail_crop'));
				        		}
				        	}
			        	}

						$data = array(
							'name'			=>	$template,
							'slug'			=>	$slug,
							'description'	=>	$this->input->post('description'),
							'guid'			=> 	base_url() . 'mailing/' . $slug . '/index.html',
							'guid_path'		=>	$file_html['file_path'],
							'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
							'image'			=>	(!empty($featured) && $featured != '') ? $featured : '',
							'created'		=>	$this->user->id,
							'created_at'	=>	date('Y-m-d H:i:s')
						);

						$last_id = $this->Templates_model->add(NULL, $data);

						$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
						redirect('admin/templates/edit/' . $last_id);
			        }
				}
			} else {
				$this->lang->load('upload');
				$this->template->add_message(array('error' => $this->lang->line('upload_no_file_selected')));
			}
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'templates/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_add_template'));
		$this->template->set('_active', 'templates');
		$this->template->set('_token', $this->user->token());
		$this->template->render('templates/add');
	}

	/**
	* Método que verifica si el nombre ingresado al template ya existe
	*
	* @access public
	* @param  $name 		Nombre del template a ingresar
	* @return boolean 		Si no existe devuelve TRUE caso contrario FALSE
	*/
	public function name_check($name)
	{
		$this->load->model('Templates_model');
		if ($this->Templates_model->get(NULL, 'id', array('name' => $name))) {
			$this->form_validation->set_message('name_check', $this->lang->line('error_name_check'));
			return FALSE;
		} else {
			return TRUE;
		}
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

		if (!$this->user->has_permission('edit_any_tmp_email')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Templates_model');

		// Traemos los datos de la plantilla
		$fields = 'id, name, slug, description, guid, guid_path, status, image';

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
				$this->load->helper(array('path', 'functions', 'directory'));
				$this->load->library(array('upload', 'image_lib'));

				$row = $this->Templates_model->get(NULL, $fields, array('id' => (int)$id));

				$template = $this->input->post('name');
				$description = $this->input->post('description');
				$status = $this->input->post('status');

				$slug = $row[0]->slug;
				$dir = $row[0]->guid_path;

				if (isset($_FILES['source']) && $_FILES['source']['error'] == 4) {
					$this->lang->load('upload');
					$this->template->set_flash_message(array('error' => $this->lang->line('upload_no_file_selected')));
					redirect('admin/templates/edit/' . $id);
				}

				if ($template != $row[0]->name) {
					// Verificamos que no exista plantilla con el nuevo nombre
					if (!$this->Templates_model->get(NULL, 'id', array('name' => $template))) {
						// Eliminamos directorio anterior
						$slug = slug($template);
						$dir = set_realpath('./mailing/' . $slug . '/');

						rename($row[0]->guid_path, $dir);
					} else {

						$this->template->set_flash_message(array('error' => $this->lang->line('error_name_check')));
		        		redirect('admin/templates/edit/' . $id);
					}
				}

				$file_html = '';
				// En caso se haya eliminado el archivo html correspondiente a la plantilla
				if (isset($_FILES['source']) && $_FILES['source']['error'] == 0) {
					// Eliminarmos archivo anterior
					if (file_exists($row[0]->guid_path . 'index.html')) {
						unlink($row[0]->guid_path . 'index.html');
					}

					// Subimos el archivo html
					$config['upload_path'] = './mailing/' . $slug;
			        $config['allowed_types'] = 'html';
			        $config['file_name'] = 'index';
			        $config['max_size'] = '1024';
			        $this->upload->initialize($config);

			        if (!$this->upload->do_upload('source')) {
			        	$this->template->set_flash_message(array('error' => $this->upload->display_errors()));
			        	redirect('admin/templates/edit/' . $id);
			        } else {
			        	$file_html = $this->upload->data();
			        }
				}

				// Vemos si existe imagen destacada y generamos los thumbnails configurados
	        	$featured = $this->input->post('featured');
	        	if (!empty($featured) && ($featured != $row[0]->image)) {
	        		if (!empty($featured)) {
			        	$featured_arr = explode('/', $featured);
			        	$name_featured = $featured_arr[count($featured_arr) - 1];

			        	$dir_thumb = directory_map('./ad-content/thumbs/');

			        	if ($this->config->item('cms_thumbnail_size_w') > 0 OR $this->config->item('cms_thumbnail_size_h') > 0) {
			        		if (!in_array($name_featured . '-' . $this->config->item('cms_thumbnail_size_w') . 'x' . $this->config->item('cms_thumbnail_size_h'), $dir_thumb)) {
			        			create_thumbnail($name_featured, $this->config->item('cms_thumbnail_size_w'), $this->config->item('cms_thumbnail_size_h'), $this->config->item('cms_thumbnail_crop'));
			        		}
			        	}

			        	if ($this->config->item('cms_medium_size_w') > 0 OR $this->config->item('cms_medium_size_h') > 0) {
			        		if (!in_array($name_featured . '-' . $this->config->item('cms_medium_size_w') . 'x' . $this->config->item('cms_medium_size_h'), $dir_thumb)) {
			        			create_thumbnail($name_featured, $this->config->item('cms_medium_size_w'), $this->config->item('cms_medium_size_h'), $this->config->item('cms_thumbnail_crop'));
			        		}
			        	}

			        	if ($this->config->item('cms_large_size_w') > 0 OR $this->config->item('cms_large_size_h') > 0) {
			        		if (!in_array($name_featured . '-' . $this->config->item('cms_large_size_w') . 'x' . $this->config->item('cms_large_size_h'), $dir_thumb)) {
			        			create_thumbnail($name_featured, $this->config->item('cms_large_size_w'), $this->config->item('cms_large_size_h'), $this->config->item('cms_thumbnail_crop'));
			        		}
			        	}
		        	}
	        	}

				$data = array(
					'name'				=>	$template,
					'slug'				=>	$slug,
					'description'		=>	$description,
					'guid'				=> 	base_url() . 'mailing/' . $slug . '/index.html',
					'guid_path'			=>	(!empty($file_html['file_path']) && $file_html['file_path'] != '') ? $file_html['file_path'] : $dir,
					'status'			=>	(isset($status) && !empty($status)) ? $status : 0,
					'image'				=>	(!empty($featured) && $featured != '') ? $featured : '',
					'modified'			=>	$this->user->id,
					'modified_at'		=>	date('Y-m-d H:i:s')
				);

				if ($this->Templates_model->edit(NULL, array('id' => $id), $data)) {
					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				}
			}
		}

		$row = $this->Templates_model->get(NULL, $fields, array('id' => (int)$id));
		if ($row) {
			$this->template->set('_temp', $row[0]);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'templates/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_template'));
		$this->template->set('_active', 'templates');
		$this->template->set('_token', $this->user->token());
		$this->template->render('templates/edit');
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
			$this->load->model('Templates_model');

			$data = array(
				'status'		=>	$status,
				'modified'		=> 	$this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')
			);

			if ($this->Templates_model->edit(NULL, array('id' => $id), $data)) {
				echo TRUE;
			} else {
				echo FALSE;
			}
		} else {
			echo FALSE;
		}
	}

	/**
	* Método para eliminar un template
	*
	* @access public
	* @param  $id 			Id de template
	*/
	public function delete()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			if ($this->user->has_permission('del_any_tmp_email')) {
				$this->load->model('Templates_model');
				$result = $this->Templates_model->get(NULL, 'guid_path, image_path', array('id' => $id));

				// Eliminamos carpeta de template
				$dir = $result[0]->guid_path;

				$this->load->helper('functions');
				rrmdir($dir);

				if ($this->Templates_model->delete(NULL, array('id' => $id))) {
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

/* End of file newsletters.php */
/* Location: ./application/modules/admin/controllers/templates.php */ ?>