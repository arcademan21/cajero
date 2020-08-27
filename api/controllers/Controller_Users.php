<?php

	require_once '../api/models/Model_users.php';  

	class Users extends Model_users{

	
		public function addUser($data){
			return Model_users::addUser($data);
		}

		public function getUserById($id){
			return Model_users::getUserById($id);
		}

		public function getUserByDni($dni){
			return Model_users::getUserByDni($dni);
		}

		public function getAllUsers(){
			return Model_users::getAllUsers();
		}

		public function getLastUser(){
			return Model_users::getLastUser();
		}

		public function updateUser($id){
			return Model_users::updateUser($id);
		}

		public function updateUserByColumn($data){
			return Model_users::updateUserByColumn($data);
		}

		public function deleteUser($id){
			return Model_users::deleteUser($id);
		}	

		public function loginUser($data){
			return Model_users::loginUser($data);
		}	

		public function logoutUser(){
			return Model_users::logoutUser();
		}

	}

	$class = new Users;
	