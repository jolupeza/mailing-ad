<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends MY_Controller
{
	/**
	* Mostrar listado de posts ya sea publicados - despublicados, publicados o que se encuentren en la papelera
	*
	* @access public
	* @param  $status 		str 		Indicamos que status de post queremos mostrar (all, 1, 2)
	* @param  $offset 		int 		Utilizado para la paginación esto se actualiza automáticamente a través de la librería pagination
	*/
	public function display()
	{
		if ($this->user->is_logged_in()) {
			$this->template->set('_title', $this->lang->line('cms_general_title_posts'));
			$this->template->add_css('base', 'bootstrap/css/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'bootstrap/moment');
			$this->template->add_js('base', 'bootstrap/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'libraries/summernote/summernote.min');
			$this->template->add_js('view', 'posts/script');
			$this->template->render('posts/index');
		} else {
			redirect('admin');
		}
	}

	public function displayPostAjax($status = 'all', $sort_by = 'created_at', $sort_order="desc", $search = 'all', $category = 'all', $offset = 0)
	{
		$limit = 10;
		$total = 0;
		$this->load->model('Posts_model');
		if ($status == 'all') {
			$result = $this->Posts_model->getAllPosts('post', array(0, 1), $limit, $offset, $sort_by, $sort_order, $search, $category);
			$posts = $result['data'];
			$total = $result['num_rows'];
		} else {
			$result = $this->Posts_model->getAllPosts('post', array($status), $limit, $offset, $sort_by, $sort_order, $search, $category);
			$posts = $result['data'];
			$total = $result['num_rows'];
		}

		if (sizeof($posts) > 0) {
			$this->template->set('_posts', $posts);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/posts/displayPostAjax/' . $status . '/' . $sort_by . '/' . $sort_order . '/' . $search . '/' . $category);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 9;
				$this->pagination->initialize($config);
				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_status', (!is_null($status)) ? $status : 'all');
		$this->template->set('_countTotal', $this->Posts_model->countRows(array(0,1,2)));
		$this->template->set('_countPublish', $this->Posts_model->countRows(array(1)));
		$this->template->set('_countTrush', $this->Posts_model->countRows(array(2)));
		$this->template->set('_total', $total);
		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->set('_category', $category);
		$this->template->renderAjax('posts/displayAjax');
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
				var_dump($this->input->post()); exit;
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
					        	redirect('admin/posts/edit/' . $id);
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
						'post_excerpt'		=>	$this->input->post('post_excerpt'),
						'post_status'		=>	$this->input->post('post_status'),
						'post_name'			=>	$this->input->post('post_name'),
						'published_at'		=>	$this->input->post('published_at'),
						'modified'			=>	$this->user->id,
						'modified_at'		=>	date('Y-m-d H:i:s')
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

					$relationships = $this->Posts_model->get('term_relationships', array('post_id' => $id), $id);
					//var_dump($relationships); exit;

					if ($this->input->post('post_category') && is_array($this->input->post('post_category'))) {
						$terms = $this->input->post('post_category');

						$arrRelationships = array();
						if (count($relationships) > 0) {
							foreach ($relationships as $rel) {
								$arrRelationships[] = $rel->term_taxonomy_id;

								if (!in_array($rel->term_taxonomy_id, $terms)) {
									$this->Posts_model->delete('term_relationships', array('post_id' => $id, 'term_taxonomy_id' => $rel->term_taxonomy_id));

									$term = $this->Posts_model->get('term_taxonomy', array('term_id' => $rel->term_taxonomy_id), $rel->term_taxonomy_id);
									$count = $term[0]->count;
									$count--;
									$this->Posts_model->edit('term_taxonomy', array('term_id' => $rel->term_taxonomy_id), array('count' => $count, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')), $rel->term_taxonomy_id);
								}
							}
						}

						if (count($arrRelationships) > 0) {
							foreach ($terms as $post_category) {
								if (!in_array($post_category, $arrRelationships)) {
									$data = array(
										'post_id'			=>	$id,
										'term_taxonomy_id'	=>	$post_category,
										'created'			=>	$this->user->id,
										'created_at'		=>	date('Y-m-d H:i:s')
									);
									$this->Posts_model->addTermRelationships($data);

									// Seleccionamos cuantos posts asociados tiene la categoría actualmente y le agregamos en 1 por cada acategoría
									$term = $this->Posts_model->get('term_taxonomy', array('term_id' => $post_category), $post_category);
									$count = $term[0]->count;
									$count++;
									$this->Posts_model->edit('term_taxonomy', array('term_id' => $post_category), array('count' => $count, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')), $post_category);
								}
							}
						}
					} else {
						if (count($relationships) > 0) {
							foreach ($relationships as $rel) {
								$term = $this->Posts_model->get('term_taxonomy', array('term_id' => $rel->term_taxonomy_id), $rel->term_taxonomy_id);
								$count = $term[0]->count;
								$count--;
								$this->Posts_model->edit('term_taxonomy', array('term_id' => $rel->term_taxonomy_id), array('count' => $count, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')), $rel->term_taxonomy_id);
							}
						}

						// Debemos eliminar todos las categorías relacionadas y activar solo la categoría Sin Categoría
						$this->Posts_model->deletePostTaxonomyRelationships($id);
						$data = array(
							'post_id'			=>	$id,
							'term_taxonomy_id'	=>	1,
							'created'			=>	$this->user->id,
							'created_at'		=>	date('Y-m-d H:i:s')
						);
						$this->Posts_model->addTermRelationships($data);

						// Seleccionamos cuantos posts asociados tiene la categoría actualmente y le agregamos en 1 por cada acategoría
						$term = $this->Posts_model->get('term_taxonomy', array('term_id' => 1), 1);
						$count = $term[0]->count;
						$count++;
						$this->Posts_model->edit('term_taxonomy', array('term_id' => 1), array('count' => $count, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')), 1);
					}
					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				}
			}

			$this->load->helper('form');
			$post = $this->Posts_model->getPost((int)$id);
			$postCategories = $this->Posts_model->getCategoriesPost((int)$id);

			if (is_object($post)) {
				$this->template->set('_post', $post);
				$categories = array();
				if (count($postCategories) > 0) {
					foreach ($postCategories as $cat) {
						$categories[] = $cat->term_taxonomy_id;
					}
				}
				$this->template->set('_categoriesPost', $categories);
			}

			$this->template->add_css('base', 'bootstrap/css/bootstrap-datetimepicker.min');
			$this->template->add_css('base', 'libraries/summernote/summernote');
			$this->template->add_js('base', 'bootstrap/moment');
			$this->template->add_js('base', 'bootstrap/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'libraries/summernote/summernote.min');
			$this->template->add_js('view', 'posts/script');
			$this->template->set('_title', $this->lang->line('cms_general_title_edit_post'));
			$this->template->set('_token', $this->user->token());
			$this->load->model('Terms_Model');
			$this->template->set('_categories', $this->Terms_Model->getCategoriesPost());
			$this->template->render('posts/edit');
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
	* Método para agregar un nuevo post
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
					        	redirect('admin/posts/add');
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
						'post_excerpt'		=>	$this->input->post('post_excerpt'),
						'post_status'		=>	$this->input->post('post_status'),
						'post_name'			=>	$slug,
						'created'			=>	$this->user->id,
						'created_at'		=>	date('Y-m-d H:i:s'),
						'published_at'		=>	$this->input->post('published_at')
					);
					$last_id = $this->Posts_model->addPost($data);

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

					if ($this->input->post('post_category') && is_array($this->input->post('post_category'))) {
						foreach ($this->input->post('post_category') as $post_category) {
							$data = array(
								'post_id'			=>	$last_id,
								'term_taxonomy_id'	=>	$post_category,
								'created'			=>	$this->user->id,
								'created_at'		=>	date('Y-m-d H:i:s')
							);
							$this->Posts_model->addTermRelationships($data);

							// Seleccionamos cuantos posts asociados tiene la categoría actualmente y le agregamos en 1 por cada acategoría
							$term = $this->Posts_model->get('term_taxonomy', array('term_id' => $post_category), $post_category);
							$count = $term->count + 1;
							$this->Posts_model->edit('term_taxonomy', array('term_id' => $post_category), array('count' => $count, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')), $post_category);
						}
					} else {
						$data = array(
							'post_id'			=>	$last_id,
							'term_taxonomy_id'	=>	1,
							'created'			=>	$this->user->id,
							'created_at'		=>	date('Y-m-d H:i:s')
						);
						$this->Posts_model->addTermRelationships($data);

						// Seleccionamos cuantos posts asociados tiene la categoría actualmente y le agregamos en 1 por cada acategoría
						$term = $this->Posts_model->get('term_taxonomy', array('term_id' => 1), 1);
						$count = $term->count + 1;
						$this->Posts_model->edit('term_taxonomy', array('term_id' => 1), array('count' => $count, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')), 1);
					}

					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					redirect('admin/posts/edit/' . $last_id);
				}
			}

			$this->load->helper('form');
			$this->template->add_css('base', 'bootstrap/css/bootstrap-datetimepicker.min');
			$this->template->add_css('base', 'libraries/summernote/summernote');
			$this->template->add_js('base', 'bootstrap/moment');
			$this->template->add_js('base', 'bootstrap/bootstrap-datetimepicker.min');
			$this->template->add_js('base', 'libraries/summernote/summernote.min');
			$this->template->add_js('view', 'posts/script');
			$this->template->set('_title', $this->lang->line('cms_general_label_add_post'));
			$this->template->set('_token', $this->user->token());
			$this->load->model('Terms_Model');
			$this->template->set('_categories', $this->Terms_Model->getCategoriesPost());
			$this->template->render('posts/add');
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
			redirect('admin/posts/display');
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
			redirect('admin/posts/display');
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
/* Location: ./application/controllers/posts.php */