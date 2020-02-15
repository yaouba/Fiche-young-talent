<?php

  defined('BASEPATH') or exit('no direct script access allowed');

  /**
   * Model Aritst
   */
  class Category extends CI_Model
  {

    private $_id;
    private $_name;
    private $_created_at;

    private $_is_found;

    const ONLINE = 1;
    const OFFLINE = 0;

    public function __construct()
    {
      Parent::__construct();
      $this->clear_data();

      $this->load->database();
    }

    public function clear_data()
    {
      $this->_id = NULL;
      $this->_name = NULL;
      $this->_created_at = NULL;
      $this->_is_found = NULL;
    }

    public function __get($key)
    {
      $method_name = 'get_property_'.$key;
  	  if (method_exists($this, $method_name)) {
  	    return $this->$method_name();
  	  }
  	  else {
  	    return Parent::__get($key);
  	  }
    }

    public function __set($key, $value) {
      $method_name = 'set_property_'.$key;
  	  if (method_exists($this, $method_name)) {
  	    return $this->$method_name($value);
  	  }
  	  else {
  	    return Parent::__set($key, $value);
  	  }
    }

    private function get_property_id()
    {
      return $this->_id;
    }

    private function get_property_name()
    {
      return $this->_name;
    }

    private function get_property_created_at()
    {
      return $this->_created_at;
    }

    private function get_property_is_found()
    {
      return $this->_is_found;
    }

    public function set_property_id ($id) {
  		$id = (int) $id;
  		if ($id > 0) {
  			$this->_id = 0;
  		}
    }
    
    public function set_property_created_at ($date) {
  		if (validate_date($date)) {
  			$this->_created_at = $date;
  		}
  	}

  	public function set_property_name($name) {
  		$length = strlen($name);
  		if (is_string($name) && $length > 0 && $length < 255) {
  			$this->_name = $name;
  		}
  	}

    public function load($data = NULL)
    {
      $category = $this->db->select('id, name, created_at')
                         ->from('categories')
                         ->or_where([
                           'id' => $data,
                           'name' => $data
                         ])
                         ->get()->first_row();
                         
      if ($category) {
        $this->_id = $category->id;
        $this->_name = $category->name;
        $this->_created_at = $category->created_at;
        $this->_is_found = TRUE;
      }
    }

    public function get_single($data)
    {
      return $this->db->select('id, name')
                        ->from('categories')
                        ->or_where([
                         'id' => $data,
                         'name' => $data
                       ])
                       ->get()->first_row();
    }

    public function get_list($id = 0, $limit = NULL)
    {
      return $this->db->select('id, name')
                  ->from('categories')
                  ->where('id >=', $id)
                  ->limit($limit)
                  ->get()->result_object();
    }


    public function get_categories($artist_id)
    {
      return $this->db->select('C.id, C.name')
											 ->from('categories_artists CA')
											 ->join ('categories C', 'C.id = CA.category_id', 'INNER')
  										 ->where(array(
  												'CA.artist_id' => $artist_id
  										 ))
  										 ->get()->result_object();
    }

    public function save () {
  		$data = array (
  			'name' => $this->_name,
  		);

  		if ($this->_is_found) {
  			$this->db->where('id', $this->_id)->update('categories', $data);
  		}
  		else {
  			$this->db->insert('categories', $data);
  			$this->load($this->db->insert_id());
  		}
  	}

  }

?>
