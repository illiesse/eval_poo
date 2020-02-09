<?php

require_once ("bdd.php");

class Agenda {

	/**
	* name
	* @var mixed null
	*/
	protected $name=null;

	/**
	* color
	* @var mixed null
	*/
	protected $color=null;

	/**
	* ID
	* @param $id
	*/
	protected $id=null;


	function __construct($id=null) {
		if(!empty($id)){
			$dbConnection = BDD::getConnexion();
			$inst = $dbConnection-> query('SELECT * FROM agenda.agenda WHERE id='.$id);
			if (!$inst)
				return;
			$result = $inst->fetch(PDO::FETCH_ASSOC);
			if (!$result || empty($result['id']) )
				return;
			$this->id = $result['id'];
			$this->name = $result['name'];
			$this->color = $result['color'];
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
		$query = 'SELECT * FROM agenda.agenda '.$where.' LIMIT 0,1';
		// var_dump($query);
		$res=$bdd->query($query);
		$res->setFetchMode(PDO::FETCH_CLASS, 'Agenda');
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
					INNER JOIN agenda.agenda as aa ON ae.idAgenda=aa.id
					WHERE idAgenda='.$this->id.' AND DATE('.$k.')= "'.$f.'"';
		$res = $bdd->query($query);
		// var_dump($query);
		return $res->fetchAll(PDO::FETCH_CLASS, 'Events');
	}
}