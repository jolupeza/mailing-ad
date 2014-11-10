<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

	public function index()
	{
		if (!$this->user->is_logged_in()) {
			redirect('admin');
		}

		$this->template->set('_title', $this->lang->line('cms_general_title_dashboard'));
		$this->template->set('_active', 'dashboard');
		$this->template->render('dashboard/index');
	}
}

/* End of file dashboard.php */
/* Location: ./application/modules/admin/controllers/dashboard.php */