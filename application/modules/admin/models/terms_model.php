<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms_Model extends CI_Model
{
	private $_table = 'terms';

		/**
		* Obtenemos el listado de todos los posts del tipo post
		*
		* @access private
		* @param  $type 		str 	Indicamos el type de post a obtener (category, nav-menu, post-format, post-tag)
		* @param  $status 		arr 	Indicamos el status de post a obtener
		* @param  $limit  		int 	Indicamos el límite de registros a obtener
		* @param  $offset 		int 	Indicamos desde donde queremos obtener los datos
		* @param  $sort_by 		str 	Indicamos el campo por el que queremos ordenar
		* @param  $sorder_order	str 	Indicamos si queremos ordenar ascendente o descendentemente
		* @return 				obj 	Objeto que contiene todos los posts
		*/
	public function getAllTerms($taxonomy = 'category', $limit = null, $offset = 0, $sort_by = 'name', $sort_order = 'asc', $search = 'all')
	{
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('term_id', 'name', 'description', 'slug', 'count');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'term_id';

		$this->db->select('t.term_id, t.name, t.slug, tt.description, tt.parent, tt.count');
		$this->db->from($this->_table . ' t');
		$this->db->join('term_taxonomy tt', 't.term_id = tt.term_id', 'left');
		$this->db->where('tt.taxonomy', $taxonomy);
		$this->db->where(array('tt.taxonomy' => $taxonomy));

		if ($search != 'all') {
			$this->db->like('name', $search);
		}
		$this->db->order_by($sort_by, $sort_order);
		if ($limit) {
			$result = $this->db->get('', (int) $limit, (int) $offset)->result();
		} else {
			$result = $this->db->get()->result();
		}

		if ($search == 'all') {
			$res['masters'] = $res['childrens'] = array();
			foreach ($result as $row) {
				if ($row->parent == 0) {
					array_push($res['masters'], $row);
				} else {
					array_push($res['childrens'], $row);
				}
			}
		} else {
			$res['data'] = $result;
		}


		if ($search != 'all') {
			$this->db->like('name', $search);
		}
		$result = $this->db->get($this->_table);

		$res['num_rows'] = $result->num_rows();

		return $res;
	}

	/**
	* Obtenemos una categoría indicada como parámetro
	*
	* @access private
	* @param  $id  			int 	Indicamos el id de la categoría a recuperar sus datos
	* @return 				obj 	Objeto con los datos de la categoría a recuperar. En caso no se encuentre en la base de datos se devuelve FALSE.
	*/
	public function getTerm($id = 0)
	{
		if ((int) $id > 0) {
			$this->db->select('t.term_id, t.name, t.slug, tt.taxonomy, tt.description, tt.parent, tt.count');
			$this->db->from($this->_table . ' t');
			$this->db->join('term_taxonomy tt', 't.term_id = tt.term_id', 'left');
			$this->db->where('tt.term_id', (int) $id);
			return $this->db->get()->row();
		} else {
			return FALSE;
		}
	}

	/**
	* Editamos una categoría determinada
	*
	* @access public
	* @param  $data 	arr		Array con los datos de la categoría a editar.
	* @param  $id 		int 	Id de la categoría a editar
	*/
	public function editTerm($data = array(), $id = 0)
	{
		if ((int)$id > 0) {
			//Verificamos que existe el post
			$result = $this->db->select('term_id')->where('term_id', $id)->get($this->_table);
			if ($result->num_rows() == 1) {
				if (sizeof($data)) {
					// Realizamos la actualización
					$this->db->where('term_id', $id);
					$this->db->update($this->_table, $data);
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	public function editTermTaxonomy($data = array(), $id = 0)
	{
		if ((int)$id > 0) {
			$result = $this->db->select('term_id')->where('term_id', $id)->get('term_taxonomy');
			if ($result->num_rows() == 1) {
				if (sizeof($data)) {
					$this->db->where('term_id', $id);
					$this->db->update('term_taxonomy', $data);
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	* Agregar una nueva categoría
	*
	* @access public
	* @param  $data 	arr 		Array con los datos de la nueva categoría a agregar
	* @return 			bol 		En caso de agregar correctamente devuelve Id de categoría agregada, caso contrario devuelve FALSE
	*/
	public function addTerm($data = array())
	{
		if (sizeof($data) > 0) {
			$this->db->insert($this->_table, $data);
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

		/**
		* Agregar la nueva categoría a la tabla term_taxonomy
		*
		* @access public
		* @param  $data 		arr 		Array con los datos de la nueva categoría
		* @return 				bol 		En caso de agregar correctamente devuelve Id de categoría agregada, caso contrario devuelve FALSE
		*/
	public function addTermTaxonomy($data = array())
	{
		if (sizeof($data) > 0) {
			$this->db->insert('term_taxonomy', $data);
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	/**
	* Método para borrar una categoría.
	*
	* @access public
	* @param  $id 			int			id de la categoría a eliminar
	* @return 				bool 		En caso de eliminar la categoría devuelve TRUE sino devuelve FALSE
	*/
	public function deleteTerm($id = 0)
	{
		if ((int)$id > 0) {
			$result = $this->db->select('term_id')->where('term_id', $id)->get($this->_table);
			if ($result->num_rows() == 1) {
				// Eliminamos de la tabla terms
				$this->db->where('term_id', $id);
				$this->db->delete($this->_table);
				// Eliminamos de la tabla term_taxonomy
				$this->db->where('term_id', $id);
				$this->db->delete('term_taxonomy');
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	* Obtenemos la lista completa de categorías
	*
	* @access public
	* @return arr 		Array con la lista completa de categorías
	*/
	public function getCategories()
	{
		$this->db->select('t.term_id, t.name');
		$this->db->from($this->_table . ' t');
		$this->db->join('term_taxonomy tt', 't.term_id = tt.term_id', 'left');
		$this->db->where('tt.taxonomy', 'category');
		return $this->db->get()->result();
	}

	/**
	* Obtenemos listado de categorías con categorías hijas
	*
	* @access public
	* @return arr 		Array con categorías padres y categorías hijas
	*/
	public function getCategoriesPost()
	{
		$this->db->select('t.term_id, t.name, tt.parent');
		$this->db->from($this->_table . ' t');
		$this->db->join('term_taxonomy tt', 't.term_id = tt.term_id', 'left');
		$this->db->where('tt.taxonomy', 'category');
		$result = $this->db->get()->result();

		$res['masters'] = $res['childrens'] = array();
		foreach ($result as $row) {
			if ($row->parent == 0) {
				array_push($res['masters'], $row);
			} else {
				array_push($res['childrens'], $row);
			}
		}

		return $res;
	}

	/**
	* Obtener listado de nav_menu disponibles
	*
	* @access public
	* @return obj 		Lista de nav_menus
	*/
	public function getNavMenu($idMenu = 0)
	{
		$this->db->select('t.name, t.term_id, tt.term_taxonomy_id, tt.taxonomy, tt.count');
		$this->db->from('term_taxonomy tt');
		$this->db->join($this->_table . ' t', 'tt.term_id = t.term_id', 'left');
		$this->db->where('tt.taxonomy', 'nav_menu');
		// Si pasamos la variable where entonces traeremos un menu específico
		if ((int)$idMenu > 0 && $idMenu != 'all') {
			$this->db->where('t.term_id', $idMenu);
		}

		if ($idMenu === 'all') {
			return $this->db->get()->result();
		} else if ((int)$idMenu >= 0) {
			$this->db->limit(1);
			return $this->db->get()->row();
		}
	}

	public function get($table, $id = 0, $field = null, $order = null)
	{
		if ((int)$id > 0) {
			if (!is_null($field)) {
				$this->db->where($field, $id);
			}
			if (!is_null($order)) {
				$this->db->order_by($order, 'asc');
			}
			return $this->db->get($table)->result();
		}
	}

}

/* End of file Terms_Model.php */
/* Location: ./application/models/Terms_Model.php */ ?>