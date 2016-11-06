<div id="map" style="height: 100%; width: 100%"></div>
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

		var infoWindow = new google.maps.InfoWindow({map: map});
		map.setOptions({styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.landcover","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"all","stylers":[{"visibility":"on"},{"weight":"0.64"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"simplified"},{"lightness":"19"},{"saturation":"0"}]},{"featureType":"poi.place_of_worship","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":"0"},{"lightness":"41"},{"gamma":"1.27"}]},{"featureType":"transit.station.airport","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit.station.bus","elementType":"all","stylers":[{"visibility":"on"},{"hue":"#ff0000"}]},{"featureType":"transit.station.rail","elementType":"all","stylers":[{"visibility":"on"},{"saturation":"23"},{"lightness":"0"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d3f3f4"},{"visibility":"on"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text","stylers":[{"color":"#ffffff"},{"weight":"0.01"},{"visibility":"off"}]}]});

		var options = {
			enableHighAccuracy: true,
			timeout: 5000,
			maximumAge: 0
		};

		function success(pos) {
			var crd = pos.coords;

			console.log('Your current position is:');
			console.log('Latitude : ' + crd.latitude);
			console.log('Longitude: ' + crd.longitude);
			console.log('More or less ' + crd.accuracy + ' meters.');
		};

		function error(err) {
			console.warn('ERROR(' + err.code + '): ' + err.message);
		};

		navigator.geolocation.getCurrentPosition(success, error, options);

		google.maps.event.addListener(map, 'click', function(event) {
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

	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ?
			'Error: The Geolocation service failed.' :
			'Error: Your browser doesn\'t support geolocation.');
	}


</script>