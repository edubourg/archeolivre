<?php
require_once("../inc/init.inc.php");

if(!userConnecteAdmin()) {  
        header("location:../connexion.php");
    }

//-------------- SUPPRESSION article EN BDD

if(isset($_GET['action']) && $_GET['action'] == "suppression" ){
	$livre = req("SELECT * FROM article WHERE id_article='$_GET[id_article]'");
	$livre_a_sup = $livre -> fetch_assoc();
	$chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE .  '/photo/' .$livre_a_sup['photo'];

	if(!empty($livre_a_sup['photo']) && file_exists($chemin_photo_a_supprimer )){
		unlink($chemin_photo_a_supprimer);
	}
	
	req("DELETE FROM article WHERE id_article='$_GET[id_article]'");
	$msg .= '<p style="color: white; background-color: green; padding: 10px;">L\'article id:' . $_GET['id_article'] .  ' a été supprimé avec succès !</div>';
	$_GET['action'] = 'affichage';  
}

//-------------- AJOUT ET MODIFICATION LIVRE EN BDD
if($_POST){
	//debug($_POST);
	$photo_bdd = ''; 

	// Pour parer aux failles XSS
	foreach ($_POST as $indice => $valeur) { 
		$_POST[$indice] = htmlspecialchars($valeur);
	}
	
	foreach($_POST as $indice => $valeur){ // Injection SQL
		$_POST[$indice] = htmlentities(addslashes($valeur));
	}

	if (strlen($_POST['titre']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir le titre de l\'article</p>';
		}

	if (strlen($_POST['description']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir une description de l\'article</p>';
		}

	if (strlen($_POST['reference']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir une référence de l\'article</p>';
		}

	if (strlen($_POST['prix']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir un prix pour l\'article</p>';
		}

	if (strlen($_POST['date_publication']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez choisir une date de publication pour l\'article</p>';
		}

	if (strlen($_POST['stock']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez choisir un stock pour l\'article</p>';
		}
		
	// récupération de la photo actuelle
	if(isset($_GET['action']) && $_GET['action'] == 'modification' ){ 
		$photo_bdd = $_POST['photo_actuelle']; // Cf remplissage formulaire
	}
	
	// Gestion de l'ajout de photo
	if(!empty($_FILES['photo']['name'])){
		//debug($_FILES);
		//$nom_photo = $_POST['titre'] . '_' . $_FILES['photo']['name'];
		//$photo_bdd = $nom_photo; 
		//$photo_dossier = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'photo/' . $nom_photo;
		$photo_dossier = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . 'photo/' . $_FILES['photo']['name'];

		// Contrôle du type de fichier
		$type_fichier = $_FILES['photo']['type'];
		switch ($type_fichier)
			{
				case 'image/png':
				case 'image/jpg':
				case 'image/jpeg': 
				case 'image/gif':
					copy($_FILES['photo']['tmp_name'], $photo_dossier);
					break;
					
				default:
					$msg .= '<p style="color: white; background-color: red; padding: 10px;">Tentative d\'upload d\'un fichier interdit : gif, png, jpeg, jpg autorisé !</p>'; 
					break;
			}
	}


	if(empty($msg))
		{
			// Transforme date
			$date_en_publication = convertDate($_POST['date_publication']);

			$tab_personne = explode(' ', $_POST['id_personne']);
			$personne = $tab_personne[0];
			
			$id_photo = $_FILES['photo']['name'];
			if (empty($id_photo)) 
			{
				$id_photo = $_POST['reference'] . '.jpg';
			}
			
			req("REPLACE INTO article (id_article, id_personne, titre, type, description, reference, photo, prix, date_publication, langue, stock) 
			VALUES ($_POST[id_article], $personne, '$_POST[titre]', '$_POST[type]', '$_POST[description]', '$_POST[reference]', 
			'$id_photo', $_POST[prix], '$date_en_publication', '$_POST[langue]', $_POST[stock])");
		
			//echo $msg;
			$_GET['action'] = "affichage";
			//header("location:gestion_livres.php?action=affichage");
		}
}

$title="Gestion articles";
require_once("../inc/haut.back.inc.php");
echo $msg;

?>

<!-- HTML -->
<div id="page-wrapper">

<p><a href="?action=ajout">Ajouter un article</a></p>
<hr/>

<?php

// Affichage de la table livre

if(isset($_GET['action']) && $_GET['action'] == "affichage" ){

$resultat = req("SELECT * FROM article");

?>

    <div class="container-fluid">

		<div class="col-lg-12 col-xs-12 col-sm-12">
			<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped" 
			id="pagination" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
				<?php while($colonne = $resultat -> fetch_field()){
						if ($colonne->name != 'description') { // n'affiche pas la colonne description
								echo '<th>' . $colonne -> name . '</th>';	
						}
					
					} ?>
				<th>actions</th>
				</tr>
			</thead>
		
			<?php
			while($ligne = $resultat -> fetch_assoc()){
			echo '<tr>';
			foreach($ligne as $indice => $valeur){
				switch ($indice) {
					
					case 'photo':
	/*					echo '<td>';
						echo '<div class="col-lg-12 col-xs-12 col-sm-12">';
						echo '<a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox">'; 
						echo '<img height="80px" src="' . RACINE_SITE . 'photo/' . $valeur . '" alt="..." />';
						echo '</a></div></td>';
						break;*/
					echo '<td>';
				    echo '<div class="col-lg-12 col-xs-12 col-sm-12">';
					echo '<a href="#" class="thumbnail" data-toggle="modal" data-target="#lightbox">'; 
					echo '<img height="80px" src="' . RACINE_SITE . 'photo/' . $valeur . '" alt="..." />';
					echo '</a></div></td>';
						break;
					
					case 'date_publication':
						$date_reformate = date("d/m/Y H:i:s", strtotime($valeur));
						$date_only = explode(" ", $date_reformate);
						echo '<td>' . $date_only[0] . '</td>';
						break;
						
					case 'description':
						break;
						
					default:
						echo '<td>' . $valeur . '</td>';
						break;
				}
			}

			// actions : affichage, modification, suppression	
			echo '<td><a href="?action=affichage&id_article=' . $ligne['id_article'] . '">';
			echo '<span class="glyphicon glyphicon-zoom-out"></span>&nbsp;</a>';
			echo '<a href="?action=modification&id_article=' . $ligne['id_article'] . '">';
			echo '<span class="glyphicon glyphicon-edit"></span>&nbsp;</a>';
			echo '<a href="?action=suppression&id_article=' . $ligne['id_article'] . '">';
			echo '<span class="glyphicon glyphicon-trash"></span></a></td>';
			
			echo '</tr>';
			}
			echo "</table>";
			?>	
			</div>
		</div>
	</div>

	<!-- Fermeture LightBox -->
	<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
			<div class="modal-content">
				<div class="modal-body">
					<img src="" alt="" />
				</div>
			</div>
		</div>
	</div>
	
<?php								
}

// Affichage de la table 
if(isset($_GET['action']) && ($_GET['action'] == "ajout" || $_GET['action'] == "modification")){
	
	if(isset($_GET['id_article'])){
		$resultat = req("SELECT * FROM article WHERE id_article='$_GET[id_article]'");
		$livre_actuelle = $resultat -> fetch_assoc();
		//debug($livre_actuelle);
		echo '<h1>Correction d\'un article</h1>';
	}
	else{
		echo '<h1>Ajout d\'un livre</h1>';
	}

?>

	<!---------------- FORMULAIRE HTML ---------------------->
 

            <div class="container-fluid">

                <div class="row">
                    <form role="form" action="" method="post" enctype="multipart/form-data">
						<div class="col-lg-6">

							<input  type="hidden" name="id_article" class="form-control" value="<?php if(isset($livre_actuelle)){echo $livre_actuelle['id_article'];} else { echo '0'; }?>" />

							
							<div class="form-group">
								<label>Personne</label>
								<select class="form-control" name="id_personne">
								<?php 
								$resultat = req("SELECT id_personne, nom, prenom FROM personne 
											WHERE statut != 2
											ORDER BY 1");

								while($ligne = $resultat->fetch_assoc()){

									if ($livre_actuelle['id_personne'] == $ligne['id_personne']) {
										echo '<option selected>'. $ligne['id_personne'] . ' - ' . $ligne['nom'] . ' - ' .$ligne['prenom'] . ' </option>'; 
										}
									 else {
										 echo '<option>'. $ligne['id_personne'] .' - ' . $ligne['nom'] . ' - ' . $ligne['prenom'] .' </option>';
									 }
								}
								?>
								</select>
							</div>
							
							<div class="form-group">
								<label for="titre">Titre</label>
								<input type="text" class="form-control" name="titre" placeholder="Titre de l'article" value="<?php if(isset($livre_actuelle)){echo $livre_actuelle['titre'];}?>">
							</div>
								
 							<div class="form-group">
								<label>Description</label>
								<textarea name="description" class="form-control" rows="3" placeholder="Description de l'article"><?php if(isset($livre_actuelle)){echo $livre_actuelle['description'];}?></textarea>
							</div>

							<div class="form-group">
								<label>Catégorie</label>
								<select class="form-control" name="type">
										<option value="livre" <?php if(isset($livre_actuelle) && $livre_actuelle['type'] == 'livre'){echo 'selected';}?>  >Livre</option>
										<option value="revue" <?php if(isset($livre_actuelle) && $livre_actuelle['type'] == 'revue'){echo 'selected';}?>  >Revue</option>
										<option value="dvd" <?php if(isset($livre_actuelle) && $livre_actuelle['type'] == 'dvd'){echo 'selected';}?>>DVD</option>
								</select>
							</div>

							<div class="form-group">
								<label>Photo</label>
								<?php if(isset($livre_actuelle) && !empty($livre_actuelle['photo'])){
								echo '<img src="' . RACINE_SITE . 'photo/' . $livre_actuelle['photo'] . '" width="100px"/>';
								echo '<input type="hidden" name="photo_actuelle" value="' . $livre_actuelle['photo'] . '"/>';
								}
								?>
								<input type="file" class="form-control" name="photo">
							</div>
	
							
						</div>
						
						<div class="col-lg-6">

							<div class="form-group">
								<label for="titre">Référence</label>
								<input type="text" class="form-control" name="reference" placeholder="Référence" value="<?php if(isset($livre_actuelle)){echo $livre_actuelle['reference'];}?>">
							</div>

							<div class="form-group">
								<label for="titre">Prix</label>
								<input type="text" class="form-control" name="prix" placeholder="Prix de l'article" value="<?php if(isset($livre_actuelle)){echo $livre_actuelle['prix'];}?>">
							</div>

							<div class="form-group">
								<label>Date de publication</label>
								<div class="input-group" id="date_publication">
									<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
									<input type='text' class="form-control" name="date_publication" 
									value="<?php if(isset($livre_actuelle)){
										$date_reformate = date("d/m/Y H:i:s", strtotime($livre_actuelle['date_publication']));
										echo $date_reformate;}?>" />
								</div>
							</div>

							<div class="form-group">
								<label>Langue</label>
								<select class="form-control" name="langue">
										<option value="francais" <?php if(isset($livre_actuelle) && $livre_actuelle['langue'] == 'francais'){echo 'selected';}?>  >Français</option>
										<option value="anglais" <?php if(isset($livre_actuelle) && $livre_actuelle['langue'] == 'anglais'){echo 'selected';}?>  >Anglais</option>
										<option value="allemand" <?php if(isset($livre_actuelle) && $livre_actuelle['langue'] == 'allemand'){echo 'selected';}?>>Allemand</option>
										<option value="espagnol" <?php if(isset($livre_actuelle) && $livre_actuelle['langue'] == 'espagnol'){echo 'selected';}?>>Espagnol</option>
								</select>
							</div>

							<div class="form-group">
								<label for="titre">Stock</label>
								<input type="text" class="form-control" name="stock" placeholder="Stock" value="<?php if(isset($livre_actuelle)){echo $livre_actuelle['stock'];}?>">
							</div>
							
							<div class="form-group">
								<button type="submit" class="btn btn-default">Enregistrer</button>
								<button type="reset" class="btn btn-default">Remise à zéro</button>
                            </div>

						</div>
					</form>	


            </div>
            <!-- /.container-fluid -->

</div> <!-- /#page-wrapper -->


<?php
}
require_once("../inc/bas.back.inc.php");
?>

