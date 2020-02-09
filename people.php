<?php

require_once ("bdd.php");

class People {

	/**
	* name
	* @var mixed null
	*/
	protected $name=null;

	/**
	* ID
	* @param $id
	*/
	protected $id=null;


	function __construct($id=null) {
		if(!empty($id)){
			$dbConnection = BDD::getConnexion();
			$inst = $dbConnection-> query('SELECT * FROM agenda.people WHERE id='.$id);
			if (!$inst)
				return;
			$result = $inst->fetch(PDO::FETCH_ASSOC);
			if (!$result || empty($result['id']) )
				return;
			$this->id = $result['id'];
			$this->name = $result['name'];
		}
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
		$query = 'SELECT * FROM agenda.people '.$where.' LIMIT 0,1';
		// var_dump($query);
		$res=$bdd->query($query);
		$res->setFetchMode(PDO::FETCH_CLASS, 'People');
		return $res->fetch();
	}

	public function getAllEvents($filters=[]) {
		// var_dump($filters);
		$bdd = BDD::getConnexion();
		$clauses=[];
		foreach ($filters as $k => $f) {
			$clauses[]= $k.'='.$bdd->quote($f);
		}
		$k="dateTime";
		$query = 'SELECT *
					FROM agenda.events as ae
					INNER JOIN agenda.event_people as aep ON ae.id=aep.idEvent
					INNER JOIN agenda.people as ap ON aep.idPeople=ap.id
					WHERE name="'.$this->name.'" AND DATE('.$k.')= "'.$f.'"';
		$res = $bdd->query($query);
		var_dump($query);
		return $res->fetchAll(PDO::FETCH_CLASS, 'People');
	}
}