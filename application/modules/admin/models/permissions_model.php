<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions_model extends CI_Model
{

	private $_table = 'roles';

	/**
	* Método para obtener los proyectos
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
		$sort_columns = array('id', 'role');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'id';

		$result = array();
		$res = FALSE;

		$this->db->select('id, role, status');
		//$this->db->where('customer_id', $this->session->userdata('customer_id'));

		if ($search != 'all') {
			$this->db->like('role', $search);
		}
		$this->db->order_by($sort_by, $sort_order);

		if ((int)$limit > 0) {
			$result = $this->db->get($this->_table, (int)$limit, (int)$offset);
		}

		if ($result->num_rows() > 0) {
			$res['data'] = $result->result();

			$this->db->select('id');
			//$this->db->where('id', $this->session->userdata('customer_id'));

			if ($search != 'all') {
				$this->db->like('role', $search);
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

		if (count($where) > 0) {
			if (!empty($fields)) {
				$this->db->select($fields);
			}
			$this->db->where($where);
			$result = $this->db->get($table);

			if ($result->num_rows() > 0) {
				return $result->result();
			}
		}

		return FALSE;
	}

	/**
	* Metodo para obtener lista de resultado en base a where especial que contiene texto
	*
	* @access public
	* @param  $table 		Tabla
	* @param  $field 		Campos a selecionar
	* @param  $where 		Condiciones ingresadas como texto
	* @return Objecto 		En caso de encontrar los resultado devuelve un objeto, sino devuelve FALSE
	*/
	public function getWhere($table = NULL, $field = '', $where = '')
	{
		$table = (is_null($table)) ? $this->_table : $table;

		if (!empty($fields) && $fields != '') {
			$this->db->select($fields);
		}

		if (!empty($where) && $where != '') {
			$this->db->where($where);
		}

		$result = $this->db->get($table);

		if ($result->num_rows() > 0) {
			return $result->result();
		}

		return FALSE;
	}

	public function getSpecial($table = NULL, $fields = '', $where = array(), $order = array(), $offset = 0, $limit = 0)
	{
		$table = (is_null($table)) ? $this->_table : $table;

		$result = array();
		$res = FALSE;

		if (!empty($fields) && $fields != '') {
			$this->db->select($fields);
		}

		if (count($where) > 0) {
			$this->db->where($where);
		}

		if (count($order) > 0)  {
			foreach ($order as $ord) {
				$this->db->order_by($ord['row'], $ord['type']);
			}
		}

		if ((int)$offset > 0 || (int)$limit > 0) {
			$this->db->limit($limit, $offset);
		}

		$result = $this->db->get($table);

		if ($result->num_rows() > 0) {
			$res['data'] = $result->result();

			$this->db->select($fields);

			if (count($where) > 0) {
				$this->db->where($where);
			}

			$result = $this->db->get($table);
			$res['num_rows'] = $result->num_rows();
		}

		return $res;
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
			$this->db->where('customer_id', $this->session->userdata('customer_id'));
			$this->db->where_in('status', $status);

			$result = $this->db->get($this->_table);
			return $result->num_rows();
		}
	}

	/**
     * Crear array asociativo teniendo como índice el key, de todos los permisos
     * de un role indicado.
     *
     * @return
     *   Array asociativo de permisos.
     */
    public function getPermisosRole($roleID)
    {
        $roleID = (int) $roleID;
        $this->db->select('role, permission, value');
        $this->db->where('role', $roleID);
        $result = $this->db->get('role_permissions')->result();

        $data = array();
        for ($i = 0; $i < count($result) ; $i++) {
        	$key = $this->getPermisoKey($result[$i]->permission);

        	if ($key == '') { continue; }

        	if ($result[$i]->value == 1) {
        		$v = 1;
        	} else {
        		$v = 0;
        	}

        	$data[$key] = array(
        		'key'		=>	$key,
        		'valor'		=>	$v,
        		'nombre'		=>	$this->getPermisoNombre($result[$i]->permission),
        		'id'		=>	$result[$i]->permission
        	);
        }
        $data = array_merge($this->getPermisosAll(), $data);

        /*
        foreach ($data as $key => $value) {
        	$nombre[$key] = $value['nombre'];
        }
        array_multisort($nombre, SORT_ASC, $data);*/
        return $data;
    }

    /**
     * Crear array asociativo teniendo como índice el key, de todos los permisos.
     *
     * @return
     *   Array asociativo de permisos.
     */
    public function getPermisosAll()
    {
    	$this->db->select('id, title, name');
    	$result = $this->db->get('permissions')->result();

    	for ($i = 0; $i < sizeof($result); $i++) {
    		if ($result[$i]->name == '') { continue; }
    		$data[$result[$i]->name] = array(
    			'key'		=>	$result[$i]->name,
    			'valor'		=>	'x',
    			'nombre'	=>	$result[$i]->title,
    			'id'		=>	$result[$i]->id
    		);
    	}
    	return $data;
    }

    // Obtener el key del permiso
    public function getPermisoKey($permisoId)
    {
        $permisoId = (int) $permisoId;
        $this->db->select('name');
        $this->db->where('id', $permisoId);
        $result = $this->db->get('permissions')->row();

        return $result->name;
    }

    // Obtener el nombre del permiso
    public function getPermisoNombre($permisoId)
    {
        $permisoId = (int) $permisoId;
        $this->db->select('title');
        $this->db->where('id', $permisoId);
        $result = $this->db->get('permissions')->row();

        return $result->title;
    }

    /**
     * Denegar permisos a un rol
     *
     * @param int $roleID
     *   Id del role a denegar.
     *
     * @param int $permisoID
     *   Id del permisos a denegar.
     *
     * @return
     *   FALSE en caso no se pueda editar el permiso o TRUE en caso de sí.
     */
    public function eliminarPermisoRole($roleID, $permisoID)
    {
        $roleID = (int) $roleID;
        $permisoID = (int) $permisoID;
        $this->db->where(array('role' => $roleID, 'permission' => $permisoID));
        $this->db->delete('role_permissions');
    }

    /**
     * Editar el permisos asignado a un rol
     *
     * @param int $roleID
     *   Id del role a editar.
     *
     * @param int $permisoID
     *   Id del permisos a editar.
     *
     * @param int $valor
     *   Estado del permisos a editar.
     *
     * @return
     *   FALSE en caso no se pueda editar el permiso o TRUE en caso de sí.
     */
    public function editarPermisoRole($roleID, $permisoID, $valor)
    {
        $roleID = (int) $roleID;
        $permisoID = (int) $permisoID;
        $valor = (int) $valor;

        $this->db->query('REPLACE INTO role_permissions SET role = ' . $roleID . ', permission =  ' . $permisoID . ', value = ' . $valor);
    }

}

/* End of file pemissions_model.php */
/* Location: ./application/modules/admin/models/pemissions_model.php */