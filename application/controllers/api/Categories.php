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

    public function categories_get($id = NULL)
    {
      if ($id) {
          $category = $this->category->get_single($id);
          if ($category) {
            $this->response($category, REST_Controller::HTTP_OK);
          }
          else {
            $this->response([
              'status' => FALSE,
              'message' => 'No category were found.'
          ], REST_Controller::HTTP_NOT_FOUND);
          }
      }
      else {
        $categories = $this->category->get_list();
        if ($categories) {
          $this->response($categories, REST_Controller::HTTP_OK);
        }
        else {
          $this->response([
              'status' => FALSE,
              'message' => 'No category were found.'
          ], REST_Controller::HTTP_NOT_FOUND);
        }
      }
    }

    public function categories_post()
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


    public function categories_put($id = NULL)
    {
      if($id) {
        $this->load->library ('form_validation');

        $this->category->load($id);

        if($this->category->is_found) {
          parse_str(file_get_contents("php://input"),$put_vars);

          if(isset($put_vars['name'])) {
            $name = trim($this->db->escape(xss_clean($put_vars['name'])), '\'');
            $is_unique = '';
            if ($this->category->name != $name) {
              $is_unique = '|is_unique[categories.name]';
            }

            $this->category->name = $name;
            $this->category->save();
            
            $this->response([
              'status' => TRUE,
              'message' => 'Category updated successfuly.',
              'category' => $this->category->get_single($this->category->id)
            ], REST_Controller::HTTP_OK);
          }
          else {
            $this->response([
              'status' => FALSE,
              'message' => 'There is some error please try again later.',
              'errors' => 'category name missing'
            ], REST_Controller::HTTP_BAD_REQUEST);
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
        $this->response([
          'status' => FALSE,
          'message' => 'There is some error please try again later.',
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }
  }
