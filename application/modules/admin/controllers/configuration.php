<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends MY_Controller
{
	public function index()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_site_configuration')) {
			show_error('¡Acceso restringido!');
		}

		$this->load->model('Configuration_model');
		$this->load->helper('form');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			// Validación
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'blogname',
					'label'		=>	'lang:cms_general_label_site_title',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'admin_email',
					'label'		=>	'lang:cms_general_label_admin_email',
					'rules'		=>	'trim|required|valid_email'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				if ($this->input->post('blogname') && $this->input->post('blogname') != '') {
					$this->Configuration_model->update(array('option_value' => $this->input->post('blogname'), 'modified_at' => date('Y-m-d H:i:s')), 'blogname');
				}

				$this->Configuration_model->update(array('option_value' => $this->input->post('blogdescription'), 'modified_at' => date('Y-m-d H:i:s')), 'blogdescription');

				if ($this->input->post('admin_email') && $this->input->post('admin_email') != '') {
					$this->Configuration_model->update(array('option_value' => $this->input->post('admin_email'), 'modified_at' => date('Y-m-d H:i:s')), 'admin_email');
				}

				if ($this->input->post('date_format') != '\c\u\s\t\o\m') {
					$this->Configuration_model->update(array('option_value' => $this->input->post('date_format'), 'modified_at' => date('Y-m-d H:i:s')), 'date_format');
				} else {
					$this->Configuration_model->update(array('option_value' => $this->input->post('date_format_custom'), 'modified_at' => date('Y-m-d H:i:s')), 'date_format');
				}

				if ($this->input->post('time_format') != '\c\u\s\t\o\m') {
					$this->Configuration_model->update(array('option_value' => $this->input->post('time_format'), 'modified_at' => date('Y-m-d H:i:s')), 'time_format');
				} else {
					$this->Configuration_model->update(array('option_value' => $this->input->post('time_format_custom'), 'modified_at' => date('Y-m-d H:i:s')), 'time_format');
				}

				$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
			}
		}

		$this->template->set('_blogname', $this->Configuration_model->getRow('blogname')->option_value);
		$this->template->set('_blogdescription', $this->Configuration_model->getRow('blogdescription')->option_value);
		$this->template->set('_admin_email', $this->Configuration_model->getRow('admin_email')->option_value);
		$this->template->set('_date_format', $this->Configuration_model->getRow('date_format')->option_value);
		$this->template->set('_time_format', $this->Configuration_model->getRow('time_format')->option_value);

		$this->template->add_js('view', 'configuration/script');
		$this->template->set('_title', $this->lang->line('cms_general_label_title_general_settings'));
		$this->template->set('_active', 'configurations');
		$this->template->set('_token', $this->user->token());
		$this->template->render('configuration/index');
	}

	public function media()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_site_configuration')) {
			show_error('¡Acceo restringido!');
		}

		$this->load->model('Configuration_model');
		$this->load->helper('form');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			// Validación
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'thumb-width',
					'label'		=>	'lang:cms_general_label_width_thumb',
					'rules'		=>	'trim|is_natural|max_length[4]'
				),
				array(
					'field'		=>	'thumb-height',
					'label'		=>	'lang:cms_general_label_height_thumb',
					'rules'		=>	'trim|is_natural|max_length[4]'
				),
				array(
					'field'		=>	'medio-width',
					'label'		=>	'lang:cms_general_label_width_medio',
					'rules'		=>	'trim|is_natural|max_length[4]'
				),
				array(
					'field'		=>	'medio-height',
					'label'		=>	'lang:cms_general_label_height_medio',
					'rules'		=>	'trim|is_natural|max_length[4]'
				),
				array(
					'field'		=>	'large-width',
					'label'		=>	'lang:cms_general_label_width_large',
					'rules'		=>	'trim|is_natural|max_length[4]'
				),
				array(
					'field'		=>	'large-height',
					'label'		=>	'lang:cms_general_label_height_large',
					'rules'		=>	'trim|is_natural|max_length[4]'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				if ($this->input->post('thumb_width') != $this->config->item('cms_thumbnail_size_w')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('thumb_width'), 'modified_at' => date('Y-m-d H:i:s')), 'thumbnail_size_w');
				}

				if ($this->input->post('thumb_height') != $this->config->item('cms_thumbnail_size_h')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('thumb_height'), 'modified_at' => date('Y-m-d H:i:s')), 'thumbnail_size_h');
				}

				if ($this->input->post('crop') != $this->config->item('cms_thumbnail_crop')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('crop'), 'modified_at' => date('Y-m-d H:i:s')), 'thumbnail_crop');
				}

				if ($this->input->post('medio_width') != $this->config->item('cms_medium_size_w')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('medio_width'), 'modified_at' => date('Y-m-d H:i:s')), 'medium_size_w');
				}

				if ($this->input->post('medio_height') != $this->config->item('cms_medium_size_h')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('medio_height'), 'modified_at' => date('Y-m-d H:i:s')), 'medium_size_h');
				}

				if ($this->input->post('large_width') != $this->config->item('cms_large_size_w')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('large_width'), 'modified_at' => date('Y-m-d H:i:s')), 'large_size_w');
				}

				if ($this->input->post('large_height') != $this->config->item('cms_large_size_h')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('large_height'), 'modified_at' => date('Y-m-d H:i:s')), 'large_size_h');
				}

				$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
			}
		}

		$this->template->set('_thumb_s_w', $this->Configuration_model->getRow('thumbnail_size_w')->option_value);
		$this->template->set('_thumb_s_h', $this->Configuration_model->getRow('thumbnail_size_h')->option_value);
		$this->template->set('_thumb_crop', $this->Configuration_model->getRow('thumbnail_crop')->option_value);
		$this->template->set('_med_s_w', $this->Configuration_model->getRow('medium_size_w')->option_value);
		$this->template->set('_med_s_h', $this->Configuration_model->getRow('medium_size_h')->option_value);
		$this->template->set('_larg_s_w', $this->Configuration_model->getRow('large_size_w')->option_value);
		$this->template->set('_larg_s_h', $this->Configuration_model->getRow('large_size_h')->option_value);
		$this->template->set('_title', $this->lang->line('cms_general_title_media'));
		$this->template->set('_active', 'configurations');
		$this->template->set('_token', $this->user->token());
		$this->template->render('configuration/media');
	}

	public function smtp()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		if (!$this->user->has_permission('admin_site_configuration')) {
			show_error('¡Acceo restringido!');
		}

		$this->load->model('Configuration_model');
		$this->load->helper('form');

		if ($this->input->post('token') == $this->session->userdata('token')) {
			// Validación
			$this->load->library('form_validation');

			$rules = array(
				array(
					'field'		=>	'sender',
					'label'		=>	'lang:cms_general_label_sender',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'email_sender',
					'label'		=>	'lang:cms_general_label_email_sender',
					'rules'		=>	'trim|required|valid_email'
				),
				array(
					'field'		=>	'email_reply',
					'label'		=>	'lang:cms_general_label_email_reply',
					'rules'		=>	'trim|required|valid_email'
				),
				array(
					'field'		=>	'mailgun_key',
					'label'		=>	'lang:cms_general_mailgun_key',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'mailgun_pubkey',
					'label'		=>	'lang:cms_general_mailgun_pubkey',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'mailgun_domain',
					'label'		=>	'lang:cms_general_mailgun_domain',
					'rules'		=>	'trim|required'
				),
				array(
					'field'		=>	'mailgun_secret',
					'label'		=>	'lang:cms_general_mailgun_secret',
					'rules'		=>	'trim|required'
				),
			);

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {


				$this->Configuration_model->update(array('option_value' => $this->input->post('sender'), 'modified_at' => date('Y-m-d H:i:s')), 'sender');

				$this->Configuration_model->update(array('option_value' => $this->input->post('email_sender'), 'modified_at' => date('Y-m-d H:i:s')), 'email_sender');

				$this->Configuration_model->update(array('option_value' => $this->input->post('email_reply'), 'modified_at' => date('Y-m-d H:i:s')), 'email_reply');

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_key'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_key');

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_pubkey'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_pubkey');

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_domain'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_domain');

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_secret'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_secret');

				$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
			}
		}

		$this->template->set('_sender', $this->Configuration_model->getRow('sender')->option_value);
		$this->template->set('_email_sender', $this->Configuration_model->getRow('email_sender')->option_value);
		$this->template->set('_email_reply', $this->Configuration_model->getRow('email_reply')->option_value);

		$this->template->set('_mailgun_key', $this->Configuration_model->getRow('mailgun_key')->option_value);
		$this->template->set('_mailgun_pubkey', $this->Configuration_model->getRow('mailgun_pubkey')->option_value);
		$this->template->set('_mailgun_domain', $this->Configuration_model->getRow('mailgun_domain')->option_value);
		$this->template->set('_mailgun_secret', $this->Configuration_model->getRow('mailgun_secret')->option_value);

		$this->template->set('_title', $this->lang->line('cms_general_title_setting_smtp'));
		$this->template->set('_active', 'configurations');
		$this->template->set('_token', $this->user->token());
		$this->template->render('configuration/smtp');
	}

}

/* End of file configuration.php */
/* Location: ./application/modules/admin/controllers/configuration.php */