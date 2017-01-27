<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body onunload="GUnload()">
        <div id="global">
            <div id="map" onClick="document.getElementById('lat').value=getCurrentLat();document.getElementById('lng').value=getCurrentLng();">
                <?php

                require('GoogleMapAPIv3.class.php');
				
                $gmap = new GoogleMapAPI();
                $gmap->setCenter('Paris France');
                $gmap->setEnableWindowZoom(false);
                $gmap->setEnableAutomaticCenterZoom(true);
                $gmap->setDisplayDirectionFields(false);
                $gmap->setSize(1000,600);
                $gmap->setZoom(2);
                // $gmap->setLang('en');
                $gmap->setDefaultHideMarker(false);
                // Ajout d'un marqueur à partir d'une adresse physique
				//$gmap->addMarkerByCoords(41.3,19.8,'Tirane');
				
				$adresse = "4 avenue de la Soeur Rosalie, 75013 Paris";
				
				$coordonnees = $gmap->geocoding($adresse);
				var_dump($coordonnees);
				$gmap->addMarkerByCoords($coordonnees[2],$coordonnees[3],'Le Turbin');
				//$gmap->addMarkerByAddress($adresse);
			
                $gmap->generate();
                echo $gmap->getGoogleMap();
				
                ?>
            </div>
        </div>
    </body>
</html>