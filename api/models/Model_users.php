<?php  

	require_once './models/Main_model.php';

	class Model_users extends Main_model{

		
		protected function addUser($data){
			return Main_model::addUser($data);
		}

		protected function getUserById($id){
			return Main_model::getUserById($id);
		}

		protected function getUserByDni($dni){
			return Main_model::getUserByDni($dni);
		}

		protected function getAllUsers(){
			return Main_model::getAllUsers();
		}

		protected function updateUser($data){
			return Main_model::updateUser($data);
		}

		protected function updateUserByColumn($data){
			return Main_model::updateUserByColumn($data);
		}

		protected function deleteUser($id){
			return Main_model::deleteUser($id);	
		}

		protected function getLastUser(){
			return Main_model::getLastUser();
		}

		protected function loginUser($data){
			return Main_model::loginUser($data);
		}

		protected function logoutUser(){
			return Main_model::logoutUser();
		}

	}

	
	