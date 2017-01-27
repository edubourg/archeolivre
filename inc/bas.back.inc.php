
    </div>
    <!-- /.container -->

	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	
 	<!-- Javascript pour fenêtres date -->
	<script type="text/javascript" src="<?php echo RACINE_SITE;?>js/datepickr.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
	<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

	<!-- Lightbox -->
	<script type="text/javascript">
		$(document).ready(function() {
		var $lightbox = $('#lightbox');

		$('[data-target="#myModal"]').on('mouseover', function(event) {
		var $img = $(this).find('img')
		$lightbox.find('.modal-dialog').css({'width': document.getElementById($img.attr('id')).naturalWidth});
		$lightbox.find('.modal-dialog').css({'max-width': '100%'});
		});    
    
		$('[data-target="#lightbox"]').on('click', function(event) {
			var $img = $(this).find('img'), 
				src = $img.attr('src'),
				alt = $img.attr('alt'),
				css = {
					'maxWidth': $(window).width() - 100,
					'maxHeight': $(window).height() - 100
				};
    
			$lightbox.find('.close').addClass('hidden');
			$lightbox.find('img').attr('src', src);
			$lightbox.find('img').attr('alt', alt);
			$lightbox.find('img').css(css);
		});
    
		$lightbox.on('shown.bs.modal', function (e) {
			var $img = $lightbox.find('img');
            
		$lightbox.find('.modal-dialog').css({'width': $img.width()});
		$lightbox.find('.close').removeClass('hidden');
		});
	});
	</script>
	
      <script type="text/javascript">
            $(function () {
                $('#date_publication').datetimepicker({
                    locale: 'fr'
                });
            });
        </script>
      <script type="text/javascript">
            $(function () {
                $('#date_inscription').datetimepicker({
                    locale: 'fr'
                });
            });
        </script>

				
	<!-- Pour la pagination -->
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#pagination').DataTable( {
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/French.json"
				}
			} );
		} );	
	</script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

</div>

</body>

</html>
