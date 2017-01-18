<?php
require_once("inc/init.inc.php");

$title="Lire Commentaire";
require_once("inc/haut.front.inc.php");
echo $msg;

if ($_POST) {
	
	
	foreach ($_POST as $indice => $valeur) { // faille XSS
		$_POST[$indice] = htmlspecialchars($valeur);
	}

	foreach($_POST as $indice => $valeur){ // Injection SQL
		$_POST[$indice] = htmlentities(addslashes($valeur));
	}

	// Conversion int pour note
	//$note = intval(substr($_POST['note'], 0, 2));

	$resultat = req("SELECT a.id_avis, a.id_personne, a.id_livre, a.commentaire, a.note, a.date_enregistrement, l.pseudo 
						FROM avis a, personne p 
						WHERE a.id_livre = l.id_livre
						AND id_livre = " . $_POST['id_livre']);

	if($resultat->num_rows != 0){

			while ($ligne = $resultat -> fetch_assoc()) {
				echo 'Commentaire : ' . $ligne['commentaire'] . ' - Note : ' . $ligne['note'] . '<br/>';
				
			}
			
	}
	
}
	
	echo $msg;
	echo '<h4 class="pull-right"><a href="accueil.php">Retour vers le catalogue</a></h4>';


require_once("formulaires.php");
require_once("inc/bas.front.inc.php");
	
?>

