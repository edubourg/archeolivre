<!DOCTYPE html>
<html lang="fr">

<head>

	<!-- Les balises meta-->
	<meta charset="UTF-8" />  

	<!-- pour les réseaux sociaux -->
	<!-- Twitter Card data -->
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@ericdubourg10">
	<meta name="twitter:title" content="Page Title">
	<meta name="twitter:description" content="Archéolivre | Le site de notre service universitaire clermontois">
	<meta name="twitter:creator" content="@ericdubourg10">
	<!-- Twitter Summary card images must be at least 120x120px -->
	<meta name="twitter:image" content="http://www.example.com/image.jpg">

	<meta property="og:title" content="Archéolivre | Le site de notre service universitaire clermontois" />
	<meta property="og:description" content="ArchéoLivre | Le site de notre service universitaire clermontois. Ventes des livres de notre service" />
	<meta property="og:url" content="http://devnet-studio.fr/archeolivre" />
	<meta property="og:image" content="image.png" />
	<meta property="og:type" content="réservation de livree" />
   	<meta property="og:site_name"   content="ArchéoLivre by Eric Dubourg Studio" />
	<meta name="keywords" content="Wedding Store Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
	Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
	<!-- fin pour les réseaux sociaux -->

	<!-- pour les smartphones et IE ancien -->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- fin pour les smartphones -->

	<title>ArchéoLivre Index - <?php echo $title; ?></title>	


	<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
 
    <!-- CSS -->
	<!-- Bootstrap Core CSS -->
    <link href="<?php echo RACINE_SITE;?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo RACINE_SITE;?>css/shop-homepage.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo RACINE_SITE;?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo RACINE_SITE;?>css/carousel.css" rel="stylesheet">
	
	<!-- Fenêtre modale -->
	<link rel="stylesheet" href="<?php echo RACINE_SITE;?>css/jquery.modal.css" />
	
	<!-- CSS pour le site-->
    <link href="<?php echo RACINE_SITE;?>css/archeolivre.css" rel="stylesheet">

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.js"></script>
	
	<script>
		$(function() {
			$(".toggle-info").on("click", function() {
			$(this).next(".info-panel").slideToggle(200);
		});
		$(".info-panel").on("click", function() {
		$(this).slideUp(200);
		});
	});
	</script>	
	
</head>
	
<body> 

    <div class="page-contenu">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
			<a class="navbar-brand" href="#">
				<div id="logo">
					<img src="<?php echo RACINE_SITE;?>images/logo.jpg" alt="logo" title="logo" />
				</div></a>
		  </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
					<?php
					ob_start();
					//echo '<li><a class="'; if($title=="Index Front" ){echo 'current';} echo'" href="' . RACINE_SITE . 'index.php" >Archéolivre</a></li>';
					
					if (userConnecteAdmin()) {
						echo '<li><a class="'; if($title=="Index Back"){echo 'current';} echo'" href="' . RACINE_SITE . 'admin/gestion_articles.php?action=affichage">Admin Back Office</a></li>';
					}

					
					echo '<li><a class="'; if($title=="Accueil" ){echo 'current';} echo'" href="' . RACINE_SITE . 'index.php" >Accueil</a></li>';
					echo '<li><a href="' . RACINE_SITE . 'selection.php?selection=revue">Revues</a></li>';
					echo '<li><a href="' . RACINE_SITE . 'selection.php?selection=livre">Livres</a></li>';
					echo '<li><a href="' . RACINE_SITE . 'selection.php?selection=dvd">DVD</a></li>';

					// Requête pour obtenir le prénom nom des auteurs
					$resultat = req("SELECT id_personne, prenom, nom
										FROM personne
										WHERE statut != 2
										ORDER BY nom, prenom");


					// Affichage des auteurs
					echo '<li class="dropdown">';
					//echo '<li><a href="' . RACINE_SITE . 'auteurs.php">Auteurs</a></li>';
					echo '<a href="' . RACINE_SITE . 'auteurs.php?selection=1" class="dropdown-toggle" data-toggle="dropdown" 
					role="button" aria-haspopup="true" aria-expanded="false">Auteurs<span class="caret"></span></a>';
					echo '<ul class="dropdown-menu">';
					while ($ligne = $resultat -> fetch_assoc()) {
						echo '<li><a href="' . RACINE_SITE . 'auteurs.php?selection=' . $ligne['id_personne'] . '">' . $ligne['prenom'] . ' ' . $ligne['nom'] . '</a></li>';

					}
					echo '</ul></li>';
					
					//-----------------------------
					if (userConnecte()) {
						echo '<li><a class="'; if($title=="Profil" ){echo 'current';} echo'" href="' . RACINE_SITE . 'profil.php" >Profil</a></li>';
						echo '<li><a class="'; if($title=="Profil" ){echo 'current';} echo'" href="' . RACINE_SITE . 'panier.php" >Panier</a></li>';
						echo '<li><a class="'; if($title=="Profil" ){echo 'current';} echo'" href="' . RACINE_SITE . 'connexion.php?action=deconnexion" >Déconnexion</a></li>';
					}
					else {
						//echo '<li><a href="#inscription" rel="modal:open" class="'; if($title=="Inscription" ){echo 'current';} echo'" href="' . RACINE_SITE . 'inscription.php" >Inscription</a></li>';
						echo '<li><a class="'; if($title=="Inscription" ){echo 'current';} echo'" href="' . RACINE_SITE . 'inscription.php" >Inscription</a></li>';
						echo '<li><a class="'; if($title=="Connexion" ){echo 'current';} echo'" href="' . RACINE_SITE . 'connexion.php" >Connexion</a></li>';
					}
					
					//echo '<li><a href="#contact" rel="modal:open" class="'; if($title=="Contact" ){echo 'current';} echo'" href="' . RACINE_SITE . 'contact.php" >Contact</a></li>';
					echo '<li><a class="'; if($title=="Contact" ){echo 'current';} echo'" href="' . RACINE_SITE . 'contact.php" >Contact</a></li>';
					
					ob_start();

					?>
			  
             <!-- <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>-->
            </ul>
<!--            <ul class="nav navbar-nav navbar-right">
			 <div class="cart box_1">
				 <a href="checkout.html">
					<div class="total">
					<span class="simpleCart_total"></span> (<span id="simpleCart_quantity" class="simpleCart_quantity"></span>)</div>
					<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
				</a>
				<p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>
			 	<div class="clearfix"> </div>
			 </div>
 


				<li class="active"><a href="./">Default <span class="sr-only">(current)</span></a></li>
              <li><a href="../navbar-static-top/">Static top</a></li>
              <li><a href="../navbar-fixed-top/">Fixed top</a></li>
            </ul>-->
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
	  
    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="images/bnr.jpg" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Notre motivation : la recherche</h1>
              <p>Notre patrimoine commun, les anciennes civilisations</p>
<!--              <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>-->
            </div>
          </div>
        </div>
        <div class="item">
          <img class="second-slide" src="images/bnr2.jpg" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Tout près de nos locaux</h1>
              <p>Un décor agréable pour un travail efficace</p>
 <!--             <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>-->
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="images/bnr3.jpg" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Notre crédo : la découverte</h1>
              <p>L'ouverture sur le passé à la découverte des comportements humains</p>
              <!--<p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>-->
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->  

	