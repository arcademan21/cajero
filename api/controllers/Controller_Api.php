<?php  

	class Controller_Api{

		protected function send($request){

			$request = self::inspectedRequest($request);
			
			if($request['status'] == 'OK'){

				$request = $request['request'];
				$controller = ucwords($request->controller); 
				$file = './controllers/Controller_'.$controller.'.php';

				if(is_file($file)){
					
					require_once $file;

					if(method_exists($class, $request->method)){
						
						$method = strval($request->method);
						if(isset($request->param))
							$param = $request->param;
						else
							$param = NULL;
						
						return $class->$method($param);
					}
					
					return json_encode(array(
						'status'=>'KO', 
						'message'=>'Error: Method not found!'
					));
					
				}	

				return json_encode(array(
					'status'=>'KO', 
					'message'=>'Error: Controller not found!'
				));

			}

			return json_encode(array(
				'status'=>'KO', 
				'message'=>$request['message']
			));
				
		}

		private function inspectedRequest($request){
			
			if(gettype($request) != 'object'){
				return array(
					'status'=>'KO', 
					'message'=>'Error: Bad request! (not object request)'
				);
			}

			if(in_array($request->controller, self::getBlackList())){
				return array(
					'status'=>'KO', 
					'message'=>'Error: Bad request! (not permision request/controller)'
				);
			}

			if(in_array($request->method, self::getBlackList())){
				return array(
					'status'=>'KO', 
					'message'=>'Error: Bad request! (not permision request/method)'
				);
			}

			if(isset($request->param)){
				if(in_array($request->param, self::getBlackList())){
					return array(
						'status'=>'KO', 
						'message'=>'Error: Bad request! (not permision request/param)'
					);
				}
			}
			

			return array(
				'status'=>'OK', 
				'message'=>'Pass request',
				'request'=>$request
			);

		}

		private function getBlackList(){ 
			return [
				'<script>',
				'</script>',
				'<script src=',
				'<script type=',
				'SELECT * FROM',
				'DELETE * FROM',
				'INSERT INTO',
				'^',
				'[',
				']',
				'==',
				';'
			];
		
		}
	
	}
