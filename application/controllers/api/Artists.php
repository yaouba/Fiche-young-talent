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

    public function artists_get($id = NULL)
    {
      if ($id) {
        $artist = $this->artist->get_single($id);
        if ($artist) {
          $this->response($artist, REST_Controller::HTTP_OK);
        }
        else {
          $this->response([
            'status' => FALSE,
            'message' => 'No artists were found.'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
      else {
        $artists = $this->artist->get_list();
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
    }

    public function artists_post()
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

    public function artists_put($id) {
      if($id) {
        $this->load->library ('form_validation');

        $this->artist->load($id);

        parse_str(file_get_contents("php://input"),$put_vars);

        if($this->artist->is_found) {
          
          $this->artist->name = trim($this->db->escape(xss_clean($put_vars['name'])), '\'');
          $this->artist->subname = trim($this->db->escape(xss_clean($put_vars['subname'])), '\'');
          $this->artist->sexe = trim($this->db->escape(xss_clean($put_vars['sexe'])), '\'');
          $this->artist->age = trim($this->db->escape(xss_clean($put_vars['age'])), '\'');
          $this->artist->location = trim($this->db->escape(xss_clean($put_vars['location'])), '\'');
          $this->artist->phone = trim($this->db->escape(xss_clean($put_vars['phone'])), '\'');
          $this->artist->artist_name = trim($this->db->escape(xss_clean($put_vars['artist_name'])), '\'');
          $this->artist->event_participation = trim($this->db->escape(xss_clean($put_vars['event_participation'])), '\'');
          $this->artist->experiences_years = trim($this->db->escape(xss_clean($put_vars['experiences_years'])), '\'');
          
          $this->artist->save();


          $this->load->model('category');
          $this->load->model('categoryArtist');

          if($this->input->post('categories')) {
            die();
            $this->categoryArtist->reset($this->artist->id);
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
          'errors' => validation_errors()
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }
    
  }
?>
