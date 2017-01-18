<br />
<br />
<div class="footer">
	 <div class="container">
		 <div class="footer-grids">
			 <div class="col-md-3 about-us">
					<img src="<?php echo RACINE_SITE;?>images/logo.jpg" alt="logo" title="logo" />
			 </div>

			 <div class="col-md-3 about-us">
				 <h4>Adresse postale</h4>
				 <p>Service Universitaire Clermontois</p>
				 <p>34 avenue Carnot</p>
				 <p>63000 Clermont-Ferrand</p>
				 <a href="mailto:infos@archeolivre.fr">infos@archeolivre.fr</a>
			 </div>

			 <div class="col-md-3 ftr-grid">
				 <h4>Informations</h4>
					<ul class="nav-bottom">
						<li><a href="qui-sommes-nous.php">Qui sommes-nous ?</a></li>
						<li><a href="contact.php">Contact</a></li>
						<li><a href="mentions_legales.php">Mentions légales</a></li>
						<li><a href="questions.php">Questions relatives à la commande</a></li>
					</ul>					
			 </div>
			 
			 <div class="col-md-3">
					<!-- Liens de partage -->
					<a class="liens-partage" target="_blank" href="https://www.facebook.com/sharer.php?url=https://devnet-studio.fr/archeolivre" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800');return false;"><img src="images/facebook.png" alt="facebook" title="facebook"></a>
					<a class="liens-partage" target="_blank" href="https://twitter.com/share?url=https://devnet-studio.fr/archeolivre" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800');return false;"><img src="images/twitter.png" alt="twitter" title="twitter"></a>
					<a class="liens-partage" target="_blank" href="https://plus.google.com/share?url=https://devnet-studio.fr/archeolivre" rel="nofollow" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800');return false;"><img src="images/googleplus.png" alt="googleplus" title="googleplus"></a>
				<br /><br />
				<p>© 2016 Eric DUBOURG - Tous droits réservés (ce site est à vocation non commerciale)</p>
				</div>
			</div>
		</div>
</div>	



	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	
	<!-- Javascript pour fenêtres modales -->
	<script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
	<script src="<?php echo RACINE_SITE;?>js/jquery.modal.js" type="text/javascript" charset="utf-8"></script>

	<!-- Script -->
 	<!-- Javascript pour fenêtres date -->
	<script type="text/javascript" src="<?php echo RACINE_SITE;?>js/datepickr.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
	<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

      <script type="text/javascript">
            $(function () {
                $('#date_arrivee').datetimepicker({
                    locale: 'fr'
                });
            });
        </script>
      <script type="text/javascript">
            $(function () {
                $('#date_depart').datetimepicker({
                    locale: 'fr'
                });
            });
        </script>

</div>	
</body>

</html>
