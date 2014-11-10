<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends MY_Controller
{

	public function index($menu = 0)
	{
		if ($this->user->is_logged_in()) {
			$this->load->model('Terms_model');
			$this->load->model('Posts_model');

			$menu = ((int)$menu > 0) ? $menu : 0;

			$navMenu = $this->Terms_model->getNavMenu('all');
			if (count($navMenu) > 0) {
				$this->template->set('_navMenu', $navMenu);
			} else {
				redirect('admin/menus/addMenu');
			}

			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');
				$menu_id = $this->input->post('menu-id');
				$db_id = $this->input->post('menu-item-db-id');

				if ($db_id && count($db_id) > 0) {
					foreach ($db_id as $key => $value) {
						$type = $this->input->post('menu-item-type');
						$parent = $this->input->post('menu-item-parent-id');
						$objectid = $this->input->post('menu-item-object-id');
						$object = $this->input->post('menu-item-object');

						$target = $this->input->post('menu-item-target');
						$target = (count($target) > 0) ? $target : '';
						$targetText = (count($target) > 0 && $target[$value] == 'on') ? '_blank' : '';

						$classes = $this->input->post('menu-item-classes');

						$url = $this->input->post('menu-item-url');
						$url = (isset($url[$value]) && count($url) > 0) ? $url[$value] : '';

						$data = array(
							'_menu_item_type'				=>	$type[$value],
							'_menu_item_menu_item_parent'	=>	$parent[$value],
							'_menu_item_object_id'			=>	$objectid[$value],
							'_menu_item_object'				=>	$object[$value],
							'_menu_item_target'				=>	$targetText,
							'_menu_item_classes'			=>	$classes[$value],
							'_menu_item_url'				=>	$url,
						);

						foreach ($data as $clave => $valor) {
							$datapm = array(
								'meta_value' 	=> 	$valor,
								'modified'		=>	$this->user->id,
								'modified_at'	=>	date('Y-m-d H:i:s'),
							);
							$this->Posts_model->edit('postmeta', array('post_id' => $value, 'meta_key' => $clave), $datapm, $value);
						}

						// Editamos la información en la tabla post
						$title = $this->input->post('menu-item-title');
						$attrTitle = $this->input->post('menu-item-attr-title');
						$position = $this->input->post('menu-item-position');

						$data = array(
							'post_status'		=>	1,
							'post_excerpt'		=>	$attrTitle[$value],
							'menu_order'		=>	$position[$value],
							'modified'			=>	$this->user->id,
							'modified_at'		=>	date('Y-m-d H:i:s')
						);

						if ($title[$value] != '') {
							$data['post_title'] = $title[$value];
						}

						$this->Posts_model->editPost($data, $value);

						// Verificamos que las relaciones ya existan
						if (count($this->Posts_model->get('term_relationships', array('post_id' => $value, 'term_taxonomy_id' => $menu_id), $menu_id)) > 0) {
							$datos = array(
								'modified'		=>	$this->user->id,
								'modified_at'	=>	date('Y-m-d H:i:s')
							);

							$this->Posts_model->edit('term_relationships', array('post_id' => $value, 'term_taxonomy_id'=> $menu_id), $datos, $menu_id);
						} else {
							$datos = array(
								'post_id'				=>	$value,
								'term_taxonomy_id'		=>	$menu_id,
								'created'				=>	$this->user->id,
								'created_at'			=>	date('Y-m-d H:i:s')
							);

							$this->Posts_model->add('term_relationships', $datos);
						}
					}
				}


				if ($this->input->post('menu-item-object-id')) {
					$count = count($this->input->post('menu-item-object-id'));

					$editCount = array(
						'count'			=>	$count,
						'modified'		=>	$this->user->id,
						'modified_at'	=>	date('Y-m-d H:i:s'),
					);

					if ($this->Posts_model->edit('term_taxonomy', array('term_id' => $menu_id), $editCount, $menu_id)) {
						$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
					}
				}
			}

			// Traemos los datos del menú por defecto
			$mainmenu = $this->Terms_model->getNavMenu($menu);
			$this->template->set('_mainMenu', $mainmenu);

			$items = $this->Terms_model->get('term_relationships', $mainmenu->term_taxonomy_id, 'term_taxonomy_id');
			$postItem = array();
			$data = array();

			if (count($items) > 0) {
				foreach ($items as $item) {
					$postItem = $this->Terms_model->get('posts', $item->post_id, 'id');


					$data[$postItem[0]->menu_order] = array(
						'id'			=>	$postItem[0]->id,
						'post_title'	=>	$postItem[0]->post_title,
						'post_excerpt'	=>	$postItem[0]->post_excerpt,
						'post_status'	=>	$postItem[0]->post_status,
						'post_parent'	=>	$postItem[0]->post_parent,
						'menu_order'	=>	$postItem[0]->menu_order,
					);

					$postmetas = $this->Terms_model->get('postmeta', $item->post_id, 'post_id');

					foreach ($postmetas as $postmeta) {
						switch ($postmeta->meta_key) {
							case '_menu_item_type':
								$data[$postItem[0]->menu_order]['_menu_item_type'] = $postmeta->meta_value;
								break;

							case '_menu_item_menu_item_parent':
								$data[$postItem[0]->menu_order]['_menu_item_menu_item_parent'] = $postmeta->meta_value;
								break;

							case '_menu_item_object_id':
								$data[$postItem[0]->menu_order]['_menu_item_object_id'] = $postmeta->meta_value;
								break;

							case '_menu_item_object':
								$data[$postItem[0]->menu_order]['_menu_item_object'] = $postmeta->meta_value;
								break;

							case '_menu_item_target':
								$data[$postItem[0]->menu_order]['_menu_item_target'] = $postmeta->meta_value;
								break;

							case '_menu_item_classes':
								$data[$postItem[0]->menu_order]['_menu_item_classes'] = $postmeta->meta_value;
								break;

							case '_menu_item_url':
								$data[$postItem[0]->menu_order]['_menu_item_url'] = $postmeta->meta_value;
								break;

							default:
								break;
						}
					}
				}
			}

			if (count($data) > 0) {
				ksort($data);
				$this->template->set('_postItems', $data);
			}

			$this->load->helper('form');
			$this->template->add_js('view', 'menus/script');
			$this->template->set('_title', $this->lang->line('cms_general_menu_menus'));
			$this->template->set('_pages', $this->Posts_model->getPagesAll());
			$this->template->set('_categories', $this->Terms_model->getCategoriesPost());
			$this->template->set('_token', $this->user->token());
			$this->template->render('menus/index');
		} else {
			redirect('admin');
		}
	}

	/**
	* Agregar un nuevo Menú
	*
	* @access public
	*/
	public function addMenu()
	{
		if ($this->user->is_logged_in()) {
			$this->load->model('Terms_model');
			$this->load->model('Posts_model');

			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');
				$rules = array(
					array(
						'field'		=>	'name_menu',
						'label'		=>	'lang:cms_general_label_name_menu',
						'rules'		=>	'trim|required'
					)
				);

				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() === TRUE) {
					$this->load->helper('functions');
					$slug = slug($this->input->post('name_menu'));

					$data = array(
						'name'			=>	$this->input->post('name_menu'),
						'slug'			=>	$slug,
						'created'		=>	$this->user->id,
						'created_at'	=>	date('Y-m-d H:i:s'),
					);

					$last_id = $this->Terms_model->addTerm($data);
					if (is_integer($last_id)) {
						$data = array(
							'term_id'		=>	$last_id,
							'taxonomy'		=>	'nav_menu',
							'created'		=>	$this->user->id,
							'created_at'	=>	date('Y-m-d H:i:s'),
						);
						$this->Terms_model->addTermTaxonomy($data);
						$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));

						redirect('admin/menus/index/' . $last_id);
					}
				}
			}

			$this->load->helper('form');
			$navMenu = $this->Terms_model->getNavMenu('all');
			if (count($navMenu) > 0) {
				$this->template->set('_navMenu', $navMenu);
			}
			$this->template->add_js('view', 'menus/script');
			$this->template->set('_title', $this->lang->line('cms_general_menu_menus'));
			$this->template->set('_pages', $this->Posts_model->getPagesAll());
			$this->template->set('_categories', $this->Terms_model->getCategoriesPost());
			$this->template->set('_token', $this->user->token());
			$this->template->render('menus/addMenu');
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
	* @return 				str 		String con la lista de categorías hijas
	*/
	public static function childrenCategories($rows = array(), $parent_id = 0, $disabled = null)
	{
		$CI =& get_instance();
		$html = "";
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if ($row->parent == $parent_id) {
					$html .= '<ul>';
					$html .= '<li>';
					$html .= '<div class="checkbox">';
					$html .= '<label>';
					$disabled = (!is_null($disabled)) ? $disabled : '';
					$html .= '<input type="checkbox" class="chk-opt" name="post_category[]" data-type="' . $CI->lang->line('cms_general_title_categories') . '" data-title="' . $row->name . '" data-object="category" value="' . $row->term_id . '" ' . $disabled . ' />';
					$html .= $row->name;
					$html .= '</label>';
					$html .= '</div>';
					$html .= self::childrenCategories($rows, $row->term_id);
					$html .= '</li>';
					$html .= '</ul>';
				}
			}
		}
		return $html;
	}

	/**
	* Cargar páginas hijas
	*
	* @access public
	* @param  $rows 		arr 		Array con las páginas hijas
	* @param  $parent_id	int 		Id con la página padre
	* @return 				str 		String con la lista de páginas hijas
	*/
	public static function childrenPages($rows = array(), $parent_id = 0, $disabled = null)
	{
		$CI =& get_instance();
		$html = "";
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if ($row->post_parent == $parent_id) {
					$html .= '<ul>';
					$html .= '<li>';
					$html .= '<div class="checkbox">';
					$html .= '<label>';
					$disabled = (!is_null($disabled)) ? $disabled : '';
					$html .= '<input type="checkbox" class="chk-opt" name="post_page[]" data-type="' . $CI->lang->line('cms_general_title_page') . '" data-title="' . $row->post_title . '" data-object="page" value="' . $row->id . '" ' . $disabled . ' />';
					$html .= $row->post_title;
					$html .= '</label>';
					$html .= '</div>';
					$html .= self::childrenPages($rows, $row->id);
					$html .= '</li>';
					$html .= '</ul>';
				}
			}
		}
		return $html;
	}

	/**
	* Agregamos item de menú de tipo custom
	*
	* @access public
	* @param  $url 			Url
	* @param  $name			Name
	* @return id 			Id del item custom agregado
	*/
	public function addLinkCustom()
	{
		$url = $this->input->post('url');
		$name = $this->input->post('name');

		// Agregamos a la tabla posts y en tabla postmeta
		$this->load->model('Posts_model');
		$data = array(
			'post_title'	=>	$name,
			'post_status'	=>	2,
			'menu_order'	=>	1,
			'post_type'		=> 	'nav_menu_item',
			'created'		=>	$this->user->id,
			'created_at'	=>	date('Y-m-d H:i:s'),
		);
		$last_id = $this->Posts_model->addPost($data);
		$data = array(
			'guid'			=>	base_url() . '?p=' . $last_id
		);
		$this->Posts_model->editPost($data, $last_id);

		$keys = array(
			'_menu_item_type' 				=> 'custom',
			'_menu_item_menu_item_parent'	=>	'0',
			'_menu_item_object_id'			=>	$last_id,
			'_menu_item_object'				=>	'custom',
			'_menu_item_target'				=>	'',
			'_menu_item_classes'			=>	'',
			'_menu_item_url'				=>	$url
		);

		foreach ($keys as $key => $value) {
			$data = array(
				'post_id'		=>	$last_id,
				'meta_key'		=>	$key,
				'meta_value'	=> 	$value,
				'created'		=>	$this->user->id,
				'created_at'	=>	date('Y-m-d H:i:s'),
			);
			$this->Posts_model->add('postmeta', $data);
		}

		echo json_encode(array('id' => $last_id));
	}

	/**
	* Obtenemos los datos del item custom solicitado
	*
	* @access public
	* @param  $id 			Id del item custom
	* @return data 			Array json
	*/
	public function getPostMeta()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Posts_model');
			echo json_encode($this->Posts_model->get('postmeta', array('post_id' => $id), $id));
		}
	}

	/**
	* Agregamos item de menú de tipo page o taxonomy
	*
	* @access public
	* @param  $id 			Id
	* @param  $object		Page, Category
	* @param  $item_type	post_type, taxonomy
	* @return id 			Id del item custom agregado
	*/
	public function addMenuItem()
	{
		$id = $this->input->post('id');
		$object = $this->input->post('object');
		$item_type = ($object == 'page') ? 'post_type' : 'taxonomy';

		// Agregamos a la tabla posts y en tabla postmeta
		$this->load->model('Posts_model');
		$data = array(
			'post_status'	=>	2,
			'post_parent'	=>	$id,
			'menu_order'	=>	1,
			'post_type'		=> 	'nav_menu_item',
			'created'		=>	$this->user->id,
			'created_at'	=>	date('Y-m-d H:i:s'),
		);
		$last_id = $this->Posts_model->addPost($data);
		$data = array(
			'guid'			=>	base_url() . '?p=' . $last_id
		);
		$this->Posts_model->editPost($data, $last_id);

		$keys = array(
			'_menu_item_type' 				=> 	$item_type,
			'_menu_item_menu_item_parent'	=>	'0',
			'_menu_item_object_id'			=>	$id,
			'_menu_item_object'				=>	$object,
			'_menu_item_target'				=>	'',
			'_menu_item_classes'			=>	'',
			'_menu_item_url'				=>	''
		);

		foreach ($keys as $key => $value) {
			$data = array(
				'post_id'		=>	$last_id,
				'meta_key'		=>	$key,
				'meta_value'	=> 	$value,
				'created'		=>	$this->user->id,
				'created_at'	=>	date('Y-m-d H:i:s'),
			);
			$this->Posts_model->add('postmeta', $data);
		}

		echo json_encode(array('id' => $last_id));

		//echo json_encode($this->Posts_model->get('postmeta', 'post_id', $last_id));
	}

	/**
	* Eliminar un item de menú no utilizado
	*
	* @access public
	* @param  $id 			Id del item de menú a eliminar
	* @return bool 			True si eliminó o False si no eliminó
	*/
	public function deleteItemMenu()
	{
		$id = $this->input->post('id');
		$idMenu = $this->input->post('idMenu');
		$result = FALSE;

		if ((int)$id > 0) {
			$this->load->model('Posts_model');
			$this->Posts_model->deletePost($id);
			$result = $this->Posts_model->delete('postmeta', array('post_id' => $id));

			if ((int)$idMenu > 0) {
				if ($this->Posts_model->delete('term_relationships', array('post_id' => $id, 'term_taxonomy_id' => $idMenu))) {
					// Obtenemos el Menu
					$menu = $this->Posts_model->get('term_taxonomy', array('term_taxonomy_id' => $idMenu), $idMenu);

					$count = $menu[0]->count - 1;

					$result = $this->Posts_model->edit('term_taxonomy', array('term_taxonomy_id' => $idMenu), array('count' => $count, 'modified' => $this->user->id, 'modified_at' => date('Y-m-d H:i:s')), $idMenu);
				}

			}
		}

		echo $result;
	}

	public function delMenu()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Posts_model');

			$items = $this->Posts_model->get('term_relationships', array('term_taxonomy_id' => $id), $id);

			if (count($items) > 0) {
				foreach ($items as $it) {
					$this->Posts_model->delete('posts', array('id' => $it->post_id));
					$this->Posts_model->delete('postmeta', array('post_id' => $it->post_id));
					$this->Posts_model->delete('term_relationships', array('post_id' => $it->post_id, 'term_taxonomy_id' => $id));
				}
			}

			$this->Posts_model->delete('term_taxonomy', array('term_taxonomy_id' => $id));
			echo $this->Posts_model->delete('terms', array('term_id' => $id));
		}
	}

}

/* End of file menus.php */
/* Location: ./application/controllers/menus.php */ ?>