<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templates_Model extends CI_Model
{
	private $_table = 'templates_email';

	/**
	* Método para obtener lista de boletines
	*
	* @access public
	* @param  $limit 		int 		Indicamos el número de registros  a obtener.
	* @param  $offset 		int 		Indicamos desde que registro obtenemos los resultados.
	* @param  $sort_by 		str 		Indicamos si ordenamos ascendente o descendentemente.
	* @param  $sort_order	str 		Indicamos a través de que campo ordenamos.
	* @param  $search		str 		Indicamos si obtenemos los resultados en base a un criterio de búsqueda.
	* @return $res 			arr 		Devolvemos un array bidimensional, uno con los datos y otro con el número total de registros obtenidos por la consulta.
	*/
	public function getAll($limit = null, $offset = 0, $sort_by, $sort_order, $search = 'all')
	{
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('id', 'name', 'status');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'id';

		$result = array();
		$res = FALSE;

		$this->db->select('id, name, description, status, image');

		if ($search != 'all') {
			$this->db->like('name', $search);
		}
		$this->db->order_by($sort_by, $sort_order);

		if ((int)$limit > 0) {
			$result = $this->db->get($this->_table, (int)$limit, (int)$offset);
		}

		if ($result->num_rows() > 0) {
			$res['data'] = $result->result();

			$this->db->select('id');

			if ($search != 'all') {
				$this->db->like('name', $search);
			}

			$result = $this->db->get($this->_table);
			$res['num_rows'] = $result->num_rows();
		}

		return $res;
	}


	/**
	* Método para obtener un registro
	*
	* @access public
	* @param  $table 		Indicamos la tabla
	* @param  $fields 		Indicamos los campos que queremos obtener
	* @param  $where 		Indicamos en un array el registro que queremos obtener
	* @return boolean 		En caso de obtener el resultado lo devolvemos, caso contrario devuelve FALSE
	*/
	public function get($table = NULL, $fields = '', $where = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (!empty($fields)) {
			$this->db->select($fields);
		}

		if (count($where) > 0) {
			$this->db->where($where);
		}

		$result = $this->db->get($table);

		if ($result->num_rows() > 0) {
			return $result->result();
		}

		return FALSE;
	}

	/**
	* Método para agregar un item a la tabla
	*
	* @access public
	* @param $table 		Nombre de la tabla
	* @param $data 			Array con los datos a agregar
	* @return bool 			En caso de insertar devuelve el id del nuevo registro, caso contrario devuelve FALSE.
	*/
	public function add($table = NULL, $data = array())
	{
		$table = (is_null($table)) ? $this->_table: $table;

		if (sizeof($data) > 0) {
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		}

		return FALSE;
	}

	/**
	* Método para editar alún registro
	*
	* @access public
	* @param  $table 		Indicamos la tabla
	* @param  $fields 		Indicamos a través de un array que registro se modificará
	* @param  $data 		Array con los datos a modificar
	* @return boolean 		Si se actualiza los datos devuelve TRUE caso contrario devuelve FALSE
	*/
	public function edit($table = NULL, $fields = array(), $data = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (count($fields) > 0) {
			$result = $this->db->where($fields)->get($table);

			if ($result->num_rows() > 0) {
				if (count($data) > 0) {
					$this->db->where($fields);
					$this->db->update($table, $data);
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	* Eliminar un registro
	*
	* @access public
	* @param  $table 		Indicamos la tabla
	* @param  $where 		Indicamos el registro a eliminar
	* @return boolean 		Si se elimina el registro devuelve TRUE caso contrario devuelve FALSE
	*/
	public function delete($table = NULL, $where = array())
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (count($where) > 0) {
			if ($this->db->where($where)->get($table)->num_rows() > 0) {
				$this->db->where($where);
				$this->db->delete($table);
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	* Obtenemos el número total de registros de acuerdo a su estado
	*
	* @access private
	* @param  $status 		array 		Indicamos los estados que queremos obtener
	* @return 				int 		Número de registros de acuerdo al estado
	*/
	public function countRows($status = array())
	{
		if (count($status) > 0) {
			$this->db->where_in('status', $status);

			$result = $this->db->get($this->_table);
			return $result->num_rows();
		}
	}
}

/* End of file Newsletters_Model.php */
/* Location: ./application/modules/admin/models/Templates_Model.php */ ?>