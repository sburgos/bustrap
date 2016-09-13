<select class="empresa">
        <option>Seleccione una empresa</option>
    <?php foreach($lines as $line): ?>
        <option value="<?= $line['id'] ?>"><?= $line['name'] ?></option>
    <?php endforeach; ?>
</select>
<div id="map" style="height: 90%; width: 90%"></div>
<script>

        // This example creates a 2-pixel-wide red polyline showing the path of William
        // Kingsford Smith's first trans-Pacific flight between Oakland, CA, and
        // Brisbane, Australia.

        function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 12,
                        center: {lat: -12.0900186, lng: -77.0665256},
                        mapTypeId: google.maps.MapTypeId.TERRAIN
                });

                var flightPlanCoordinates = <?= json_encode($json) ?>;
                var flightPath = new google.maps.Polyline({
                        path: flightPlanCoordinates,
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                });

                flightPath.setMap(map);
        }

</script>
<?php
$this->registerJS('$(\'.empresa\').change(function(){location.href="?id="+$(this).val()});');
?>