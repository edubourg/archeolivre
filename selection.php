<?php
require_once("inc/init.inc.php");

$title="Index Front";
require_once("inc/haut.front.inc.php");
echo $msg;

if ($_GET) {

	// Récupération des paramètres : un seul, le numéro du livre
	foreach ($_POST as $indice => $valeur) { // faille XSS
		$_POST[$indice] = htmlspecialchars($valeur);
	}

	foreach($_POST as $indice => $valeur){ // Injection SQL
		$_POST[$indice] = htmlentities(addslashes($valeur));
	}
	
	extract($_GET);
	$erreur = 0;
	
	$selection = $_GET['selection'];

	/*if (empty($selection)) {
		$selection = 'all';
	}*/
	
	// Premier cas : le type de produit (livre, revue, dvd, all) est NULL (effacé)
	if (!empty($selection)) {
	
		// Requête à traiter
		if ($selection == 'all')
		{
		$resultat = req("SELECT l.id_article, l.photo, l.titre, l.type, l.reference, l.prix, l.description, l.date_publication, l.langue, l.stock, p.prenom, p.nom
				FROM article l, personne p
				WHERE l.id_personne = p.id_personne
				ORDER BY l.date_publication desc
				LIMIT 0,20");
		}
		else
		{
		$resultat = req("SELECT l.id_article, l.photo, l.titre, l.type, l.reference, l.prix, l.description, l.date_publication, l.langue, l.stock, p.prenom, p.nom
				FROM article l, personne p
				WHERE l.id_personne = p.id_personne
				AND l.type = '" . $selection . "'				
				ORDER BY l.date_publication desc
				LIMIT 0,20");
		}
			
		// Deuxième cas : le type de produit est inconnu
		if ($resultat -> num_rows == 0) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;">Le type de produit ' . $selection . ' est inconnu.';	
			$erreur++;
			echo $msg;
		}		
	}
	else 
	{
		$msg .= '<p style="color: white; background-color: red; padding: 10px;">Le type du produit est manquant.';	
		$erreur++;
		echo $msg;
	}

	if (empty($erreur))
	{
?>

		<?php

		// Affichage des résultats les plus récents
		$rubrique = ' Notre sélection du mois';
		affichage_selection_mois ($resultat, $rubrique);

	}
// Les pop-up pour l'inscription et la connexion
}
require_once("formulaires.php");
require_once("inc/bas.front.inc.php");

 

 ?>
 