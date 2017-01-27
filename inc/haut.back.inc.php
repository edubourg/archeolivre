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

	<meta property="og:title" content="Lokisalle | Location de salle pour vos meetings ! | lokisalle.com" />
	<meta property="og:description" content="ArchéoLivre | Le site de notre service universitaire clermontois. Ventes des livres de notre service" />
	<meta property="og:url" content="http://eric.dubourg1.free.fr/archeolivre" />
	<meta property="og:image" content="image.png" />
	<meta property="og:type" content="réservation de livree" />
   	<meta property="og:site_name"   content="ArchéoLivre by Eric Dubourg Studio" />
	<!-- fin pour les réseaux sociaux -->

	<!-- pour les smartphones et IE ancien -->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- fin pour les smartphones -->

	<title>Archeolivre - <?php echo $title; ?></title>	

	<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="<?php echo RACINE_SITE;?>css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo RACINE_SITE;?>css/dataTables.bootstrap.min.css"/>
    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">


	<!--<link rel="stylesheet" href="<?php echo RACINE_SITE;?>css/jquery.modal.css" />-->
	
    <!-- Custom CSS -->
    <link href="<?php echo RACINE_SITE;?>css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo RACINE_SITE;?>css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo RACINE_SITE;?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- Bootstrap Lightbox -->
    <link rel="stylesheet" href="http://bootsnipp.com/dist/bootsnipp.min.css?ver=7d23ff901039aef6293954d33d23c066">
    <link href="//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.css" rel="stylesheet">


	<!-- CSS pour le site-->
    <link href="<?php echo RACINE_SITE;?>css/style.css" rel="stylesheet">

	
</head>



<body>

 <!--   <div id="wrapper">-->
     <!-- Page Content -->
    <div class="container">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo RACINE_SITE;?>index.php">Archéolivre Front</a>
            </div>
            <!-- Top Menu Items -->

            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
     <!--           <ul class="nav navbar-nav side-nav">-->
                <ul class="nav navbar-right top-nav">
                    <li>
                        <a href="<?php echo RACINE_SITE;?>admin/gestion_articles.php?action=affichage"><i class="admin-lokisalle"></i> Gestion des articles</a>
                    </li>
                    <li>
                        <a href="<?php echo RACINE_SITE;?>admin/gestion_membres.php?action=affichage"><i class="admin-lokisalle"></i> Gestion des membres</a>
                    </li>
                    <li>
                        <a href="<?php echo RACINE_SITE;?>admin/gestion_avis.php?action=affichage"><i class="admin-lokisalle"></i> Gestion des avis</a>
                    </li>
                    <li>
                        <a href="<?php echo RACINE_SITE;?>admin/gestion_commandes.php?action=affichage"><i class="admin-lokisalle"></i> Gestion des commandes</a>
                    </li>
                    <li>
                        <a href="<?php echo RACINE_SITE;?>admin/statistiques.php"><i class="admin-lokisalle"></i> Statistiques</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

 
