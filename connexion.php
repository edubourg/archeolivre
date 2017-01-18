<?php
//ob_start();
require_once("inc/init.inc.php");
$title="Connexion";
require_once("inc/haut.front.inc.php");

// Si connecté : redirection vers profil
	if(userConnecte()){
		header('location:profil.php');	
}

//--- DECONNEXION
if(isset($_GET['action']) && $_GET['action'] == "deconnexion" ){
	unset($_SESSION['membre']);
	session_destroy();
	header('location:index.php');	
}

//--- CONNEXION
if($_POST){

	// Tests de vérification
	// Pour parer aux failles CRLF
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
 
	if (empty($msg)) {
		/*if(!empty($_POST['pseudo'])){
			$_POST['pseudo'] = htmlentities(addslashes($_POST['pseudo']));
		}*/

		// Je vérifie si le pseudo existe bien dans la Base de données.
		$resultat = req("SELECT * FROM personne WHERE pseudo = '$_POST[pseudo]'");
	
		if($resultat -> num_rows != 0){ 
			$membre = $resultat -> fetch_assoc(); // Fetch_assoc() me permet de récupérer les infos du membre et donc son MDP
			//debug($membre);
			
			if($membre['mdp'] == $_POST['mdp']){ // Si le MDP en BDD est équivalent à celui transmis dans le formulaire
			
				// Je crée dans $_SESSION un ARRAY (tableau multidimensionnel) avec toutes les infos de l'utilisateur sauf le MDP (sécurité) et donc l'utilisateur est connecté !! 
				foreach($membre as $indice => $valeur){
					if($indice != 'mdp'){
						$_SESSION['membre'][$indice] = $valeur; 
					}
				}
				//debug($_SESSION);
				header('location:profil.php');
			}
			else {
				$msg .= '<p style="color: white; background-color: red; padding: 10px;">Erreur de Mot de passe ! </p>';
			}
		}
		else{
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Erreur de pseudo ! </p>';
		}
	
		// Si connecté : redirection vers profil
		if(userConnecte()){
			header('location:profil.php');	
		}
	}
	
}

echo $msg;
?>

<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel panel-smart">
			<div class="panel-heading">
				<h3 class="panel-title">Connexion</h3>
			</div>
		</div>
	</div>
</div>	
			
<div class="row">
	<div class="col-md-6 col-sm-6">
		<div class="panel panel-smart">
			<div class="panel-heading">
				<h3 class="panel-title">Pas encore inscrit ?</h3>
			</div>

			<div class="panel-body">
			
				<p class="text-justify">En créant un compte Archéolivre, vous pourrez acheter directement via le site.</p>
                <p class="text-justify">Vous pourrez également gérer vos coordonnées, vérifier la disponibilité des produits!</p>
                <p class="text-center"><a class="btn btn-default" href="inscription.php" role="button">Inscrivez-vous</a></p>			

			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-6">
		<div class="panel panel-smart">
			<div class="panel-heading">
					<h3 class="panel-title">Déjà membre ?</h3>
			</div>
 
			<div class="panel-body">

				<form action="" method="post">

					<div class="form-group">
						<label>Pseudo</label><br/>
						<input pattern="[a-zA-Z0-9-_.]{5,20}" title="Voici les formats autorisés : De A à Z et de 0 à 9" type="text" required="required" name="pseudo" class="form-control" value="<?php if(isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" />
					</div>

					<div class="form-group">
						<label>Mot de passe</label>
						<input type="password" name="mdp" class="form-control"  value="<?php if(isset($_POST['mdp'])){echo $_POST['mdp'];} ?>"/>
					</div>

					<div class="form-group">
						<input type="submit" value="Connexion"/>
					</div>
	
				</form>

			</div>
		</div>
	</div>
</div>

<?php
// Formulaire de connexion
//require_once("formulaires.php");
require_once('inc/bas.front.inc.php');
//ob_end_flush();
?>