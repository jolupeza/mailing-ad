<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products_Model extends CI_Model
{
	public function getAll()
	{
		$results = $this->db->get('products')->result();
		foreach ($results as $result) {
			if ($result->option_values) {
				$result->option_values = explode(',', $result->option_values);
			}
		}

		return $results;
	}

	public function get($id)
	{
		$result = $this->db->get_where('products', array('id' => $id))->row();

		if ($result->option_values) {
			$result->option_values = explode(',', $result->option_values);
		}

		return $result;
	}
}

/* End of file Products_Model.php */
/* Location: ./application/models/Products_Model.php */ ?>