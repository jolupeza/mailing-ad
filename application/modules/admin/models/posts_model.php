<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_Model extends CI_Model
{
	private $_table = 'posts';

		/**
		* Obtenemos el listado de todos los posts del tipo post
		*
		* @access private
		* @param  $type 		str 	Indicamos el type de post a obtener (post, page, nav_menu_item, attachment)
		* @param  $status 		arr 	Indicamos el status de post a obtener
		* @param  $limit  		int 	Indicamos el límite de registros a obtener
		* @param  $offset 		int 	Indicamos desde donde queremos obtener los datos
		* @param  $sort_by 		str 	Indicamos el campo por el que queremos ordenar
		* @param  $sorder_order	str 	Indicamos si queremos ordenar ascendente o descendentemente
		* @return 				obj 	Objeto que contiene todos los posts
		*/
	public function getAllPosts($type = 'post', $status = null, $limit = null, $offset = 0, $sort_by, $sort_order, $search = 'all', $category = 'all', $master = null)
	{
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('id', 'post_title', 'published_at');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'created_at';

		$this->db->select('p.id, p.post_title, p.post_status, p.post_parent, p.post_type, u.user, p.created, p.created_at, p.published_at');
		$this->db->from($this->_table . ' p');
		$this->db->join('users u', 'p.created = u.id', 'left');

		if ((int)$category > 0) {
			$this->db->join('term_relationships tt', 'tt.post_id = p.id', 'left');
			$this->db->where('tt.term_taxonomy_id', $category);
		}

		$this->db->where('p.post_type', $type);
		if (is_array($status)) {
			$this->db->where_in('post_status', $status);
		}
		if ($search != 'all') {
			$this->db->like('post_title', $search);
		}
		$this->db->order_by($sort_by, $sort_order);
		if ($limit) {
			$result = $this->db->get('', (int) $limit, (int) $offset)->result();
		} else {
			$result = $this->db->get()->result();
		}

		if (!is_null($master)) {
			$res['masters'] = $res['childrens'] = array();
			foreach ($result as $row) {
				if ($row->post_parent == 0) {
					array_push($res['masters'], $row);
				} else {
					array_push($res['childrens'], $row);
				}
			}
		} else {
			$res['data'] = $result;
		}

		$this->db->from($this->_table . ' p');
		if ((int)$category > 0) {
			$this->db->join('term_relationships tt', 'tt.post_id = p.id', 'left');
			$this->db->where('tt.term_taxonomy_id', $category);
		}
		$this->db->where('p.post_type', $type);
		if (is_array($status)) {
			$this->db->where_in('post_status', $status);
		}
		if ($search != 'all') {
			$this->db->like('post_title', $search);
		}
		$result = $this->db->get();

		$res['num_rows'] = $result->num_rows();

		return $res;
	}

	/**
	* Obtenemos un post indicado como parámetro
	*
	* @access private
	* @param  $id  			int 	Indicamos el id del post a recuperar sus datos
	* @return 				obj 	Objeto con los datos del post a recuperar. En caso no se encuentre en la base de datos se devuelve FALSE.
	*/
	public function getPost($id = 0)
	{
		if ((int) $id > 0) {
			$this->db->where('id', (int) $id);
			return $this->db->get($this->_table)->row();
		} else {
			return FALSE;
		}
	}

	/**
	* Editamos un post determinado
	*
	* @access public
	* @param  $data 	arr		Array con los datos del post a editar.
	* @param  $id 		int 	Id del post a editar
	*/
	public function editPost($data = array(), $id = 0)
	{
		if ((int)$id > 0) {
			//Verificamos que existe el post
			$result = $this->db->select('id')->where('id', $id)->get($this->_table);
			if ($result->num_rows() == 1) {
				if (sizeof($data)) {
					// Realizamos la actualización
					$this->db->where('id', $id);
					$this->db->update($this->_table, $data);
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	/**
	* Método para obtener las categorías a la que pertenece un post
	*
	* @access public
	* @param  $id 		int 	Id del post a obtener las categorías
	* @return 			obj 	Objeto con los datos de las categorías del post, caso contrario devuelve FALSE.
	*/
	public function getCategoriesPost($id = 0)
	{
		if ((int)$id > 0) {
			$this->db->select('tt.term_taxonomy_id, t.name');
			$this->db->from('term_relationships tt');
			$this->db->join('terms t', 'tt.term_taxonomy_id = t.term_id', 'left');
			$this->db->where('post_id', $id);
			return $this->db->get()->result();
		}
		return FALSE;
	}

	/**
	* Agregar las categorías a la que pertenece un post
	*
	* @access public
	* @param  $data 		Array con los datos de la relación entre categorías y post
	* @return bool 			En caso de que se haya actualizado devuelve TRUE caso contrario devuelve FALSE
	*/
	public function addTermRelationships($data = array())
	{
		$this->db->insert('term_relationships', $data);
	}

	/**
	* Agregar un nuevo post
	*
	* @access public
	* @param  $data 	arr 	Array con los datos del nuevo post a agregar
	* @return 			bol 	En caso de agregar correctamente devuelve TRUE, caso contrario devuelve FALSE
	*/
	public function addPost($data = array())
	{
		if (sizeof($data) > 0) {
			$this->db->insert($this->_table, $data);
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	/**
	* Método que permite obtener número de registros de acuerdo a su estado
	*
	* @access public
	* @param  $post_status	arr 		Array en donde indicamos el estatus del post que queremos obtener su número total de registros.
	* @return 				int 		Número de registros obtenidos.
	*/
	public function countRows($post_status = array(), $post_type = 'post')
	{
		if (sizeof($post_status)) {
			$this->db->where('post_type', $post_type);
			$this->db->where_in('post_status', $post_status);
		}
		$result = $this->db->get($this->_table);
		return $result->num_rows();
	}

	/**
	* Método para borrar un post.
	*
	* @access public
	* @param  $id 			int			id del post a eliminar
	* @return 				bool 		En caso de eliminar el post devuelve TRUE sino devuelve FALSE
	*/
	public function deletePost($id = 0)
	{
		if ((int)$id > 0) {
			$result = $this->db->select('id')->where('id', $id)->get($this->_table);
			if ($result->num_rows() == 1) {
				$this->db->where('id', $id);
				$this->db->delete($this->_table);
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	* Eliminar las relaciones de la categoría con un post determinado
	*
	* @access public
	* @param  $id 			id del post a eliminar sus relaciones con las categorías
	* @return bool 			En caso de eliminar las relaciones del post con sus categorías devuelve TRUE sino devuelve FALSE
	*/
	public function deletePostTaxonomyRelationships($id = 0)
	{
		if ((int)$id > 0) {
			if ($this->db->select('post_id')->where('post_id', $id)->get('term_relationships')->num_rows() > 0) {
				$this->db->where('post_id', $id);
				$this->db->delete('term_relationships');;
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	* Obtener imagen destacada de post
	*
	* @access public
	* @param  $id 		int 	Id del post a obtener su imagen destacada
	* @return 			obj 	Objeto con la imagen destacada del post
	*/
	public function getFeaturedImage($id = 0)
	{
		if ((int)$id > 0) {
			$where = array('post_parent' => $id, 'post_type' => 'attachment');
			$this->db->where($where);
			return $this->db->get($this->_table)->row();
		}
	}

	/**
	* Obtener listado de páginas padres con sus hijas
	*
	* @access public
	* @return $res 		Dos Array uno contiene las páginas padres y en otras las páginas hijas
	*/
	public function getPages()
	{
		$this->db->select('id, post_title, post_parent');
		$this->db->where('post_type', 'page');
		return $this->db->get($this->_table)->result();

		/*$res['masters'] = $res['childrens'] = array();
		foreach ($result as $row) {
			if ($row->parent == 0) {
				array_push($res['masters'], $row);
			} else {
				array_push($res['childrens'], $row);
			}
		}

		return $res;*/
	}

	/**
	* Verificamos si una página tiene páginas hijas
	*
	* @access public
	* @param  $id 		Id de la página a comprobar si tiene páginas hijas
	* @return bool 		True en caso de que tenga páginas hijas, caso contrario devuelve FALSE.
	*/
	public function verifyPagesChildren($id)
	{
		if ((int)$id) {
			$this->db->select('id');
			$this->db->where(array('post_parent' => $id, 'post_type' => 'page'));
			$result = $this->db->get($this->_table);
			if ($result->num_rows() > 0) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	* Obtenemos un array multidimensional con las páginas y sus hijas
	*
	* @access public
	* @return $res 		bool 	Array multidimensional
	*/
	public function getPagesAll()
	{
		$this->db->select('id, post_title, post_parent');
		$this->db->where('post_type', 'page');
		$result = $this->db->get($this->_table)->result();

		$res['masters'] = $res['childrens'] = array();
		foreach ($result as $row) {
			if ($row->post_parent == 0) {
				array_push($res['masters'], $row);
			} else {
				array_push($res['childrens'], $row);
			}
		}

		return $res;
	}

	public function add($table, $data = array())
	{
		if (sizeof($data) > 0) {
			$this->db->insert($table, $data);
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	public function get($table, $where = array(), $id)
	{
		if (count($where) > 0) {
			$this->db->where($where);
			$result = $this->db->get($table);
			return $result->result();
		}

		return FALSE;
	}

	public function delete($table, $where = array())
	{
		if (count($where) > 0) {
			if ($this->db->where($where)->get($table)->num_rows() > 0) {
				$this->db->where($where);
				$this->db->delete($table);
				return TRUE;
			}
		}

		return FALSE;
	}

	public function edit($table, $fields = array(), $data = array(), $id = 0)
	{
		if ((int)$id > 0) {
			//Verificamos que existe el post
			$result = $this->db->where($fields)->get($table);

			if ($result->num_rows() > 0) {
				if (sizeof($data)) {
					// Realizamos la actualización
					$this->db->where($fields);
					$this->db->update($table, $data);
					return TRUE;
				}
			}
		}
		return FALSE;
	}
}

/* End of file Posts_Model.php */
/* Location: ./application/models/Posts_Model.php */ ?>