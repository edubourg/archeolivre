<?php
require_once("../inc/init.inc.php");

if(!userConnecteAdmin()) {  
        header("location:../connexion.php");
    }

$title="Gestion Membres";
require_once("../inc/haut.back.inc.php");
	
//-------------- SUPPRESSION personne EN BDD

if(isset($_GET['action']) && $_GET['action'] == "suppression" ){
	$personne = req("SELECT * FROM personne WHERE id_personne='$_GET[id_personne]'");
	$personne_a_sup = $personne -> fetch_assoc();

	req("DELETE FROM personne WHERE id_personne='$_GET[id_personne]'");
	$msg .= '<p style="color: white; background-color: green; padding: 10px;">La personne id:' . $_GET['id_personne'] .  ' a été supprimée avec succès !</p>';
	$_GET['action'] = 'affichage';  
}

//-------------- AJOUT ET MODIFICATION personne EN BDD
if($_POST){
	//debug($_POST);
	
	// Pour parer aux failles XSS
	foreach ($_POST as $indice => $valeur) { 
		$_POST[$indice] = htmlspecialchars($valeur);
	}
	
	foreach($_POST as $indice => $valeur){ // Injection SQL
		$_POST[$indice] = htmlentities(addslashes($valeur));
	}

	if (strlen($_POST['pseudo']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir un pseudo</p>';
		}

	if (strlen($_POST['mdp']) == 0) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir un mot de passe</p>';
	}

	if (strlen($_POST['nom']) == 0 ) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir un nom</p>';
		}
		
	if (strlen($_POST['prenom']) == 0) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir un prénom</p>';
	}

	if (strlen($_POST['email']) == 0) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir un email</p>';
	}

	// test adresse mail valide
	$email = $_POST['email'];
	$_POST['email'] = str_replace(array("\n","\r",PHP_EOL),'',$email); // suppression du retour chariot adresse mail

	if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
			$msg .=  '<p style="color: white; background-color: red; padding: 10px;">' . $_POST['email'] . 'n\'est pas une adresse email valide.</p>';
	}	

	// validation pseudo
	$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);  
	
	if(!$verif_caractere || strlen($_POST['pseudo']) < 6 || strlen($_POST['pseudo']) > 20 ){
		$msg .= '<p style="color: white; background-color: red; padding: 10px;">Le pseudo doit contenir entre 5 et 20 caractères.</p>';
		$msg .= '<p style="color: white; background-color: red; padding: 10px;">Caractères acceptés : Lettres de A à Z et chiffres de 0 à 9.</p>';
	}
	
	// validation mdp
	$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['mdp']);  
	
	if(!$verif_caractere || strlen($_POST['mdp']) < 5  ){
		$msg .= '<p style="color: white; background-color: red; padding: 10px;">Le mpt de passe doit avoir plus de cinq caractères.</p>';
		$msg .= '<p style="color: white; background-color: red; padding: 10px;">Caractères acceptés : Lettres de A à Z et chiffres de 0 à 9.</p>';
	}

	if (strlen($_POST['date_inscription']) == 0) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir une date d\'inscription</p>';
	}

	if(empty($msg))
		{
			// Transforme date
			$date_en_enreg = convertDate($_POST['date_inscription']);
	
			req("REPLACE INTO personne (id_personne, pseudo, mdp, nom, prenom, email, civilite, statut, date_inscription) 
			VALUES ('$_POST[id_personne]', '$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]', '$_POST[civilite]', '$_POST[statut]', '$date_en_enreg')");

			$msg .= '<p style="color: white; background-color: green; padding: 10px;">La personne a été enregistrée ou modifiée !</p>';
			$_GET['action'] = 'affichage';  
			//header("location:gestion_membres.php?action=affichage");
		}	

}

echo $msg;

?>

<!-- HTML -->
<div id="page-wrapper">

<p><a href="?action=ajout">Ajouter une personne</a></p>
<hr/>

<?php

// Affichage de la table personne

if(isset($_GET['action']) && $_GET['action'] == "affichage" ){

$resultat = req("SELECT * FROM personne");

?>

    <div class="container-fluid">

		<div class="col-lg-12">
			<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped" 
			id="pagination" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
				<?php while($colonne = $resultat -> fetch_field()){
					if ($colonne-> name != 'mdp') { // non affichage du mot de passe
						echo '<th>' . $colonne -> name . '</th>';	} 
					} ?>
				<th>actions</th>
				</tr>
			</thead>
		
			<?php
			while($ligne = $resultat -> fetch_assoc()){
			echo '<tr>';
			foreach($ligne as $indice => $valeur){

				switch($indice) {
				
					case 'civilite':
						if ($valeur == 'm')
							{ echo '<td> Homme </td>'; }
						else
							{ echo '<td> Femme </td>'; }
						break;
						
					case 'date_inscription':
					// Traitement des dates pour transformer au format DD/MM/YYYY HH:MM
						$date_inscription = date("d/m/Y H:i:s", strtotime($valeur)); 
						echo '<td>' . $date_inscription . '</td>';
						break;
						
					case 'mdp': // ne rien afficher
						break;
						
					default:
						echo '<td>' . $valeur . '</td>';
						break;
				}
			}
		
			// actions : affichage, modification, suppression	
			echo '<td><a href="?action=affichage&id_personne=' . $ligne['id_personne'] . '">';
			echo '<span class="glyphicon glyphicon-zoom-out"></span>&nbsp;</a>';
			echo '<a href="?action=modification&id_personne=' . $ligne['id_personne'] . '">';
			echo '<span class="glyphicon glyphicon-edit"></span>&nbsp;</a>';
			if(!$ligne['statut'] == 0) { // On ne peut pas supprimer l'administrateur !
				echo '<a href="?action=suppression&id_personne=' . $ligne['id_personne'] . '">';
				echo '<span class="glyphicon glyphicon-trash"></span></a></td>';
			}
			
			echo '</tr>';
			}
			echo "</table>";
			?>	
			</div>
		</div>
	</div>

<?php								
}

// Affichage de la table 
if(isset($_GET['action']) && ($_GET['action'] == "ajout" || $_GET['action'] == "modification")){
	
	if(isset($_GET['id_personne'])){
		$resultat = req("SELECT * FROM personne WHERE id_personne='$_GET[id_personne]'");
		$personne_actuel = $resultat -> fetch_assoc();
		//debug($personne_actuel);
		echo '<h1>Correction d\'une personne</h1>';
	}
	else{
		echo '<h1>Ajout d\'une personne</h1>';
	}

?>

	<!---------------- FORMULAIRE HTML ---------------------->
 

            <div class="container-fluid">

                <div class="row">
                    <form role="form" action="" method="post">
						<div class="col-lg-6">

							<input  type="hidden" name="id_personne" value="<?php if(isset($personne_actuel)){echo $personne_actuel['id_personne'];} else { echo '0'; }?>" />

                            <div class="form-group">
								<label>Pseudo</label>
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
									<input type="text" class="form-control" name="pseudo" placeholder="pseudo" value="<?php if(isset($personne_actuel)){echo $personne_actuel['pseudo'];}?>">
								</div>
							</div>

                            <div class="form-group">
								<label>Mot de passe</label>
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
									<input type="password" class="form-control" name="mdp" placeholder="password" value="<?php if(isset($personne_actuel)){echo $personne_actuel['mdp'];}?>">
								</div>
							</div>

                            <div class="form-group">
								<label>Nom</label>
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></div>
									<input type="text" class="form-control" name="nom" placeholder="votre nom" value="<?php if(isset($personne_actuel)){echo $personne_actuel['nom'];}?>">
								</div>
							</div>

                            <div class="form-group">
								<label>Prénom</label>
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></div>
									<input type="text" class="form-control" name="prenom" placeholder="votre prénom" value="<?php if(isset($personne_actuel)){echo $personne_actuel['prenom'];}?>">
								</div>
							</div>
							
						</div>		
						<div class="col-lg-6">

							<div class="form-group">
								<label>Email</label>
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
									<input type="text" class="form-control" name="email" placeholder="votre email" value="<?php if(isset($personne_actuel)){echo $personne_actuel['email'];}?>"><br />
								</div>
							</div>

							<div class="form-group">
                                <label>Civilité</label>
								<select class="form-control" name="civilite" style="width:30%;">
									<option value="m" <?php if(isset($personne_actuel) && $personne_actuel['civilite'] == 'm'){echo 'selected';}?>  >Homme</option>
									<option value="f" <?php if(isset($personne_actuel) && $personne_actuel['civilite'] == 'f'){echo 'selected';}?>  >Femme</option>
								</select>                       
							</div>

							<div class="form-group">
                                <label>Statut</label>
								<select class="form-control" name="statut" style="width:30%;">
									<option value="0" <?php if(isset($personne_actuel) && $personne_actuel['statut'] == '0'){echo 'selected';}?>  >Admin</option>
									<option value="1" <?php if(isset($personne_actuel) && $personne_actuel['statut'] == '1'){echo 'selected';}?>  >Membre du Service</option>
									<option value="2" <?php if(isset($personne_actuel) && $personne_actuel['statut'] == '2'){echo 'selected';}?>  >Client</option>
								</select>                       
							</div>
								
							<div class="form-group">
                                <label>Date inscription</label>
								<div class="input-group" id="date_inscription">
									<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
								<input type='text' class="form-control" name="date_inscription" 
									value="<?php if(isset($personne_actuel)){
										$date_reformate = date("d/m/Y H:i:s", strtotime($personne_actuel['date_inscription']));
										echo $date_reformate;}?>" />
								</div>
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
require_once("../inc/datescriptjs.inc.php");
require_once("../inc/bas.back.inc.php");
?>

