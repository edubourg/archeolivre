<?php
require_once("../inc/init.inc.php");

if(!userConnecteAdmin()) {  
        header("location:../connexion.php");
    }

$title="Gestion avis";
require_once("../inc/haut.back.inc.php");
echo $msg;

?>

<!-- HTML -->
<div id="page-wrapper">

<?php
// Les requêtes

// top 5 des livres les mieux notés	
$livre_note = req("SELECT l.titre, avg(note) as Moyenne FROM article l, avis a
					WHERE l.id_article = a.id_article GROUP BY 1 ORDER BY 2 DESC LIMIT 0,5");

// top 5 des livres les plus commandées
$livre_commande = req("SELECT l.titre, CONCAT (p.prenom, ' ', p.nom) as auteur FROM article l, ouvrages_commandes oc, personne p 
					WHERE l.id_article = oc.id_article
					AND l.id_personne = p.id_personne LIMIT 0,5");

// top 5 des membres qui achètent le plus (en termes de quantité)					
$membre_achat = req("SELECT DISTINCT pseudo, CONCAT (prenom, ' ', nom) AS personne, COUNT(id_liste_commande) as Nb_commandes 
					FROM personne p, liste_commandes lc
					WHERE p.id_personne = lc.id_personne GROUP BY 1, 2 ORDER BY 3 DESC LIMIT 0,5");

// top 5 des membres qui achètent le plus (en termes de prix)
$membre_cmd = req("SELECT DISTINCT pseudo, CONCAT (prenom, ' ', nom) AS personne, sum(oc.prix) as max_prix 
					FROM personne p, liste_commandes lc, ouvrages_commandes oc
					WHERE p.id_personne = lc.id_personne AND lc.id_liste_commande = oc.id_liste_commande 
					GROUP BY 1, 2 ORDER BY 3 DESC LIMIT 0, 5;");
					
// L'affichage des requêtes

?>

    <div class="container-fluid">

	<div class="col-lg-6">
			<?php affichage_requete ($livre_note, 'Top 5 des articles les mieux notés');					
		
			affichage_requete ($livre_commande, 'Top 5 des articles les plus commandés');					
		
		?>					 
	</div>

	<div class="col-lg-6">

			<?php affichage_requete ($membre_achat, 'Top 5 des membres (en quantité)');					
			affichage_requete ($membre_cmd, 'Top 5 des membres (en prix)');	?>				

		</div>

	</div>


</div> <!-- /#page-wrapper -->

<?php
require_once("../inc/bas.back.inc.php");
?>

