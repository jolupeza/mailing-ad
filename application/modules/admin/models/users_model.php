<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{
	private $_table = 'users';

	public function getAll($role = 0, $status = 'all', $limit = 0, $offset = 0, $sort_by, $sort_order, $search = 'all')
	{
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('user', 'name', 'email, status');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'user';

		$result = array();
		$res = FALSE;

		$this->db->select('u.id, u.name, u.email, u.user, r.role, u.status, u.active, u.last_login');
		$this->db->from($this->_table . ' u');
		$this->db->join('roles r', 'u.role = r.id', 'left');

		if ((int)$role > 0) {
			$this->db->where('u.role', $role);
		}

		if ($status == 'all') {
			$this->db->where_in('u.status', array(0, 1));
		} else {
			$this->db->where_in('u.status', $status);
		}

		if ($search != 'all') {
			$this->db->like('name', $search);
			$this->db->or_like('user', $search);
		}
		$this->db->order_by($sort_by, $sort_order);

		if ((int)$limit > 0) {
			$result = $this->db->get('', (int)$limit, (int)$offset);
		} else {
			$result = $this->db->get();
		}

		if ($result->num_rows() > 0) {
			$res['data'] = $result->result();

			$this->db->select('id');

			if ((int)$role > 0) {
				$this->db->where('role', $role);
			}

			if ($status == 'all') {
				$this->db->where_in('status', array(0, 1));
			} else {
				$this->db->where_in('status', $status);
			}

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

/* End of file Users_Model.php */
/* Location: ./application/modules/admin/models/Users_Model.php */ ?>