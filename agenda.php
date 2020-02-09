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

	public static $_authorisedUpdate=['name','color'];


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
		if(is_array($filters)){
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
			$query = 'SELECT * FROM agenda.agenda '.$where;
			// var_dump($query);
			$res=$bdd->query($query);
			$res->setFetchMode(PDO::FETCH_CLASS, 'Agenda');
			return $res->fetch();
		}
		else{
			return null;
		}
	}

	public function getAllEvents($filters=[]) {
		var_dump($filters);
		$bdd = BDD::getConnexion();
		$clauses=[];
		foreach ($filters as $k => $f) {
			$clauses[]= $k.'='.$bdd->quote($f);
		}
		$k="dateTime";
		if(!empty($f)){
			$query = 'SELECT *
						FROM agenda.events as ae
						INNER JOIN agenda.agenda as aa ON ae.idAgenda=aa.id
						WHERE idAgenda='.$this->id.' AND DATE('.$k.')= "'.$f.'"';
			$res = $bdd->query($query);
			// var_dump($query);
			return $res->fetchAll(PDO::FETCH_CLASS, 'Events');
		}
		else{
			return null;
		}
	}
}