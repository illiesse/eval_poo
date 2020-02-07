<?php

require_once ("bdd.php");

class Events {

	/**
	* titre
	* @var mixed null
	*/
	protected $title=null;

	/**
	* dateTime
	* @var mixed null
	*/
	protected $dateTime=null;

	/**
	* duration
	* @var mixed null
	*/
	protected $duration=null;

	/**
	* one_event
	* @param $id
	*/
	protected $id=null;

	protected $idAgenda=null;


	function __construct($id=null) {
		if(!empty($id)){
			$dbConnection = BDD::getConnexion();
			$inst = $dbConnection-> query('SELECT * FROM agenda.events WHERE id='.$id);
			if (!$inst)
				return;
			$result = $inst->fetch(PDO::FETCH_ASSOC);
			if (!$result || empty($result['id']) )
				return;
			$this->id = $result['id'];
			$this->title = $result['title'];
			$this->duration = $result['duration'];
		}
	}

	public static function getAllEvents($filters=[]) {
		var_dump($filters);
		$bdd = BDD::getConnexion();

		$clauses=[];
		foreach ($filters as $k => $f) {
			$clauses[]= $k.'='.$bdd->quote($f);
		}
		// var_dump($clauses);
		$where='';
		if(!empty($clauses)){
			$where= 'WHERE '.implode(' AND ', $clauses);
		}
		$query = 'SELECT * FROM agenda.events '.$where;
		// var_dump($query);
		$res=$bdd->query($query);
		return $res->fetchAll(PDO::FETCH_CLASS, 'Events');
	}

	public static function findAllbyDate($filters=[]) {
		// var_dump($filters);
		$bdd = BDD::getConnexion();

		$clauses=[];
		foreach ($filters as $k => $f) {
			$clauses[]= $k.'='.$bdd->quote($f);
		}
		var_dump($clauses);
		var_dump($k);
		$where='';
		if(!empty($clauses)){
			$where= 'WHERE DATE('.$k.')= "'.$f.'"';
		}
		$query = 'SELECT * FROM agenda.events '.$where;
		var_dump($query);
		$res=$bdd->query($query);
		return $res->fetchAll(PDO::FETCH_CLASS, 'Events');
	}

	public static function findOne($filters=[]) {
		// var_dump($filters);
		$bdd = BDD::getConnexion();

		$clauses=[];
		foreach ($filters as $k => $f) {
			$clauses[]= $k.'='.$bdd->quote($f);
		}
		// var_dump($clauses);
		$where='';
		if(!empty($clauses)){
			$where= 'WHERE '.implode(' AND ', $clauses);
		}
		$query = 'SELECT * FROM agenda.events '.$where;
		// var_dump($query);
		$res=$bdd->query($query);
		$res->setFetchMode(PDO::FETCH_CLASS, 'Events');
		return $res->fetch();
	}

}