<div id="alert-map"  class="alert" role="alert" style="position: absolute;
  width: 200px;
  
  z-index: 15;
  top: 20%;
  left: 50%;
  text-align: center;
  border-radius: 10px;
  border: 1px solid #0a0a0a;
  background: #000;
  color:#eee;display:block;
  margin: -25px 0 0 -100px;">
	<strong>¿Dónde estas y a dónde vas? <button type="button" class="btn btn-primary btn-lg btn-block buscarRutas" style="display: none;">Buscar Rutas</button></strong>

</div>

<div id="map" style="height: 100%; width: 100%;display:block;"></div>
<script>

	// This example creates a 2-pixel-wide red polyline showing the path of William
	// Kingsford Smith's first trans-Pacific flight between Oakland, CA, and
	// Brisbane, Australia.
	var markers = [];
	var bus = [];
	var markersBloqueados = false;
	var map = null;
	var bounds = null;
	var polilineas = [];

	function initMap() {
		 map = new google.maps.Map(document.getElementById('map'), {
			zoom: 10,
			center: {lat: -12.0548814, lng: -77.0798989},
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		bounds = new google.maps.LatLngBounds();


		//map.setOptions({styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.landcover","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"all","stylers":[{"visibility":"on"},{"weight":"0.64"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"simplified"},{"lightness":"19"},{"saturation":"0"}]},{"featureType":"poi.place_of_worship","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":"0"},{"lightness":"41"},{"gamma":"1.27"}]},{"featureType":"transit.station.airport","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit.station.bus","elementType":"all","stylers":[{"visibility":"on"},{"hue":"#ff0000"}]},{"featureType":"transit.station.rail","elementType":"all","stylers":[{"visibility":"on"},{"saturation":"23"},{"lightness":"0"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d3f3f4"},{"visibility":"on"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text","stylers":[{"color":"#ffffff"},{"weight":"0.01"},{"visibility":"off"}]}]});

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};

				map.setCenter(pos);
				map.setZoom(16);
			}, function() {

			});
		}

		google.maps.event.addListener(map, 'click', function(event) {
			placeMarker(event.latLng);
		});

		function placeMarker(location) {
			if(markersBloqueados) return;

			if(markers.length == 2)
			{
				swal({
					title: 'Espera',
					text: 'Ya seleccionaste tu punto de partida y tu destino. ¿Deseas eliminarlos?',
					type: 'warning',
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "¡Elimínalos!",
					closeOnConfirm: false
				},
					function(){
						setMapOnAll(null);
						markers = [];
						$('.buscarRutas').hide();
						swal("¡Desmarcados!", "Selecciona otra vez tu punto de inicio y tu destino final", "success");
					});
			}
			else
			{
				var marker = new google.maps.Marker({
					position: location,
					draggable: true,
					map: map
				});

				bounds.extend(new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng()));
				markers[markers.length] = marker;

				if(markers.length == 2)
				{
					map.fitBounds(bounds);
					$('.buscarRutas').show();
					aBuscar();
				}
			}
		}
	}

	$(document).on("click", ".buscarRutas", function() {
		aBuscar();
	});

	function setMapOnAll(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	function aBuscar()
	{
		swal({
				title: "Confirmación",
				text: "¡A buscar líneas de transporte!",
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: false,
				showLoaderOnConfirm: true
			},
			function(){
				pos = '';
				for(var i = 0; i < markers.length; i++)
				{
					pos+=markers[i].getPosition().lat()+';'+markers[i].getPosition().lng()+'|';
				}
				$.ajax({
					url: "/routes/rest/index?_format=json&pos="+pos
				}).done(function(response) {
					swal("¡Listo!", "Tenemos un listado de rutas para ti", "success");
					bus = [];
					$('.buscarRutas').hide();
					bus = response;
					dibujar(bus);
					$("#map").hide();
					$('#alert-map').hide();
					$("#buses").show();

					setMapOnAll(null);
					markers = [];

				}).fail(function(){
					swal({
						title: 'Oops',
						text: 'No pudimos ubicar líneas de transporte para cubrir tu ruta, intenta otra vez desde distintos lugares',
						type: 'error'
					});
				});
			});
	}


	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		console.log('error al geolocalizar');
	}

	function dibujar(bus)
	{
		$("#buses").html('<nav class="navbar navbar-default"><div class="container-fluid"><div class="navbar-header"><a class="navbar-brand" href="#" style="margin: 0px;padding: 15px 10px; height: 45px;" onclick="volverInicio();"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Volver</a></div></div></nav>');
		for(i = 0; i < bus.length; ++i)
		{
			$('#plantilla-card').find(".km").html(Math.floor(bus[i].data.distance) + 'km');
			$('#plantilla-card').find(".hora").html(Math.floor(bus[i].data.distance*60/40) + "min");
			$('#plantilla-card').find(".idPoint").val(i);

			carros = bus[i].data.route;
			$('#plantilla-card').find(".carros").html(carros.length);
			$('#plantilla-card').find('.firstPoint').html(carros[0][0].name.capitalizeFirstLetter());
			if(carros.length > 1)
			{
				$('#plantilla-card').find('.lastPoint').html(carros[1][carros[1].length-1].name.capitalizeFirstLetter());
				$('#plantilla-card').find('.lastCarCar').show();
				$('#plantilla-card').find('.firstCar').html(bus[i].buses[0].name.capitalizeFirstLetter());
				$('#plantilla-card').find('.lastCar').html(bus[i].buses[1].name.capitalizeFirstLetter());
				$('#plantilla-card').find('.principalCard').attr('data-color', '#000');
				$('#plantilla-card').find('.colorCard').attr('style', 'background:#000');
			}
			else
			{
				$('#plantilla-card').find('.lastPoint').html(carros[0][carros[0].length-1].name.capitalizeFirstLetter());
				$('#plantilla-card').find('.lastCarCar').hide();
				$('#plantilla-card').find('.firstCar').html(bus[i].buses[0].name.capitalizeFirstLetter());
				$('#plantilla-card').find('.principalCard').attr('data-color', '#BA68C8');
				$('#plantilla-card').find('.colorCard').attr('style', 'background:#BA68C8');
			}




			card = $("#plantilla-card").html();

			$("#buses").append(card);
		}
	}

	var animating = false;
	var step1 = 500;
	var step2 = 500;
	var step3 = 500;
	var reqStep1 = 600;
	var reqStep2 = 800;
	var reqClosingStep1 = 500;
	var reqClosingStep2 = 500;
	var $scrollCont = $(".phone__scroll-cont");

	var flightPath = null;


	$(document).on("click", ".card:not(.active)", function() {
		if (animating) return;
		animating = true;

		var $card = $(this);
		var cardTop = $card.position().top;
		var scrollTopVal = cardTop - 30;
		$card.addClass("flip-step1 active");

		$scrollCont.animate({scrollTop: scrollTopVal}, step1);

		setTimeout(function() {
			$scrollCont.animate({scrollTop: scrollTopVal}, step2);
			$card.addClass("flip-step2");

			setTimeout(function() {
				$scrollCont.animate({scrollTop: scrollTopVal}, step3);
				$card.addClass("flip-step3");

				setTimeout(function() {
					animating = false;
				}, step3);

			}, step2*0.5);

		}, step1*0.65);
	});

	$(document).on("click", ".card:not(.req-active1) .card__header__close-btn", function() {
		if (animating) return;
		animating = true;

		var $card = $(this).parents(".card");
		$card.removeClass("flip-step3 active");

		setTimeout(function() {
			$card.removeClass("flip-step2");

			setTimeout(function() {
				$card.removeClass("flip-step1");

				setTimeout(function() {
					animating = false;
				}, step1);

			}, step2*0.65);

		}, step3/2);
	});

	String.prototype.capitalizeFirstLetter = function() {
		return this.charAt(0).toUpperCase() + this.slice(1).toLowerCase();
	};

	$(document).on("click", ".card:not(.req-active1) .card__request-btn", function(e) {
		idCard = $(this).find(".idPoint").val();

		$('#buses').hide();
		$("#map").show();

		actualBus = bus[idCard];

		var lineSymbol = {
			path: 'M 0,-2 0,1',
			strokeOpacity: 0.7,
			strokeWeight: 12,
			fillOpacity: 0.8,
			scale: 1
		};

		html = '';
		for(i = 0; i < actualBus.data.route.length; ++i)
		{
			json = [];
			html+= '<div class="stepwizard-step"><button type="button" class="btn btn-warning btn-circle"><img src="/img/bus.png" style="width:50%" /></button><p>'+actualBus.buses[i].name.capitalizeFirstLetter()+'</p></div>';
			for(j = 0; j < actualBus.data.route[i].length; ++j)
			{
				json.push({'lat':parseFloat(actualBus.data.route[i][j]['latitude']),'lng':parseFloat(actualBus.data.route[i][j]['longitude'])});
				//html+= '<li class="list-group-item">'+actualBus.data.route[i][j]['name'].capitalizeFirstLetter()+'</li>';
				name = actualBus.data.route[i][j]['name'].capitalizeFirstLetter();
				if(i == 0 && j == 0)
				{
					html+= '<div class="stepwizard-step"><button type="button" class="btn btn-success btn-circle"></button><p>'+name+'</p></div>';
				}
				else
				{
					html+= '<div class="stepwizard-step"><button type="button" class="btn btn-default btn-circle"></button><p>'+name+'</p></div>';
				}
			}

			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					var pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};

					var image = '/img/here.png';
					var marker = new google.maps.Marker({
						position: pos,
						map: map,
						icon: image
					});
					marker.setMap(map);
					markers[markers.length] = marker;
				}, function() {

				});
			}

			color = '#0000ff';
			if(i == 0)
			{
				color = '#000';
			}


			flightPath = new google.maps.Polyline({
				path: json,
				geodesic: true,
				strokeColor: color,
				strokeOpacity: 0.6,
				strokeWeight: 12,
				icons: [
					{
						icon: lineSymbol,
						offset: '0',
						repeat: '80%'
					}
				]
			});
			polilineas.push(flightPath);

			window.setTimeout(function(){
				googleLatLng = new google.maps.LatLng(actualBus.data.route[0][0]['latitude'], actualBus.data.route[0][0]['longitude']);

				map.setCenter(googleLatLng);
				map.setZoom(15);
			}, 5000);

			animateCircle(flightPath);

			flightPath.setMap(map);
		}

		markersBloqueados = true;

		$('#lista-paraderos').html(html);
		$('#paraderos').show();
	});

	function animateCircle(line) {
		var count = 0;
		window.setInterval(function() {
			count = (count + 1) % 160;

			var icons = line.get('icons');
			icons[0].offset = (count / 2) + '%';
			line.set('icons', icons);
		}, 10);
	}

	$(document).on("click",
		".card.req-active1 .card__header__close-btn, .card.req-active1 .card__request-btn",
		function() {
			if (animating) return;
			animating = true;

			var $card = $(this).parents(".card");

			$card.addClass("req-closing1");

			setTimeout(function() {
				$card.addClass("req-closing2");

				setTimeout(function() {
					$card.addClass("no-transition hidden-hack");
					$card.css("top");
					$card.removeClass("req-closing2 req-closing1 req-active2 req-active1 map-active flip-step3 flip-step2 flip-step1 active");
					$card.css("top");
					$card.removeClass("no-transition hidden-hack");
					animating = false;
				}, reqClosingStep2);

			}, reqClosingStep1);
		});

	function volverInicio()
	{
		swal({
				title: "Confirmación",
				text: "¿Quieres volver al mapa?",
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: true,
			},
			function(){
					bus = [];
					markersBloqueados = false;
					setMapOnAll(null);
					markers = [];

					$("#map").show();
					$('#alert-map').show();
					$("#buses").hide();
			});
	}

	function volverListado()
	{
		swal({
				title: "Confirmación",
				text: "¿Quieres volver al mapa?",
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: true
			},
			function(){

				for (i=0; i<polilineas.length; i++)
				{
					polilineas[i].setMap(null); //or line[i].setVisible(false);
				}

				$("#map").hide();
				$('#alert-map').hide();
				$("#paraderos").hide();
				$("#buses").show();
			});
	}

</script>

<div id="buses" style="display: none;">


</div>


<div style="display: none" id="plantilla-card">
	<section ng-repeat="card in cards" class="card theme-purple principalCard" data-color="#BA68C8">
		<section class="card__part card__part-1">
			<div class="card__part__inner">
				<header class="card__header">
					<div class="card__header__close-btn"></div>
					<span class="card__header__id ng-binding"></span>
					<span class="card__header__price ng-binding"></span>
				</header>
				<div class="card__stats" ng-style="{'background-image': 'url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/142996/deliv-1.jpg)'}" style="background-image: url(&quot;https://s3-us-west-2.amazonaws.com/s.cdpn.io/142996/deliv-1.jpg&quot;);">
					<div class="card__stats__item card__stats__item--req">
						<p class="card__stats__type"></p>
						<span class="card__stats__value ng-binding"></span>
					</div>
					<div class="card__stats__item card__stats__item--pledge">
						<p class="card__stats__type"></p>
						<span class="card__stats__value ng-binding"></span>
					</div>
					<div class="card__stats__item card__stats__item--weight">
						<p class="card__stats__type"></p>
						<span class="card__stats__value ng-binding"></span>
					</div>
				</div>
			</div>
		</section>
		<section class="card__part card__part-2">
			<div class="card__part__side m--back">
				<div class="card__part__inner card__face">
					<div class="card__face__colored-side colorCard"></div>
					<h3 class="card__face__price ng-binding km">20km</h3>
					<div class="card__face__divider"></div>
					<div class="card__face__path"></div>
					<div class="card__face__from-to">
						<p class="ng-binding firstPoint">W 90th St, New York, NY 10025</p>
						<p class="ng-binding lastPoint">46th Ave, Woodside, NY 11101</p>
					</div>
					<div class="card__face__deliv-date ng-binding">
						Duración
						<p class="ng-binding hora">40 min</p>
					</div>
					<div class="card__face__stats card__face__stats--req">
						Buses
						<p class="ng-binding carros">2</p>
					</div>
				</div>
			</div>
			<div class="card__part__side m--front">
				<div class="card__from-to">
					<div class="card__from-to__inner">
						<div class="card__text card__text--left">
							<p class="card__text__heading">From</p>
							<p class="card__text__middle ng-binding firstPoint">W 90th St</p>
							<p class="card__text__bottom ng-binding">Lima, Perú</p>
						</div>
						<div class="card__text card__text--right">
							<p class="card__text__heading">To</p>
							<p class="card__text__middle ng-binding lastPoint">46th Ave</p>
							<p class="card__text__bottom ng-binding">Lima, Perú</p>
						</div>
					</div>
				</div>
				<div class="card__sender">
					<div class="card__from-to__inner">
						<div class="card__text card__text--left">
							<p class="card__text__middle ng-binding">&nbsp;</p>
							<p class="card__text__heading">Tomar el bus</p>
							<p class="card__text__bottom ng-binding firstCar">Lima, Perú</p>
						</div>
						<div class="card__text card__text--right lastCarCar">
							<p class="card__text__middle ng-binding">&nbsp;</p>
							<p class="card__text__heading">Luego tomar el bus</p>
							<p class="card__text__bottom ng-binding lastCar">Lima, Perú</p>
						</div>
					</div>
				</div>
				<section class="card__part card__part-3">
					<div class="card__part__side m--back"></div>
					<div class="card__part__side m--front">
						<div class="card__timings">
							<div class="card__timings__inner">
								<div class="card__text card__text--left">
									<p class="card__text__heading">Distancia</p>
									<p class="card__text__middle ng-binding hora">40km</p>
								</div>
								<div class="card__text card__text--right">
									<p class="card__text__heading">Duración promedio</p>
									<p class="card__text__middle ng-binding km">24 minutes</p>
								</div>
							</div>
						</div>
						<div class="card__timer">60 min 00 sec</div>
						<section class="card__part card__part-4">
							<div class="card__part__side m--back"></div>
							<div class="card__part__side m--front">
								<button type="button" class="card__request-btn">
									<input type="hidden" name="id" class="idPoint" />
									<span class="card__request-btn__text-1">Empezar Viaje</span>
									<span class="card__request-btn__text-2">Start</span>
								</button>
							</div>
						</section>
					</div>
				</section>
			</div>
		</section>
	</section>
</div>


<div id="paraderos" style="display:none; position:absolute; z-index:10; top:0px">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#" style="margin: 0px;padding: 15px 10px; height: 45px; width: 100px" onclick="volverListado();">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Volver
				</a>
			</div>
		</div>
	</nav>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="collapseListGroupHeading1">
			<h4 class="panel-title">
				<a href="#collapseListGroup1" class="" role="button" data-toggle="collapse" aria-expanded="true" aria-controls="collapseListGroup1">
					Paraderos <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
				</a>
			</h4>
		</div>
		<div class="panel-collapse collapse in" role="tabpanel" id="collapseListGroup1" aria-labelledby="collapseListGroupHeading1" aria-expanded="true">
			<!--<ul class="list-group" id="lista-paraderos"  style="overflow-y: auto; max-height:300px; min-width:400px;">
			</ul>-->
			<div style="overflow-y: auto; max-height:300px; min-width:300px;">
				<div class="stepwizard">
					<div class="stepwizard-row" id="lista-paraderos">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--<div style="display: none;" id="plantilla-card">
	<a href="#" class="cards">
		<input type="hidden" name="id" class="id" />
		<div class="media">
			<div class="media-left" style="text-align: center;">
				<h2 style="margin:0px;padding:0px;" class="hora">12</h2>min.
			</div>
			<div class="media-body media-middle" style="padding-right:10px">
				<h4 class="media-heading carros">0</h4>
				<span class="km">20</span> km
			</div>
			<div class="media-right media-middle">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			</div>
		</div>
	</a>
</div>--!>