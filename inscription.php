<?php
ob_start();
require_once("inc/init.inc.php");
$title="Inscription";
require_once("inc/haut.front.inc.php");
echo $msg;

// Si connecté : redirection vers profil
if(userConnecte()){
	header('location:profil.php');	
}

if($_POST){
	//debug($_POST);

	// Tests de vérification
	
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

	// Teste si le pseudo est déjà connu
	$resultat = req("SELECT pseudo FROM personne WHERE pseudo = '" . $_POST['pseudo'] . "'");
	
	if($resultat->num_rows == 1){	
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention le pseudo est déjà pris</p>';
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
	
	if(empty($msg)){ 
		
		// Vérification de la disponibilité du pseudo : 
		$membre = req("SELECT * FROM personne WHERE pseudo='$_POST[pseudo]'");
		if($membre -> num_rows > 0){ 
			$msg .= '<p style="color: white; background-color: red; padding: 10px;">Pseudo indisponible ! Veuillez choisir un autre pseudo.</p>';
		}
		else{ // L'utilisateur a un pseudo unique
			foreach($_POST as $indice => $valeur){
				$_POST[$indice] = htmlentities(addslashes($valeur));
			}
			
			// Requete d'insertion de l'utilisateur dans la BDD
			req("INSERT INTO personne (pseudo, mdp, nom, prenom, 
			email, civilite,  statut, date_inscription) VALUES ('$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]', '$_POST[civilite]', '2', NOW())");
			
			// Envoi d'un message mail au nouveau membre
			extract ($_POST);

			$expediteur = "admin-archeolivre@archeolivre.com";

			// Préparation du message
			$header = "From: $expediteur" . "\r\n";
			$header .= "Reply-To: $expediteur" . "\r\n";
			$header .= "MIME-Version: 1.0 \r\n";
			$header .= "Content-type: text/html; charset=iso-8859-1 \r\n";
			$header .= "X-Mailer: PHP/" . phpversion();

			$contenu_email .= "<p>Félicitations pour votre inscription sur le service Archéolivre !</p>";
			$contenu_email .= '<p>Votre nom : ' . $nom . '</p>';
			$contenu_email .= '<p>Votre prénom : ' . $prenom . '</p>';
			$contenu_email .= '<p>Votre pseudo : ' . $pseudo . '</p>';
			$contenu_email .= '<p>Votre email : ' . $email . '</p>';
			$contenu_email .= '<p>Votre mot de passe : ' . $mdp . '</p>';
			
			$sujet = "Inscription à Archéolivre";
			$destinataire = $email;
			
			mail($destinataire, $sujet, $contenu_email, $header);
			
			//Message de félicitations
			$msg .= '<p style="color: white; background-color:green; padding: 10px;">Félicitations ' . $_POST['pseudo'] . ', vous êtes inscrit sur notre site ! <a href="connexion.php">Se connecter</a></div>';
			
			echo $msg;
			
			//Redirection
			header('location: connexion.php');	
		}
	}
	else { echo $msg; }
}

?>
<div class="row">
	<form class="form-horizontal" action="" method="post">

	<div class="col-md-6 col-sm-6">
		<div class="panel panel-smart">
			<div class="panel-heading">
				<h3 class="panel-title">Créer un nouveau compte</h3><br /><br />
			</div>


				<div class="form-group">
					<label>Pseudo<span class="mandatory">*</span></label><br/>
					<input type="text" name="pseudo" class="form-control" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" />
				</div>

				<div class="form-group">
					<label>Mot de passe<span class="mandatory">*</span></label>
					<input type="password" name="mdp" class="form-control" value="<?php if(isset($_POST['mdp'])){echo $_POST['mdp'];} ?>"/>
				</div>
			
				
		</div>
	</div>
	
	<div class="col-md-6 col-sm-6">
		<div class="panel panel-smart">
 	
				<div class="form-group">
					<label>Nom<span class="mandatory">*</span></label>
					<input type="text" name="nom" class="form-control" required="required" value="<?php if(isset($_POST['nom'])){echo $_POST['nom'];} ?>"/>
				</div>

				<div class="form-group">
					<label>Prénom<span class="mandatory">*</span></label><br/>
					<input type="text" name="prenom" class="form-control" required="required" value="<?php if(isset($_POST['prenom'])){echo $_POST['prenom'];} ?>"/>
				</div>
	
				<div class="form-group">
					<label>Email<span class="mandatory">*</span></label><br/>
					<input type="text" name="email" class="form-control" required="required" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>"  />
				</div>

				<div class="form-group">
					<select name="civilite" class="form-control">
					<option value="m" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'm' ){echo 'selected';} ?>>Homme</option>
					<option value="f" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'f' ){echo 'selected';} ?>>Femme</option>
					</select>
				</div>

				<!-- Le statut et la date d'enregistrement ne sont pas demandés à l'utilisateur--> 
	
				<div class="form-group">
					<input type="submit" value="Inscription"/>
				</div>
		</div>
	</div>
	</form>
</div>

<?php
// Formulaire de connexion avec pop-up
//require_once("formulaires.php");
require_once("inc/bas.front.inc.php");
ob_end_flush();

?>