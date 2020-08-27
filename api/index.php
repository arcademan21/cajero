<?php  
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json");

	$method = $_SERVER['REQUEST_METHOD'];
	$request = json_decode(file_get_contents('php://input'));
 	
	require_once './core/Allowed_Methods.php';
	require_once './Init_Api.php';

	Init_Api::sendRequest($method, $request, $method_list);