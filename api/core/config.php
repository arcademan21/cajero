<?php  
	
	//URIS..........................
	const SERVER_URL = 'http://localhost/COSAS_CON_FRANCIS/cajero/api/';

	//Configuracion de la conexion
	const SERVER_HOST = 'localhost';
	const SERVER_USER = 'root';
	const SERVER_PASS = '';
	const SERVER_BASE = 'cajero';
	const PDO_SDN = 'mysql:dbname='.SERVER_BASE.'; host='.SERVER_HOST.'; charset=utf8';
	
	//Otras configuraciones
	const COMPANY = 'Api_cajero';
	const SECRET_KEY = 'ELTORONTONTON@21';
	const SECRET_IV = '2130';
	const METHOD = 'AES-256-CBC';

	date_default_timezone_set('Europe/Madrid');
