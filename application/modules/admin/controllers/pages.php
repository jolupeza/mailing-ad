<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends MY_Controller
{
	/**
	* Mostrar listado de páginas ya sea publicados - despublicados, publicados o que se encuentren en la papelera
	*
	* @access public
	*/
	public function display()
	{
		if ($this->user->is_logged_in()) {
			$this->template->set('_title', $this->lang->line('cms_general_title_pages'));
			$this->template->add_css('base', 'bootstrap/css/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'bootstrap/moment');
			$this->template->add_js('base', 'bootstrap/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'libraries/tinymce/tinymce.min');
			$this->template->add_js('view', 'pages/script');
			$this->template->render('pages/index');
		} else {
			redirect('admin');
		}
	}

	public function displayPageAjax($status = 'all', $sort_by = 'created_at', $sort_order="desc", $search = 'all', $offset = 0)
	{
		$limit = 10;
		$total = 0;
		$this->load->model('Posts_model');
		if ($status == 'all') {
			$result = $this->Posts_model->getAllPosts('page', array(0, 1), $limit, $offset, $sort_by, $sort_order, $search, null, 'master');
			$posts = (isset($result['masters'])) ? $result['masters'] : $result['data'];
			$total = $result['num_rows'];
		} else {
			$result = $this->Posts_model->getAllPosts('page', array($status), $limit, $offset, $sort_by, $sort_order, $search, null, 'master');
			$posts = (isset($result['masters'])) ? $result['masters'] : $result['data'];
			$total = $result['num_rows'];
		}

		if (sizeof($posts) > 0) {
			$this->template->set('_masters', $posts);

			if (isset($result['childrens'])) {
				$this->template->set('_childrens', $result['childrens']);
			}

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/pages/displayPageAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 8;
				$this->pagination->initialize($config);
				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_status', (!is_null($status)) ? $status : 'all');
		$this->template->set('_countTotal', $this->Posts_model->countRows(array(0,1), 'page'));
		$this->template->set('_countPublish', $this->Posts_model->countRows(array(1), 'page'));
		$this->template->set('_countTrush', $this->Posts_model->countRows(array(2), 'page'));
		$this->template->set('_total', $total);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->renderAjax('pages/displayAjax');
	}

	public static function nested($rows = array(), $parent_id = 0)
	{
		$CI =& get_instance();
		$html = "";
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if ($row->post_parent == $parent_id) {
					if ($CI->session->userdata('children')) {
						$CI->session->set_userdata('children', $CI->session->userdata('children') + 1);
					} else {
						$CI->session->set_userdata('children', 1);
					}

					$html .= '<tr><td class="text-center">' . $row->id . '</td>';
					$html .= '<td class="view-option-post">';
					$html .= '<a class="children-' . $CI->session->userdata('children') . '" href="' . base_url() . 'admin/pages/edit/' . $row->id .'">'. $row->post_title . '</a>';
					$html .= '<div class="opt-post">';
					$html .= '<ul class="list-inline">';
					$html .= '<li>';
					$html .= '<small>';
					$html .= '<a href="' . base_url() . 'admin/pages/edit/' . $row->id . '">' . $CI->lang->line('cms_general_label_edit') . '</a>';
					$html .= '</small>';
					$html .= '</li>';
					$CI->load->model('Posts_model');
					if (!$CI->Posts_model->verifyPagesChildren($row->id)) {
						$html .= '<li><small><a class="text-danger" href="'. base_url() . 'admin/pages/action/' . $row->id . '">' . $CI->lang->line('cms_general_label_trush') . '</a></small></li>';
					}
					$html .= '<li><small><a href="#">Ver</a></small></li>';
					$html .= '</ul>';
					$html .= '</div>';
					$html .= '</td>';
					$html .= '<td>' . $row->user . '</td>';
					$html .= '<td class="text-center"><span class="glyphicon glyphicon-comment"></span></td>';
					$html .= '<td class="text-center">';
					$html .= '<span>' . date($CI->config->item('cms_date_format'), strtotime($row->published_at)). '</span>';
					$status = ($row->post_status == 1) ? $CI->lang->line('cms_general_label_publish') : $CI->lang->line('cms_general_label_unpublish');
					$html .= '<span>' . $status . '</span></td>';
					$html .= '</tr>';
					$html .= self::nested($rows, $row->id);
				}
			}
			$CI->session->unset_userdata('children');
		}
		return $html;
	}

	/**
	* Método para editar los atributos de un post en concreto
	*
	* @access public
	* @param  $id 			int			Id de post a editar
	*/
	public function edit($id)
	{
		if ($this->user->is_logged_in()) {
			$this->load->model('Posts_model');

			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');

				$rules = array(
					array(
						'field'		=>	'post_title',
						'label'		=>	'lang:cms_general_label_title',
						'rules'		=>	'trim|required'
					),
					array(
						'field'		=>	'post_name',
						'label'		=>	'lang:cms_general_label_permalink',
						'rules'		=>	'trim|required|alpha_dash'
					)
				);

				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() === TRUE) {
					$this->load->helper('functions');
					$file_info = '';
					if (isset($_FILES['image'])) {
						if ($_FILES['image']['name'] != '') {
							// En caso de haber asignado imagen destacada subimos la imagen y la almacenamos como un post nuevo de tipo attachment
							$name = explode('.', $_FILES['image']['name']);
							$config['upload_path'] = './uploads/';
					        $config['allowed_types'] = 'gif|jpg|png';
					        $config['file_name'] = slug($name[0]);
					        $config['max_size'] = '1024';
					        $config['max_width'] = '1680';
					        $config['max_height'] = '1050';
					        $this->load->library('upload', $config);

					        if (!$this->upload->do_upload('image')) {
					        	$this->template->set_flash_message(array('error' => $this->upload->display_errors()));
					        	redirect('pages/edit/' . $id);
					        } else {
					        	$file_info = $this->upload->data();

					        	$this->load->library('image_lib');
					        	// Creamos los thumbnails
					        	if ($this->config->item('cms_thumbnail_size_w') > 0 OR $this->config->item('cms_thumbnail_size_h') > 0) {
					        		$this->_create_thumbnail($file_info['file_name'], $this->config->item('cms_thumbnail_size_w'), $this->config->item('cms_thumbnail_size_h'), $this->config->item('cms_thumbnail_crop'));
					        	}

					        	if ($this->config->item('cms_medium_size_w') > 0 OR $this->config->item('cms_medium_size_h') > 0) {
					        		$this->_create_thumbnail($file_info['file_name'], $this->config->item('cms_medium_size_w'), $this->config->item('cms_medium_size_h'));
					        	}

					        	if ($this->config->item('cms_large_size_w') > 0 OR $this->config->item('cms_large_size_h') > 0) {
					        		$this->_create_thumbnail($file_info['file_name'], $this->config->item('cms_large_size_w'), $this->config->item('cms_large_size_h'));
					        	}
					        }
						}
					}

					$data = array(
						'post_content'		=>	$this->input->post('post_content'),
						'post_title'		=>	$this->input->post('post_title'),
						'post_status'		=>	$this->input->post('post_status'),
						'post_name'			=>	$this->input->post('post_name'),
						'post_parent'		=>	$this->input->post('post_parent'),
						'published_at'		=>	$this->input->post('published_at'),
						'modified'			=>	$this->user->id,
						'modified_at'		=>	date('Y-m-d H:i:s'),
					);

					$this->Posts_model->editPost($data, $this->input->post('id'));

					if (is_array($file_info)) {
						$slug = slug($file_info['raw_name']);
						$data = array(
							'post_title'		=>	$file_info['raw_name'],
							'post_name'			=>	$slug,
							'post_parent'		=>	$id,
							'guid'				=>	base_url() . 'uploads/' . $slug . $file_info['file_ext'],
							'post_type'			=>	'attachment',
							'post_mime_type'	=>	$file_info['file_type'],
							'created'			=>	$this->user->id,
							'created_at'		=>	date('Y-m-d H:i:s'),
							'published_at'		=>	$this->input->post('published_at')
						);
						$this->Posts_model->addPost($data);
					}

					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				}
			}

			$this->load->helper('form');
			$page = $this->Posts_model->getPost((int)$id);

			if (is_object($page)) {
				$this->template->set('_page', $page);
			}

			$this->template->add_css('base', 'bootstrap/css/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'bootstrap/moment');
			$this->template->add_js('base', 'bootstrap/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'libraries/tinymce/tinymce.min');
			$this->template->add_js('view', 'pages/script');
			$this->template->set('_title', $this->lang->line('cms_general_title_edit_page'));
			$this->template->set('_token', $this->user->token());
			$this->template->set('_pages', $this->Posts_model->getPages());
			$this->template->render('pages/edit');
		} else {
			redirect('admin');
		}
	}

		/**
		* Cargar categorías hijas
		*
		* @access public
		* @param  $rows 		arr 		Array con las categorías hijas
		* @param  $parent_id	int 		Id con las categoría padre
		* @param  $post_id 		int 		Id del post actual
		* @return 				str 		String con la lista de categorías hijas
		*/
	public static function childrenCategories($rows = array(), $parent_id = 0, $post_id = 0)
	{
		$CI =& get_instance();
		$html = "";
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if ($row->parent == $parent_id) {
					$select = '';
					if ((int)$post_id > 0) {
						$CI->load->model('Posts_model');
						$postCategories = $CI->Posts_model->getCategoriesPost((int)$post_id);
						$categories = array();
						if (count($postCategories) > 0) {
							foreach ($postCategories as $cat) {
								$categories[] = $cat->term_taxonomy_id;
							}
						}
						$select = (in_array($row->term_id, $categories)) ? 'checked="checked"' : '';
					}

					$html .= '<ul>';
					$html .= '<li>';
					$html .= '<div class="checkbox">';
					$html .= '<label>';
					$html .= '<input type="checkbox" name="post_category[]" value="' . $row->term_id . '" ' . $select . ' />';
					$html .= $row->name;
					$html .= '</label>';
					$html .= '</div>';
					$html .= self::childrenCategories($rows, $row->term_id, $post_id);
					$html .= '</li>';
					$html .= '</ul>';
				}
			}
		}
		return $html;
	}

	/**
	* Método para agregar una nueva página
	*
	* @access public
	*/
	public function add()
	{
		if ($this->user->is_logged_in()) {
			$this->load->model('Posts_model');

			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');

				$rules = array(
					array(
						'field'		=>	'post_title',
						'label'		=>	'lang:cms_general_label_title',
						'rules'		=>	'trim|required'
					)
				);

				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() === TRUE) {
					$this->load->helper('functions');
					$slug = slug($this->input->post('post_title'));

					$file_info = '';
					if (isset($_FILES['image'])) {
						if ($_FILES['image']['name'] != '') {
							// En caso de haber asignado imagen destacada subimos la imagen y la almacenamos como un post nuevo de tipo attachment
							$name = explode('.', $_FILES['image']['name']);
							$config['upload_path'] = './uploads/';
					        $config['allowed_types'] = 'gif|jpg|png';
					        $config['file_name'] = slug($name[0]);
					        $config['max_size'] = '1024';
					        $config['max_width'] = '1680';
					        $config['max_height'] = '1050';
					        $this->load->library('upload', $config);

					        if (!$this->upload->do_upload('image')) {
					        	$this->template->set_flash_message(array('error' => $this->upload->display_errors()));
					        	redirect('pages/add');
					        } else {
					        	$file_info = $this->upload->data();

					        	$this->load->library('image_lib');
					        	// Creamos los thumbnails
					        	if ($this->config->item('cms_thumbnail_size_w') > 0 OR $this->config->item('cms_thumbnail_size_h') > 0) {
					        		$this->_create_thumbnail($file_info['file_name'], $this->config->item('cms_thumbnail_size_w'), $this->config->item('cms_thumbnail_size_h'), $this->config->item('cms_thumbnail_crop'));
					        	}

					        	if ($this->config->item('cms_medium_size_w') > 0 OR $this->config->item('cms_medium_size_h') > 0) {
					        		$this->_create_thumbnail($file_info['file_name'], $this->config->item('cms_medium_size_w'), $this->config->item('cms_medium_size_h'));
					        	}

					        	if ($this->config->item('cms_large_size_w') > 0 OR $this->config->item('cms_large_size_h') > 0) {
					        		$this->_create_thumbnail($file_info['file_name'], $this->config->item('cms_large_size_w'), $this->config->item('cms_large_size_h'));
					        	}
					        }
						}
					}

					$data = array(
						'post_content'		=>	$this->input->post('post_content'),
						'post_title'		=>	$this->input->post('post_title'),
						'post_status'		=>	$this->input->post('post_status'),
						'post_name'			=>	$slug,
						'post_parent'		=>	$this->input->post('post_parent'),
						'post_type'			=>	'page',
						'created'			=>	$this->user->id,
						'created_at'		=>	date('Y-m-d H:i:s'),
						'published_at'		=>	$this->input->post('published_at')
					);
					$last_id = $this->Posts_model->addPost($data);
					// Ingresamos el guid de la página
					$data = array(
						'guid'				=>	base_url() . '?page_id=' . $last_id
					);
					$this->Posts_model->editPost($data, $last_id);

					if (is_array($file_info)) {
						$slug = slug($file_info['raw_name']);
						$data = array(
							'post_title'		=>	$file_info['raw_name'],
							'post_name'			=>	$slug,
							'post_parent'		=>	$last_id,
							'guid'				=>	base_url() . 'uploads/' . $slug . $file_info['file_ext'],
							'post_type'			=>	'attachment',
							'post_mime_type'	=>	$file_info['file_type'],
							'created'			=>	$this->user->id,
							'created_at'		=>	date('Y-m-d H:i:s'),
							'published_at'		=>	$this->input->post('published_at')
						);
						$this->Posts_model->addPost($data);
					}

					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					redirect('pages/edit/' . $last_id);
				}
			}

			$this->load->helper('form');
			$this->template->add_css('base', 'bootstrap/css/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'bootstrap/moment');
			$this->template->add_js('base', 'bootstrap/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'libraries/tinymce/tinymce.min');
			$this->template->add_js('view', 'pages/script');
			$this->template->set('_title', $this->lang->line('cms_general_label_add_page'));
			$this->template->set('_token', $this->user->token());
			$this->template->set('_pages', $this->Posts_model->getPages());
			$this->template->render('pages/add');
		} else {
			redirect('admin');
		}
	}

	/**
	* Método que permite realizar dos acciones a un post en conreto (mover a la papelera o restaurar de la papelera)
	*
	* @access public
	* @param  $id 			int			Id del post a editar
	* @param  $action 		int			Indicamos el estado que queremos colocar al post 2 mover a la papelera o 0 restaurar.
	*/
	public function action($id = 0, $action = 0)
	{
		if ($this->user->is_logged_in()) {
			if ((int)$id > 0) {
				$this->load->model('Posts_model');
				$data = array(
					'post_status'		=>	(int)$action,
					'modified'			=>	$this->user->id,
					'modified_at'		=>	date('Y-m-d H:i:s')
				);
				if (!$this->Posts_model->editPost($data, $id)) {
					$this->template->set_flash_message(array('error' => $this->lang->line('cms_general_label_error_action')));
				} else {
					if ($action == 0) {
						$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_restore')));
					} else if ($action == 2) {
						$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_trush')));
					}
				}
			}
			redirect('admin/pages/display');
		} else {
			redirect('admin');
		}
	}

	/**
	* Método para eliminar un post.
	*
	* @access public
	* @param  $id 			int 		Id del post a eliminar
	*/
	public function delete($id = 0)
	{
		if ($this->user->is_logged_in()) {
			if ((int)$id > 0) {
				$this->load->model('Posts_model');
				if ($this->Posts_model->deletePost($id)) {
					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_delete')));
				} else {
					$this->template->set_flash_message(array('error' => $this->lang->line('cms_general_label_error_action')));
				}
			}
			redirect('admin/pages/display');
		} else {
			redirect('admin');
		}
	}

	/**
	* Desasociar una imagen destacada asignada a un post
	*
	* @access public
	* @return snippet 		snippet
	*/
	public function deleteFeaturedImages()
	{
		$id = $this->input->post('id');
		$data = array(
			'post_parent'		=>	0,
			'modified'			=>	$this->user->id,
			'modified_at'		=>	date('Y-m-d H:i:s')
		);
		$this->load->model('Posts_model');
		if ($this->Posts_model->editPost($data, $id)) {
			echo TRUE;
		} else {
			echo FALSE;
		}
	}

	/**
	* Método para generar thumbnails con los tamaños pasados como parámetros
	*
	* @access private
	* @param  $filename 	str 	Nombre de la imagen a generar los thumbnails
	* @param  $width 		str 	Ancho del thumbnail
	* @param  $height 		str 	Alto del thumbnail
	* @param  $crop 		int 	Si realizamos crop o no
	*/
	private function _create_thumbnail($filename, $width, $height, $crop = 0){

        $config['image_library'] = 'gd2';
        //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
        $config['source_image'] = 'uploads/'.$filename;
        $config['create_thumb'] = TRUE;
        $crop = ($crop == 0) ? TRUE : FALSE;
        $config['maintain_ratio'] = $crop;
        //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
        $config['new_image']='uploads/';
        $config['thumb_marker'] = '-' . $width . 'x' . $height;
        $config['width'] = $width;
        $config['height'] = $height;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }
}

/* End of file posts.php */
/* Location: ./application/modules/admin/controllers/pages.php */