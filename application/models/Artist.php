<?php

  defined('BASEPATH') or exit('no direct script access allowed');

  /**
   * Model Aritst
   */
  class Artist extends CI_Model
  {

    private $_id;
    private $_name;
    private $_subname;
    private $_sexe;
    private $_age;
    private $_location;
    private $_phone;
    private $_artist_name;
    private $_event_participation;
    private $_experiences_years;
    private $_created_at;

    private $_is_found;

    const PARTICIPATE = 1;
    const NOT_PARTICIPATE = 0;

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
      $this->_subname = NULL;
      $this->_sexe = NULL;
      $this->_age = NULL;
      $this->_location = NULL;
      $this->_phone = NULL;
      $this->_artist_name = NULL;
      $this->_event_participation = NULL;
      $this->_experiences_years = NULL;
      $this->_created_at = NULL;

      $this->_is_found = NULL;
    }

    public function __get($key)
    {
      $method_name = 'get_'.$key;
  	  if (method_exists($this, $method_name)) {
  	    return $this->$method_name();
  	  }
  	  else {
  	    return Parent::__get($key);
  	  }
    }

    public function __set($key, $value) {
			$method = 'set_' . $key;
			if (method_exists($this, $method)) {
				return $this->$method($value);
			}
			else {
				return Parent::__set($key, $value);
			}
		}

    public function get_id()
    {
      return $this->_id;
    }

    public function get_name()
    {
      return $this->_name;
    }

    public function get_subname()
    {
      return $this->_subname;
    }

    public function _get_propertysexe()
    {
      return $this->_sexe;
    }

    public function get_age()
    {
      return $this->_age;
    }

    public function get_location()
    {
      return $this->_location;
    }

    public function get_phone()
    {
      return $this->_phone;
    }

    public function get_artist_name()
    {
      return $this->_artist_name;
    }

    public function get_event_participation()
    {
      return $this->_event_participation;
    }

    public function get_experiences_years()
    {
      return $this->_experiences_years;
    }

    public function get_created_at()
    {
      return $this->_created_at;
    }

    public function get_is_found()
    {
      return $this->_is_found;
    }

    public function set_id($id) {
			$id = (int) $id;
			if ($id > 0) {
				$this->_id = $id;
			}
		}

		public function set_name($name) {
  		$length = strlen($name);
  		if (is_string($name) && $length > 0 && $length < 255) {
  			$this->_name = $name;
  		}
  	}

  	public function set_subname($subname) {
  		$length = strlen($subname);
  		if (is_string($subname) && $length > 0 && $length < 255) {
  			$this->_subname = $subname;
  		}
  	}

  	public function set_sexe($sexe) {
  		$length = strlen($sexe);
  		if (is_string($sexe) && $length > 0 && $length < 255) {
  			$this->_sexe = $sexe;
  		}
  	}

  	public function set_age($age) {
			$age = (int) $age;
			if ($age > 0) {
				$this->_age = $age;
			}
		}

		public function set_location($location) {
  		$length = strlen($location);
  		if (is_string($location) && $length > 0 && $length < 255) {
  			$this->_location = $location;
  		}
  	}

  	public function set_phone($phone) {
  		$length = strlen($phone);
  		if (is_string($phone) && $length > 0 && $length < 255) {
  			$this->_phone = $phone;
  		}
  	}

  	public function set_artist_name($artist_name) {
  		$length = strlen($artist_name);
  		if (is_string($artist_name) && $length > 0 && $length < 255) {
  			$this->_artist_name = $artist_name;
  		}
  	}

  	public function set_event_participation ($event_participation) {
  		if (in_array($event_participation, array(self::PARTICIPATE, self::NOT_PARTICIPATE))) {
  			$this->_event_participation = $event_participation;
  		}
  	}

  	public function set_experiences_years($experiences_years) {
			$experiences_years = (int) $experiences_years;
			if ($experiences_years > 0) {
				$this->_experiences_years = $experiences_years;
			}
		}

  	public function set_status ($status) {
  		if (in_array($status, array(self::OFFLINE, self::ONLINE))) {
  			$this->_status = $status;
  		}
  	}

  	public function set_created_at ($date) {
  		if (validate_date($date)) {
  			$this->_created_at = $date;
  		}
  	}


    public function load($data = NULL)
    {
      $artist = $this->db->select(
                            'id, name, subname, sexe, age, location, phone, artist_name, event_participation, experiences_years, created_at'
                          )
                         ->from('artists')
                         ->or_where([
                           'id' => $data,
                           'artist_name' => $data
                         ])
                         ->get()->first_row();




      if ($artist) {
        $this->_id = $artist->id;
        $this->_name = $artist->name;
        $this->_subname = $artist->subname;
        $this->_sexe = $artist->sexe;
        $this->_age = $artist->age;
        $this->_location = $artist->location;
        $this->_phone = $artist->phone;
        $this->_artist_name = $artist->artist_name;
        $this->_event_participation = $artist->event_participation;
        $this->_experiences_years = $artist->experiences_years;
        $this->_created_at = $artist->created_at;
        $this->_is_found = TRUE;
      }
    }

    public function get_single($data)
    {
      $data = $this->db->select('id, name, subname, sexe, age, location, phone, artist_name, experiences_years, event_participation')
  										->from('artists')
  									 	->or_where([
                         'id' => $data,
                         'artist_name' => $data
                       ])
                       ->get()->first_row();
                       
      if($data) {
        $data->categories = $this->get_categories($data->id);
      }

      return $data;
    }

    public function get_list($id = 0, $limit = 20)
    {
      $artists = $this->db->select('id, name, subname, sexe, age, location, phone, artist_name, experiences_years, event_participation')
  										->from('artists')
  									 	->where('id >=', $id)
                      ->limit($limit)
                      ->order_by('name')
                       ->get()->result_object();
      if($artists) {
        for($i = 0; $i < sizeof($artists); $i++) {
          $artists[$i]->categories = $this->get_categories($artists[$i]->id);
        }
        
      }

      return $artists;
    }

    public function get_albums_list($id)
    {
      $data = $this->db->select('id, name, sexe, location, realesed_at, event_participation, likes')
  										 ->from('albums')
  										 ->where(array(
  												'phone' => self::ONLINE,
  												'artist_id' => $id
  										 ))
  										 ->get()->result_object();
  		return $data;
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
        'subname' => $this->_subname,
        'sexe' => $this->_sexe,
        'age' => $this->_age,
        'location' => $this->_location,
        'phone' => $this->_phone,
        'artist_name' => $this->_artist_name,
        'event_participation' => $this->_event_participation,
        'experiences_years' => $this->_experiences_years
  		);

  		if ($this->_is_found) {
  			$this->db->where('id', $this->_id)->update('artists', $data);
  		}
  		else {
  			$this->db->insert('artists', $data);
  			$this->load($this->db->insert_id());
  		}
  	}
  }

?>
