<?php  
	
	require_once './models/Main_model.php';
	
	class Model_cuenta extends Main_model{
		
		protected function addCuenta($data){
			return Main_model::addCuenta($data);
		}

		protected function getInfoCuenta(){
			return Main_model::getInfoCuenta();
		}

		protected function getNumCuenta(){
			return Main_model::getNumCuenta($data);
		}

		protected function getTypeCuenta($code){
			return Main_model::getTypeCuenta($code);
		}

		protected function transferCuenta($data){
			return Main_model::transferCuenta($data);
		}

		protected function toDepositCuenta($data){
			return Main_model::toDepositCuenta($data);
		}

		protected function removeMoneyCuenta($data){
			return Main_model::removeMoneyCuenta($data);
		}
	}
