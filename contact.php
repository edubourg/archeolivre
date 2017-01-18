<?php
require_once("inc/init.inc.php");

$title="Contact";
require_once("inc/haut.front.inc.php");
echo $msg;

if ($_POST) {
	
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
	$_POST['email'] = str_replace(array("\n","\r",PHP_EOL),'',$email); // suppression du retour chariot adresse mail - faille CRLF

	if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
			$msg .=  '<p style="color: white; background-color: red; padding: 10px;">' . $_POST['email'] . 'n\'est pas une adresse email valide.</p>';
	}	

	if (strlen($_POST['message']) == 0) {
			$msg .= '<p style="color: white; background-color: red; padding: 10px;"> Attention vous devez saisir un message</p>';
	}
	
	if(empty($msg)){ 

		extract ($_POST);

		$expediteur = $nom . ' ' . $prenom;

		// Préparation du message
		$header = "From: $expediteur" . "\r\n";
		$header .= "Reply-To: $expediteur" . "\r\n";
		$header .= "MIME-Version: 1.0 \r\n";
		$header .= "Content-type: text/html; charset=iso-8859-1 \r\n";
		$header .= "X-Mailer: PHP/" . phpversion();

		$contenu_email = "<h1>Mail envoyé par $expediteur</h1>";
		$contenu_email .= "<p>$message</p>";

		$destinataire = "ericdubourg10@gmail.com";
		
		mail($destinataire, $sujet, $contenu_email, $header);
	
		// Message de confirmation	
		$msg .=  '<p style="color: white; background-color: green; padding: 10px;">Le message a été envoyé.</p>';
		echo $msg;
	}
	
}


// version sans pop-up
?>

<div class="row">
	<div class="col-md-6 col-sm-6">
		<div class="panel panel-smart">
			<div class="panel-heading">
				<h3 class="panel-title">Formulaire de contact</h3>
			</div>

			<div class="panel-body">
				<p>Utilisez ce formulaire si vous avez des questions ou besoin d'informations complémentaires.</p>
					
				<form class="form-horizontal" action="" method="post">
				<fieldset>

				<div class="form-group">
					<label class="control-label">Nom<span class="mandatory">*</span></label>
					<input type="text" name="nom" class="form-control" value="<?php if(isset($_POST['nom'])){echo $_POST['nom'];} ?>"/>
				</div>
	
				<div class="form-group">
					<label>Prénom</label><br/>
					<input type="text" name="prenom" class="form-control"  value="<?php if(isset($_POST['prenom'])){echo $_POST['prenom'];} ?>"/>
				</div>
	
				<div class="form-group">
					<label class="control-label">Email<span class="mandatory">*</span></label><br/>
					<input type="text" name="email" class="form-control"  value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>"/>
				</div>

				<div class="form-group">
					<label class="control-label">Sujet<span class="mandatory">*</span></label><br/>
						<select name="sujet" class="form-control" required="required">
							<option selected value="Question(s) sur un achat">Question(s) sur un achat</option>
							<option value="problème de connexion" >Problème de connexion</option>
							<option value="Question générale" >Question générale</option>
						</select>
				</div>

				<div class="form-group">
					<label>Message</label>
					<textarea class="form-control" name="message" placeholder="Comment pouvons-nous vous aider ?" ></textarea>
				</div>

				<div class="form-group">
					<input type="submit" value="Envoi"/>
				</div>

				</fieldset>		
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-md-6 col-sm-6">
		<div class="panel panel-smart">
			<div class="panel-heading">
					<h3 class="panel-title">Archéolivre</h3>
			</div>
 
			<div class="panel-body">
				<address>
					<strong>Adresse:</strong><br>
					34, avenue Carnot<br>
					63000 Clermont-Ferrand<br>
					<strong>Tél:</strong> +33 123 456 789
				</address>
                <?php

        require('GoogleMapAPIv3.class.php');
				
        $gmap = new GoogleMapAPI(); 
        $gmap->setCenter('Nantes France');
        $gmap->setEnableWindowZoom(false);
		$gmap->setEnableAutomaticCenterZoom(true);
        $gmap->setDisplayDirectionFields(false);
        $gmap->setSize(450,300);
        //$gmap->setSize(1000,600);
        $gmap->setZoom(4);
        // $gmap->setLang('en');
        $gmap->setDefaultHideMarker(false);
		// Ajout d'un marqueur manuellement
		// $gmap->addMarkerByCoords(41.3,19.8,'Tirana');
         
		// Ajout d'un marqueur à partir d'une adresse
		$adresse = "34 avenue Carnot, 63000 Clermont-Ferrand";
		//echo $adresse;
				
		//$gmap->addMarkerByAddress($adresse);
				
		$coordonnees = $gmap->geocoding($adresse);
		$latitude = $coordonnees[2];
		$longitude = $coordonnees[3];
				
		//echo 'Latitude = ' . $latitude;
		//echo ' Longitude = ' . $longitude;
				
		// Il faudrait stocker en base les informations $latitude et $longitude pour ne pas dépasser le quota d'utilisation gratuite de GoogleMaps
		$gmap->addMarkerByCoords($latitude,$longitude,'Mon bureau');
		//print_r($coordonnees);
				
				
		// Génération et affichage de la carte
		$gmap->generate();
        echo $gmap->getGoogleMap();
				
                ?>

				
			</div>
		</div>
	</div>
</div>


<?php
		
require_once("inc/bas.front.inc.php");

 

 ?>
 