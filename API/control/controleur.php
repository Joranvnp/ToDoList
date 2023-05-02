<?php
class controleur {
	
	public function erreur404() {
		(new vue)->erreur404();
	}

	public function verifierAttributsJson($objetJson, $listeDesAttributs) {
        $verifier = true;
        foreach($listeDesAttributs as $unAttribut) {
            if(!isset($objetJson->$unAttribut)) {
                $verifier = false;
            }
        }
        return $verifier;
    }

	public function afficherTache() {
		if(isset($_GET["id"])) {
			$laTache = (new tache)->getTache($_GET["id"]);
			if(count($laTache) > 0) {
				(new vue)->afficherObjetEnJSON($laTache);
			}
			else {
				(new vue)->erreur404();
			}
		}
		else {
			$lesTaches = (new tache)->getAll();
			(new vue)->afficherObjetEnJSON($lesTaches);
		}
	}
	
	public function ajouterTache() {
		$corpsRequete = file_get_contents('php://input');

		$donnees = json_decode(file_get_contents("php://input"));
		$renvoi = null;
		if($donnees === null) {
			http_response_code(400);
			$renvoi = array("message" => "JSON envoyé incorrect");
		} else {
			$attributsRequis = array("tache", "priorite", "categorie");

			if($this->verifierAttributsJson($donnees, $attributsRequis)) {
				
				$resultat = (new tache)->ajouterTache($donnees->tache, $donnees->priorite, $donnees->categorie);
					
				if($resultat != false) {
					http_response_code(200);
					$renvoi = array("message" => "Ajout effectué avec succès");
				}
				else {
					http_response_code(500);
					$renvoi = array("message" => "Une erreur interne est survenue");
				}
				
			}
			else {
				http_response_code(400);
				$renvoi = array("message" => "Données manquantes");
			}

			(new vue)->transformerJson($renvoi);

		}
		
		// if($json = json_decode($corpsRequete, true)) {
		// 	if(isset($json["tache"]) && isset($json["priorite"]) && isset($json["categorie"])) {
		// 		$ajout = (new tache)->ajouterTache($json["tache"], $json["priorite"], $json["categorie"]);
				
		// 		if($ajout === true) {
		// 			http_response_code(201);
		// 			$json = '{ "code":201, "message": "Tache a l\'id '.$json["id"].' ajoutée." }';
		// 			(new vue)->afficherJSON($json);
		// 		}
		// 		else {
		// 			http_response_code(500);
					
		// 			$json = '{ "code":500, "message": "Erreur lors de l\'insertion." }';
		// 			(new vue)->afficherJSON($json);
		// 		}
		// 	}
		// 	else {
		// 		http_response_code(400);
			
		// 		$json = '{ "code":400, "message": "Données manquantes." }';
		// 		(new vue)->afficherJSON($json);
		// 	}
		// }
		// else {
		// 	http_response_code(400);
			
		// 	$json = '{ "code":400, "message": "Le corps de la requête est invalide." }';
		// 	(new vue)->afficherJSON($json);
		// }
	}

	public function modifierTache() {
		$donnees = json_decode(file_get_contents("php://input"));
		$renvoi = null;
		if($donnees === null) {
			http_response_code(400);
			$renvoi = array("message" => "JSON envoyé incorrect");
		}
		else {
			$attributsRequis = array("tache","priorite","categorie","id");
			if($this->verifierAttributsJson($donnees, $attributsRequis)) {
				if((new tache)->exists($donnees->id)) {
					$resultat = (new tache)->modifierTache($donnees->tache, $donnees->priorite,$donnees->categorie, $donnees->id);
					
					if($resultat != false) {
						http_response_code(201);
						$renvoi = array("message" => "Modification effectuée avec succès");
					}
					else {
						http_response_code(500);
						$renvoi = array("message" => "Une erreur interne est survenue");
					}
				}
				else {
					http_response_code(400);
					$renvoi = array("message" => "La tache spécifié n'existe pas");
				}
			}
			else {
				http_response_code(400);
				$renvoi = array("message" => "Données manquantes");
			}
		}

		(new vue)->transformerJson($renvoi);
	}
	
	public function supprimerTache() {
		$donnees = json_decode(file_get_contents("php://input"));
		$renvoi = null;
		if($donnees === null) {
			http_response_code(400);
			$renvoi = array("message" => "JSON envoyé incorrect");
		}
		else {
			$attributsRequis = array("id");
			if($this->verifierAttributsJson($donnees, $attributsRequis)) {
				if((new tache)->exists($donnees->id)) {
					$resultat = (new tache)->supprimerTache($donnees->id);
					
					if($resultat === true) {
						http_response_code(200);
						$renvoi = array("message" => "Suppression effectuée avec succès");
					}
					else {
						http_response_code(500);
						$renvoi = array("message" => "Une erreur interne est survenue");
					}
				}
				else {
					http_response_code(400);
					$renvoi = array("message" => "La tache spécifiée n'existe pas");
				}
			}
			else {
				http_response_code(400);
				$renvoi = array("message" => "Données manquantes");
			}
		}

		(new vue)->transformerJson($renvoi);
	}
}