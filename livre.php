<?php
require_once("inc/init.inc.php");
$title="Livre";
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
	
	$livre = $_GET['id_livre'];
	
	// Premier cas : le numéro de livre est NULL (effacé par l'internaute)
	if (!empty($livre)) {
		
		// Requête à traiter
		$resultat = req("SELECT l.id_livre, l.id_personne, l.photo, l.titre, l.reference, l.prix, l.description, l.date_publication, l.langue, p.prenom, p.nom
				FROM livre l, personne p
				WHERE l.id_personne = p.id_personne
				AND l.id_livre = " . $livre);
				
		// Deuxième cas : le numéro de livre est inconnu
		if ($resultat -> num_rows == 0) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;">Le numéro du livre ' . $livre . ' est inconnu.';	
			$erreur++;
		}		
	}
	else 
	{
		$msg .= '<p style="color: white; background-color: red; padding: 10px;">Le numéro du livre est manquant.';	
		$erreur++;
	}
	
	if (empty($erreur))
	{
		// Affichage du livre le détail
		$personne = affichage_details ($resultat);		

		// Affichage des commentaires s'il y en a
		$resultat = req("SELECT a.id_avis, a.id_personne, a.id_livre, a.commentaire, a.note, a.date_enregistrement, p.pseudo 
						FROM avis a, personne p 
						WHERE a.id_personne = p.id_personne
						AND id_livre = " . $livre);
		   
		?>

		<!-- On affiche ou on masque les commentaires -->
		<div class="offers">
			<div class="container">
				<div class="toggle-info">
					<span class="bouton" id="bouton_texte" href="#collapse1" class="nav-toggle"><h3><span class="glyphicon glyphicon-triangle-right"></span> Commentaires sur le livre</h3></span>
				</div>
		
				<div class="info-panel">
					<?php echo '<div id="affichage" class="affichage">';
						affichage_avis ($resultat);
						echo '</div>'
					?>
				</div>

			</div>
		</div>
		
		<?php
		// L'utilisateur est-il connecté ?

		// Des mêmes auteurs - Requête à traiter
		$resultat = req("SELECT l.id_livre, l.photo, l.titre, l.reference, l.prix, l.description, l.date_publication, l.langue, p.prenom, p.nom
				FROM livre l, personne p
				WHERE l.id_personne = p.id_personne
				AND l.id_personne = " . $personne . "
				AND l.id_livre != " . $livre . "
				ORDER BY l.date_publication
				LIMIT 0,3");

		// Affichage des résultats les plus récents
		$rubrique = ' Du même auteur';
		affichage_selection_mois ($resultat, $rubrique);

	}

				
	// De la même époque
}

?>
<?php
echo $msg;
require_once("formulaires.php");
require_once("inc/bas.front.inc.php");
?>
