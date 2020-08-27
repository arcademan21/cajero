<?php  
	
	require_once '../api/core/config.php';

	class Main_model{
		
		//Metodos para el modelo Model_users usuario...
		
		protected function addUser($data){

			if(
				isset($data->dni) 		and 
				isset($data->nombre) 	and
				isset($data->apellido) 	and
				isset($data->telefono) 	and
				isset($data->direccion) and
				isset($data->email) 	and
				isset($data->password)
			){
				$userExists = self::singleQuery('select * from users where dni = "'.$data->dni.'"', 'fetch');
			}else{
				return json_encode(array(
					'status'=>'KO',
					'message'=>'Error: failed execute query addUser (Incomplete data).'
				));
			}
			
			if($userExists){
				return json_encode(array(
					'status'=>'KO',
					'message'=>'Error: user ['.$data->nombre.'] exists.',
				));
			}

			$connection = self::singleton();
			$sql = $connection->prepare('
				INSERT INTO users (dni, nombre, apellido, telefono, direccion, email, password, fecha_registro, codigo)
				VALUES (:dni, :nombre, :apellido, :telefono, :direccion, :email, :password, :fecha_registro, :codigo)
			');

			$num_user = json_decode(self::getLastUser())->data->id +1;
			$codigo = self::randomCode(strtoupper($data->nombre[0]), 6, $num_user);
			$date = date('Y-m-d H:i:s');
			$password = self::encription($data->password);

			$sql->bindParam(':dni', $data->dni);
			$sql->bindParam(':nombre', $data->nombre);
			$sql->bindParam(':apellido', $data->apellido);
			$sql->bindParam(':telefono', $data->telefono);
			$sql->bindParam(':direccion', $data->direccion);
			$sql->bindParam(':email', $data->email);
			$sql->bindParam(':password', $password);
			$sql->bindParam(':fecha_registro', $date);
			$sql->bindParam(':codigo', $codigo);

			try{
				$sql->execute();
				return json_encode(array(
					'status'=>'OK',
					'message'=>'New user added correctly.'
				));
			}catch(PDOException $e){
				return json_encode(array(
					'status'=>'KO',
					'message'=>'Error: failed execute query addUser.',
					'errorCode'=>$e->getCode()
				));
			}
				
		}

		protected function getUserById($id){

			if(isset($id)){
				
				$user_exist = self::singleQuery('select * from users where id = '.$id, 'fetch');

				if($user_exist > 0){
					return json_encode(array(
						'status'=>'OK',
						'message'=>'user founded!',
						'data'=>$user_exist
					));
				}

				return json_encode(array(
					'status'=>'KO',
					'message'=>'user not founded!'
				));
			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'Error: failed execute query getUserById (Incomplete data).'
			));
				
		}

		protected function getUserByDni($dni){

			if(isset($dni)){
				
				$user_exist = self::singleQuery('select * from users where dni = "'.$dni.'"', 'fetch');

				if($user_exist){
					return json_encode(array(
						'status'=>'OK',
						'message'=>'user founded!',
						'data'=>$user_exist
					));
				}

				return json_encode(array(
					'status'=>'KO',
					'message'=>'user not founded!'
				));
			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'Error: failed execute query getUserByDni (Incomplete data).'
			));
		}

		protected function getAllUsers(){

			$data = self::singleQuery('select * from users limit 50', 'fetchAll');

			if($data){
				return json_encode(array(
					'status'=>'OK',
					'message'=>'take '.count($data).' registers limit 50',
					'data'=>$data
				));
			}else{
				return json_encode(array(
					'status'=>'KO',
					'message'=>'No data registers'
				));
			}

		}

		protected function getLastUser(){

			$user_exist = self::singleQuery('select * from users order by id desc limit 1', 'fetch');

			if(!$user_exist){
				return json_encode(array(
					'status'=>'KO',
					'message'=>'user not founded!'
				));
			}

			return json_encode(array(
				'status'=>'OK',
				'message'=>'user founded!',
				'data'=>$user_exist
			));

		}

		protected function updateUser($data){
			
			if(
				isset($data->id) and 
				isset($data->nombre) and
				isset($data->apellido) and
				isset($data->telefono) and
				isset($data->direccion) 
			){
				$user_exist = self::singleQuery('select nombre from users where id = '.$data->id, 'fetch');
			}else{
				return json_encode(array(
					'status'=>'KO',
					'message'=>'Error: failed execute query updateUser (Incomplete data).'
				));
			}
		
			if($user_exist){

				$user = json_decode(self::getUser($data->id));
				
				if($user->data == $data){
					return json_encode(array(
						'status'=>'KO',
						'message'=>'There is nothing to update.'
					));
				}else{

					$connection = self::singleton();
					$sql = $connection->prepare('
						UPDATE users 
						SET dni = :dni, 
							nombre = :nombre, 
							apellido = :apellido, 
							telefono = :telefono, 
							direccion = :direccion
						WHERE id = '.$data->id
					);

					$sql->bindParam(':dni', $data->dni);
					$sql->bindParam(':nombre', $data->nombre);
					$sql->bindParam(':apellido', $data->apellido);
					$sql->bindParam(':telefono', $data->telefono);
					$sql->bindParam(':direccion', $data->direccion);

					try{
						$sql->execute();
						return json_encode(array(
							'status'=>'OK',
							'message'=>'The user '.$data->nombre.' was updated successfully.'
						));
					}catch(PDOException $e){
						return json_encode(array(
							'status'=>'KO',
							'message'=>'Error: failed execute query updateUser.',
							'errorCode'=>$e->getCode()
						));
					}

				}
				
			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'user not founded!'
			));
		
		}

		protected function updateUserByColumn($data){
			
			if(
				isset($data->id) and 
				isset($data->column) and 
				isset($data->value) 
			){
				$user_exist = self::singleQuery('select nombre from users where id = '.$data->id, 'fetch');
			}else{
				return json_encode(array(
					'status'=>'KO',
					'message'=>'Error: failed execute query updateUserByColumn (Incomplete data).'
				));
			}

			if($user_exist){
				
				$connection = self::singleton();
				$sql = $connection->prepare('
					UPDATE users 
					SET '.$data->column.' = :value where id = '.$data->id
				);

				$sql->bindParam(':value', $data->value);
				
				try{
					$sql->execute();
					return json_encode(array(
						'status'=>'OK',
						'message'=>'The user '.$user_exist->nombre.' was updated successfully.'
					));
				}catch(PDOException $e){
					return json_encode(array(
						'status'=>'KO',
						'message'=>'Error: failed execute query updateUserByColumn.',
						'errorCode'=>$e->getCode()
					));
				}
			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'user not founded!'
			));
		
		}

		protected function deleteUser($id){
			
			if(isset($id)){
				$user = self::singleQuery('select * from users where id = '.$id);
			}else{
				return json_encode(array(
					'status'=>'KO',
					'message'=>'Error: failed execute query deleteUser (Incomplete data).'
				));
			}
			

			if($user){
				self::singleQuery('delete from users where id = '.$id);
				return json_encode(array(
					'status'=>'OK',
					'message'=>'user deleted correctly.'
				));
			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'user not founded!'
			));
		
		}

		protected function loginUser($data){

			if(isset($data->email) and isset($data->password)){
				
				session_start();
				
				if(!isset($_SESSION['estado_session'])){
					
					$user_exist = self::singleQuery('
						select * 
						from users 
						where email = "'.$data->email.'" 
						and password = "'.self::encription($data->password).'"', 
					'fetch');

					if($user_exist > 0){
						

						$user = json_decode(json_encode($user_exist));

						$_SESSION['estado_session'] = true;
						$_SESSION['user_dni'] = $user->dni;
						$_SESSION['user_nombre'] = $user->nombre;
						$_SESSION['user_apellido'] = $user->apellido;
						$_SESSION['user_telefono'] = $user->telefono;
						$_SESSION['user_direccion'] = $user->direccion;
						$_SESSION['user_email'] = $user->email;
						$_SESSION['user_codigo'] = $user->codigo;

						return json_encode(array(
							'status'=>'OK',
							'message'=>'Login user '.$_SESSION['user_nombre'].' successfully',
							'data'=>$user_exist
						));
					}

					return json_encode(array(
						'status'=>'KO',
						'message'=>'user not founded!'
					));
				}

				return json_encode(array(
					'status'=>'KO',
					'message'=>'user session is allready!'
				));

			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'Error: failed execute query addUser (Incomplete data).'
			));

		}

		protected function logoutUser(){
			
			session_start();
			if(isset($_SESSION['estado_session'])){
				session_destroy();
				session_unset();
				return json_encode(array(
					'status'=>'OK',
					'message'=>'User logout correctly!'
				));
			}
			
			return json_encode(array(
				'status'=>'KO',
				'message'=>'not User login!'
			));
			
		}

		//Metodos para el modelo Model_Cuenta...
		protected function addCuenta($data){

			session_start();

			if(isset($_SESSION['estado_session'])){

				$cuentaExists = self::singleQuery('select * from cuentas where id_cuenta = "'.$_SESSION['user_codigo'].'"', 'fetch');
				if($cuentaExists){
					return json_encode(array(
						'status'=>'KO',
						'message'=>'Error: cuenta all ready exists.',
					));
				}

				$connection = self::singleton();
				$sql = $connection->prepare('
					INSERT INTO propietarios (dni, nombre, apellido, telefono, direccion, email, codigo)
					VALUES (:dni, :nombre, :apellido, :telefono, :direccion, :email, :codigo)
				');

				$connection = self::singleton();
				$sql_cuenta = $connection->prepare('
					INSERT INTO cuentas (id_cuenta, num_cuenta, tipo_cuenta, fondos, fecha_creacion, caducidad, codigo_secreto)
					VALUES (:id_cuenta, :num_cuenta, :tipo_cuenta, :fondos, :fecha_creacion, :caducidad, :codigo_secreto)
				');

				if(isset($data->tipo_cuenta) and isset($data->fondos)){

					$date = date('Y-m-d H:i:s');
					$caducidad = date('Y-m-d H:i:s', strtotime("+5 years"));

					if(isset($data->dni))
						$dni_char = explode('-', $data->dni);
					else
						$dni_char = explode('-', $_SESSION['user_dni']);

					if(isset($data->nombre))
						$num_cuenta = self::randomCode(strtoupper($data->nombre[0]), 24, $dni_char[1]);
					else
						$num_cuenta = self::randomCode(strtoupper($_SESSION['user_nombre'][0]), 24, $dni_char[1]);

					
					$codigo_secreto = self::secretCodeGenerator(2);

					$sql_cuenta->bindParam(':id_cuenta', $_SESSION['user_codigo']);
					$sql_cuenta->bindParam(':num_cuenta', $num_cuenta);
					$sql_cuenta->bindParam(':tipo_cuenta', $data->tipo_cuenta);
					$sql_cuenta->bindParam(':fondos', $data->fondos);
					$sql_cuenta->bindParam(':fecha_creacion', $date);
					$sql_cuenta->bindParam(':caducidad', $caducidad);
					$sql_cuenta->bindParam(':codigo_secreto', $codigo_secreto);

				}else{
					return json_encode(array(
						'status'=>'KO',
						'message'=>'Error: failed execute query addCuenta (Incomplete data).'
					));
				}

				if(
					isset($data->dni) 		and 
					isset($data->nombre) 	and
					isset($data->apellido) 	and
					isset($data->telefono) 	and
					isset($data->direccion) and
					isset($data->email) 	and
					isset($data->password)
				){
					$sql->bindParam(':dni', $data->dni);
					$sql->bindParam(':nombre', $data->nombre);
					$sql->bindParam(':apellido', $data->apellido);
					$sql->bindParam(':telefono', $data->telefono);
					$sql->bindParam(':direccion', $data->direccion);
					$sql->bindParam(':email', $data->email);
					$sql->bindParam(':codigo', $_SESSION['user_codigo']);

				}else{

					$sql->bindParam(':dni', $_SESSION['user_dni']);
					$sql->bindParam(':nombre', $_SESSION['user_nombre']);
					$sql->bindParam(':apellido', $_SESSION['user_apellido']);
					$sql->bindParam(':telefono', $_SESSION['user_telefono']);
					$sql->bindParam(':direccion', $_SESSION['user_direccion']);
					$sql->bindParam(':email', $_SESSION['user_email']);
					$sql->bindParam(':codigo', $_SESSION['user_codigo']);

				}
				
				try{
					$sql_cuenta->execute();
					$sql->execute();
					return json_encode(array(
						'status'=>'OK',
						'message'=>'New Cuenta added correctly.'
					));
				}catch(PDOException $e){
					return json_encode(array(
						'status'=>'KO',
						'message'=>'Error: failed execute query addCuenta.',
						'errorCode'=>$e->getCode()
					));
				}

			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'No session init to addcuenta'
			));
		
		}

		protected function getInfoCuenta(){
			
			session_start();

			if($_SESSION['estado_session']){
				
				$result = self::singleQuery('
					select * from cuentas, propietarios 
					where cuentas.id_cuenta = "'.$_SESSION['user_codigo'].'" 
					and propietarios.codigo = "'.$_SESSION['user_codigo'].'"
				', 'fetch');

				return json_encode(array(
					'status'=>'OK',
					'message'=>'Result of the cuenta '.$result['id_cuenta'],
					'data'=>[
						'cuenta'=>[
							'id_cuenta'=>$result['id_cuenta'],
							'num_cuenta'=>$result['num_cuenta'],
							'tipo_cuenta'=>$result['tipo_cuenta'],
							'fondos'=>$result['fondos'],
							'estado'=>$result['estado'],
							'fecha_creacion'=>$result['fecha_creacion'],
							'caducidad'=>$result['caducidad'],
							'codigo_secreto'=>$result['codigo_secreto']
						],
						'propietario'=>[
							'dni'=>$result['dni'],
							'nombre'=>$result['nombre'],
							'apellido'=>$result['apellido'],
							'telefono'=>$result['telefono'],
							'direccion'=>$result['direccion'],
							'email'=>$result['email'],
							'codigo'=>$result['codigo']
						]
					]
				));
			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'Not session init'
			));
		
		}

		protected function getNumCuenta(){
			
			session_start();

			if($_SESSION['estado_session']){
				
				$result = self::singleQuery('
					select num_cuenta from cuentas 
					where id_cuenta = "'.$_SESSION['user_codigo'].'"', 	
				'fetch');

				return json_encode(array(
					'status'=>'OK',
					'message'=>'Result of the cuenta '.$result['num_cuenta'],
					'data'=>$result
				));
			}

			return json_encode(array(
				'status'=>'KO',
				'message'=>'Not session init'
			));

		}

		protected function getTypeCuenta($code){
			return json_encode(array(
				'status'=>'OK',
				'message'=>'estoy resiviendo! (getTypeCuenta)'
			));
		
		}

		protected function transferCuenta($data){
			return json_encode(array(
				'status'=>'OK',
				'message'=>'estoy resiviendo! (transactionCuenta)'
			));
		
		}

		protected function toDepositCuenta($data){
			return json_encode(array(
				'status'=>'OK',
				'message'=>'estoy resiviendo! (toDepositCuenta)'
			));
		
		}

		protected function removeMoneyCuenta($data){
			return json_encode(array(
				'status'=>'OK',
				'message'=>'estoy resiviendo! (removeMoneyCuenta)'
			));
		
		}

		//Metodos de clase...
		
		private function singleton(){
			
			try{
				$options = array(
					PDO::ATTR_PERSISTENT => true,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				); $connection = new PDO(PDO_SDN, SERVER_USER, SERVER_PASS, $options);
			}catch(PDOException $e){
			    echo $e->getMessage();
			}

			return $connection;
		
		}

		private function registerExist($query){
			$connection = self::singleton();
			$response = $connection->prepare($query);
			$response->execute();
			return $response->rowCount();
		
		}

		private function singleQuery($query, $type=false){
			
			$connection = self::singleton();
			$response = $connection->prepare($query);
			
			try{
				
				if($type){
					$response->execute();
					$type = strval($type);
					return $response->$type(PDO::FETCH_ASSOC);
				}

				return $response->execute();

			}catch(PDOException $e){
				return json_encode(array(
					'status'=>'KO',
					'message'=>'Error: failed execute query.',
					'errorCode'=>$e->getCode()
				));
			}

		}

		private function encription($str){
			$output = false;
			$key = hash('sha256', SECRET_KEY);
			$iv = substr(hash('sha256', SECRET_IV), 0, 16);
			$output = openssl_encrypt($str, METHOD, $key, 0, $iv);
			$output = base64_encode($output);
			return $output;
		
		}

		private function decription($str){
			$key = hash('sha256', SECRET_KEY);
			$iv = substr(hash('sha256', SECRET_IV), 0, 16);
			$output = openssl_decrypt(base64_decode($str), METHOD, $key, 0, $iv);
			return $output;
		
		}

		private function randomCode($char, $long, $val){
			for($i = 1; $i <= $long; $i++){
				$rand = rand(0,9);
				$char.= $rand;
			}
			return $char.'-'.$val;
		
		}

		private function secretCodeGenerator($long){
			for($i = 0; $i <= $long; $i++){
				$rand .= rand(0,9);
			}
			return $rand;
		
		}

	}
