
<?php  
	
	require_once '../api/models/Model_cuenta.php';

	class Cuenta extends Model_cuenta{

		public function addCuenta($data){
			return Model_cuenta::addCuenta($data);
		}

		public function getInfoCuenta(){
			return Model_cuenta::getInfoCuenta();
		}

		public function getNumCuenta(){
			return Model_cuenta::getNumCuenta($data);
		}

		public function getTypeCuenta($code){
			return Model_cuenta::getTypeCuenta($code);
		}

		public function transferCuenta($data){
			return Model_cuenta::transferCuenta($data);
		}

		public function toDepositCuenta($data){
			return Model_cuenta::toDepositCuenta($data);
		}

		public function removeMoneyCuenta($data){
			return Model_cuenta::removeMoneyCuenta($data);
		}
		
	}

	$class = new Cuenta;
