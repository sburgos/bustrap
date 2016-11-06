
<div id="map" style="height: 90%; width: 90%"></div>
<script>

	// This example creates a 2-pixel-wide red polyline showing the path of William
	// Kingsford Smith's first trans-Pacific flight between Oakland, CA, and
	// Brisbane, Australia.
	var markers = [];

	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 12,
			center: {lat: -12.0900186, lng: -77.0665256},
			mapTypeId: google.maps.MapTypeId.TERRAIN
		});

		<?php
			foreach( $json as $k => $j):
		?>
			var flightPlanCoordinates = <?= json_encode($j) ?>;
			var flightPath = new google.maps.Polyline({
				path: flightPlanCoordinates,
				geodesic: true,
				strokeColor: <?= $k ==1?'\'#FF0000\'':'\'#00ff00\'' ?>,
				strokeOpacity: 0.7,
				strokeWeight: 4
			});

			flightPath.setMap(map);
		<?php endforeach; ?>

		map.setOptions({styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]});


		google.maps.event.addListener(map, 'cliyoutck', function(event) {
			placeMarker(event.latLng);
		});

		function placeMarker(location) {
			if(markers.length == 2)
			{
				alert("Ya marcaste inicio y fin");
			}
			else
			{
				var marker = new google.maps.Marker({
					position: location,
					map: map
				});

				markers[markers.length] = marker;
			}
		}
	}


</script>

