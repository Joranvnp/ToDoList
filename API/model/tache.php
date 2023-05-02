<?php

class tache {
	// Objet PDO servant à la connexion à la base
	private $pdo;

	// Connexion à la base de données
	public function __construct() {
		$config = parse_ini_file("config.ini");
		
		try {
			$this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function getAll() {
		$sql = "SELECT * FROM tache";
		
		$req = $this->pdo->prepare($sql);
		$req->execute();
		
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getTache($id) {
		$sql = "SELECT * FROM tache WHERE id = :id";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':id', $id, PDO::PARAM_STR);
		$req->execute();
		
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	
	public function ajouterTache($tache, $priorite, $categorie) {
		$sql = "INSERT INTO tache (tache, priorite, categorie) VALUES (:tache, :priorite, :categorie)";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':tache', $tache, PDO::PARAM_STR);
		$req->bindParam(':priorite', $priorite, PDO::PARAM_INT);
		$req->bindParam(':categorie', $categorie, PDO::PARAM_STR);

		return $req->execute();
	}
	
	public function modifierTache($tache, $priorite, $categorie, $id) {
		$sql = "UPDATE tache set tache = :tache, priorite = :priorite , categorie = :categorie where id = :id";

		$req = $this->pdo->prepare($sql);
		$req->bindParam(':tache', $tache, PDO::PARAM_STR);
		$req->bindParam(':priorite', $priorite, PDO::PARAM_INT);
		$req->bindParam(':categorie', $categorie, PDO::PARAM_STR);
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		return $req->execute();
	}
	
	public function supprimerTache($id) {
		$sql = "DELETE FROM tache WHERE id= :id";
		$req = $this->pdo->prepare($sql);
	
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		return $req->execute();
	}

	public function exists($id) {
		$sql = "SELECT COUNT(*) AS nb FROM tache WHERE id = :id";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		$req->execute();
		
		$nb = $req->fetch(\PDO::FETCH_ASSOC)["nb"];
		if($nb == 1) {
			return true;
		}
		else {
			return false;
		}
	}
}