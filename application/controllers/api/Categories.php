<?php

  if (!defined('BASEPATH')) exit('No direct script access allowed');

  require APPPATH . '/libraries/REST_Controller.php';

  /**
   * Artist Ctrl
   */
  class Categories extends REST_Controller
  {

    public function __construct()
    {
      Parent::__construct();

      $this->load->model('category');
    }

    public function category_get($slug = NULL)
    {
      if ($slug) {
          $category = $this->category->get_single($slug);
          if ($category) {
            $this->response($category, REST_Controller::HTTP_OK);
          }
      }
    }

    public function list_get()
    {
      $categories = $this->category->get_list();
      if ($categories) {
        $this->response($categories, REST_Controller::HTTP_OK);
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

      if ($this->form_validation->run ('category/create') ==  false) {
				$this->response([
          'status' => FALSE,
          'message' => 'There is some error please try again later.',
          'errors' => validation_errors()
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
      else {
				$name = trim($this->db->escape(xss_clean($this->input->post('name'))), '\'');

        $this->category->load($name);

        if($this->category->is_found === NULL) {
          $this->category->name = $name;
          $this->category->save();
          
          $this->response([
            'status' => TRUE,
            'message' => 'Category add successfuly.',
            'category' => $this->category->get_single($this->category->id)
          ], REST_Controller::HTTP_OK);
        }
        else {
          $this->response([
            'status' => TRUE,
            'message' => 'This Category already exist.',
            'category' => $this->category->get_single($this->category->id)
          ], REST_Controller::HTTP_OK);
        }

		  	

			}
    }


    public function edit_post($id = NULL)
    {
      if($id) {
        $this->load->library ('form_validation');

        $this->category->load($id);

        if($this->category->is_found) {
          $name = trim($this->db->escape(xss_clean($this->input->post('name'))), '\'');
          $is_unique = '';
          if ($this->category->name != $name) {
            $is_unique = '|is_unique[categories.name]';
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
            $this->category->name = $name;
            $this->category->save();
            
            $this->response([
              'status' => TRUE,
              'message' => 'Category updated successfuly.',
              'category' => $this->category->get_single($this->category->id)
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
