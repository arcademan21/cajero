<?php  
	
	$method_list = [
		
		'get_method_list'=>[
			'getUserById', 
			'getUserByDni', 
			'getAllUsers', 
			'getLastUser'
		],

		'post_method_list'=>[ 
			'--- [Controller_users] ---',
			'addUser', 
			'getUserById', 
			'getUserByDni', 
			'getAllUsers', 
			'getLastUser', 
			'loginUser', 
			'logoutUser',
			'--- [Controller_cuenta] ---',
			'addCuenta',
			'getInfoCuenta',
			'getInfoCuenta',
			'getNumCuenta',
			'getTypeCuenta',
			'transferCuenta',		
			'toDepositCuenta',
			'removeMoneyCuenta'
		],

		'put_method_list'=>[
			'updateUser', 
			'updateUserOneColumn'
		],

		'delete_method_list'=>[
			'deleteUser'
		] 

	];

	
