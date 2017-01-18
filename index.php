<?php
require_once("inc/init.inc.php");

$title="Index Front";
require_once("inc/haut.front.inc.php");
echo $msg;

// Requête à traiter
$resultat = req("SELECT l.id_article, l.photo, l.titre, l.reference, l.prix, l.description, l.date_publication, l.langue, l.stock, p.prenom, p.nom
				FROM article l, personne p
				WHERE l.id_personne = p.id_personne
				ORDER BY l.date_publication desc
				LIMIT 0,10");


// Affichage des résultats les plus récents
$rubrique = ' Notre sélection du mois';
affichage_selection_mois ($resultat, $rubrique);

require_once("formulaires.php");
require_once("inc/bas.front.inc.php");

 

 ?>
 