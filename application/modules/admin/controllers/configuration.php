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

		$this->template->set('_blogname', $this->Configuration_model->getRow('blogname'));
		$this->template->set('_blogdescription', $this->Configuration_model->getRow('blogdescription'));
		$this->template->set('_admin_email', $this->Configuration_model->getRow('admin_email'));
		$this->template->set('_date_format', $this->Configuration_model->getRow('date_format'));
		$this->template->set('_time_format', $this->Configuration_model->getRow('time_format'));

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
					$this->config->set_item('cms_thumbnail_size_w', $this->input->post('thumb_width'));
				}

				if ($this->input->post('thumb_height') != $this->config->item('cms_thumbnail_size_h')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('thumb_height'), 'modified_at' => date('Y-m-d H:i:s')), 'thumbnail_size_h');
					$this->config->set_item('cms_thumbnail_size_h', $this->input->post('thumb_height'));
				}

				if ($this->input->post('crop') != $this->config->item('cms_thumbnail_crop')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('crop'), 'modified_at' => date('Y-m-d H:i:s')), 'thumbnail_crop');
					$this->config->set_item('cms_thumbnail_crop', $this->input->post('crop'));
				}

				if ($this->input->post('medio_width') != $this->config->item('cms_medium_size_w')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('medio_width'), 'modified_at' => date('Y-m-d H:i:s')), 'medium_size_w');
					$this->config->set_item('cms_medium_size_w', $this->input->post('medio_width'));
				}

				if ($this->input->post('medio_height') != $this->config->item('cms_medium_size_h')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('medio_height'), 'modified_at' => date('Y-m-d H:i:s')), 'medium_size_h');
					$this->config->set_item('cms_medium_size_h', $this->input->post('medio_height'));
				}

				if ($this->input->post('large_width') != $this->config->item('cms_large_size_w')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('large_width'), 'modified_at' => date('Y-m-d H:i:s')), 'large_size_w');
					$this->config->set_item('cms_large_size_w', $this->input->post('large_width'));
				}

				if ($this->input->post('large_height') != $this->config->item('cms_large_size_h')) {
					$this->Configuration_model->update(array('option_value' => $this->input->post('large_height'), 'modified_at' => date('Y-m-d H:i:s')), 'large_size_h');
					$this->config->set_item('cms_large_size_h', $this->input->post('large_height'));
				}

				$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
			}
		}

		$this->template->set('_thumb_s_w', $this->Configuration_model->getRow('thumbnail_size_w'));
		$this->template->set('_thumb_s_h', $this->Configuration_model->getRow('thumbnail_size_h'));
		$this->template->set('_thumb_crop', $this->Configuration_model->getRow('thumbnail_crop'));
		$this->template->set('_med_s_w', $this->Configuration_model->getRow('medium_size_w'));
		$this->template->set('_med_s_h', $this->Configuration_model->getRow('medium_size_h'));
		$this->template->set('_larg_s_w', $this->Configuration_model->getRow('large_size_w'));
		$this->template->set('_larg_s_h', $this->Configuration_model->getRow('large_size_h'));
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

		//echo $this->config->item('mailgun_pubkey'); exit;

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
				$this->config->set_item('cms_sender', $this->input->post('sender'));

				$this->Configuration_model->update(array('option_value' => $this->input->post('email_sender'), 'modified_at' => date('Y-m-d H:i:s')), 'email_sender');
				$this->config->set_item('cms_email_sender', $this->input->post('email_sender'));

				$this->Configuration_model->update(array('option_value' => $this->input->post('email_reply'), 'modified_at' => date('Y-m-d H:i:s')), 'email_reply');
				$this->config->set_item('cms_email_reply', $this->input->post('email_reply'));

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_key'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_key');
				$this->config->set_item('mailgun_key', $this->input->post('mailgun_key'));

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_pubkey'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_pubkey');
				$this->config->set_item('mailgun_pubkey', $this->input->post('mailgun_pubkey'));

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_domain'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_domain');
				$this->config->set_item('mailgun_domain', $this->input->post('mailgun_domain'));

				$this->Configuration_model->update(array('option_value' => $this->input->post('mailgun_secret'), 'modified_at' => date('Y-m-d H:i:s')), 'mailgun_secret');
				$this->config->set_item('mailgun_secret', $this->input->post('mailgun_secret'));

				$this->template->add_message(array('success' => $this->lang->line('cms_general_label_success_edit')));
			}
		}

		$this->template->set('_sender', $this->Configuration_model->getRow('sender'));
		$this->template->set('_email_sender', $this->Configuration_model->getRow('email_sender'));
		$this->template->set('_email_reply', $this->Configuration_model->getRow('email_reply'));
		$this->template->set('_mailgun_key', $this->config->item('mailgun_key'));
		$this->template->set('_mailgun_pubkey', $this->config->item('mailgun_pubkey'));
		$this->template->set('_mailgun_domain', $this->config->item('mailgun_domain'));
		$this->template->set('_mailgun_secret', $this->config->item('mailgun_secret'));
		$this->template->set('_title', $this->lang->line('cms_general_title_setting_smtp'));
		$this->template->set('_active', 'configurations');
		$this->template->set('_token', $this->user->token());
		$this->template->render('configuration/smtp');
	}

}

/* End of file configuration.php */
/* Location: ./application/modules/admin/controllers/configuration.php */