# Fiche-young-talent
Api for colleting some information about artist


#prerequis

serveur web (xampp, wampp)
Rest client

there is so many plugins for firefox ou chrome
	firefox: RESTClient 
	chrome: Postman

#configuration

go on your htdocs folder an create a new folder "api"
extract the file you download on "api" folder" or just use git pull

Now you should have this structure
httdocs/api/
	-application
	-systeme
	-index.php
	-databse.sql
	-.htaccess

start you web server
go to phpmyadmin an create a new database that you call api
then import database.sql file

now you can go on httdocs/api/application/config/database.php file
hostname: should be 'localhost'
username: should be your database username
password: your database password

#REST Client configuration

Authentication:
	type: basic
	username: admin
	password: 1234

you can change it on httdocs/api/application/config/rest.php

Headers:
	
	name: X-API-KEY
	value: API@123
	you can change or add other on api_keys table
	
	if you use firefox rest client you should add another header to send post or put data
	name: Content-Type
	value: application/x-www-form-urlencoded


now it's done just follow the documentation


#Documentation

	#Artist
	
	description: get specific artist
	url: localhost/young/api/artists/id
	method: GET
	result: an artist


	description: get the list of artits
	url: localhost/young/api/artists/
	method: GET
	result: array list of artists

	description: add new artist
	url: localhost/young/api/artists/
	method: POST
	body: name, subnmae, sexe, age, location, phone, artist_name, event_participation, experiences_years, categories
	#categories should be a list like "pop;rock"
	result: 
		status: True is all is good
			false if not
		errors: messages errors
		

	description: Edit an artist
	url: localhost/young/api/artists/id
	method: PUT
	body: name, subnmae, sexe, age, location, phone, artist_name, event_participation, experiences_years
	#you just send the data that you want to change
	result: 
		status: True is all is good
			false if not

	#Categories
	
	description: get specific categories
	url: localhost/young/api/categories/id
	method: GET
	result: a category


	description: get the list of categories
	url: localhost/young/api/categorie/
	method: GET
	result: array list of categories

	description: add new categories
	url: localhost/young/api/categories/
	method: POST
	body: name
	result: 
		status: True is all is good
			false if not
		errors: messages errors
		

	description: Edit an categories
	url: localhost/young/api/categories/id
	method: PUT
	body: name
	result: 
		status: True is all is good
			false if not
		errors: messages errors






