<?php

class BDD {

	protected static $_instance = null ;

	static function getConnexion(){
		if (is_null(self::$_instance)){
			self::$_instance = new PDO('mysql:host=localhost;dbname=agenda', 'root', '0000');
		}
		return self::$_instance;
	}
}
