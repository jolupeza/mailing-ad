<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms extends MY_Controller
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
			$this->template->set('_title', $this->lang->line('cms_general_label_categories'));
			$this->template->add_js('view', 'terms/script');
			$this->template->render('terms/index');
		} else {
			redirect('admin');
		}
	}

	public function displayTermsAjax($sort_by = 'name', $sort_order="asc", $search = 'all', $offset = 0)
	{
		$limit = 10;
		$total = 0;
		$this->load->model('Terms_model');
		$result = $this->Terms_model->getAllTerms('category', $limit, $offset, $sort_by, $sort_order, $search);
		$terms = (isset($result['masters'])) ? $result['masters'] : $result['data'];
		$total = $result['num_rows'];

		if (sizeof($terms) > 0) {
			$this->template->set('_masters', $terms);
			if (isset($result['childrens'])) {
				$this->template->set('_childrens', $result['childrens']);
			}

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/terms/displayTermsAjax/' . $sort_by . '/' . $sort_order . '/' . $search);
				$config['total_rows'] = $total;
				$config['per_page'] = $limit;
				$config['uri_segment'] = 7;
				$this->pagination->initialize($config);
				$this->template->set('_pagination', $this->pagination->create_links());
			}
		}

		$this->template->set('_sort_order', $sort_order);
		$this->template->set('_sort_by', $sort_by);
		$this->template->set('_limit', $limit);
		$this->template->set('_search', $search);
		$this->template->renderAjax('terms/displayAjax');
	}

	public static function nested($rows = array(), $parent_id = 0)
	{
		$CI =& get_instance();
		$html = "";
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if ($row->parent == $parent_id) {
					if ($CI->session->userdata('children')) {
						$CI->session->set_userdata('children', $CI->session->userdata('children') + 1);
					} else {
						$CI->session->set_userdata('children', 1);
					}

					$html .= '<tr><td class="text-center">' . $row->term_id . '</td>';
					$html .= '<td class="view-option-post">';
					$html .= '<a class="children-' . $CI->session->userdata('children') . '" href="' . base_url() . 'admin/terms/edit/' . $row->term_id .'">'. $row->name . '</a>';
					$html .= '<div class="opt-post">';
					$html .= '<ul class="list-inline">';
					$html .= '<li>';
					$html .= '<small>';
					$html .= '<a href="' . base_url() . 'terms/edit/' . $row->term_id . '">' . $CI->lang->line('cms_general_label_edit') . '</a>';
					$html .= '</small>';
					$html .= '</li>';
					$html .= '<li><small><a class="text-danger" href="'. base_url() . 'admin/terms/delete/' . $row->term_id . '">' . $CI->lang->line('cms_general_label_delete') . '</a></small></li>';
					$html .= '<li><small><a href="#">Ver</a></small></li>';
					$html .= '</ul>';
					$html .= '</div>';
					$html .= '</td>';
					$html .= '<td>' . $row->description. '</td>';
					$html .= '<td class="text-center">'. $row->slug . '</td>';
					$html .= '<td class="text-center">' . $row->count . '</td>';
					$html .= '</tr>';
					$html .= self::nested($rows, $row->term_id);
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
			$this->load->model('Terms_model');

			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');

				$rules = array(
					array(
						'field'		=>	'name',
						'label'		=>	'lang:cms_general_label_name',
						'rules'		=>	'trim|required'
					),
					array(
						'field'		=>	'slug',
						'label'		=>	'lang:cms_general_label_slug',
						'rules'		=>	'trim|required|alpha_dash'
					)
				);

				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() === TRUE) {
					$data = array(
						'name'				=>	$this->input->post('name'),
						'slug'				=>	$this->input->post('slug'),
						'modified'			=>	$this->user->id,
						'modified_at'		=>	date('Y-m-d H:i:s')
					);
					if ($this->Terms_model->editTerm($data, $this->input->post('id'))) {
						$data = array(
							'description'		=>	$this->input->post('description'),
							'parent'			=>	$this->input->post('parent'),
							'modified'			=>	$this->user->id,
							'modified_at'		=>	date('Y-m-d H:i:s')
						);
					}
					$this->Terms_model->editTermTaxonomy($data, $this->input->post('id'));
					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				}
			}

			$this->load->helper('form');
			$term = $this->Terms_model->getTerm((int)$id);
			if (is_object($term)) {
				$this->template->set('_term', $term);
			}
			$this->template->set('_title', $this->lang->line('cms_general_title_edit_category'));
			$this->template->set('_token', $this->user->token());
			$this->template->set('_categories', $this->Terms_model->getCategories());
			$this->template->render('terms/edit');
		} else {
			redirect('admin');
		}
	}

	/**
	* Método para agregar una nueva categoría
	*
	* @access public
	*/
	public function add()
	{
		if ($this->user->is_logged_in()) {
			$this->load->model('Terms_model');

			if ($this->input->post('token') == $this->session->userdata('token')) {
				$this->load->library('form_validation');

				$rules = array(
					array(
						'field'		=>	'name',
						'label'		=>	'lang:cms_general_label_name',
						'rules'		=>	'trim|required'
					),
					array(
						'field'		=>	'slug',
						'label'		=>	'lang:cms_general_label_slug',
						'rules'		=>	'trim|alpha_dash'
					)
				);

				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() === TRUE) {
					$this->load->helper('functions');
					$slug = (!empty($this->input->post('slug')) || $this->input->post('slug') != '') ? $this->input->post('slug') : slug($this->input->post('name'));

					// Debemos agregar a las dos tablas terms y term_taxonomy
					$data = array(
						'name'				=>	$this->input->post('name'),
						'slug'				=>	$slug,
						'created'			=>	$this->user->id,
						'created_at'		=>	date('Y-m-d H:i:s')
					);
					$last_id = $this->Terms_model->addTerm($data);

					$data = array(
						'term_id' 			=>	$last_id,
						'taxonomy'			=>	'category',
						'description'		=>	$this->input->post('description'),
						'parent'			=>	$this->input->post('parent'),
						'created'			=>	$this->user->id,
						'created_at'		=>	date('Y-m-d H:i:s')
					);

					$this->Terms_model->addTermTaxonomy($data);

					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
					redirect('admin/terms/edit/' . $last_id);
				}
			}

			$this->load->helper('form');
			$this->template->set('_title', $this->lang->line('cms_general_label_add_category'));
			$this->template->set('_token', $this->user->token());
			$this->template->set('_categories', $this->Terms_model->getCategories());
			$this->template->render('terms/add');
		} else {
			redirect('admin');
		}
	}

	/** Pendiente a ver si lo necesitamos
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
				$this->load->model('Posts_Model');
				$data = array(
					'post_status'		=>	(int)$action,
					'modified'			=>	$this->user->id,
					'modified_at'		=>	date('Y-m-d H:i:s')
				);
				if (!$this->Posts_Model->editPost($data, $id)) {
					$this->template->set_flash_message(array('error' => $this->lang->line('cms_general_label_error_action')));
				} else {
					if ($action == 0) {
						$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_restore')));
					} else if ($action == 2) {
						$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_trush')));
					}
				}
			}
			redirect('admin/terms/display');
		} else {
			redirect('admin');
		}
	}

	/**
	* Método para eliminar una categoría.
	*
	* @access public
	* @param  $id 			int 		Id de la categoría a eliminar
	*/
	public function delete($id = 0)
	{
		if ($this->user->is_logged_in()) {
			if ((int)$id > 0) {
				$this->load->model('Terms_model');
				if ($this->Terms_model->deleteTerm($id)) {
					$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_delete')));
				} else {
					$this->template->set_flash_message(array('error' => $this->lang->line('cms_general_label_error_action')));
				}
			}
			redirect('admin/terms/display');
		} else {
			redirect('admin');
		}
	}

}

/* End of file terms.php */
/* Location: ./application/modules/admin/controllers/terms.php */ ?>