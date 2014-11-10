<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use Mailgun\Mailgun;

class MY_Controller extends CI_Controller
{
	private $_mailgun;
	private $_mailgunValidate;
	private $_mailgunOptIn;

	public function __construct()
	{
		parent::__construct();

		if (!$this->config->item('cms_admin_panel_uri')) {
			show_error('Configuration error.');
		}

		$this->_setLanguage();

		// Seteando parámetros generales desde la base de datos
		$this->load->model('Configuration_model');
		$this->config->set_item('cms_site_name', $this->Configuration_model->getRow('blogname')->option_value);
		$this->config->set_item('cms_site_desc', $this->Configuration_model->getRow('blogdescription')->option_value);
		$this->config->set_item('cms_date_format', $this->Configuration_model->getRow('date_format')->option_value);
		$this->config->set_item('cms_time_format', $this->Configuration_model->getRow('time_format')->option_value);
		$this->config->set_item('cms_thumbnail_size_w', $this->Configuration_model->getRow('thumbnail_size_w')->option_value);
		$this->config->set_item('cms_thumbnail_size_h', $this->Configuration_model->getRow('thumbnail_size_h')->option_value);
		$this->config->set_item('cms_thumbnail_crop', $this->Configuration_model->getRow('thumbnail_crop')->option_value);
		$this->config->set_item('cms_medium_size_w', $this->Configuration_model->getRow('medium_size_w')->option_value);
		$this->config->set_item('cms_medium_size_h', $this->Configuration_model->getRow('medium_size_h')->option_value);
		$this->config->set_item('cms_large_size_w', $this->Configuration_model->getRow('large_size_w')->option_value);
		$this->config->set_item('cms_large_size_h', $this->Configuration_model->getRow('large_size_h')->option_value);
		$this->config->set_item('cms_sender', $this->Configuration_model->getRow('sender')->option_value);
		$this->config->set_item('cms_email_sender', $this->Configuration_model->getRow('email_sender')->option_value);
		$this->config->set_item('cms_email_reply', $this->Configuration_model->getRow('email_reply')->option_value);
		$this->config->set_item('mailgun_key', $this->Configuration_model->getRow('mailgun_key')->option_value);
		$this->config->set_item('mailgun_pubkey', $this->Configuration_model->getRow('mailgun_pubkey')->option_value);
		$this->config->set_item('mailgun_domain', $this->Configuration_model->getRow('mailgun_domain')->option_value);
		$this->config->set_item('mailgun_secret', $this->Configuration_model->getRow('mailgun_secret')->option_value);

		$this->lang->load(array('cms_general', 'error'));
		$this->load->library(array('template', 'user'));

		$this->_mailgun = new Mailgun($this->config->item('mailgun_key'));
		$this->_mailgunValidate = new Mailgun($this->config->item('mailgun_pubkey'));
		$this->_mailgunOptIn = $this->_mailgun->OptInHandler();
	}

	public function adminPanel()
	{
		return strtolower($this->uri->segment(1)) == $this->config->item('cms_admin_panel_uri');
	}

	private function _setLanguage()
	{
		$lang = $this->session->userdata('global_lang');

		if ($lang && in_array($lang, $this->config->item('cms_languages'))) {
			$this->config->set_item('language', $lang);
		}
	}

	public function getMailgun()
	{
		return $this->_mailgun;
	}

	public function getMailgunValidate()
	{
		return $this->_mailgunValidate;
	}

	public function getMailgunOptIn()
	{
		return $this->_mailgunOptIn;
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */