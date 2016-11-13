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
	var bus = [{"key":344,"buses":[{"idLine":"344","name":"Multiservicios E Inversiones Virgen De Copacabana S. A.C","extraInfo":{"colores_fondo":"AZUL POLICROMADO, CELESTE, ","colores_franjas_hexa":"#CC0000,#009900,#FFF701","ultima_modificacion_por_importacion_lista_puntos":1464212257,"alias":"","colores_fondo_hexa":"#0D1659,42B4E6","route_new_code":"4511","colores_franjas":"ROJO, VERDE, AMARILLO, "}}],"data":{"distance":3.9,"realDistance":3.902443161852,"route":[[{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.12788","longitude":"-76.987731","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128563","longitude":"-76.985231","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.129574","longitude":"-76.982509","name":"AV. ALFREDO BENAVIDES CON: CA. ARTESANOS"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":509,"buses":[{"idLine":"509","name":"Empresa De Transportes Y Turismo Star Tours S.A.C.","extraInfo":{"colores_fondo":"","ultima_modificacion_por_importacion_lista_puntos":1464226428,"colores_franjas_hexa":"","alias":"","colores_fondo_hexa":"","colores_franjas":"","route_new_code":""}}],"data":{"distance":6.44,"realDistance":6.4369549979392,"route":[[{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.12788","longitude":"-76.987731","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128563","longitude":"-76.985231","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.129574","longitude":"-76.982509","name":"AV. ALFREDO BENAVIDES CON: CA. ARTESANOS"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":430,"buses":[{"idLine":"430","name":"Transportes Hogar Tours S.A.","extraInfo":{"colores_fondo":"","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464212114,"alias":"","colores_fondo_hexa":"","route_new_code":"","colores_franjas":""}}],"data":{"distance":6.44,"realDistance":6.4369549979392,"route":[[{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.12788","longitude":"-76.987731","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128563","longitude":"-76.985231","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.129574","longitude":"-76.982509","name":"AV. ALFREDO BENAVIDES CON: CA. ARTESANOS"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"431-429","buses":[{"idLine":"431","name":"Sociedad Urbana De Buses S.A.C.","extraInfo":{"colores_fondo":"","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464211713,"alias":"","colores_fondo_hexa":"","route_new_code":"","colores_franjas":""}},{"idLine":"429","name":"E.T. Serv. Nueva Jerusalen De La Rinconada S.A.","extraInfo":{"colores_fondo":"BLANCO","colores_franjas_hexa":"#0066CC,#F15C00,#CC0000","ultima_modificacion_por_importacion_lista_puntos":1464211670,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"7203","colores_franjas":"AZUL, NARANJA , ROJO"}}],"data":{"distance":8.32,"realDistance":8.3183555692631,"route":[[{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.12788","longitude":"-76.987731","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128563","longitude":"-76.985231","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.129574","longitude":"-76.982509","name":"AV. ALFREDO BENAVIDES CON: CA. ARTESANOS"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.131332","longitude":"-76.977542","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.135367","longitude":"-76.976231","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.138047","longitude":"-76.97507","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.140498","longitude":"-76.975","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.144112","longitude":"-76.97344","name":"AV. DEFENSORES LIMA CON: AV. J. ECHENIQUE"},{"latitude":"-12.148181","longitude":"-76.969078","name":"AV. SALVADOR ALLENDE CON: AV. PROLONGACION SAN JUAN"},{"latitude":"-12.146509","longitude":"-76.970625","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.145296","longitude":"-76.971736","name":"AV. DEFENSORES LIMA CON: AV. EDILBERTO RAMOS"},{"latitude":"-12.144386","longitude":"-76.972566","name":"AV. DEFENSORES LIMA CON: AV. SOLIDARIDAD"},{"latitude":"-12.14079","longitude":"-76.974881","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.138075","longitude":"-76.974985","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"}],[{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.131417","longitude":"-76.976704","name":"Avenida Benavides/SALVADOR ALLENDE"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"174-153","buses":[{"idLine":"174","name":"E.T. Unidos Chama S.A.","extraInfo":{"colores_fondo":"VERDE PETRÓLEO, ROJO, ","colores_franjas_hexa":"#4B5D23,#FCFBF9","ultima_modificacion_por_importacion_lista_puntos":1464211798,"alias":"Chama","colores_fondo_hexa":"#062F05,#CC0000","route_new_code":"7503","colores_franjas":"VERDE PACAE, BLANCO, "}},{"idLine":"153","name":"E.T.Angamos S.A.","extraInfo":{"colores_fondo":"BLANCO","ultima_modificacion_por_importacion_lista_puntos":1464226037,"colores_franjas_hexa":"#86008F,#FFF701","alias":"Angamos","colores_fondo_hexa":"#FCFBF9","colores_franjas":"MORADO , AMARILLO","route_new_code":"4701"}}],"data":{"distance":15.66,"realDistance":15.657357230537,"route":[[{"latitude":"-12.128603","longitude":"-76.984925","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.127828","longitude":"-76.9872","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128585","longitude":"-76.997547","name":"AV. ALFREDO BENAVIDES CON: ENTRE CA. MOSTO Y CA. TENAUD"},{"latitude":"-12.128719","longitude":"-77.000338","name":"AV. ALFREDO BENAVIDES CON: AV. AVIACION (OV. LOS CABITOS)"},{"latitude":"-12.128465","longitude":"-77.001516","name":"AV. TOMAS MARSANO CON: OVALOS LOS CABITOS (VIAS AUXILIARES)"},{"latitude":"-12.128448","longitude":"-77.003447","name":"AV. ALFREDO BENAVIDES CON: CA. MARCO SKENONE"},{"latitude":"-12.128279","longitude":"-77.005935","name":"AV. ALFREDO BENAVIDES CON: ENTRE CA. SOR MATE Y CALLE 04"},{"latitude":"-12.127571","longitude":"-77.00933","name":"AV. ALFREDO BENAVIDES CON: AV. RAMIREZ GASTON"},{"latitude":"-12.127133","longitude":"-77.012759","name":"AV. ALFREDO BENAVIDES CON: AV. GRAL. MONTAGNE"},{"latitude":"-12.126331","longitude":"-77.017949","name":"AV. ALFREDO BENAVIDES CON: AV. REPUBLICA DE PANAMA"},{"latitude":"-12.123479","longitude":"-77.018131","name":"AV. REPUBLICA DE PANAMA CON: AV. RICARDO PALMA"},{"latitude":"-12.120775","longitude":"-77.018184","name":"AV. REPUBLICA DE PANAMA CON: AV. ROCA Y BOLOGÑA"},{"latitude":"-12.119586","longitude":"-77.019306","name":"AV. ANDRES A. CACERES CON: CA. J. FERNANDEZ"},{"latitude":"-12.119727","longitude":"-77.021394","name":"AV. ANDRES A. CACERES CON: CA. IRRIBARREN"},{"latitude":"-12.119538","longitude":"-77.024575","name":"AV. RICARDO PALMA CON: CA. MARIANO ODICIO"},{"latitude":"-12.118923","longitude":"-77.026177","name":"AV. PASEO DE LA REPUBLICA CON: AV. RICARDO PALMA"},{"latitude":"-12.118405","longitude":"-77.028036","name":"AV. PETIT TOHUARS CON: AV. NARCISO DE LA COLINA"},{"latitude":"-12.116918","longitude":"-77.030248","name":"CA. ENRIQUE PALACIOS CON: CA. ATAHUALPA"},{"latitude":"-12.11684","longitude":"-77.033334","name":"AV. ENRIQUE PALACIOS CON: CA. GRAL. BORGOÑO"},{"latitude":"-12.116886","longitude":"-77.036729","name":"CA. ENRIQUE PALACIOS CON: AV. COMANDANTE ESPINAR"},{"latitude":"-12.117029","longitude":"-77.041637","name":"CA. ENRIQUE PALACIOS CON: AV. SANTA CRUZ"},{"latitude":"-12.115496","longitude":"-77.04341","name":"AV. LA MAR CON: CA. MANUEL TOVAR"},{"latitude":"-12.113348","longitude":"-77.045531","name":"AV. LA MAR CON: CA. IGNACIO MERINO"},{"latitude":"-12.111903","longitude":"-77.046938","name":"AV. LA MAR CON: AV. FEDERICO VILLARREAL"},{"latitude":"-12.110577","longitude":"-77.048258","name":"AV. LA MAR CON: CA. HIPOLITO UNANUE"}],[{"latitude":"-12.110577","longitude":"-77.048258","name":"AV. LA MAR CON: CA. HIPOLITO UNANUE"},{"latitude":"-12.11048","longitude":"-77.047144","name":"Espejo/General Córdova"},{"latitude":"-12.111559","longitude":"-77.042475","name":"AV. ANGAMOS OESTE CON: CA. IGNACIO MERINO"},{"latitude":"-12.11396","longitude":"-77.040045","name":"AV. ANGAMOS OESTE CON: AV. SANTA CRUZ"},{"latitude":"-12.114064","longitude":"-77.037019","name":"AV. ANGAMOS OESTE CON: AV. CMTE. ESPINAR"},{"latitude":"-12.113929","longitude":"-77.033573","name":"AV. ANGAMOS OESTE CON: CA. GRAL. BORGOÑO"},{"latitude":"-12.113771","longitude":"-77.030169","name":"AV. ANGAMOS OESTE CON: AV. AREQUIPA"},{"latitude":"-12.113636","longitude":"-77.027405","name":"AV. ANGAMOS ESTE CON: CA. SUAREZ"},{"latitude":"-12.113549","longitude":"-77.024602","name":"AV. ANGAMOS ESTE CON: AV. FRANCISCO MORENO"},{"latitude":"-12.113373","longitude":"-77.022522","name":"AV. ANGAMOS ESTE CON: JR. JOSE MANUEL IRRIBARREN"},{"latitude":"-12.113121","longitude":"-77.018728","name":"AV. ANGAMOS ESTE CON: AV. REPUBLICA DE PANAMA"},{"latitude":"-12.112973","longitude":"-77.016546","name":"AV. ANGAMOS ESTE CON: JR. SAN FELIPE"},{"latitude":"-12.112639","longitude":"-77.012422","name":"AV. ANGAMOS ESTE CON: AV. TOMAS MARSANO"},{"latitude":"-12.112477","longitude":"-77.009851","name":"AV. ANGAMOS ESTE CON: JR. MIGUEL IGLESIAS"},{"latitude":"-12.112306","longitude":"-77.007447","name":"AV. ANGAMOS ESTE CON: AV. PRINCIPAL"},{"latitude":"-12.112064","longitude":"-77.003639","name":"AV. ANGAMOS ESTE CON: CA. FUENTES"},{"latitude":"-12.111789","longitude":"-77.00052","name":"AV. ANGAMOS ESTE CON: AV. AVIACION"},{"latitude":"-12.111522","longitude":"-76.998482","name":"AV. ANGAMOS ESTE CON: PSJE. F4"},{"latitude":"-12.111359","longitude":"-76.993734","name":"AV. ANGAMOS ESTE (Via auxiliar intercambio vial) CON: CA. INTIHUATANA"},{"latitude":"-12.111131","longitude":"-76.991689","name":"AV. PRIMAVERA (Via auxiliar intercambio vial) CON: CA. VERONA"},{"latitude":"-12.110839","longitude":"-76.984921","name":"AV. PRIMAVERA CON: AV. VELASCO ASTETE"},{"latitude":"-12.110567","longitude":"-76.98401","name":"AV. PRIMAVERA CON: AV. VELASCO ASTETE"},{"latitude":"-12.11043","longitude":"-76.978661","name":"AV. PRIMAVERA CON: PANAMERICANA SUR"}]]}},{"key":"524-162","buses":[{"idLine":"524","name":"Agrup. De Trans. En Camionetas S.A.(A.T.C.R. S.A.)","extraInfo":{"colores_fondo":"ROJO , AMARILLO , MARRON , BLANCO","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464211943,"alias":"La 24","colores_fondo_hexa":"#CC0000,#FFF701,#501F00,#FCFBF9","route_new_code":"7601","colores_franjas":""}},{"idLine":"162","name":"Empresa De Servicios Multiples Fenix 2000 S.A.","extraInfo":{"colores_fondo":"DORADO","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464211739,"alias":"La Fenix","colores_fondo_hexa":"#A38A38","route_new_code":"7101","colores_franjas":""}}],"data":{"distance":17.44,"realDistance":17.443056246961,"route":[[{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.12788","longitude":"-76.987731","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128563","longitude":"-76.985231","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.129574","longitude":"-76.982509","name":"AV. ALFREDO BENAVIDES CON: CA. ARTESANOS"},{"latitude":"-12.131163","longitude":"-76.982418","name":"AV. CAMINOS DEL INCA CON: CA. MERCADERES"},{"latitude":"-12.133349","longitude":"-76.983032","name":"AV. CAMINOS DEL INCA CON: CA. LAS NAZARENAS"},{"latitude":"-12.135533","longitude":"-76.98391","name":"AV. CAMINOS DEL INCA CON: CA. BIELICH FLORES"},{"latitude":"-12.140104","longitude":"-76.984555","name":"AV. CAMINOS DEL INCA CON: JR. LOMA UMBROSA"},{"latitude":"-12.143145","longitude":"-76.985084","name":"AV. CAMINOS DEL INCA CON: CA. ANDRES TINOCO"},{"latitude":"-12.146982","longitude":"-76.985613","name":"AV. CAMINOS DEL INCA CON: JR. LOMA DE LOS PENSAMIENTOS"},{"latitude":"-12.149227","longitude":"-76.9869","name":"AV. LOS PROCERES CON: JR. JOSE MARIA CORBACHO"},{"latitude":"-12.15048","longitude":"-76.988016","name":"AV. LOS PROCERES CON: PSJ. CHOCAVENTO"},{"latitude":"-12.153088","longitude":"-76.989761","name":"AV. LOS PROCERES CON: AV. PASEO DE LA REPUBLICA"},{"latitude":"-12.155995","longitude":"-76.99029","name":"AV. LOS PROCERES CON: JR. VISTA BELLA"},{"latitude":"-12.160103","longitude":"-76.989019","name":"AV. LOS PROCERES CON: JR.ALCIDES VIGO HURTADO"},{"latitude":"-12.162971","longitude":"-76.990752","name":"AV. LOS PROCERES CON: JR. LUIS DEXTRE"},{"latitude":"-12.164955","longitude":"-76.991846","name":"Calle 2/Guardia Civil Norte"},{"latitude":"-12.162948","longitude":"-76.995936","name":"JR. VISTA ALEGRE CON: JR. LAS GAVIOTAS"},{"latitude":"-12.164611","longitude":"-76.997109","name":"Las Golondrinas"},{"latitude":"-12.166355","longitude":"-76.998154","name":"P2525"},{"latitude":"-12.168","longitude":"-76.99865","name":"Las Gaviotas/Los Asteroides"},{"latitude":"-12.168438","longitude":"-76.997539","name":"Los Asteroides/Universo"},{"latitude":"-12.170685","longitude":"-76.996778","name":"LAS PLEYADES/Los Asteroides"},{"latitude":"-12.170668","longitude":"-76.998375","name":"Universo"},{"latitude":"-12.171495","longitude":"-76.998268","name":"Andromeda/Universo"},{"latitude":"-12.174412","longitude":"-77.001676","name":"Andromeda/El Sol"},{"latitude":"-12.174888","longitude":"-77.000444","name":"El Sol/Los Sauces"},{"latitude":"-12.176442","longitude":"-76.998478","name":"El Sol/Los Faisanes"},{"latitude":"-12.178249","longitude":"-76.996267","name":"Alipio Ponce/El Sol"}],[{"latitude":"-12.178249","longitude":"-76.996267","name":"Alipio Ponce/El Sol"},{"latitude":"-12.174158","longitude":"-76.992852","name":"Alipio Ponce"},{"latitude":"-12.174741","longitude":"-76.989567","name":"Guardia Civil"},{"latitude":"-12.1734367","longitude":"-76.9878143","name":"sin_nombre"},{"latitude":"-12.172414","longitude":"-76.982913","name":"Alipio Ponce/ALIPIO PONCE VASQUEZ"},{"latitude":"-12.170322","longitude":"-76.980269","name":"AV. ALIPIO PONCHE VASQUEZ CON: CA. LOS ALAMOS"},{"latitude":"-12.168554","longitude":"-76.979176","name":"AV. VARGAS MACHUCA CON: AV. PANAMERICANA SUR"},{"latitude":"-12.166738","longitude":"-76.978196","name":"Guardia Civil/RAMON VARGAS MACHUCA"},{"latitude":"-12.1646356","longitude":"-76.9787342","name":"sin_nombre"},{"latitude":"-12.1625332","longitude":"-76.9792724","name":"sin_nombre"},{"latitude":"-12.1604312","longitude":"-76.9798125","name":"sin_nombre"},{"latitude":"-12.1583381","longitude":"-76.980375","name":"sin_nombre"},{"latitude":"-12.152679","longitude":"-76.983763","name":"AV. PANAMERICANA SUR CON: AV. LOS LIRIOS"},{"latitude":"-12.150334","longitude":"-76.983186","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL ATOCONGO"},{"latitude":"-12.145699","longitude":"-76.981837","name":"AV. PANAMERICANA SUR CON: JR. LOMA DE LAS CLIVAS"},{"latitude":"-12.143302","longitude":"-76.981632","name":"AV. PANAMERICANA SUR CON: AV. ANDRES TINOCO"},{"latitude":"-12.141012","longitude":"-76.981002","name":"AV. PANAMERICANA SUR CON: JR. LOMA UMBROSA"},{"latitude":"-12.135536","longitude":"-76.979688","name":"AV. PANAMERICANA SUR CON: AV. CERRO CHICO (MARCONA)"},{"latitude":"-12.131463","longitude":"-76.978169","name":"AV. PANAMERICANA SUR CON: AV. BENAVIDES"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"230-429","buses":[{"idLine":"230","name":"Transporte Rapido Universal S.A.C.","extraInfo":{"colores_fondo":"","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464212036,"alias":"","colores_fondo_hexa":"","route_new_code":"","colores_franjas":""}},{"idLine":"429","name":"E.T. Serv. Nueva Jerusalen De La Rinconada S.A.","extraInfo":{"colores_fondo":"BLANCO","colores_franjas_hexa":"#0066CC,#F15C00,#CC0000","ultima_modificacion_por_importacion_lista_puntos":1464211670,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"7203","colores_franjas":"AZUL, NARANJA , ROJO"}}],"data":{"distance":17.85,"realDistance":17.848698188557,"route":[[{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.12788","longitude":"-76.987731","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128563","longitude":"-76.985231","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.129574","longitude":"-76.982509","name":"AV. ALFREDO BENAVIDES CON: CA. ARTESANOS"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.131332","longitude":"-76.977542","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.135367","longitude":"-76.976231","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.138047","longitude":"-76.97507","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.140498","longitude":"-76.975","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.144112","longitude":"-76.97344","name":"AV. DEFENSORES LIMA CON: AV. J. ECHENIQUE"},{"latitude":"-12.145418","longitude":"-76.972223","name":"AV. DEFENSORES LIMA CON: JR. GABRIEL TORRES"},{"latitude":"-12.146593","longitude":"-76.971119","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.147799","longitude":"-76.970015","name":"AV. DEFENSORES LIMA CON: AV. SAN JUAN"},{"latitude":"-12.150114","longitude":"-76.967702","name":"AV. DEFENSORES LIMA CON: JR. JULIO RODRIGUEZ"},{"latitude":"-12.152574","longitude":"-76.964726","name":"AV. DEFENSORES LIMA CON: AV. GRAL. CESAR CANEVARO"},{"latitude":"-12.154029","longitude":"-76.963025","name":"AV. DEFENSORES LIMA CON: JR. ENRIQUE DEL VILLAR"},{"latitude":"-12.155742","longitude":"-76.960854","name":"AV. DEFENSORES LIMA CON: CA. JULIO C. TELLO"},{"latitude":"-12.158072","longitude":"-76.957985","name":"AV. DEFENSORES LIMA CON: JR. TUPAC AMARU"},{"latitude":"-12.159593","longitude":"-76.956241","name":"AV. DEFENSORES LIMA CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.161408","longitude":"-76.954617","name":"AV. DEFENSORES LIMA CON: JR. SIMON BOLIVAR"},{"latitude":"-12.163778","longitude":"-76.952472","name":"AV. DEFENSORES LIMA CON: AV. SAN MARTIN DE PORRES"},{"latitude":"-12.166086","longitude":"-76.950252","name":"AV. DEFENSORES LIMA CON: AV. VILLA MARIA"},{"latitude":"-12.166895","longitude":"-76.949486","name":"DEFENSORES DE LIMA/El Triunfo"},{"latitude":"-12.1656854","longitude":"-76.9476571","name":"sin_nombre"},{"latitude":"-12.164454","longitude":"-76.9458436","name":"sin_nombre"},{"latitude":"-12.1632051","longitude":"-76.9440423","name":"sin_nombre"},{"latitude":"-12.1619562","longitude":"-76.9422409","name":"sin_nombre"},{"latitude":"-12.159391","longitude":"-76.938541","name":"El Triunfo/Ricardo Palma"},{"latitude":"-12.15626","longitude":"-76.93579","name":"Alfonso Ugarte/Villa Maria"},{"latitude":"-12.15736","longitude":"-76.93745","name":"Victor Fajardo/Villa Maria"},{"latitude":"-12.158143","longitude":"-76.938574","name":"11 de Agosto/Villa Maria"},{"latitude":"-12.1593822","longitude":"-76.9403822","name":"sin_nombre"},{"latitude":"-12.1606214","longitude":"-76.9421905","name":"sin_nombre"},{"latitude":"-12.1618605","longitude":"-76.9439987","name":"sin_nombre"},{"latitude":"-12.16478","longitude":"-76.94827","name":"La Union/Villa Maria"},{"latitude":"-12.163757","longitude":"-76.952428","name":"AV. DEFENSORES LIMA CON: AV. SAN MARTIN DE PORRES"},{"latitude":"-12.161408","longitude":"-76.954552","name":"AV. DEFENSORES LIMA CON: JR. SIMON BOLIVAR"},{"latitude":"-12.159687","longitude":"-76.956085","name":"AV. DEFENSORES LIMA CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.158278","longitude":"-76.957664","name":"AV. DEFENSORES LIMA CON: CA. SANTO TORIBIO"},{"latitude":"-12.155839","longitude":"-76.960682","name":"AV. DEFENSORES LIMA CON: CA. JULIO C. TELLO"},{"latitude":"-12.154798","longitude":"-76.961999","name":"AV. DEFENSORES LIMA CON: CA. LAS VIOLETAS"},{"latitude":"-12.152564","longitude":"-76.964643","name":"AV. DEFENSORES LIMA CON: AV. GRAL. CESAR CANEVARO"},{"latitude":"-12.150258","longitude":"-76.967375","name":"AV. DEFENSORES LIMA CON: AV. CENTENARIO"},{"latitude":"-12.148181","longitude":"-76.969078","name":"AV. SALVADOR ALLENDE CON: AV. PROLONGACION SAN JUAN"},{"latitude":"-12.146509","longitude":"-76.970625","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.145296","longitude":"-76.971736","name":"AV. DEFENSORES LIMA CON: AV. EDILBERTO RAMOS"},{"latitude":"-12.144386","longitude":"-76.972566","name":"AV. DEFENSORES LIMA CON: AV. SOLIDARIDAD"},{"latitude":"-12.14079","longitude":"-76.974881","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.138075","longitude":"-76.974985","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"}],[{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.131417","longitude":"-76.976704","name":"Avenida Benavides/SALVADOR ALLENDE"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"247-429","buses":[{"idLine":"247","name":"Translima S.A.","extraInfo":{"colores_fondo":"CELESTE, ","ultima_modificacion_por_importacion_lista_puntos":1464226296,"colores_franjas_hexa":"#108BCF,#FCFBF9","alias":"","colores_fondo_hexa":"42B4E6","colores_franjas":"AZUL ELÉCTRICO, BLANCO, ","route_new_code":"8604"}},{"idLine":"429","name":"E.T. Serv. Nueva Jerusalen De La Rinconada S.A.","extraInfo":{"colores_fondo":"BLANCO","colores_franjas_hexa":"#0066CC,#F15C00,#CC0000","ultima_modificacion_por_importacion_lista_puntos":1464211670,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"7203","colores_franjas":"AZUL, NARANJA , ROJO"}}],"data":{"distance":17.85,"realDistance":17.848698188557,"route":[[{"latitude":"-12.128228","longitude":"-76.993077","name":"AV. ALFREDO BENAVIDES CON: AV. AYACUCHO"},{"latitude":"-12.128061","longitude":"-76.991594","name":"AV. ALFREDO BENAVIDES CON: AV. HIGUERETA"},{"latitude":"-12.12788","longitude":"-76.987731","name":"AV. ALFREDO BENAVIDES CON: AV. VELASCO ASTETE"},{"latitude":"-12.128563","longitude":"-76.985231","name":"AV. ALFREDO BENAVIDES CON: CA. BATALLON CALLAO"},{"latitude":"-12.129574","longitude":"-76.982509","name":"AV. ALFREDO BENAVIDES CON: CA. ARTESANOS"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.131332","longitude":"-76.977542","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.135367","longitude":"-76.976231","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.138047","longitude":"-76.97507","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.140498","longitude":"-76.975","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.144112","longitude":"-76.97344","name":"AV. DEFENSORES LIMA CON: AV. J. ECHENIQUE"},{"latitude":"-12.145418","longitude":"-76.972223","name":"AV. DEFENSORES LIMA CON: JR. GABRIEL TORRES"},{"latitude":"-12.146593","longitude":"-76.971119","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.147799","longitude":"-76.970015","name":"AV. DEFENSORES LIMA CON: AV. SAN JUAN"},{"latitude":"-12.150114","longitude":"-76.967702","name":"AV. DEFENSORES LIMA CON: JR. JULIO RODRIGUEZ"},{"latitude":"-12.152574","longitude":"-76.964726","name":"AV. DEFENSORES LIMA CON: AV. GRAL. CESAR CANEVARO"},{"latitude":"-12.154029","longitude":"-76.963025","name":"AV. DEFENSORES LIMA CON: JR. ENRIQUE DEL VILLAR"},{"latitude":"-12.155742","longitude":"-76.960854","name":"AV. DEFENSORES LIMA CON: CA. JULIO C. TELLO"},{"latitude":"-12.158072","longitude":"-76.957985","name":"AV. DEFENSORES LIMA CON: JR. TUPAC AMARU"},{"latitude":"-12.159593","longitude":"-76.956241","name":"AV. DEFENSORES LIMA CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.161408","longitude":"-76.954617","name":"AV. DEFENSORES LIMA CON: JR. SIMON BOLIVAR"},{"latitude":"-12.163778","longitude":"-76.952472","name":"AV. DEFENSORES LIMA CON: AV. SAN MARTIN DE PORRES"},{"latitude":"-12.166086","longitude":"-76.950252","name":"AV. DEFENSORES LIMA CON: AV. VILLA MARIA"},{"latitude":"-12.166895","longitude":"-76.949486","name":"DEFENSORES DE LIMA/El Triunfo"},{"latitude":"-12.1656854","longitude":"-76.9476571","name":"sin_nombre"},{"latitude":"-12.164454","longitude":"-76.9458436","name":"sin_nombre"},{"latitude":"-12.1632051","longitude":"-76.9440423","name":"sin_nombre"},{"latitude":"-12.1619562","longitude":"-76.9422409","name":"sin_nombre"},{"latitude":"-12.159391","longitude":"-76.938541","name":"El Triunfo/Ricardo Palma"},{"latitude":"-12.15626","longitude":"-76.93579","name":"Alfonso Ugarte/Villa Maria"},{"latitude":"-12.15736","longitude":"-76.93745","name":"Victor Fajardo/Villa Maria"},{"latitude":"-12.158143","longitude":"-76.938574","name":"11 de Agosto/Villa Maria"},{"latitude":"-12.1593822","longitude":"-76.9403822","name":"sin_nombre"},{"latitude":"-12.1606214","longitude":"-76.9421905","name":"sin_nombre"},{"latitude":"-12.1618605","longitude":"-76.9439987","name":"sin_nombre"},{"latitude":"-12.16478","longitude":"-76.94827","name":"La Union/Villa Maria"},{"latitude":"-12.163757","longitude":"-76.952428","name":"AV. DEFENSORES LIMA CON: AV. SAN MARTIN DE PORRES"},{"latitude":"-12.161408","longitude":"-76.954552","name":"AV. DEFENSORES LIMA CON: JR. SIMON BOLIVAR"},{"latitude":"-12.159687","longitude":"-76.956085","name":"AV. DEFENSORES LIMA CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.158278","longitude":"-76.957664","name":"AV. DEFENSORES LIMA CON: CA. SANTO TORIBIO"},{"latitude":"-12.155839","longitude":"-76.960682","name":"AV. DEFENSORES LIMA CON: CA. JULIO C. TELLO"},{"latitude":"-12.154798","longitude":"-76.961999","name":"AV. DEFENSORES LIMA CON: CA. LAS VIOLETAS"},{"latitude":"-12.152564","longitude":"-76.964643","name":"AV. DEFENSORES LIMA CON: AV. GRAL. CESAR CANEVARO"},{"latitude":"-12.150258","longitude":"-76.967375","name":"AV. DEFENSORES LIMA CON: AV. CENTENARIO"},{"latitude":"-12.148181","longitude":"-76.969078","name":"AV. SALVADOR ALLENDE CON: AV. PROLONGACION SAN JUAN"},{"latitude":"-12.146509","longitude":"-76.970625","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.145296","longitude":"-76.971736","name":"AV. DEFENSORES LIMA CON: AV. EDILBERTO RAMOS"},{"latitude":"-12.144386","longitude":"-76.972566","name":"AV. DEFENSORES LIMA CON: AV. SOLIDARIDAD"},{"latitude":"-12.14079","longitude":"-76.974881","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.138075","longitude":"-76.974985","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"}],[{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.131417","longitude":"-76.976704","name":"Avenida Benavides/SALVADOR ALLENDE"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"227-506","buses":[{"idLine":"227","name":"E.T. La Unidad De Villa S.A.","extraInfo":{"colores_fondo":"BLANCO, ","colores_franjas_hexa":"#FFF701,#0066CC,#009900","ultima_modificacion_por_importacion_lista_puntos":1464212408,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"7504","colores_franjas":"AMARILLO, AZUL, VERDE, "}},{"idLine":"506","name":"E.T. Urbanos Los Chinos S.A.","extraInfo":{"colores_fondo":"BLANCO","colores_franjas_hexa":"#0066CC,42B4E6","ultima_modificacion_por_importacion_lista_puntos":1464212148,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"1802","colores_franjas":"AZUL , CELESTE"}}],"data":{"distance":18.79,"realDistance":18.792112770858,"route":[[{"latitude":"-12.124121","longitude":"-76.984436","name":"Avenida Caminos del Inca/Libres De Trujillo"},{"latitude":"-12.12971","longitude":"-76.981882","name":"AV. CAMINOS DEL INCA CON: AV. BENAVIDES"},{"latitude":"-12.131163","longitude":"-76.982418","name":"AV. CAMINOS DEL INCA CON: CA. MERCADERES"},{"latitude":"-12.133349","longitude":"-76.983032","name":"AV. CAMINOS DEL INCA CON: CA. LAS NAZARENAS"},{"latitude":"-12.135533","longitude":"-76.98391","name":"AV. CAMINOS DEL INCA CON: CA. BIELICH FLORES"},{"latitude":"-12.140104","longitude":"-76.984555","name":"AV. CAMINOS DEL INCA CON: JR. LOMA UMBROSA"},{"latitude":"-12.143145","longitude":"-76.985084","name":"AV. CAMINOS DEL INCA CON: CA. ANDRES TINOCO"},{"latitude":"-12.146982","longitude":"-76.985613","name":"AV. CAMINOS DEL INCA CON: JR. LOMA DE LOS PENSAMIENTOS"},{"latitude":"-12.147879","longitude":"-76.986017","name":"AV. TOMAS MARSANO CON: AV. PROCERES"},{"latitude":"-12.150974","longitude":"-76.979676","name":"AV. LOS HEROES CON: CA. LOS ALGARROBOS"},{"latitude":"-12.152314","longitude":"-76.976277","name":"AV. LOS HEROES CON: JR. ANTONIO BUCKINGHAM"},{"latitude":"-12.153097","longitude":"-76.974565","name":"AV. LOS HEROES CON: CA. JOAQUIN TORRICO"},{"latitude":"-12.154108","longitude":"-76.972017","name":"AV. LOS HEROES CON: AV. SAN JUAN"},{"latitude":"-12.155106","longitude":"-76.972227","name":"AV. SAN JUAN CON: CA. CHARIARSE"},{"latitude":"-12.158381","longitude":"-76.973251","name":"AV. SAN JUAN CON: AV. BILLINGHURST"},{"latitude":"-12.161942","longitude":"-76.97308","name":"AV. SAN JUAN CON: CA. TIMORAN"},{"latitude":"-12.165053","longitude":"-76.972699","name":"AV. SAN JUAN CON: AV. VARGAS MACHUCA"},{"latitude":"-12.164889","longitude":"-76.971023","name":"RAMON VARGAS MACHUCA"},{"latitude":"-12.165777","longitude":"-76.969218","name":"CESAR CANEVARO/Jose Maria Vilchez"},{"latitude":"-12.168886","longitude":"-76.968553","name":"CESAR CANEVARO/Pedro Villalobos"},{"latitude":"-12.169798","longitude":"-76.968313","name":"CESAR CANEVARO/CESAR CANEVARO;VICTOR CASTRO I/Víctor Castro Iglesias"},{"latitude":"-12.172548","longitude":"-76.9676","name":"César Canevaro/CESAR CANEVARO;JUAN VELASCO AL/Juan Velasco Alvarado"},{"latitude":"-12.172251","longitude":"-76.965321","name":"Juan Velasco Alvarado/Leoncio Prado"},{"latitude":"-12.173382","longitude":"-76.962411","name":"Almirante Miguel Grau/Miguel Iglesias"},{"latitude":"-12.174581","longitude":"-76.96002","name":"Juan Velazco Alvarado"},{"latitude":"-12.175608","longitude":"-76.958588","name":"Almirante Miguel Grau"},{"latitude":"-12.177369","longitude":"-76.957962","name":"CALLE 25/Prolongación Héroes del Pacífi"},{"latitude":"-12.179097","longitude":"-76.957802","name":"Calle 13/Manuel Scorza"},{"latitude":"-12.184304","longitude":"-76.954926","name":"Los Cipreses"},{"latitude":"-12.182971","longitude":"-76.955779","name":"Juan Pablo II/Manuel Scorza"},{"latitude":"-12.17548","longitude":"-76.958564","name":"Almirante Miguel Grau/Juan Velazco Alvarado/Mateo Pumacahua"},{"latitude":"-12.17338","longitude":"-76.96241","name":"Juan Velazco Alvarado/Miguel Iglesias"},{"latitude":"-12.17254","longitude":"-76.9676","name":"César Canevaro/CESAR CANEVARO;JUAN VELASCO AL/Juan Velasco Alvarado"},{"latitude":"-12.16979","longitude":"-76.96831","name":"CESAR CANEVARO/CESAR CANEVARO;VICTOR CASTRO I/Víctor Castro Iglesias"},{"latitude":"-12.167522","longitude":"-76.968918","name":"CESAR CANEVARO/Enrique Oppenheimer"},{"latitude":"-12.162128","longitude":"-76.973011","name":"AV. SAN JUAN CON: CA. TIMORAN"},{"latitude":"-12.15869","longitude":"-76.97324","name":"AV. SAN JUAN CON: AV. BILLIGHURST"},{"latitude":"-12.156138","longitude":"-76.972613","name":"AV. SAN JUAN CON: CA. CARRANZA"},{"latitude":"-12.154396","longitude":"-76.971921","name":"AV. SAN JUAN CON: AV. LOS HEROES"},{"latitude":"-12.152242","longitude":"-76.976175","name":"AV. LOS HEROES CON: JR. ANTONIO BUCKINGHAM"},{"latitude":"-12.150912","longitude":"-76.979264","name":"AV. LOS HEROES CON: ENTRE LA CA. CARDENAS Y CA. ZELAYA"}],[{"latitude":"-12.150912","longitude":"-76.979264","name":"AV. LOS HEROES CON: ENTRE LA CA. CARDENAS Y CA. ZELAYA"},{"latitude":"-12.145699","longitude":"-76.981837","name":"AV. PANAMERICANA SUR CON: JR. LOMA DE LAS CLIVAS"},{"latitude":"-12.143302","longitude":"-76.981632","name":"AV. PANAMERICANA SUR CON: AV. ANDRES TINOCO"},{"latitude":"-12.141012","longitude":"-76.981002","name":"AV. PANAMERICANA SUR CON: JR. LOMA UMBROSA"},{"latitude":"-12.135536","longitude":"-76.979688","name":"AV. PANAMERICANA SUR CON: AV. CERRO CHICO (MARCONA)"},{"latitude":"-12.131463","longitude":"-76.978169","name":"AV. PANAMERICANA SUR CON: AV. BENAVIDES"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"227-178","buses":[{"idLine":"227","name":"E.T. La Unidad De Villa S.A.","extraInfo":{"colores_fondo":"BLANCO, ","colores_franjas_hexa":"#FFF701,#0066CC,#009900","ultima_modificacion_por_importacion_lista_puntos":1464212408,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"7504","colores_franjas":"AMARILLO, AZUL, VERDE, "}},{"idLine":"178","name":"E.T. Luis Banchero Rossi S.A.","extraInfo":{"colores_fondo":"AMARILLO , BLANCO , AZUL","colores_franjas_hexa":"#FCFBF9","ultima_modificacion_por_importacion_lista_puntos":1464212133,"alias":"","colores_fondo_hexa":"#FFF701,#FCFBF9,#0066CC","route_new_code":"8302","colores_franjas":"BLANCO, "}}],"data":{"distance":18.79,"realDistance":18.792112770858,"route":[[{"latitude":"-12.124121","longitude":"-76.984436","name":"Avenida Caminos del Inca/Libres De Trujillo"},{"latitude":"-12.12971","longitude":"-76.981882","name":"AV. CAMINOS DEL INCA CON: AV. BENAVIDES"},{"latitude":"-12.131163","longitude":"-76.982418","name":"AV. CAMINOS DEL INCA CON: CA. MERCADERES"},{"latitude":"-12.133349","longitude":"-76.983032","name":"AV. CAMINOS DEL INCA CON: CA. LAS NAZARENAS"},{"latitude":"-12.135533","longitude":"-76.98391","name":"AV. CAMINOS DEL INCA CON: CA. BIELICH FLORES"},{"latitude":"-12.140104","longitude":"-76.984555","name":"AV. CAMINOS DEL INCA CON: JR. LOMA UMBROSA"},{"latitude":"-12.143145","longitude":"-76.985084","name":"AV. CAMINOS DEL INCA CON: CA. ANDRES TINOCO"},{"latitude":"-12.146982","longitude":"-76.985613","name":"AV. CAMINOS DEL INCA CON: JR. LOMA DE LOS PENSAMIENTOS"},{"latitude":"-12.147879","longitude":"-76.986017","name":"AV. TOMAS MARSANO CON: AV. PROCERES"},{"latitude":"-12.150974","longitude":"-76.979676","name":"AV. LOS HEROES CON: CA. LOS ALGARROBOS"},{"latitude":"-12.152314","longitude":"-76.976277","name":"AV. LOS HEROES CON: JR. ANTONIO BUCKINGHAM"},{"latitude":"-12.153097","longitude":"-76.974565","name":"AV. LOS HEROES CON: CA. JOAQUIN TORRICO"},{"latitude":"-12.154108","longitude":"-76.972017","name":"AV. LOS HEROES CON: AV. SAN JUAN"},{"latitude":"-12.155106","longitude":"-76.972227","name":"AV. SAN JUAN CON: CA. CHARIARSE"},{"latitude":"-12.158381","longitude":"-76.973251","name":"AV. SAN JUAN CON: AV. BILLINGHURST"},{"latitude":"-12.161942","longitude":"-76.97308","name":"AV. SAN JUAN CON: CA. TIMORAN"},{"latitude":"-12.165053","longitude":"-76.972699","name":"AV. SAN JUAN CON: AV. VARGAS MACHUCA"},{"latitude":"-12.164889","longitude":"-76.971023","name":"RAMON VARGAS MACHUCA"},{"latitude":"-12.165777","longitude":"-76.969218","name":"CESAR CANEVARO/Jose Maria Vilchez"},{"latitude":"-12.168886","longitude":"-76.968553","name":"CESAR CANEVARO/Pedro Villalobos"},{"latitude":"-12.169798","longitude":"-76.968313","name":"CESAR CANEVARO/CESAR CANEVARO;VICTOR CASTRO I/Víctor Castro Iglesias"},{"latitude":"-12.172548","longitude":"-76.9676","name":"César Canevaro/CESAR CANEVARO;JUAN VELASCO AL/Juan Velasco Alvarado"},{"latitude":"-12.172251","longitude":"-76.965321","name":"Juan Velasco Alvarado/Leoncio Prado"},{"latitude":"-12.173382","longitude":"-76.962411","name":"Almirante Miguel Grau/Miguel Iglesias"},{"latitude":"-12.174581","longitude":"-76.96002","name":"Juan Velazco Alvarado"},{"latitude":"-12.175608","longitude":"-76.958588","name":"Almirante Miguel Grau"},{"latitude":"-12.177369","longitude":"-76.957962","name":"CALLE 25/Prolongación Héroes del Pacífi"},{"latitude":"-12.179097","longitude":"-76.957802","name":"Calle 13/Manuel Scorza"},{"latitude":"-12.184304","longitude":"-76.954926","name":"Los Cipreses"},{"latitude":"-12.182971","longitude":"-76.955779","name":"Juan Pablo II/Manuel Scorza"},{"latitude":"-12.17548","longitude":"-76.958564","name":"Almirante Miguel Grau/Juan Velazco Alvarado/Mateo Pumacahua"},{"latitude":"-12.17338","longitude":"-76.96241","name":"Juan Velazco Alvarado/Miguel Iglesias"},{"latitude":"-12.17254","longitude":"-76.9676","name":"César Canevaro/CESAR CANEVARO;JUAN VELASCO AL/Juan Velasco Alvarado"},{"latitude":"-12.16979","longitude":"-76.96831","name":"CESAR CANEVARO/CESAR CANEVARO;VICTOR CASTRO I/Víctor Castro Iglesias"},{"latitude":"-12.167522","longitude":"-76.968918","name":"CESAR CANEVARO/Enrique Oppenheimer"},{"latitude":"-12.162128","longitude":"-76.973011","name":"AV. SAN JUAN CON: CA. TIMORAN"},{"latitude":"-12.15869","longitude":"-76.97324","name":"AV. SAN JUAN CON: AV. BILLIGHURST"},{"latitude":"-12.156138","longitude":"-76.972613","name":"AV. SAN JUAN CON: CA. CARRANZA"},{"latitude":"-12.154396","longitude":"-76.971921","name":"AV. SAN JUAN CON: AV. LOS HEROES"},{"latitude":"-12.152242","longitude":"-76.976175","name":"AV. LOS HEROES CON: JR. ANTONIO BUCKINGHAM"},{"latitude":"-12.150912","longitude":"-76.979264","name":"AV. LOS HEROES CON: ENTRE LA CA. CARDENAS Y CA. ZELAYA"}],[{"latitude":"-12.150912","longitude":"-76.979264","name":"AV. LOS HEROES CON: ENTRE LA CA. CARDENAS Y CA. ZELAYA"},{"latitude":"-12.145699","longitude":"-76.981837","name":"AV. PANAMERICANA SUR CON: JR. LOMA DE LAS CLIVAS"},{"latitude":"-12.143302","longitude":"-76.981632","name":"AV. PANAMERICANA SUR CON: AV. ANDRES TINOCO"},{"latitude":"-12.141012","longitude":"-76.981002","name":"AV. PANAMERICANA SUR CON: JR. LOMA UMBROSA"},{"latitude":"-12.135536","longitude":"-76.979688","name":"AV. PANAMERICANA SUR CON: AV. CERRO CHICO (MARCONA)"},{"latitude":"-12.131463","longitude":"-76.978169","name":"AV. PANAMERICANA SUR CON: AV. BENAVIDES"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"291-429","buses":[{"idLine":"291","name":"E.T. Y Mult.Imp.Y Exp.San Francisco De Asis De Los Olivos S.A","extraInfo":{"colores_fondo":"ROJO FERRARI, ","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464211723,"alias":"","colores_fondo_hexa":"#FF2801","route_new_code":"8104","colores_franjas":""}},{"idLine":"429","name":"E.T. Serv. Nueva Jerusalen De La Rinconada S.A.","extraInfo":{"colores_fondo":"BLANCO","colores_franjas_hexa":"#0066CC,#F15C00,#CC0000","ultima_modificacion_por_importacion_lista_puntos":1464211670,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"7203","colores_franjas":"AZUL, NARANJA , ROJO"}}],"data":{"distance":19.46,"realDistance":19.460831008849,"route":[[{"latitude":"-12.124121","longitude":"-76.984436","name":"Avenida Caminos del Inca/Libres De Trujillo"},{"latitude":"-12.130068","longitude":"-76.981763","name":"AV. CAMINOS DEL INCA CON: AV. BENAVIDES"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.131332","longitude":"-76.977542","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.135367","longitude":"-76.976231","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.138047","longitude":"-76.97507","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.140498","longitude":"-76.975","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.144112","longitude":"-76.97344","name":"AV. DEFENSORES LIMA CON: AV. J. ECHENIQUE"},{"latitude":"-12.145418","longitude":"-76.972223","name":"AV. DEFENSORES LIMA CON: JR. GABRIEL TORRES"},{"latitude":"-12.146593","longitude":"-76.971119","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.147799","longitude":"-76.970015","name":"AV. DEFENSORES LIMA CON: AV. SAN JUAN"},{"latitude":"-12.150114","longitude":"-76.967702","name":"AV. DEFENSORES LIMA CON: JR. JULIO RODRIGUEZ"},{"latitude":"-12.152574","longitude":"-76.964726","name":"AV. DEFENSORES LIMA CON: AV. GRAL. CESAR CANEVARO"},{"latitude":"-12.154029","longitude":"-76.963025","name":"AV. DEFENSORES LIMA CON: JR. ENRIQUE DEL VILLAR"},{"latitude":"-12.155742","longitude":"-76.960854","name":"AV. DEFENSORES LIMA CON: CA. JULIO C. TELLO"},{"latitude":"-12.158072","longitude":"-76.957985","name":"AV. DEFENSORES LIMA CON: JR. TUPAC AMARU"},{"latitude":"-12.159593","longitude":"-76.956241","name":"AV. DEFENSORES LIMA CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.161408","longitude":"-76.954617","name":"AV. DEFENSORES LIMA CON: JR. SIMON BOLIVAR"},{"latitude":"-12.163778","longitude":"-76.952472","name":"AV. DEFENSORES LIMA CON: AV. SAN MARTIN DE PORRES"},{"latitude":"-12.166086","longitude":"-76.950252","name":"AV. DEFENSORES LIMA CON: AV. VILLA MARIA"},{"latitude":"-12.168445","longitude":"-76.948092","name":"AV. DEFENSORES LIMA CON: AV. SANTA ROSA"},{"latitude":"-12.170819","longitude":"-76.946022","name":"AV. DEFENSORES LIMA CON: AV. PARADO DE BELLIDO"},{"latitude":"-12.174225","longitude":"-76.943212","name":"AV. DEFENSORES LIMA CON: CA. 28 DE JULIO"},{"latitude":"-12.177744","longitude":"-76.939586","name":"AV. 26 DE NOVIEMBRE CON: AV. SALVADOR ALLENDE"},{"latitude":"-12.179389","longitude":"-76.941925","name":"AV. 26 DE NOVIEMBRE CON: PROP.  CA. VALLEJO"},{"latitude":"-12.180481","longitude":"-76.943508","name":"AV. PACHACUTEC CON: AV. 26 DE NOVIEMBRE"},{"latitude":"-12.182514","longitude":"-76.942277","name":"AV. PACHACUTEC CON: AV. MATEO PUMACAHUA"},{"latitude":"-12.187632","longitude":"-76.939353","name":"AV. PACHACUTEC CON: AV. 1º DE MAYO"},{"latitude":"-12.187718","longitude":"-76.939274","name":"AV. PACHACUTEC CON: AV. 1º DE MAYO"},{"latitude":"-12.182386","longitude":"-76.942316","name":"AV. PACHACUTEC CON: JR. LA MERCED"},{"latitude":"-12.174085","longitude":"-76.943245","name":"AV. DEFENSORES LIMA CON: CA. PROGRESO"},{"latitude":"-12.170797","longitude":"-76.94598","name":"AV. DEFENSORES LIMA CON: AV. PARADO DE BELLIDO"},{"latitude":"-12.168452","longitude":"-76.948011","name":"AV. DEFENSORES LIMA CON: AV. SATA ROSA"},{"latitude":"-12.166165","longitude":"-76.95013","name":"AV. DEFENSORES LIMA CON: AV. VILLA MARIA"},{"latitude":"-12.163757","longitude":"-76.952428","name":"AV. DEFENSORES LIMA CON: AV. SAN MARTIN DE PORRES"},{"latitude":"-12.161408","longitude":"-76.954552","name":"AV. DEFENSORES LIMA CON: JR. SIMON BOLIVAR"},{"latitude":"-12.159687","longitude":"-76.956085","name":"AV. DEFENSORES LIMA CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.158278","longitude":"-76.957664","name":"AV. DEFENSORES LIMA CON: CA. SANTO TORIBIO"},{"latitude":"-12.155839","longitude":"-76.960682","name":"AV. DEFENSORES LIMA CON: CA. JULIO C. TELLO"},{"latitude":"-12.154798","longitude":"-76.961999","name":"AV. DEFENSORES LIMA CON: CA. LAS VIOLETAS"},{"latitude":"-12.152564","longitude":"-76.964643","name":"AV. DEFENSORES LIMA CON: AV. GRAL. CESAR CANEVARO"},{"latitude":"-12.150258","longitude":"-76.967375","name":"AV. DEFENSORES LIMA CON: AV. CENTENARIO"},{"latitude":"-12.148181","longitude":"-76.969078","name":"AV. SALVADOR ALLENDE CON: AV. PROLONGACION SAN JUAN"},{"latitude":"-12.146509","longitude":"-76.970625","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.145296","longitude":"-76.971736","name":"AV. DEFENSORES LIMA CON: AV. EDILBERTO RAMOS"},{"latitude":"-12.144386","longitude":"-76.972566","name":"AV. DEFENSORES LIMA CON: AV. SOLIDARIDAD"},{"latitude":"-12.14079","longitude":"-76.974881","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.138075","longitude":"-76.974985","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"}],[{"latitude":"-12.135448","longitude":"-76.976088","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.131417","longitude":"-76.976704","name":"Avenida Benavides/SALVADOR ALLENDE"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"100-506","buses":[{"idLine":"100","name":"E.T.Ha.De Servicios Multiples De Propietarios Unidos Huascar","extraInfo":{"colores_fondo":"BLANCO , ROJO , PLOMO , AZUL","colores_franjas_hexa":"#CC0000,#7F7F7F,#0066CC","ultima_modificacion_por_importacion_lista_puntos":1464211690,"alias":"La HA","colores_fondo_hexa":"#FCFBF9,#CC0000,#7F7F7F,#0066CC","route_new_code":"3701","colores_franjas":"ROJO,PLOMO,AZUL"}},{"idLine":"506","name":"E.T. Urbanos Los Chinos S.A.","extraInfo":{"colores_fondo":"BLANCO","colores_franjas_hexa":"#0066CC,42B4E6","ultima_modificacion_por_importacion_lista_puntos":1464212148,"alias":"","colores_fondo_hexa":"#FCFBF9","route_new_code":"1802","colores_franjas":"AZUL , CELESTE"}}],"data":{"distance":19.5,"realDistance":19.497986177429,"route":[[{"latitude":"-12.124121","longitude":"-76.984436","name":"Avenida Caminos del Inca/Libres De Trujillo"},{"latitude":"-12.12971","longitude":"-76.981882","name":"AV. CAMINOS DEL INCA CON: AV. BENAVIDES"},{"latitude":"-12.131163","longitude":"-76.982418","name":"AV. CAMINOS DEL INCA CON: CA. MERCADERES"},{"latitude":"-12.133349","longitude":"-76.983032","name":"AV. CAMINOS DEL INCA CON: CA. LAS NAZARENAS"},{"latitude":"-12.135533","longitude":"-76.98391","name":"AV. CAMINOS DEL INCA CON: CA. BIELICH FLORES"},{"latitude":"-12.140104","longitude":"-76.984555","name":"AV. CAMINOS DEL INCA CON: JR. LOMA UMBROSA"},{"latitude":"-12.143145","longitude":"-76.985084","name":"AV. CAMINOS DEL INCA CON: CA. ANDRES TINOCO"},{"latitude":"-12.146982","longitude":"-76.985613","name":"AV. CAMINOS DEL INCA CON: JR. LOMA DE LOS PENSAMIENTOS"},{"latitude":"-12.148599","longitude":"-76.986451","name":"AV. LOS PROCERES CON: JR. PASCUAL SACO OLIVEROS"},{"latitude":"-12.147879","longitude":"-76.986017","name":"AV. TOMAS MARSANO CON: AV. PROCERES"},{"latitude":"-12.150974","longitude":"-76.979676","name":"AV. LOS HEROES CON: CA. LOS ALGARROBOS"},{"latitude":"-12.152314","longitude":"-76.976277","name":"AV. LOS HEROES CON: JR. ANTONIO BUCKINGHAM"},{"latitude":"-12.153097","longitude":"-76.974565","name":"AV. LOS HEROES CON: CA. JOAQUIN TORRICO"},{"latitude":"-12.154108","longitude":"-76.972017","name":"AV. LOS HEROES CON: AV. SAN JUAN"},{"latitude":"-12.155106","longitude":"-76.972227","name":"AV. SAN JUAN CON: CA. CHARIARSE"},{"latitude":"-12.158381","longitude":"-76.973251","name":"AV. SAN JUAN CON: AV. BILLINGHURST"},{"latitude":"-12.161942","longitude":"-76.97308","name":"AV. SAN JUAN CON: CA. TIMORAN"},{"latitude":"-12.165053","longitude":"-76.972699","name":"AV. SAN JUAN CON: AV. VARGAS MACHUCA"},{"latitude":"-12.164889","longitude":"-76.971023","name":"RAMON VARGAS MACHUCA"},{"latitude":"-12.165777","longitude":"-76.969218","name":"CESAR CANEVARO/Jose Maria Vilchez"},{"latitude":"-12.168886","longitude":"-76.968553","name":"CESAR CANEVARO/Pedro Villalobos"},{"latitude":"-12.169798","longitude":"-76.968313","name":"CESAR CANEVARO/CESAR CANEVARO;VICTOR CASTRO I/Víctor Castro Iglesias"},{"latitude":"-12.172548","longitude":"-76.9676","name":"César Canevaro/CESAR CANEVARO;JUAN VELASCO AL/Juan Velasco Alvarado"},{"latitude":"-12.178557","longitude":"-76.96605","name":"P1976"},{"latitude":"-12.17983","longitude":"-76.966748","name":"Mariano Pastor Sevilla"},{"latitude":"-12.184833","longitude":"-76.964901","name":"Calle 7/Mariano Pastor Sevilla"},{"latitude":"-12.18731","longitude":"-76.963985","name":"Los Geranios/Mariano Pastor Sevilla"},{"latitude":"-12.192496","longitude":"-76.960895","name":"AV. MARIANO PASTOR SEVILLA CON: AV. MATEO PUMACAHUA"},{"latitude":"-12.18731","longitude":"-76.96398","name":"Los Geranios/Mariano Pastor Sevilla"},{"latitude":"-12.18483","longitude":"-76.9649","name":"Mariano Pastor Sevilla/Santa Rita"},{"latitude":"-12.181516","longitude":"-76.965114","name":"César Canevaro/Santa Beatriz"},{"latitude":"-12.18014","longitude":"-76.96564","name":"César Canevaro/Juan Pablo II"},{"latitude":"-12.17254","longitude":"-76.9676","name":"César Canevaro/CESAR CANEVARO;JUAN VELASCO AL/Juan Velasco Alvarado"},{"latitude":"-12.16979","longitude":"-76.96831","name":"CESAR CANEVARO/CESAR CANEVARO;VICTOR CASTRO I/Víctor Castro Iglesias"},{"latitude":"-12.167522","longitude":"-76.968918","name":"CESAR CANEVARO/Enrique Oppenheimer"},{"latitude":"-12.162128","longitude":"-76.973011","name":"AV. SAN JUAN CON: CA. TIMORAN"},{"latitude":"-12.15869","longitude":"-76.97324","name":"AV. SAN JUAN CON: AV. BILLIGHURST"},{"latitude":"-12.156138","longitude":"-76.972613","name":"AV. SAN JUAN CON: CA. CARRANZA"},{"latitude":"-12.154396","longitude":"-76.971921","name":"AV. SAN JUAN CON: AV. LOS HEROES"},{"latitude":"-12.152242","longitude":"-76.976175","name":"AV. LOS HEROES CON: JR. ANTONIO BUCKINGHAM"},{"latitude":"-12.150912","longitude":"-76.979264","name":"AV. LOS HEROES CON: ENTRE LA CA. CARDENAS Y CA. ZELAYA"}],[{"latitude":"-12.150912","longitude":"-76.979264","name":"AV. LOS HEROES CON: ENTRE LA CA. CARDENAS Y CA. ZELAYA"},{"latitude":"-12.145699","longitude":"-76.981837","name":"AV. PANAMERICANA SUR CON: JR. LOMA DE LAS CLIVAS"},{"latitude":"-12.143302","longitude":"-76.981632","name":"AV. PANAMERICANA SUR CON: AV. ANDRES TINOCO"},{"latitude":"-12.141012","longitude":"-76.981002","name":"AV. PANAMERICANA SUR CON: JR. LOMA UMBROSA"},{"latitude":"-12.135536","longitude":"-76.979688","name":"AV. PANAMERICANA SUR CON: AV. CERRO CHICO (MARCONA)"},{"latitude":"-12.131463","longitude":"-76.978169","name":"AV. PANAMERICANA SUR CON: AV. BENAVIDES"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"291-178","buses":[{"idLine":"291","name":"E.T. Y Mult.Imp.Y Exp.San Francisco De Asis De Los Olivos S.A","extraInfo":{"colores_fondo":"ROJO FERRARI, ","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464211723,"alias":"","colores_fondo_hexa":"#FF2801","route_new_code":"8104","colores_franjas":""}},{"idLine":"178","name":"E.T. Luis Banchero Rossi S.A.","extraInfo":{"colores_fondo":"AMARILLO , BLANCO , AZUL","colores_franjas_hexa":"#FCFBF9","ultima_modificacion_por_importacion_lista_puntos":1464212133,"alias":"","colores_fondo_hexa":"#FFF701,#FCFBF9,#0066CC","route_new_code":"8302","colores_franjas":"BLANCO, "}}],"data":{"distance":20.28,"realDistance":20.279759534571,"route":[[{"latitude":"-12.124121","longitude":"-76.984436","name":"Avenida Caminos del Inca/Libres De Trujillo"},{"latitude":"-12.130068","longitude":"-76.981763","name":"AV. CAMINOS DEL INCA CON: AV. BENAVIDES"},{"latitude":"-12.130923","longitude":"-76.978935","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.131332","longitude":"-76.977542","name":"AV. ALFREDO BENAVIDES CON: PANAMERICANA SUR"},{"latitude":"-12.135367","longitude":"-76.976231","name":"AV. DEFENSORES LIMA CON: PSJE. EL CERRITO (CDRA Nº1)"},{"latitude":"-12.138047","longitude":"-76.97507","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO BELLO (CDRA. Nº 01)"},{"latitude":"-12.140498","longitude":"-76.975","name":"AV. DEFENSORES LIMA CON: PSJE. CERRO GRIS (CDRA. Nº 04)"},{"latitude":"-12.144112","longitude":"-76.97344","name":"AV. DEFENSORES LIMA CON: AV. J. ECHENIQUE"},{"latitude":"-12.145418","longitude":"-76.972223","name":"AV. DEFENSORES LIMA CON: JR. GABRIEL TORRES"},{"latitude":"-12.146593","longitude":"-76.971119","name":"AV. DEFENSORES LIMA CON: AV. CENTRAL"},{"latitude":"-12.147799","longitude":"-76.970015","name":"AV. DEFENSORES LIMA CON: AV. SAN JUAN"},{"latitude":"-12.150114","longitude":"-76.967702","name":"AV. DEFENSORES LIMA CON: JR. JULIO RODRIGUEZ"},{"latitude":"-12.152574","longitude":"-76.964726","name":"AV. DEFENSORES LIMA CON: AV. GRAL. CESAR CANEVARO"},{"latitude":"-12.154029","longitude":"-76.963025","name":"AV. DEFENSORES LIMA CON: JR. ENRIQUE DEL VILLAR"},{"latitude":"-12.155742","longitude":"-76.960854","name":"AV. DEFENSORES LIMA CON: CA. JULIO C. TELLO"},{"latitude":"-12.158072","longitude":"-76.957985","name":"AV. DEFENSORES LIMA CON: JR. TUPAC AMARU"},{"latitude":"-12.159593","longitude":"-76.956241","name":"AV. DEFENSORES LIMA CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.161408","longitude":"-76.954617","name":"AV. DEFENSORES LIMA CON: JR. SIMON BOLIVAR"},{"latitude":"-12.163778","longitude":"-76.952472","name":"AV. DEFENSORES LIMA CON: AV. SAN MARTIN DE PORRES"},{"latitude":"-12.166086","longitude":"-76.950252","name":"AV. DEFENSORES LIMA CON: AV. VILLA MARIA"},{"latitude":"-12.168445","longitude":"-76.948092","name":"AV. DEFENSORES LIMA CON: AV. SANTA ROSA"},{"latitude":"-12.170819","longitude":"-76.946022","name":"AV. DEFENSORES LIMA CON: AV. PARADO DE BELLIDO"},{"latitude":"-12.174225","longitude":"-76.943212","name":"AV. DEFENSORES LIMA CON: CA. 28 DE JULIO"},{"latitude":"-12.177744","longitude":"-76.939586","name":"AV. 26 DE NOVIEMBRE CON: AV. SALVADOR ALLENDE"},{"latitude":"-12.179389","longitude":"-76.941925","name":"AV. 26 DE NOVIEMBRE CON: PROP.  CA. VALLEJO"},{"latitude":"-12.180481","longitude":"-76.943508","name":"AV. PACHACUTEC CON: AV. 26 DE NOVIEMBRE"},{"latitude":"-12.182514","longitude":"-76.942277","name":"AV. PACHACUTEC CON: AV. MATEO PUMACAHUA"},{"latitude":"-12.187632","longitude":"-76.939353","name":"AV. PACHACUTEC CON: AV. 1º DE MAYO"},{"latitude":"-12.187718","longitude":"-76.939274","name":"AV. PACHACUTEC CON: AV. 1º DE MAYO"},{"latitude":"-12.182386","longitude":"-76.942316","name":"AV. PACHACUTEC CON: JR. LA MERCED"}],[{"latitude":"-12.182386","longitude":"-76.942316","name":"AV. PACHACUTEC CON: JR. LA MERCED"},{"latitude":"-12.180459","longitude":"-76.943487","name":"AV. PACHACUTEC CON: AV. 26 DE NOVIEMBRE"},{"latitude":"-12.176964","longitude":"-76.945503","name":"AV. PACHACUTEC CON: AV. 09 DE DICIEMBRE"},{"latitude":"-12.175051","longitude":"-76.946763","name":"AV. PACHACUTEC CON: AV. 8 DE OCTUBRE"},{"latitude":"-12.17355","longitude":"-76.947872","name":"AV. PACHACUTEC CON: PSJE. WIRACOCHA"},{"latitude":"-12.17259","longitude":"-76.948414","name":"AV. PACHACUTEC CON: AV. MARIA PARADO DE BELLIDO"},{"latitude":"-12.168822","longitude":"-76.95034","name":"AV. PACHACUTEC CON: AV. EL SOL"},{"latitude":"-12.166676","longitude":"-76.951715","name":"AV. PACHACUTEC CON: AV. VILLA MARIA DEL TRIUNFO"},{"latitude":"-12.16436","longitude":"-76.953528","name":"AV. PACHACUTEC CON: CA. SAN MARTIN DE PORRES"},{"latitude":"-12.162115","longitude":"-76.9553","name":"AV. PACHACUTEC CON: CA. BOLIVAR"},{"latitude":"-12.160315","longitude":"-76.956799","name":"AV. LOS HEROES CON: AV. JOSE CARLOS MARIATEGUI"},{"latitude":"-12.159386","longitude":"-76.95857","name":"AV. LOS HEROES CON: TUPAC AMARU"},{"latitude":"-12.157999","longitude":"-76.961916","name":"AV. LOS HEROES CON: CA. ARICA (ALTURA CUADRA 10)"},{"latitude":"-12.156573","longitude":"-76.965417","name":"AV. LOS HEROES CON: JR. ARTURO ARMERO"},{"latitude":"-12.155495","longitude":"-76.968051","name":"AV. LOS HEROES CON: CA. N. DE PIEROLA (MUNICIPIO DE SJM)"},{"latitude":"-12.154099","longitude":"-76.971681","name":"AV. LOS HEROES CON: AV. SAN JUAN"},{"latitude":"-12.152242","longitude":"-76.976175","name":"AV. LOS HEROES CON: JR. ANTONIO BUCKINGHAM"},{"latitude":"-12.150912","longitude":"-76.979264","name":"AV. LOS HEROES CON: ENTRE LA CA. CARDENAS Y CA. ZELAYA"},{"latitude":"-12.145699","longitude":"-76.981837","name":"AV. PANAMERICANA SUR CON: JR. LOMA DE LAS CLIVAS"},{"latitude":"-12.143302","longitude":"-76.981632","name":"AV. PANAMERICANA SUR CON: AV. ANDRES TINOCO"},{"latitude":"-12.141012","longitude":"-76.981002","name":"AV. PANAMERICANA SUR CON: JR. LOMA UMBROSA"},{"latitude":"-12.135536","longitude":"-76.979688","name":"AV. PANAMERICANA SUR CON: AV. CERRO CHICO (MARCONA)"},{"latitude":"-12.131463","longitude":"-76.978169","name":"AV. PANAMERICANA SUR CON: AV. BENAVIDES"},{"latitude":"-12.127626","longitude":"-76.976813","name":"AV. PANAMERICANA SUR CON: JR. BATALLA DE SAN JUAN"},{"latitude":"-12.122454","longitude":"-76.976875","name":"AV. PANAMERICANA SUR CON: CA. LAS LILAS"},{"latitude":"-12.119604","longitude":"-76.97696","name":"AV. PANAMERICANA SUR CON: JR. LAS GARDENIAS"},{"latitude":"-12.117516","longitude":"-76.977077","name":"Panamericana Sur"},{"latitude":"-12.110858","longitude":"-76.97798","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}},{"key":"392-284","buses":[{"idLine":"392","name":"E.S.T. Santa Catalina S.A.","extraInfo":{"colores_fondo":"ROJO , VERDE , BLANCO","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464211773,"alias":"La 23 C","colores_fondo_hexa":"#CC0000,#009900,#FCFBF9","route_new_code":"3806","colores_franjas":""}},{"idLine":"284","name":"Empresa De Transportes Turismo Y Representac. Genesis S.A.C.","extraInfo":{"colores_fondo":"","colores_franjas_hexa":"","ultima_modificacion_por_importacion_lista_puntos":1464212215,"alias":"Genesis","colores_fondo_hexa":"","route_new_code":"","colores_franjas":""}}],"data":{"distance":20.72,"realDistance":20.722582533704,"route":[[{"latitude":"-12.12412","longitude":"-76.98443","name":"Avenida Caminos del Inca/Libres De Trujillo"},{"latitude":"-12.12251","longitude":"-76.98613","name":"Alameda del Crepúsculo/Avenida Caminos del Inca"},{"latitude":"-12.120842","longitude":"-76.987816","name":"Avenida Caminos del Inca/Higuereta"},{"latitude":"-12.118732","longitude":"-76.988937","name":"Avenida Caminos del Inca/Cadiz/Mariel"},{"latitude":"-12.117002","longitude":"-76.990035","name":"Avenida Caminos del Inca/La Pampilla"},{"latitude":"-12.111242","longitude":"-76.993331","name":"AV. PRIMAVERA (Via  auxiliar intercambio vial) CON: JR. PASEO DEL BOSQUE"},{"latitude":"-12.111522","longitude":"-76.998482","name":"AV. ANGAMOS ESTE CON: PSJE. F4"},{"latitude":"-12.111617","longitude":"-76.999805","name":"AV. ANGAMOS ESTE CON: AV. AVIACION"},{"latitude":"-12.111866","longitude":"-77.003189","name":"AV. ANGAMOS ESTE CON: AV. R. MALACHOWSKY"},{"latitude":"-12.112054","longitude":"-77.005864","name":"AV. ANGAMOS ESTE CON: CA. EMILIO HARTH"},{"latitude":"-12.112297","longitude":"-77.009279","name":"AV. ANGAMOS ESTE CON: JR. MIGUEL IGLESIAS"},{"latitude":"-12.112432","longitude":"-77.011628","name":"AV. ANGAMOS ESTE CON: AV. TOMAS MARSANO"},{"latitude":"-12.112756","longitude":"-77.016103","name":"AV. ANGAMOS ESTE CON: JR. SAN FELIPE"},{"latitude":"-12.11287","longitude":"-77.018009","name":"AV. ANGAMOS ESTE CON: AV. REPUBLICA DE PANAMA"},{"latitude":"-12.113121","longitude":"-77.018728","name":"AV. ANGAMOS ESTE CON: AV. REPUBLICA DE PANAMA"},{"latitude":"-12.113247","longitude":"-77.023036","name":"AV. ANGAMOS ESTE CON: JR. DANTE ALCOCER"},{"latitude":"-12.113375","longitude":"-77.025176","name":"AV. ANGAMOS ESTE CON: AV. PASEO DE LA REPUBLICA"},{"latitude":"-12.113559","longitude":"-77.027151","name":"AV. ANGAMOS ESTE CON: CA. SUAREZ"},{"latitude":"-12.113664","longitude":"-77.029464","name":"AV. ANGAMOS ESTE CON: AV. AREQUIPA"},{"latitude":"-12.1138","longitude":"-77.033246","name":"AV. ANGAMOS OESTE CON: CA. GRAL. BORGOÑO"},{"latitude":"-12.113923","longitude":"-77.03678","name":"AV. ANGAMOS OESTE CON: AV. CMTE. ESPINAR"},{"latitude":"-12.112836","longitude":"-77.036835","name":"AV. COMANDANTE ESPINAR CON: CA. HABICH"},{"latitude":"-12.107467","longitude":"-77.036963","name":"AV. LOS CONQUISTADORES CON: AV. PARDO Y ALIAGA"},{"latitude":"-12.105333","longitude":"-77.037069","name":"AV. LOS CONQUISTADORES CON: CA. ROAUD Y PAZ SOLDAN"},{"latitude":"-12.103215","longitude":"-77.036793","name":"AV. LOS CONQUISTADORES CON: CA. CONDE DE LA MONCLOVA"},{"latitude":"-12.101182","longitude":"-77.036638","name":"AV. LOS CONQUISTADORES CON: CA. ERNESTO PLASCENCIA"},{"latitude":"-12.099199","longitude":"-77.0363","name":"AV. LOS CONQUISTADORES CON: CA. CHOQUEHUANCA"},{"latitude":"-12.096717","longitude":"-77.035417","name":"AV. LOS CONQUISTADORES CON: AV. PAZ SOLDAN"},{"latitude":"-12.095162","longitude":"-77.034439","name":"El Bosque"},{"latitude":"-12.09241","longitude":"-77.032094","name":"AV. PETIT THOUARS CON: AV. JAVIER PRADO"},{"latitude":"-12.090622","longitude":"-77.032333","name":"AV. PETIT THOUARS CON: JR. SOLEDAD"},{"latitude":"-12.089162","longitude":"-77.032547","name":"AV. PETIT THOUARS CON: JR. JOSE DE LA TORRE UGARTE"},{"latitude":"-12.085719","longitude":"-77.033046","name":"AV. PETIT THOUARS CON: JR. RISSO"},{"latitude":"-12.083341","longitude":"-77.033327","name":"AV. JOSE PARDO DE ZELA CON: AV. PETIT THOUARS"},{"latitude":"-12.08242","longitude":"-77.033539","name":"AV. PETIT THOUARS CON: JR. MANUEL CANDAMO"},{"latitude":"-12.079679","longitude":"-77.033952","name":"AV. PETIT THOUARS CON: JR. MANUEL SEGURA"},{"latitude":"-12.079257","longitude":"-77.031119","name":"AV. SOLDADO M. CASTAÑEDA CON: AV. MANUEL SEGURA"},{"latitude":"-12.078031","longitude":"-77.030083","name":"CA. MANUEL CASTAÑEDA CON: AV. JOSE GALVEZ"},{"latitude":"-12.077193","longitude":"-77.029056","name":"PROLG. AV. IQUITOS CON: AV. MEXICO"},{"latitude":"-12.07578","longitude":"-77.025677","name":"AV. MEXICO CON: AV. LUNA PIZARRO"},{"latitude":"-12.074875","longitude":"-77.023332","name":"AV. MEXICO CON: AV. PALERMO"},{"latitude":"-12.074191","longitude":"-77.020497","name":"AV. MEXICO CON: CA. BENITO PARDO FIGUEROA"},{"latitude":"-12.073946","longitude":"-77.018636","name":"AV. MEXICO CON: PROLONG. HUAMANGA"},{"latitude":"-12.073409","longitude":"-77.016461","name":"AV. MEXICO CON: AV. PROLONG. PARINACOCHAS"},{"latitude":"-12.072751","longitude":"-77.014063","name":"AV. MEXICO CON: JR. HUANUCO"},{"latitude":"-12.072039","longitude":"-77.011571","name":"AV. MEXICO CON: AV. AVIACION"},{"latitude":"-12.07027","longitude":"-77.009097","name":"AV. MEXICO CON: AV. SAN PABLO"},{"latitude":"-12.068171","longitude":"-77.00625","name":"AV. MEXICO CON: AV. SAN LUIS"},{"latitude":"-12.066129","longitude":"-77.004651","name":"AV. MEXICO CON: PSJE. LOS MOCHICAS"},{"latitude":"-12.063298","longitude":"-77.002626","name":"AV. MEXICO CON: AV. EL FLORAL"},{"latitude":"-12.062513","longitude":"-77.003635","name":"AV. NICOLAS AYLLON CON: PSJ. JUAN CARBONE"}],[{"latitude":"-12.062513","longitude":"-77.003635","name":"AV. NICOLAS AYLLON CON: PSJ. JUAN CARBONE"},{"latitude":"-12.062998","longitude":"-77.002031","name":"AV. NICOLAS AYLLON CON: AV. MEXICO"},{"latitude":"-12.064676","longitude":"-76.999215","name":"AV. CIRCUNVALACION CON: CA. PABLO RISSO"},{"latitude":"-12.066456","longitude":"-76.997846","name":"AV. CIRCUNVALACION CON: CA. LORENZO ASTRANA"},{"latitude":"-12.07034","longitude":"-76.994873","name":"AV. CIRCUNVALACION CON: JR. MANUEL BEINGOLEA"},{"latitude":"-12.073216","longitude":"-76.992731","name":"AV. CIRCUNVALACION CON: JR. HORACIO PATINO CRUZATI"},{"latitude":"-12.077501","longitude":"-76.989302","name":"AV. CIRCUNVALACION CON: AV. CANADA"},{"latitude":"-12.081377","longitude":"-76.986285","name":"AV. CIRCUNVALACION CON: CA. VICTOR VELEZ MORRO"},{"latitude":"-12.084419","longitude":"-76.98426","name":"AV. CIRCUNVALACION CON: AV. JAVIER PRADO ESTE"},{"latitude":"-12.0851929","longitude":"-76.9838118","name":"AV. CIRCUNVALACION CON: AV. JAVIER PRADO ESTE"},{"latitude":"-12.088478","longitude":"-76.981553","name":"Calle 7"},{"latitude":"-12.093054","longitude":"-76.980749","name":"AV. PANAMERICANA SUR CON: CA. CHARLES READE"},{"latitude":"-12.099286","longitude":"-76.979889","name":"AV. PANAMERICANA SUR CON: AV. EL DERVY"},{"latitude":"-12.102774","longitude":"-76.979413","name":"AV. PANAMERICANA SUR CON: AV. BIELOVUCIC CAVALIER"},{"latitude":"-12.109004","longitude":"-76.978578","name":"AV. PANAMERICANA SUR CON: INTERCAMBIO VIAL PRIMAVERA"}]]}}];
	var markersBloqueados = false;
	var map = null;
	var bounds = null;
	var polilineas = [];

	function initMap() {
		 map = new google.maps.Map(document.getElementById('map'), {
			zoom: 16,
			center: {lat: -12.0900186, lng: -77.0665256},
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
				<a class="navbar-brand" href="#" style="margin: 0px;padding: 15px 10px; height: 45px;" onclick="volverListado();">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Volver
				</a>
			</div>
		</div>
	</nav>
	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="collapseListGroupHeading1">
			<h4 class="panel-title">
				<a href="#collapseListGroup1" class="" role="button" data-toggle="collapse" aria-expanded="true" aria-controls="collapseListGroup1">
					Ruta <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
				</a>
			</h4>
		</div>
		<div class="panel-collapse collapse in" role="tabpanel" id="collapseListGroup1" aria-labelledby="collapseListGroupHeading1" aria-expanded="true">
			<!--<ul class="list-group" id="lista-paraderos"  style="overflow-y: auto; max-height:300px; min-width:400px;">
			</ul>-->
			<div style="overflow-y: auto; max-height:300px; min-width:400px;">
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