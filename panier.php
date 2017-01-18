<?php
require_once("inc/init.inc.php");

$title = 'Panier';
require_once('inc/haut.front.inc.php');

//---------- AJOUTER UN Livre AU PANIER -------------
if(isset($_POST['ajout_panier'])){ // L'ajout d'un Livre est transmis en POST par la page fiche_Livre. Je récupère un id_article et une quantité.
	//debug($_POST);
	
	// Je récupère grâce à l'id_article le prix et le titre du Livre à ajouter dans le panier. 
	$resultat = req("SELECT prix, titre FROM article WHERE id_article='$_POST[id_article]'");
	$livre = $resultat -> fetch_assoc();
	//debug($livre);
	
	// Ma fonction ajoutLivrePanier va crée un panier si nécessaire, et ajouter le Livre dans le panier (voir fonction ajoutLivrePanier dans fonction.inc.php.
	// Si un Livre existe déjà dans le panier, la fonction va simplement augmenter la quantité et non re-crée une ligne...
	ajoutLivrePanier($livre['titre'], $_POST['id_article'], $_POST['quantite'], $livre['prix']);
}
//function ajoutLivrePanier($titre, $id_article, $quantite, $prix){

//------------- VIDER LE PANIER ------------
if(isset($_GET['action']) && $_GET['action'] == 'vider' ){ // Si une action de vider le panier est transmise dans l'URL...
	unset($_SESSION['panier']); // Unset me permet de vider une partie de la super_globale SESSION, je cide donc la partie PANIER.
	// header("location:panier.php");
	echo '<script>document.location.href="panier.php";</script>';
	// la redirection en javascript ou en PHP, me permet de ne pas conserver les paramètres dans l'url => plus propre !! 
}

//-------------- RETIRER UN Livre DU PANIER ---- 
if(isset($_GET['action']) && $_GET['action'] == 'suppression'){  // Si une action de suppression est demandée dans l'url...

	
	if(isset($_GET['id_article']) && is_numeric($_GET['id_article'])){ //... je vérifie qu'il y a bien un id_article et que sa valeur est bien numérique. 
		
		// la fonction retirerLivrePanier() vérifie la présence de ce Livre dans le panier, et supprime toutes les infos le concernant. 
		retirerLivrePanier($_GET['id_article']);
		header("location:panier.php");
	}
	else{ // Sinon s'il n'y pas d'id_article dans l'URL : ERREUR, donc j'effectue une redirection. 
		header("location:panier.php");
	}
}

//---------- PAIEMENT ------------
if(isset($_POST['payer'])){
	
	// ETAPE 1 : Verification du stock ! 
	$erreur = FALSE;
	for($i = 0; $i < count($_SESSION['panier']['id_article']); $i ++){
		// Pour chaque Livre que je vais trouver dans le panier, je dois traîter les 3 cas de figure possible : 
		
		// S'il y a suffisament de stock : OK ! 
		// S'il n'y a pas assez de stock, je décrémente la quantité
		// S'il n'y a plus de stock, je retire le Livre du panier
		
		// Pour checker le stock, je dois faire une requête et vérifier le niveau
		$id_article = $_SESSION['panier']['id_article'][$i];
		$resultat = req("SELECT * FROM article WHERE id_article ='$id_article'");
		$livre = $resultat -> fetch_assoc(); 
		
		//debug($livre);
		
		// Si le stock en BDD est inférieur à celui demandé dans le panier
		if($livre['stock'] < $_SESSION['panier']['quantite'][$i]){

		
			// J'affiche des messages d'erreur
			$msg = '<p style="color: white; background-color: red; padding: 10px;">'; 
			//Attention vous devez saisir le titre du livre</p>';
			$msg .= 'Livre ' . $_SESSION['panier']['titre'][$i] . ' , stock restant : ' . $livre['stock'] . '</p>';
			$msg .= '<p style="color: white; background-color: red; padding: 10px;">'; 
			$msg .= 'Livre ' . $_SESSION['panier']['titre'][$i] . ' , quantité demandée : ' . $_SESSION['panier']['quantite'][$i] . '</p>';
				
			//Si il y a un peu de stock, je remplace dans le panier la quantité choisie par la quantité disponible. 
			if($livre['stock'] > 0){
				$msg .= '<p style="color: white; background-color: red; padding: 10px;">La quantité de l\'article ' . $_SESSION['panier']['titre'][$i] . ' est insufisante. Nous avons donc réduit la quantité du Livre dans votre panier.</p>';	
				$_SESSION['panier']['quantite'][$i] = $livre['stock'];
			}
			// S'il n'y plus du tout de stock, je retire le produit du panier. 
			else{
				$msg .= '<p style="color: white; background-color: red; padding: 10px;">'; 
				$msg .= 'L\'article ' . $_SESSION['panier']['titre'][$i] . ' est Indisponible. Nous l\'avons donc retiré de votre panier.</p>';
				retirerLivrePanier($_SESSION['panier']['id_article'][$i]);
				if($i !=0){$i --;} // ATTENTION : Le fait de retirer un Livre du panier, supprime un index dans l'ARRAY. De fait, la boucle for, risque de rater un élément, donc, je décrémente $i pour forcer la boucle for à un retour en arrière. 
			}
			// Si le stock n'était pas suffisant, je crée cette variable $erreur, qui va me permettre de ne pas continuer le processus de validation du panier. 
			// L'utilisateur peut donc modifier son panier, celui n'est pas définitivement validé. 
			$erreur = TRUE;
			echo $msg;
		}
	}

	if($erreur == FALSE){ // Cela signifie qu'il n'y a pas eu de problème au niveau du stock... Je peux donc enregistrer dans la BDD les infos sur la commande et modifier le stock du Livre. 
	
		// Insérer les détails de la commande dans la BBD (commande)
		$total = montantTotal();
		$id_personne = $_SESSION['membre']['id_personne'];
		req("INSERT INTO liste_commandes (id_personne, montant, date_commande) VALUES ('$id_personne', '$total', NOW())");
		$id_commande = $mysqli -> insert_id; // £musqli -> insert_id me retour la valeur du champs auto-increment lors de la dernière requête d'INSERT/REPLACE ou d'UPDATE
		// Donc l'id de la commande que nous venons d'enregistrer.
	
		// Enfin, pour chaque Livre dans la panier, je dois modifier le stock, et ajouter un enregistrement dans la table details_commande. je le fait donc dans une boucle...
		for($i = 0; $i < count($_SESSION['panier']['id_article']); $i ++){
			
			$id_article = $_SESSION['panier']['id_article'][$i];
			$resultat = req("SELECT * FROM article WHERE id_article = '$id_article'");
			$livre = $resultat -> fetch_assoc(); 
			// Retirer de la table Livre le nombre de Livres commandés
			$nouveau_stock = $livre['stock'] - $_SESSION['panier']['quantite'][$i];
			$id_article = $_SESSION['panier']['id_article'][$i];
			req("UPDATE article set stock = $nouveau_stock WHERE id_article='$id_article'");
			
			//--------------------
			// A TESTER !!!!!! modification du stock directement en SQL : 
			// $quantite = $_SESSION['panier']['quantite'][$i];
			// req("UPDATE Livre set stock = (stock - $quantite ) WHERE id_article='$id_article'");
			//---------------------
			
			// Insérer les détails de la commande dans la BBD (details_commande)
			$quantite = $_SESSION['panier']['quantite'][$i];
			$prix = $_SESSION['panier']['prix'][$i];
			$id_article = $_SESSION['panier']['id_article'][$i];
			
			req("INSERT INTO ouvrages_commandes (id_liste_commande, id_article, quantite, prix) VALUES ('$id_commande', '$id_article', '$quantite', '$prix')");
		}
		unset($_SESSION['panier']); // On vide le panier car celui-ci est validé et les infos enregistrées !
		
		// On peut envoyer un mail au client pour lui confirmer son achat. 
		$destinataire = $_SESSION['membre']['email'];
		$sujet = 'Confirmation de votre commande chez Archéolivre numéro '. $id_commande;

		$contenu_email = 'Merci pour votre commande chez Archéolivre ! ';
		$contenu_email .= 'Nous attendons votre réglement par chèque de : '. $total . '€ à l\'ordre d\'Archéolivre.';
		$contenu_email .= ' Votre numéro de suivi-commande est le : ' . $id_commande;
		$header = "From:admin-archeolivre@archeolivre.com";
		
		mail($destinataire, $sujet, $contenu_email, $header);
		
		//Affichage d'un message de validation !! 
		$msg_valid = '<p style="color: white; background-color: green; padding: 10px;"> Merci pour votre commande, votre numéro de suivi est le ' . $id_commande . '</p>';
		echo $msg_valid;
	}	
	//else debug($livre);
}

//-- Affichage panier -

// Panier responsive - affichage des titres
echo '<br /><br />';
echo '<div class="container-fluid">';  // 1
echo '<div class="col-lg-12">';        // 2
echo '<div class="table-responsive">'; // 3
echo '<table class="table table-bordered table-hover table-striped">'; // 4 table
echo '<thead><tr>';
echo '<th>Photo</th>';
echo '<th>Titre de l\'article</th>';
echo '<th>Référence article</th>';
echo '<th>Quantité</th>';
echo '<th>Prix unitaire</th>';
echo '<th>Action</th>';
echo '</tr></thead>';
		
// S'il n'y a pas de Livres dans le panier, alors on affiche le message "Votre panier est vide".
if(empty($_SESSION['panier']['id_article'])){
	echo '<tr><td colspan="12">Votre panier est vide !</td></tr>';
}
//Sinon on affiche toutes les infos du panier : 
else{
	$i = 0;
	while($i < count($_SESSION['panier']['id_article'])){ // on effectue une boucle qui va tourner autant de fois qu'il y a de Livres dans le panier.
		
		// pour chaque Livre trouvé dans le panier, je récupère la photo pour pouvoir l'afficher. 
		$id_article = $_SESSION['panier']['id_article'][$i];
		$photo = req("SELECT photo FROM article WHERE id_article=$id_article");
		$details_photo = $photo -> fetch_assoc();
		$nom_de_l_image = $details_photo['photo'];
		
		echo '<tr>'; // Pour chaque Livre dans le panier, on crée une nouvelle ligne dans notre tableau.
		// Pour chaque infos (photo, prix, id etc...) par Livre on crée une cellule.
		echo '<td><img src="' . RACINE_SITE . 'photo/' . $nom_de_l_image . '" height="40px"/></td>';
		echo '<td>' . $_SESSION['panier']['titre'][$i] . '</td>';
		echo '<td>' . $_SESSION['panier']['id_article'][$i] . '</td>';
		echo '<td>' . $_SESSION['panier']['quantite'][$i] . '</td>';
		echo '<td>' . $_SESSION['panier']['prix'][$i] . '</td>';
		
		// on ajoute un lien d'action pour supprimer un Livre du panier. Action = suppression et on passe obligatoire l'id_article dans l'url.
		//OnClick="return(confirm('Etes-vous certain ?'));" class="btn btn-danger"

		//echo '<td><a href="?action=suppression&id_article=' . $_SESSION['panier']['id_article'][$i] . '">';
		echo '<td><a href="?action=suppression&id_article=' . $_SESSION['panier']['id_article'][$i] . '" OnClick="return(confirm(\'Etes-vous certain ?\'));" class="btn btn-danger">';
		echo '<span class="glyphicon glyphicon glyphicon-trash"></span>&nbsp;</a></td>';
		echo '</tr>';
		$i ++;
	}

	//On affiche le total du panier grâce à la fonction montantTotal() (voir dans fonction.inc.php)
	echo '<tr><th colspan="4">TOTAL : </th><th colspan="2">' . montantTotal() . '€</th></tr>';
	
	//Si l'utilisateur est connecté, je crée le bouton pour valider la commande. Je le fait cia un formulaire pour passer les infos en post. 
	if(userConnecte()){
		echo '<form action="" method="post">';
		echo '<tr><td colspan="12"><input type="submit" name="payer" value="Valider la commande" /></td></tr>';		
		echo '</form>';
	}
	// Si l'utilisateur n'est pas connecté je ne peux pas lui permettre de valider le panier, je l'invite donc à se connecter ou à s'inscrire. 
	else{
		echo '<tr><td colspan="6"> Veuillez vous <a href="inscription.php"><u>inscrire</u></a> ou vous <a href="connexion.php"><u>connecter</u><a/> pour payer votre commande. </td></tr>';
	}
	// J'ajoute un bouto pour vider le panier. Un lien avec le paramètre action=vider.
	echo '<tr><td colspan="12"><a href="?action=vider"><u>Vider le panier</u></a></td></tr>';
}
echo '</table>'; // 4
echo '</div>';  // 3
echo '</div>'; // 2
echo '</div>'; // 1
echo '<br />';

require_once("inc/bas.front.inc.php");
11
 

 ?>
 