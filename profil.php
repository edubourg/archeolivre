<?php
require_once("inc/init.inc.php");

$title="Profil";
require_once("inc/haut.front.inc.php");
echo $msg;

// Si non connecté : redirection vers connexion
if(!userConnecte()){
	header('location: connexion.php');	
}

// HTML de ma page
echo '<h1>Mon Profil</h1>';
//----------------
echo '<ul>';
echo '	<li>Pseudo : <b>' . htmlspecialchars($_SESSION['membre']['pseudo']) . '</b></li>';
echo '	<li>Nom : <b>' . htmlspecialchars($_SESSION['membre']['nom']) . '</b></li>';
echo '	<li>Prénom : <b>' . htmlspecialchars($_SESSION['membre']['prenom']) . '</b></li>';
echo '	<li>Adresse email : <b>' . htmlspecialchars($_SESSION['membre']['email']) . '</b></li>';

$date_reformate = date("d/m/Y H:i:s", strtotime($_SESSION['membre']['date_inscription'])); 

echo '	<li>Date d\'enregistrement : <b>' . $date_reformate . '</b></li>';
echo '</ul><br /><br />';

//echo '<a href="membres.php" class="btn btn-default col-md-4 col-md-offset-4">Modifiez vos coordonnées</a>';

//echo '<br /><br />';
echo '<br /><br />';

//----------------

				
echo '<h1>Mes Commandes</h1><br />';

// Requête listant toutes les commandes existantes
$resultat = req("SELECT DISTINCT lc.id_liste_commande 
				FROM ouvrages_commandes oc, liste_commandes lc 
				WHERE lc.id_liste_commande = oc.id_liste_commande
				AND lc.id_personne = " . $_SESSION['membre']['id_personne'] );

// teste le nombre de résultats
if ($resultat -> num_rows) {

	// On affiche ou on masque les commandes
	while($ligne = $resultat -> fetch_assoc()){ 
		echo '<div class="offers"><div class="container">';
		
		echo '<div class="toggle-info">';
		echo '<div class="bouton bouton_texte nav-toggle" href="#collapse1"><h3><span class="glyphicon glyphicon-triangle-right"></span>';
		echo 'Commande numéro '. $ligne['id_liste_commande'] . '</h3></div>';
		echo '</div>';

		echo '<div class="info-panel">';
		echo '<div class="affichage">';
		affichage_commande ($ligne['id_liste_commande']);
		echo '</div>';
		echo '</div>';

		echo '</div></div>';
	}
}

 else {
	
		$msg .= '<p style="color: white; background-color: green; padding: 10px;">Aucune commande à votre actif</p>';	
		echo $msg;
		
}
	
require_once("inc/bas.front.inc.php");

 

 ?>
 