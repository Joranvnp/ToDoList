<?php
session_start();

// Test de connexion à la base
$config = parse_ini_file("config.ini");
try {
	$pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
} catch(Exception $e) {
	echo "<h1>Erreur de connexion à la base de données :</h1>";
	echo $e->getMessage();
	exit;
}

// Chargement des fichiers MVC
require("control/controleur.php");
require("view/vue.php");
require("model/tache.php");

// Routes
if(isset($_GET["action"])) {
	switch($_GET["action"]) {
		case "tache":
			switch($_SERVER["REQUEST_METHOD"]) {
				case "GET":
					(new controleur)->afficherTache();
					break;
				case "POST":
					(new controleur)->ajouterTache();
					break;
				case "PUT":
					(new controleur)->modifierTache();
					break;
				case "DELETE":
					(new controleur)->supprimerTache();
					break;
			}
			break;

		// Route par défaut : erreur 404
		default:
			(new controleur)->erreur404();
			break;
	}
}
else {
	// Pas d'action précisée = afficher l'accueil
	$json = '{ "code":200, "message": "Bienvenue dans l\'API !" }';
	(new vue)->afficherJSON($json);
}