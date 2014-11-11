<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletters extends MY_Controller
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

		if (!$this->user->has_permission('view_news')) {
			show_error('Acceso restringido!');
		}

		$this->template->set('_title', $this->lang->line('cms_general_title_newsletters'));
		$this->template->set('_active', 'newsletters');
		$this->template->add_js('view', 'newsletters/script');
		$this->template->render('newsletters/index');
	}

	/**
	* Método que vía ajax obtiene y muestra los boletínes existentes
	*
	* @access public
	* @param  $sort_by 		Indicamos a través de que campo se ordena
	* @param  $sort_order 	Indicamos si se ordena ascendente o descendentemente
	* @param  $search 		Indicamos si se pasa un parámetro de búsqueda
	* @param  $offset 		Indicamos desde que registro obtenemos la lista
	*/
	public function displayAjax($sort_by = 'id', $sort_order = 'desc', $search = 'all',  $offset = 0)
	{
		$limit = 10;
		$total = 0;
		$this->load->model('Newsletters_model');
		$this->load->helper('form');

		$result = $this->Newsletters_model->getAll($limit, $offset, $sort_by, $sort_order, $search);
		$news = $result['data'];
		$total = $result['num_rows'];

		if (count($news) > 0) {
			$this->template->set('_news', $news);

			if ($total > $limit) {
				// Pagination
				$this->load->library('pagination');
				$config = array();
				$config['base_url'] = site_url('admin/newsletters/displayAjax/' . $sort_by . '/' . $sort_order . '/' . $search);
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
		$this->template->renderAjax('newsletters/displayAjax');
	}

	/**
	* Método para añadir un boletín
	*
	* @access public
	*/
	public function add()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('create_news')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Newsletters_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required|callback_name_check'
				),
				array(
					'field'		=>	'content',
					'label'		=>	'lang:cms_general_label_content',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'temp_id',
					'label'		=>	'lang:cms_general_title_templates',
					'rules'		=>	'required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->helper(array('path', 'functions'));

				$name = $this->input->post('name');
				$status = $this->input->post('status');
				$submit_at = $this->input->post('submit_at');
				$content = $this->input->post('content');
				$temp_id = $this->input->post('temp_id');

				$template = $this->Newsletters_model->get('templates_email', 'guid', array('id' => $temp_id));

				$html = file_get_contents($template[0]->guid);
				$body = strstr($html, '<body');
				$body = strstr($body, '>', TRUE);

				$tidy = tidy_parse_string($html);
				$head = $tidy->head();

				$first = strstr($content, '</p>', TRUE);
				$content = substr_replace($content, '', 0, strlen($first));
				//$content = substr_replace($content, '', 0, strlen($first) + 6);

				$index = $head . $body . '>' . $content . '</body></html>';

				// Debemos crear carpeta con nombre y crear archivo html con el contenido
				$directory = slug($name);
				$dir = set_realpath('./newsletters/' . $directory . '/');

				// Creamos el directorio en caso de no existir
				if(!is_dir($dir)){
				    mkdir($dir, 0777);
				    chmod($dir, 0777);
				}

				$fp = fopen($dir . 'index.html', 'x');
				//$fp = fopen($dir . '\index.html', 'x');  // Windows
				fwrite($fp, $index);
				fclose($fp);

				$data = array(
					'name'			=>	$this->input->post('name'),
					'slug'			=>	$directory,
					'temp_id'		=>	$this->input->post('temp_id'),
					'guid'			=>	base_url() . 'newsletters/' . $directory . '/index.html',
					'guid_path'		=>	$dir,
					'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
					'submit_at'		=>	$this->input->post('submit_at'),
					'created'		=>	$this->user->id,
					'created_at'	=>	date('Y-m-d H:i:s')
				);

				$last_id = $this->Newsletters_model->add(NULL, $data);

				$this->template->set_flash_message(array('success' => $this->lang->line('cms_general_label_success_add')));
				redirect('admin/newsletters/edit/' . $last_id);
			}
		}

		// Mandamos a la vista la lista de templates disponibles
		$templates = $this->Newsletters_model->get('templates_email', 'id, name, image, status', $where = array('status' => 1));

		if (count($templates) > 0) {
			$this->template->set('_templates', $templates);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'newsletters/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_add_newsletter'));
		$this->template->set('_active', 'newsletters');
		$this->template->set('_token', $this->user->token());
		$this->template->render('newsletters/add');
	}

	/**
	* Método que verifica si el nombre ingresado al newsletters ya existe
	*
	* @access public
	* @param  $name 		Nombre del newsletter a ingresar
	* @return boolean 		Si no existe devuelve TRUE caso contrario FALSE
	*/
	public function name_check($name)
	{
		$this->load->model('Newsletters_model');
		if ($this->Newsletters_model->get(NULL, 'id', array('name' => $name))) {
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

		if (!$this->user->has_permission('edit_any_news')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Newsletters_model');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'name',
					'label'		=>	'lang:cms_general_label_name',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'content',
					'label'		=>	'lang:cms_general_label_content',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$this->load->helper(array('path', 'functions'));

				$name = $this->input->post('name');
				$status = $this->input->post('status');
				$submit_at = $this->input->post('submit_at');
				$content = $this->input->post('content');
				$temp_id = $this->input->post('temp_id');

				$template = $this->Newsletters_model->get('templates_email', 'guid', array('id' => $temp_id));
				$news = $this->Newsletters_model->get(NULL, 'name, slug, guid, guid_path', array('id' => (int)$id));

				$html = file_get_contents($template[0]->guid);
				$body = strstr($html, '<body');
				$body = strstr($body, '>', TRUE);

				$tidy = tidy_parse_string($html);
				$head = $tidy->head();

				$content = $head . $body . '>' . $content . '</body></html>';

				//echo $content; exit;

				$slug = $news[0]->slug;
				$dir = $news[0]->guid_path;

				if ($name != $news[0]->name) {
					if (!$this->Newsletters_model->get(NULL, 'id', array('name' => $name))) {
						// Eliminamos directorio anterior
						$slug = slug($name);
						$dir = set_realpath('./newsletters/' . $slug . '/');

						rename($news[0]->guid_path, $dir);
					} else {
						$this->template->set_flash_message(array('error' => $this->lang->line('error_name_check')));
		        		redirect('admin/newsletters/edit/' . $id);
					}
				}

				$fp = fopen($dir . 'index.html', 'w');
				//$fp = fopen($dir . '\index.html', 'w');
				fwrite($fp, $content);
				fclose($fp);

				$data = array(
					'name'			=>	$this->input->post('name'),
					'slug'			=>	$slug,
					'temp_id'		=>	$this->input->post('temp_id'),
					'guid'			=>	base_url() . 'newsletters/' . $slug . '/index.html',
					'guid_path'		=>	$dir,
					'status'		=>	(isset($status) && !empty($status)) ? $status : 0,
					'submit_at'		=>	$this->input->post('submit_at'),
					'modified'		=>	$this->user->id,
					'modified_at'	=>	date('Y-m-d H:i:s')
				);

				if ($this->Newsletters_model->edit(NULL, array('id' => $id), $data)) {
					$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
				}
			}
		}

		$fields = 'id, name, temp_id, guid, guid_path, status, submit_at';
		$row = $this->Newsletters_model->get(NULL, $fields, array('id' => (int)$id));

		if ($row) {
			$this->template->set('_news', $row);
		}

		// Mandamos a la vista la lista de templates disponibles
		$templates = $this->Newsletters_model->get('templates_email', 'id, name, image, status', $where = array('status' => 1));

		if (count($templates) > 0) {
			$this->template->set('_templates', $templates);
		}

		$this->load->helper('form');
		$this->template->add_js('view', 'newsletters/script');
		$this->template->set('_title', $this->lang->line('cms_general_title_edit_newsletter'));
		$this->template->set('_active', 'newsletters');
		$this->template->set('_token', $this->user->token());
		$this->template->render('newsletters/edit');
	}

	/**
	* Método que cambia status de newsletter vía ajax
	*
	* @access public
	* @param  $id 			Id del newsletter
	*/
	public function action()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		if ((int)$id > 0) {
			$this->load->model('Newsletters_model');

			$data = array(
				'status'		=>	$status,
				'modified'		=> 	$this->user->id,
				'modified_at'	=>	date('Y-m-d H:i:s')
			);

			if ($this->Newsletters_model->edit(NULL, array('id' => $id), $data)) {
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
	* Método para eliminar un boletín
	*
	* @access public
	* @param  $id 			Id del boletín
	*/
	public function delete()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Newsletters_model');

			$news = $this->Newsletters_model->get(NULL, 'slug, guid_path', array('id' => $id));

			if ($this->Newsletters_model->delete(NULL, array('id' => $id))) {
				// Eliminamos carpeta de newsletter
				$this->load->helper('functions');
				rrmdir($news[0]->guid_path);

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
	* Método que selecciona un template a un newsletters
	*
	* @access public
	* @param  $id 			Id del template seleccionado
	* @return json 			Array con los datos del template seleccionado
	*/
	public function selectTemplate()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Newsletters_model');

			$result = $this->Newsletters_model->get('templates_email', 'guid', array('id' => $id));

			if (count($result) > 0) {
				echo json_encode($result);
			}
		} else {
			echo FALSE;
		}

		exit;
	}

	/**
	* Método para obtener datos de newsletter vía ajax
	*
	* @access public
	* @param  $id 		    Id de newsletter
	*/
	public function getNewsletter()
	{
		$id = $this->input->post('id');

		if ((int)$id > 0) {
			$this->load->model('Newsletters_model');
			$result = $this->Newsletters_model->get(NULL, 'guid', array('id' => $id));

			if (count($result) > 0) {
				echo json_encode($result);
			}
		} else {
			echo FALSE;
		}

		exit;
	}

	public function sendEmail()
	{
		$email = $this->input->post('email');
		$name = $this->input->post('name');
		$id = $this->input->post('id');

		$mailgun = self::getMailgun();
		$mailgunValidate = self::getMailgunValidate();
		$mailgunOptIn = self::getMailgunOptIn();

		$validate = $mailgunValidate->get('address/validate', array('address' => $email))->http_response_body;

		if ($validate->is_valid) {
			$hash = $mailgunOptIn->generateHash($this->config->item('mailgun_list'), $this->config->item('mailgun_secret'), $email);

			$this->load->model('Newsletters_model');
			$newsletter = $this->Newsletters_model->get(NULL, 'guid_path', array('id' => $id));
			$content = file_get_contents($newsletter[0]->guid_path . 'index.html');

			$mailgun->sendMessage($this->config->item('mailgun_domain'), array(
				'from'		=>	'noreply@adinspector.pe',
				'to'		=>	$email,
				'subject'	=>	'Please confirm your subscription to us.',
				'html'		=>	$content
			));
		}

		/*$this->load->library('email');
		$this->email->from('AD+Inspector Mailing');
        $this->email->to($email);
        $this->email->subject('Test send email');
        $this->email->message($content);
        //$this->email->send();*/
        echo TRUE;
        exit;
	}
}

/* End of file newsletters.php */
/* Location: ./application/modules/admin/controllers/newsletters.php */ ?>