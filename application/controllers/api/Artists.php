<?php

  if (!defined('BASEPATH')) exit('No direct script access allowed');

  require APPPATH . '/libraries/REST_Controller.php';

  /**
   * Artist Ctrl
   */
  class Artists extends REST_Controller
  {

    public function __construct()
    {
      Parent::__construct();

      $this->load->model('artist');
    }

    public function artist_get($slug = NULL)
    {
      if ($slug) {
          $artist = $this->artist->get_single($slug);
          if ($artist) {
            $this->response($artist, REST_Controller::HTTP_OK);
          }
      }
    }

    public function artists_get($id = 0, $limit = NULL)
    {
      $artists = $this->artist->get_list($id, $limit);
      if ($artists) {
        $this->response($artists, REST_Controller::HTTP_OK);
      }
      else {
        $this->response([
            'status' => FALSE,
            'message' => 'No artists were found.'
        ], REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function create_post()
    {
      
			$this->load->library ('form_validation');

      if ($this->form_validation->run ('artist/create') ===  false) {
				$this->response([
          'status' => FALSE,
          'message' => 'There is some error please try again later.',
          'errors' => validation_errors()
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
      else {
				$this->artist->name = trim($this->db->escape(xss_clean($this->input->post('name'))), '\'');
        $this->artist->subname = trim($this->db->escape(xss_clean($this->input->post('subname'))), '\'');
        $this->artist->sexe = trim($this->db->escape(xss_clean($this->input->post('sexe'))), '\'');
        $this->artist->age = trim($this->db->escape(xss_clean($this->input->post('age'))), '\'');
        $this->artist->location = trim($this->db->escape(xss_clean($this->input->post('location'))), '\'');
        $this->artist->phone = trim($this->db->escape(xss_clean($this->input->post('phone'))), '\'');
        $this->artist->artist_name = trim($this->db->escape(xss_clean($this->input->post('artist_name'))), '\'');
        $this->artist->event_participation = trim($this->db->escape(xss_clean($this->input->post('event_participation'))), '\'');
        $this->artist->experiences_years = trim($this->db->escape(xss_clean($this->input->post('experiences_years'))), '\'');
        $this->artist->save();


        $this->load->model('category');
        $this->load->model('categoryArtist');

        if($this->input->post('categories')) {
          $categories = trim($this->db->escape(xss_clean($this->input->post('categories'))), '\'');
          $categories = explode(';', trim($categories, ';'));

          for ($i=0; $i <sizeof($categories) ; $i++) {
            $this->category->clear_data();
            $this->category->load($categories[$i]);
            if(!$this->category->is_found) {
              $this->category->name = $categories[$i];
              $this->category->save();
            }
            $this->categoryArtist->artist_id = $this->artist->id;
            $this->categoryArtist->category_id = $this->category->id;

            $this->categoryArtist->save();
            
          }
        }

          
        $this->response([
          'status' => TRUE,
          'message' => 'artist add successfuly.',
          'artist' => $this->artist->get_single($this->artist->id)
        ], REST_Controller::HTTP_OK);
			}
    }


    public function edit_post($id = NULL)
    {
      if($id) {
        $this->load->library ('form_validation');

        $this->artist->load($id);

        if($this->artist->is_found) {
          $name = trim($this->db->escape(xss_clean($this->input->post('artist_name'))), '\'');
          $is_unique = '';
          if ($this->artist->artist_name != $name) {
            $is_unique = '|is_unique[artists.artist_name]';
          }

          $this->form_validation->set_rules('name', 'name', 'required|trim'.$is_unique);

          if ($this->form_validation->run () ==  false) {
            $this->response([
              'status' => FALSE,
              'message' => 'There is some error please try again later.',
              'errors' => validation_errors()
            ], REST_Controller::HTTP_BAD_REQUEST);
          }
          else {
            $this->artist->name = trim($this->db->escape(xss_clean($this->input->post('name'))), '\'');
            $this->artist->subname = trim($this->db->escape(xss_clean($this->input->post('subname'))), '\'');
            $this->artist->sexe = trim($this->db->escape(xss_clean($this->input->post('sexe'))), '\'');
            $this->artist->age = trim($this->db->escape(xss_clean($this->input->post('age'))), '\'');
            $this->artist->location = trim($this->db->escape(xss_clean($this->input->post('location'))), '\'');
            $this->artist->phone = trim($this->db->escape(xss_clean($this->input->post('phone'))), '\'');
            $this->artist->artist_name = trim($this->db->escape(xss_clean($this->input->post('artist_name'))), '\'');
            $this->artist->event_participation = trim($this->db->escape(xss_clean($this->input->post('event_participation'))), '\'');
            $this->artist->experiences_years = trim($this->db->escape(xss_clean($this->input->post('experiences_years'))), '\'');
            $this->artist->save();

            $this->load->model('category');
            $this->load->model('categoryArtist');

            $this->categoryArtist->reset($this->artist->id);

            if($this->input->post('categories')) {
              $categories = trim($this->db->escape(xss_clean($this->input->post('categories'))), '\'');
              $categories = explode(';', trim($categories, ';'));

              for ($i=0; $i <sizeof($categories) ; $i++) {
                $this->category->clear_data();
                $this->category->load($categories[$i]);
                if(!$this->category->is_found) {
                  $this->category->name = $categories[$i];
                  $this->category->save();
                }
                $this->categoryArtist->artist_id = $this->artist->id;
                $this->categoryArtist->category_id = $this->category->id;

                $this->categoryArtist->save();
                
              }
            }
            
            $this->response([
              'status' => TRUE,
              'message' => 'artist updated successfuly.',
              'artist' => $this->artist->get_single($this->artist->id)
            ], REST_Controller::HTTP_OK);
          }
        }
        else {
          $this->response([
            'status' => FALSE,
            'message' => 'There is some error please try again later.',
          ], REST_Controller::HTTP_BAD_REQUEST);
        }

        
      }
      else {

      }
    }
  }
