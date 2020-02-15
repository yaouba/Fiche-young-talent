<?php 
  defined('BASEPATH') || exit('no direct script access allowed');

  $config = array(


		'category/create' => array(
      array(
				'field' => 'name',
				'label' => 'name',
				'rules' => 'required|trim|is_unique[categories.name]'
			)
		),
		'artist/create' => array(
			array(
				'field' => 'name',
				'label' => 'name',
				'rules' => 'required|trim'
      ),
      array(
				'field' => 'artist_name',
				'label' => 'artist name',
				'rules' => 'required|trim|is_unique[artists.artist_name]'
			),
			array(
				'field' => 'subname',
				'label' => 'subname',
				'rules' => 'required|trim'
			),
			array(
				'field' => 'sexe',
				'label' => 'sexe',
				'rules' => 'required|trim',
			),
			array(
				'field' => 'age',
				'label' => 'age',
				'rules' => 'required|trim|numeric'
			),
			array(
				'field' => 'location',
				'label' => 'location',
				'rules' => 'required|trim',
      ),
      array(
				'field' => 'phone',
				'label' => 'phone',
				'rules' => 'required|trim',
      ),
      array(
				'field' => 'event_participation',
				'label' => 'event_participation',
				'rules' => 'required|trim|numeric',
      ),
      array(
				'field' => 'experiences_years',
				'label' => 'experiences_years',
				'rules' => 'required|numeric',
      ),
      array(
				'field' => 'categories',
				'label' => 'categories',
				'rules' => 'required|trim',
      )
		)
	);