<?php

class personne {
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
		$sql = "SELECT * FROM personne";
		
		$req = $this->pdo->prepare($sql);
		$req->execute();
		
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getPersonne($id) {
		$sql = "SELECT * FROM personne WHERE matricule_personne = :id";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':id', $id, PDO::PARAM_STR);
		$req->execute();
		
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	
	public function ajouterPersonne($matricule, $nom, $prenom, $codenation, $dateNaissance = '0000-00-00', $photo = '') {
		$sql = "INSERT INTO personne (matricule_personne, nom_personne, prenom_personne, date_naissance_personne, photo_personne, code_nationnalite) VALUES (:matricule, :nom, :prenom, :dateNaissance, :photo, :nation)";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':matricule', $matricule, PDO::PARAM_STR);
		$req->bindParam(':nom', $nom, PDO::PARAM_STR);
		$req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
		$req->bindParam(':dateNaissance', $dateNaissance, PDO::PARAM_STR);
		$req->bindParam(':photo', $photo, PDO::PARAM_STR);
		$req->bindParam(':nation', $codenation, PDO::PARAM_STR);
		return $req->execute();
	}
	
	public function modifierPersonne($matricule, $nom = null, $prenom = null, $codenation = null, $dateNaissance = null, $photo = null) {
		$sql = "UPDATE personne SET matricule_personne = :matricule";
		
		if($nom != null) {
			$sql .= ", nom_personne = :nom";
		}
		if($prenom != null) {
			$sql .= ", prenom_personne = :prenom";
		}
		if($codenation != null) {
			$sql .= ", code_nationnalite = :codeNation";
		}
		if($dateNaissance != null) {
			$sql .= ", date_naissance_personne = :dateNaissance";
		}
		if($photo != null) {
			$sql .= ", photo_personne = :photo";
		}
		
		$sql .= " WHERE matricule_personne = :matricule ";
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':matricule', $matricule, PDO::PARAM_STR);
		
		if($nom != null) {
			$req->bindParam(':nom', $nom, PDO::PARAM_STR);
		}
		if($prenom != null) {
			$req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
		}
		if($codenation != null) {
			$req->bindParam(':codeNation', $codenation, PDO::PARAM_STR);
		}
		if($dateNaissance != null) {
			$req->bindParam(':dateNaissance', $dateNaissance, PDO::PARAM_STR);
		}
		if($photo != null) {
			$req->bindParam(':photo', $photo, PDO::PARAM_STR);
		}
		
		return $req->execute();
	}
	
	public function supprimerPersonne($matricule) {
		$sql = "DELETE FROM personne WHERE matricule_personne = :matricule";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':matricule', $matricule, PDO::PARAM_STR);
		return $req->execute();
	}
}