<?php 

  defined('BASEPATH') OR exit('no direct script access allowed');

  class CategoryArtist extends CI_Model {

    protected $_artist_id;
    protected $_category_id;

    protected $_is_found;

    public function __construct () {
      Parent::__construct();
      $this->clear_data();
    }

    public function __get ($key) {
      $method_name = 'get_'.$key;
      if (method_exists($this, $method_name)) {
        return $this->$method_name();
      }
      else {
        return Parent::__get($key);
      }
    }

    public function __set ($key, $value) {
      $method_name = 'set_'.$key;
      if (method_exists($this, $method_name)) {
        return $this->$method_name($value);
      }
      else {
        return Parent::__set($key, $value);
      }
    }

    public function clear_data() {
      $this->_artist_id = NULL;
      $this->_category_id = NULL;
     
      $this->_is_found = NULL;
    }

    //getters

    public function get_artist_id () {return $this->_artist_id;}

    public function get_category_id () {return $this->_category_id;}

    public function get_is_found () {return $this->_is_found;}

    //setters

    public function set_artist_id ($artist_id) {
      $artist_id = (int) $artist_id;
      if ($artist_id > 0) {
        $this->_artist_id = $artist_id;
      }
    }

    public function set_category_id ($category_id) {
      $category_id = (int) $category_id;
      if ($category_id > 0) {
        $this->_category_id = $category_id;
      }
    }

    public function exist ($category_id, $artist_id) {
      return (bool) $this->db->where([
                                'artist_id' => $artist_id,
                                'category_id' => $category_id
                              ])
                            ->count_all_results('categories_artists');
    }

    public function reset ($artist_id) {
      $this->db->delete('categories_artists', array('artist_id' => $artist_id));
    }

    public function save() {
      $data = array(
        'artist_id' => $this->_artist_id,
        'category_id' => $this->_category_id
      );
      $this->db->insert('categories_artists', $data);
    }

  }