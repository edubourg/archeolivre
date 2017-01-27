<?php
require_once("../inc/init.inc.php");

if(!userConnecteAdmin()) {  
        header("location:../connexion.php");
    }

//-------------- SUPPRESSION commande EN BDD

if(isset($_GET['action']) && $_GET['action'] == "suppression" ){
	
	// Récupération de la quantité d'articles à remettre en vente
	$resultat = req("SELECT oc.quantite, oc.id_article, lc.id_personne, lc.montant, lc.date_commande
				FROM ouvrages_commandes oc, liste_commandes lc 
				WHERE lc.id_liste_commande = oc.id_liste_commande
				AND oc.id_liste_commande = $_GET[id_liste_commande]
				AND oc.id_article = $_GET[id_article]");
	
	$commande = $resultat -> fetch_assoc();

	// Récupération du nombre de stock en vente
	$resultat = req("SELECT * 
				FROM article 
				WHERE id_article = $commande[id_article]");

	$livre = $resultat -> fetch_assoc();

	// Récupération des valeurs en commande
	$resultat = req("SELECT * 
				FROM ouvrages_commandes 
				WHERE id_article = $commande[id_article]");
	
	$ouvrages_cmd = $resultat -> fetch_assoc();
	
	// Remettre à jour le nombre livres commandés
	$nouveau_stock = $commande['quantite'] + $livre['stock'];
	$titre = addslashes($livre['titre']);
	$description = addslashes($livre['description']);
	
	req("REPLACE INTO article (id_article, id_personne, titre, description, reference, photo, prix, date_publication, langue, stock) 
		VALUES ($livre[id_article], $livre[id_personne], '$titre', '$description', $livre[reference], 
		'$livre[photo]', $livre[prix], '$livre[date_publication]', '$livre[langue]', $nouveau_stock)");

	// supprimer la commande
	req("DELETE FROM ouvrages_commandes WHERE id_liste_commande= $_GET[id_liste_commande] AND id_article = $_GET[id_article]");
	
	// sur la table liste_commandes diminuer le montant total
	$nouveau_montant = $commande['montant'] - $commande['quantite'] * $ouvrages_cmd['prix'];
	req("REPLACE INTO liste_commandes (id_liste_commande, id_personne, montant, date_commande)
		VALUES ($_GET[id_liste_commande], $commande[id_personne], $nouveau_montant, '$commande[date_commande]')");

	if ($nouveau_montant == 0)
	{
		req("DELETE FROM liste_commandes WHERE id_liste_commande= $_GET[id_liste_commande]");
	}

	$msg .= '<p style="color: white; background-color: green; padding: 10px;">La commande id:' . $_GET['id_liste_commande'] .  ' a été supprimé avec succès !</p>';
	$_GET['action'] = 'affichage';  
}

//-------------- MODIFICATION commande EN BDD
if($_POST){
	//debug($_POST);

	// Pour parer aux failles XSS
	foreach ($_POST as $indice => $valeur) { 
		$_POST[$indice] = htmlspecialchars($valeur);
	}
	
	foreach($_POST as $indice => $valeur){ // Injection SQL
		$_POST[$indice] = htmlentities(addslashes($valeur));
	}
	
	req("REPLACE INTO liste_commandes (id_liste_commande, id_personne, montant, date_commande) VALUES ('$_POST[id_liste_commande]', '$_POST[id_personne]', '$_POST[montant]', '$_POST[date_commande]')");

	$msg .= '<p style="color: white; background-color: green; padding: 10px;">La nouvelle commande a été enregistrée !</p>';
	$_GET['action'] = "affichage";
	header("location:gestion_commande.php");
 
}

$title="Gestion commande";
require_once("../inc/haut.back.inc.php");
echo $msg;

?>

<!-- HTML -->
<div id="page-wrapper">

<br />

<?php

// Affichage de la table commande

if(isset($_GET['action']) && $_GET['action'] == "affichage" ){

$resultat = req("SELECT lc.id_liste_commande, l.titre as Titre, oc.id_article, l.photo, oc.quantite, oc.prix, lc.date_commande 
				FROM ouvrages_commandes oc, article l, liste_commandes lc 
				WHERE oc.id_article = l.id_article
				AND lc.id_liste_commande = oc.id_liste_commande
				ORDER BY 1");

?>

    <div class="container-fluid">

		<div class="col-lg-12">
			<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped" 
			id="pagination" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
				<?php while($colonne = $resultat -> fetch_field()){
				/*	if ($colonne->name == 'id_salle') {
						echo '<th> Identifiant - Nom Salle </th>';
					}
					else
					{*/
						echo '<th>' . $colonne -> name . '</th>';	
					//} 
				} ?>
				<th>actions</th>
				</tr>
			</thead>
		
			<?php
			while($ligne = $resultat -> fetch_assoc()){
			echo '<tr>';
			foreach($ligne as $indice => $valeur){

				//récupération de l'email et concaténation avec id_personne
				switch ($indice) {
					
					case 'id_liste_commande':
						$id_liste_commande = $valeur;
						echo '<td>' . $valeur . '</td>';
						break;
					
					case 'id_personne':
							$requete2 = "SELECT p.id_personne, p.email FROM commande c, personne p WHERE c.id_personne = p.id_personne and c.id_liste_commande = " . $id_liste_commande;
							$resultat2 =  $mysqli->query($requete2);
							if ($resultat2 -> num_rows != 0) {
								while($ligne2 = $resultat2->fetch_assoc()){
									echo '<td>' . $ligne2['id_personne'] . ' - ' . $ligne2['email'] . '</td>';
								};
							}
							else 
							{
								echo '<td> personne supprimé </td>';
							}

						break;
						
					case 'date_commande':
							// Traitement des dates pour transformer au format DD/MM/YYYY HH:MM
							$date_commande = date("d/m/Y H:i:s", strtotime($valeur)); 
							echo '<td>' . $date_commande . '</td>';
							break;
						
					default:
						echo '<td>' . $valeur . '</td>';
						break;
				}
			}		
			
			// actions : affichage, modification, suppression	
			echo '<td><a href="?action=affichage&id_liste_commande=' . $ligne['id_liste_commande'] . '">';
			echo '<span class="glyphicon glyphicon-zoom-out"></span>&nbsp;</a>';
			//echo '<a href="?action=modification&id_liste_commande=' . $ligne['id_liste_commande'] . '">';
			//echo '<span class="glyphicon glyphicon-edit"></span>&nbsp;</a>';
			echo '<a href="?action=suppression&id_liste_commande=' . $ligne['id_liste_commande'] . '&id_article=' . $ligne['id_article'] . '">';
			echo '<span class="glyphicon glyphicon-trash"></span></a></td>';
		
			echo '</tr>';
			}
			echo "</table>";
			?>	
			</div>
		</div>
	</div>

<?php								
}

?>

</div> <!-- /#page-wrapper -->


<?php
require_once("../inc/bas.back.inc.php");
?>

