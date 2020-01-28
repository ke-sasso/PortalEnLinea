
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA70xe6_CTcqLBkS4CBgpXP_N8RWbvyjCk" type="text/javascript"></script>-->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIyroCWk-krJpmPelqtAUSXHBHVtpvRS8" type="text/javascript"></script>
  {!! Html::script('plugins/gmaps/gmaps.min.js') !!}
  <style type="text/css">
    #map {
      width: 100%;
      height: 400px;
    }
  </style>
</head>
<body>
  <div id="map"></div>
  <script>
    var map;

    var map = new GMaps({
      el: '#map',
      disableDefaultUI: true,
      zoom: 10,
      lat: 13.70127704353276,
      lng: -89.22430920605467,    
      panControl: true,
      zoomControl: true,
      zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.SMALL
                      },
      mapTypeControl: false,
      scaleControl: false,
      streetViewControl: false,
      overviewMapControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP, 
      fullscreenControl: true   
    });
    var icon = 'https://www.google.com/maps/vt/icon/name=assets/icons/poi/quantum/container_background-2-medium.png,assets/icons/poi/quantum/container-2-medium.png,assets/icons/poi/quantum/pharmacy-2-medium.png&highlight=ffffff,db4437,ffffff&color=ff000000?scale=1';

    <?php 
      if(isset($farmacias))
      {
        foreach ($farmacias as $key => $value) {
            echo "map.addMarker({
          lat: ".$value['lat'].",
          lng: ".$value['lng'].",
          draggable: ".$value['draggable'].",
          icon : {
                size : new google.maps.Size(32, 32),
                url : icon
              },
          infoWindow: {
            content: '".$value['info']."'
          },              
          animation: google.maps.Animation.DROP,
          dragend: function(e) {
            $('#pointX').val(e.latLng.lat());
            $('#pointY').val(e.latLng.lng());
            map.setCenter(e.latLng.lat(), e.latLng.lng());
          }
        });\n";
        }
      }
    ?>
  </script>
</body>
</html>