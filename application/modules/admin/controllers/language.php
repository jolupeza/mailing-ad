<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends MY_Controller
{

	public function change($lang)
	{
		if (in_array($lang, $this->config->item('cms_languages'))) {
			$this->session->set_userdata('global_lang', $lang);
		}

		if ($this->user->is_logged_in()) {
			redirect('admin/dashboard');
		} else {
			redirect('admin');
		}
	}

}

/* End of file language.php */
/* Location: ./application/controllers/language.php */