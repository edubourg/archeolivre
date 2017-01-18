<?php

//********************   FONCTIONS UTILISATEUR  *************************//

// Fonction pour executer des requêtes
function req($req){
	global $mysqli;
	$resultat = $mysqli -> query($req);
	if(!$resultat){
		die("Erreur sur la reqête SQL ! <br/> Message : " . $mysqli -> error . "<br/> Requête : " . $req);	
	}
	return $resultat;
}
//---------------------------
// Fonction debug pour les print_r et var_dump
function debug($var, $mode = 1){
	echo '<div style="background-color: #' . rand(111111, 999999) . '; padding : 5px; margin: 5px; color: white" >';
	if($mode === 1){
		echo '<pre>'; print_r($var); echo '</pre>';
	}
	else{
		echo '<pre>'; var_dump($var); echo '</pre>';
	}
	echo '</div>';
}
//----------------------------
function userConnecte(){
	if(!isset($_SESSION['membre'])){ // Si la session "membre" n'est pas définie (elle ne peut être définie que si nous passé par la page connexion.)
		return FALSE; 
	}
	else{
		return TRUE; 
	}	
}
//----------------------------
function userConnecteAdmin(){
	if(userConnecte() && $_SESSION['membre']['statut'] == 0){ // Si la session membre est définie, nous vérifions si l'utilisateur est admin
		return TRUE; 
	}
	else{
		return FALSE; 
	}
}
//----------------------------
// Création du panier
function creationPanier(){
	if(!isset($_SESSION['panier'])){
		$_SESSION['panier']= array();
		$_SESSION['panier']['titre']= array();
		$_SESSION['panier']['id_article']= array();
		$_SESSION['panier']['quantite']= array();
		$_SESSION['panier']['prix']= array();
		
		//debug($_SESSION['panier']['id_article']);
	}
}


//----------------------------
// AJOUTER UN PRODUIT DANS LE PANIER
function ajoutLivrePanier($titre, $id_article, $quantite, $prix){
	creationPanier();
	//debug($_SESSION['panier']['id_article']);
	$position_produit = array_search($id_article, $_SESSION['panier']['id_article']); //ARRAY SEARCH me permet de chercher un élément dans un array et retourne la position de cet élément s'il existe ou false s'il n'existe pas dans l'array. 
	//1er argument l'élément que je recherche
	//2eme argument : l'Array dans lequel je cherche 
	
	if($position_produit !== FALSE) { //le produit existe dans le panier
		$_SESSION['panier']['quantite'][$position_produit] += $quantite;	
		//... donc j'ajoute la nouvelle commande à la quantité de ce produit dans le panier. Je récupère la position de ce roduit dans le panier grâce à $position_produit.
	}
	else{ // Le produit n'existe aps encore dans le panier, donc je le crée. Les crochets vides me permettent d'ajouter à la suite.
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['titre'][] = $titre;
		$_SESSION['panier']['prix'][] = $prix;
		$_SESSION['panier']['id_article'][] = $id_article;
	}
}

//-------------------------- 
// CALCULER LE TOTAL
function montantTotal(){
	$total = 0; // Je crée un evariable qui va être incrémentée du prix des différents produits
	for($i = 0; $i < sizeof($_SESSION['panier']['id_article']); $i++){
		// Tant qu'il y a des produits dans le panier, je multiplie la quantité et le prix et j'ajoute à la variable TOTAL. 
		$total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
	}
	return round($total, 2); // J'arrondie le total à 2 chiffres après la virgule. 
}

//--------------------
//RETIRER UN PRODUIT DU PANIER
function retirerLivrePanier($id_article_a_supprimer){
	$position_produit = array_search($id_article_a_supprimer, $_SESSION['panier']['id_article']); 
	// Je vérifie dans un  premier que le produit est bien présent dans le panier. Si oui je récupère sa position. 
	
	if($position_produit !== FALSE){ // Si le produit est présent dans le panier, grâce à array_splice je supprime chaque éléments qui correspondent à ce produit. 
	// Array_splice attend 3 arg : 
	// 1 : L'array
	// 2 : L'élément à supprimer
	// 3 : Le nombre d'élément à supprimer
		array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
		array_splice($_SESSION['panier']['prix'], $position_produit, 1);
		array_splice($_SESSION['panier']['titre'], $position_produit, 1);
		array_splice($_SESSION['panier']['id_article'], $position_produit, 1);
	}
}

 // Conversion de date anglais -> français
function convertDate($date)
{
	$tabDate = explode('/' , $date); 
		
	//debug($tabDate);
	$timeDate = explode(' ', $tabDate[2]);
	//debug($timeDate);
	
	$enDate  = $timeDate[0].'-'.$tabDate[1].'-'.$tabDate[0] . ' ' . $timeDate[1];
     return $enDate;
}

 // Conversion de date français -> anglais JJ/MM/AAAA HH:MM devient AAAA-MM-JJ HH:MM
function convertDateEn($date)
{
     $tabDate = explode('/' , $date);
	//debug($tabDate);
	$timeDate = explode(' ', $tabDate[2]);
	//debug($timeDate);
	
	$enDate  = $timeDate[0].'-'.$tabDate[1].'-'.$tabDate[0] . ' ' . $timeDate[1];

    // $enDate  = $tabDate[2].'-'.$tabDate[1].'-'.$tabDate[0];
     return $enDate;
}

// Calcul de la note
function calcul_note($indice_salle) {

	$resultat = req("SELECT s.titre, round(avg(a.note), 2) as note_moyenne FROM salle s LEFT JOIN avis a 
						ON s.id_salle = a.id_salle AND s.id_salle = " . $indice_salle);
					
	$ligne = $resultat->fetch_assoc();
		
	//Notation
	if (isset($ligne['note_moyenne'])) {
				
	$note = intval($ligne['note_moyenne']);
	$note_avis = 5;
					
	if ($note < 16) { $note_avis--; }
	if ($note < 12) { $note_avis--; }
	if ($note < 8) { $note_avis--; }
	if ($note < 4) { $note_avis--; }
				
	}
	else
	{
		$note_avis = 0;
	}

	return $note_avis;
	
}
	
// Affichage requetes statistiques
function affichage_requete ($resultat, $titre)
{
	?>

		<h2><?php echo $titre ?></h2>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
				<thead>
				<tr>
					<?php while($colonne = $resultat -> fetch_field()){
						//if ($colonne->name == 'id_salle') {
							echo '<th>' . $colonne -> name . '</th>';	
						//} 
					} ?>
				</tr>
				</thead>

				<?php $i = 1;
				while($ligne = $resultat -> fetch_assoc()){
					echo '<tr>';
					foreach($ligne as $indice => $valeur){
						switch ($indice) {
							
							case 'Salle':
								echo '<td>' . $i . ' - ' . $indice . ' ' . $valeur . '</td>';
								$i++;
								break;
							
							case 'Moyenne':
								echo '<td>' . round($valeur, 2) . '</td>';
								break;
								
							default: 
								echo '<td>' . $valeur . '</td>';
								break;

						}
					}		
			echo '</tr>';
			}
			echo "</table>";
		?>
				
			</div>
<?php
	
}

// Affichage résultats sélection du mois
function affichage_selection_mois ($resultat, $rubrique)
{

		if($resultat->num_rows != 0){

			echo '<div class="offers">'; // 1
			echo '<div class="container">'; // 2
			echo '<h3><span class="glyphicon glyphicon-user"></span>' . $rubrique . '</h3>'; 
			echo '<div class="offer-grids">'; // 3
		
			while ($ligne = $resultat -> fetch_assoc()) {
		
			//Traitement des dates
			$date_publication_reformat = date("d/m/Y", strtotime($ligne['date_publication'])); 

			echo '<div class="col-md-4 grid-left">'; //4 
			echo '<div class="offer-grid1">'; // 5

			echo '<div class="ofr-pic">'; // 6
			echo '<div class="image-box"><a href="article.php?id_article=' . $ligne['id_article'] . '">
			<img src="'. RACINE_SITE . '/photo/' . $ligne['photo'] . '" alt=""></a>';
			echo '</div></div>'; // 6

			echo '<div class="ofr-pic-info">'; // 6
            echo '<p>' . '<a href="article.php?id_article=' . $ligne['id_article'] .'">' . $ligne['reference'] . ' - ' . $ligne['prenom'] . ' ' . $ligne['nom'] . '</a></p>';
            echo '<p>' . '<a href="article.php?id_article=' . $ligne['id_article'] .'">' . $ligne['titre'] . '</a></p>';
            echo '<p>' . '<a href="article.php?id_article=' . $ligne['id_article'] .'">' . $date_publication_reformat . '</a></p>';
            echo '<p>' . '<a href="article.php?id_article=' . $ligne['id_article'] .'">' . $ligne['langue'] . '</a></p>';
			echo	'</div>';  // 6
			
			echo '<div class="clearfix"></div>';

			// Récupération info : est ce que l'article est en stock ou pas ?	
			echo '<div class="ofr-pic-bas">'; // 6

			// Produit est-il en stock ? & affichage puce
			if($ligne['stock'] != 0)
				{ echo '<div class="puce-stock"><span title="Disponible en stock">•</span></div>'; }
			else
				{ echo '<div class="puce-indispo"><span title="Indisponible en stock">•</span></div>'; }
		
			echo '<div class="prix-livre">' . $ligne['prix'] . ' € </div>';

			if ($ligne['stock'] > 0)
			{
				$contenu = '';
				//$contenu .= "<i>Nombre d'produit(s) disponible : $stock </i><br /><br />";
				//Puisque j'offre la possibilité de choisir le nombre de produit à mettre au panier, je dois faire un formulaire. 
				// L'action du formulaire est de me renvoyer vers la page Panier.php
				$contenu .= '<form method="post" action="panier.php">';
				// Je passe l'ID du produit dans un champs HIDDEN, car c'est l'élément essentiel pour ajouter le bon produit dans le panier. 
				$contenu .= "<input type='hidden' name='id_article' value='$ligne[id_article]' />";
				$contenu .= '<label class="quantite_label">Quantité : </label>';
				$contenu .= '<select class="quantite_value" name="quantite">';
				// Pour offrir la possibilité au client d'acheter plusieurs produits, je fait un select, avec boucle qui tourne autant d fois que j'ai de produit en stock, mais jusqu'à 5 maximum.
				for($i = 1; $i <= $ligne['stock'] && $i <= 5; $i++)
					{
					$contenu .= "<option>$i</option>";
					}
				$contenu .= '</select>';
				//$panier = '<span class="glyphicon glyphicon-shopping-cart"></span>';
				$contenu .= '<input type="submit" name="ajout_panier" value="Panier" />';
				//$contenu .= '<button type="submit" value="commander"></button></div>';
				$contenu .= '</form>';
				
				echo $contenu;
			}
			echo '<div class="clearfix"></div>';
			echo '</div></div></div>'; // 4

			}
			
		echo '<div class="clearfix"></div>';
		echo '</div></div></div>';
		}
	

}

// Affichage résultats sélection du mois
function affichage_details ($resultat)
{

		$ligne = $resultat -> fetch_assoc(); // un seul résultat

		echo '<div class="offers">'; // 1
		echo '<div class="container">'; // 2
		echo '<h3><span class="glyphicon glyphicon-triangle-right"></span> Description détaillée de l\'article</h3>'; 
		echo '<div class="offer-grids">'; // 3

		// Traitement des dates
		$date_publication_reformat = date("d/m/Y", strtotime($ligne['date_publication'])); 
		
		// Affichage détaillé
		echo '<br /><br />';
		echo '<div class="col-md-4" style="background-color: white;">'; //4 
		echo '<div class="offer-grid1">'; // 5

		// L'image
		echo '<div class="ofr-pic">'; // 6
		echo '<a href="article.php?id_article=' . $ligne['id_article'] . '">
		<img src="'. RACINE_SITE . '/photo/' . $ligne['photo'] . '" alt=""></a>';
		echo '</div>'; // 6

		// La ligne des prix
		echo '<div class="ofr-pic"><br />'; // 6

		if($ligne['stock'] > 0)
		{ echo '<p class="article-dispo"><span title="Disponible en stock">•</span>'; }
		else
		{ echo '<p class="article-indispo"><span title="Indisponible en stock">•</span>'; }
        echo ' ' . $ligne['prix'] . ' € </p>';
		echo '</div>'; // 6

		// Le choix des quantités
		echo '<div class="ofr-pic"><br />'; // 6
		if ($ligne['stock'] > 0)
			{
				$contenu = '';
				//$contenu .= "<i>Nombre d'produit(s) disponible : $stock </i><br /><br />";
				//Puisque j'offre la possibilité de choisir le nombre de produit à mettre au panier, je dois faire un formulaire. 
				// L'action du formulaire est de me renvoyer vers la page Panier.php
				$contenu .= '<form method="post" action="panier.php">';
				// Je passe l'ID du produit dans un champs HIDDEN, car c'est l'élément essentiel pour ajouter le bon produit dans le panier. 
				$contenu .= "<input type='hidden' name='id_article' value='$ligne[id_article]' />";
				$contenu .= '<label class="quantite_label">Quantité : </label>';
				$contenu .= '<select class="quantite_value" name="quantite">';
				// Pour offrir la possibilité au client d'acheter plusieurs produits, je fait un select, avec boucle qui tourne autant d fois que j'ai de produit en stock, mais jusqu'à 5 maximum.
				for($i = 1; $i <= $ligne['stock'] && $i <= 5; $i++)
					{
					$contenu .= "<option>$i</option>";
					}
				$contenu .= '</select>';
				//$panier = '<span class="glyphicon glyphicon-shopping-cart"></span>';
				$contenu .= '<input type="submit" name="ajout_panier" value="Ajouter au panier" />';
				//$contenu .= '<button type="submit" value="commander"></button></div>';
				$contenu .= '</form>';
				
				echo $contenu;
			}
		echo '</div>'; // 6
		
		echo '</div></div>'; // 4 & 5

		// Le texte à droite
		echo '<div class="col-md-8" style="background-color: white; text-align:justify;">'; //4 
		echo '<div class="offer-grid1">'; // 5
		echo '<div class="ofr-pic-info">'; // 6
        echo '<p><strong>Titre : </strong>' . $ligne['titre'] . '</p>';
        echo '<p><strong>Référence : </strong>' . $ligne['reference'] . ' - ' . $ligne['prenom'] . ' ' . $ligne['nom'] . '</p>';
        echo '<p><strong>Stock : </strong>' . $ligne['stock'] . '</p>';
		echo '<hr>';
        echo '<p>' . $ligne['description'] . '</p>';
		echo '<hr>';
		echo '</div>'; // 6

		// Fermeture des div
		echo '<div class="clearfix"></div>';
		echo '</div></div>'; // 4 & 5


		echo '<hr>';
		//echo '<button type="submit" value="retour" style="float:inherit; margin-left:2%;"><a href="index.php">Retour vers le catalogue</a></button>';
		echo '<a href="index.php" class="btn btn-default col-md-3" style="margin-left:5%;">Retour vers le catalogue</a>';

		if (userConnecte()) {
			if (empty($erreur)) // ne pas proposer de noter un article qui n'existe pas
					{
						//echo '<button type="submit" value="commentaire" style="float:inherit; margin-left:2%;">';
						//echo '<a href="#depot_commentaire" rel="modal:open"';
						//echo '" href="' . RACINE_SITE . 'depot_commentaire.php?id_article="' . $ligne['id_article'] .'">Déposer un commentaire et une note</a></button>';
						echo '<a href="' . RACINE_SITE . 'depot_commentaire.php?id_article="' . $ligne['id_article'] .'" class="btn btn-default col-md-3" style="margin-left:5%;">Dépôt commentaire et note</a>';
					}
	
				}
				else 
					{
						//echo '<button type="submit" value="connexion" style="float:inherit; margin-left:2%;">';
						//echo '<a href="#connexion" rel="modal:open"';
						//echo '" href="' . RACINE_SITE . 'connexion.php" >Connexion</a></button>';
						//echo '<a href="' . RACINE_SITE . 'connexion.php" class="btn btn-default col-md-3" style="margin-left:5%;">Connexion</a>';
						echo '<a href="connexion.php" class="btn btn-default col-md-3" style="margin-left:5%;">Connexion</a>';
					
					}		
		
		echo '</div></div></div>';
		
		return $ligne['id_personne'];

}

// Affichage des avis
function affichage_avis ($resultat)
{
	// Avis sur l'article
	
	// Connectez-vous pour donner un avis sur l'article
	
	// Nombre de commentaires pour l'article
//	echo '<div class="offers">'; // 1
//	echo '<div class="container">'; // 2
//	echo '<span class="bouton" id="bouton_texte" onclick="javascript:afficher_cacher('affichage');"><h3><span class="glyphicon glyphicon-triangle-right"></span> Commentaires sur l\'article</h3></span>';
//	echo '<h3><span class="glyphicon glyphicon-triangle-right"></span> Commentaires sur l\'article</h3>'; 
	echo '<div class="offer-grids">'; // 3
	
	echo '<div class="col-md-12 grid-livre" style="background-color: #eee;">'; //4 
	echo '<div class="offer-grid1">'; // 5

	echo '<div class="ofr-pic-info">'; // 6
	if($resultat->num_rows != 0){

			echo '<h4>Il y\'a ' . $resultat-> num_rows . ' commentaire(s) sur cet article</h4>';
	
			while ($ligne = $resultat -> fetch_assoc()) {
				echo '<br /><strong>Posté par :</strong> ' . $ligne['pseudo'];
				echo ' <strong>Commentaire : </strong>' . $ligne['commentaire'];
				echo ' <strong>Note : </strong>' . $ligne['note'] . '/20 <br/>';
			}
	}
	else
	{
			echo '<h4>Il n\'y a pas encore de commentaires sur cet article</h4>';
			
	}	
	echo '</div>'; // 6
	echo '<div class="clearfix"></div>';

	echo '</div></div>'; // 5 & 4
	echo '<div class="clearfix"></div>';
	echo '</div>';
//	echo '</div></div>';
}

function  affichage_commande ($commande)
{
		// Affichage de la commande spécifique
		//echo $commande;
		
		$resultat2 = req("SELECT l.titre as Titre, l.photo, oc.quantite, oc.prix, lc.date_commande 
				FROM ouvrages_commandes oc, article l, liste_commandes lc 
				WHERE oc.id_article = l.id_article
				AND lc.id_liste_commande = oc.id_liste_commande
				AND lc.id_liste_commande = " . $commande . "
				AND lc.id_personne = " . $_SESSION['membre']['id_personne'] );

		// teste le nombre de résultats
		if ($resultat2 -> num_rows) { 	?>
			<div class="container-fluid">
				<div class="col-lg-12">
					<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
					<thead>
					<tr>
						<?php while($colonne = $resultat2 -> fetch_field()){
						echo '<th>' . $colonne -> name . '</th>';	
						} ?>
					</tr>
					</thead>
					
										
					<?php	while($ligne = $resultat2 -> fetch_assoc()){
						echo '<tr>';
						foreach($ligne as $indice => $valeur){

							switch ($indice) {
					
								case 'photo':
									echo '<td><img height="80px" src="' . RACINE_SITE . 'photo/' . $valeur . '" alt="' . $valeur . '"/></td>';
									break;

								case 'date_commande':
									$date_reformate = date("d/m/Y H:i:s", strtotime($valeur)); 
									echo '<td>' . $date_reformate . '</td>';
									break;
						
								default:
									echo '<td>' . $valeur . '</td>';
									break;
							}
						}
						echo '</tr>';
					} ?>
					
					
					
		<?php			echo '</table></div></div></div>';
		}
				
}
