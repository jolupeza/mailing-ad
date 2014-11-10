<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration_model extends CI_Model
{
	private $_table = 'options';

	/**
	* Obtenemos un parámetro de configuración concreto de acuerdo al option_name pasado
	*
	* @access public
	* @param  string 		Indicamos el option_name a recuperar
	* @return obj 			Objeto con los datos del option_name solicitado
	*/
	public function getRow($where)
	{
		return $this->db->where('option_name', $where)
						->get($this->_table)
						->row();
	}

	/**
	* Actualizamos el valor de un option_name indicado
	*
	* @access public
	* @param  array 		Array con los datos del option_name a actualizar
	* @param  string 		Indicamos el option_name a actualizar
	*/
	public function update($data, $where)
	{
		$this->db->where('option_name', $where);
		$this->db->update($this->_table, $data);
	}
}

/* End of file Configuration_Model.php */
/* Location: ./application/models/Configuration_Model.php */