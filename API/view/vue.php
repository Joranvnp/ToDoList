<?php

class vue {
	
	public function afficherJSON($json) {
		header("Content-type: application/json; charset=utf-8");
		header("Access-Control-Allow-Origin: *");
		echo $json;
	}

	public function erreur404() {
		http_response_code(404);

		$json = '{ "code": 404, "message": "Ressource introuvable" }';
		$this->afficherJSON($json);
	}
	
	public function afficherObjetEnJSON($obj) {
		$json = json_encode($obj);
		$this->afficherJSON($json);
	}

	public function transformerJson($donnee) {
		header('Content-Type: application/json');
		header("Access-Control-Allow-Origin: *"); // Autorise l'utilisation de cette ressource depuis l'extérieur (utile pour de l'AJAX)
		$donnee = json_encode($donnee);
		echo $donnee;
	}
}