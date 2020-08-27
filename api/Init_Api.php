<?php  

	require_once './controllers/Controller_Api.php';
	
	class Init_Api extends Controller_Api{

		public static function sendRequest($method, $request, $method_list){

			switch($method){
			
				case 'GET':
					
					if(isset($_GET['request'])){
						$request = self::defragmentRequest($_GET['request']);
						if(in_array($request->method, $method_list['get_method_list'])){
							echo Controller_Api::send($request);
						}else{ echo self::badRequestMessage(); }	
					}else{ echo self::badRequestMessage(); }
					
				break;

				case 'POST':

					if(isset($request->controller)){
						if(in_array($request->method, $method_list['post_method_list'])){
							echo Controller_Api::send($request);
						}else{ echo self::badRequestMessage(); }	
					}else{ echo self::badRequestMessage(); }

				break;

				case 'PUT':
					
					if(isset($request->controller)){
						if(in_array($request->method, $method_list['put_method_list'])){
							echo Controller_Api::send($request);
						}else{ echo self::badRequestMessage(); }	
					}else{ echo self::badRequestMessage(); }
					
				break;

				case 'DELETE':

					if(isset($request->controller)){
						if(in_array($request->method, $method_list['delete_method_list'])){
							echo Controller_Api::send($request);
						}else{ echo self::badRequestMessage(); }	
					}else{ echo self::badRequestMessage(); }

				break;

			}

		}

		private function badRequestMessage(){
			
			return json_encode(array(
				'status'=>'KO', 
				'message'=>'Error: Bad request!'
			));

		}

		private function defragmentRequest($request){
			
			$request = explode('/', $request);
			$controller = $request[0];
			$method = $request[1];	

			if(!empty($request[2])){
				$param = $request[2];
			}else{
				$param = NULL;
			}
			
			return json_decode('{
				"controller": "'.$controller.'",
				"method": "'.$method.'",
				"param": "'.$param.'" 
			}');
			
		}
	}


