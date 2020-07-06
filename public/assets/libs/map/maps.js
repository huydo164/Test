  function initialize() { 
	var myLatlng = new google.maps.LatLng(21.02448, 105.80147);
    var myOptions = {
      zoom: 16,
      center: myLatlng,
      scrollwheel: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
	
    var map = new google.maps.Map(document.getElementById("mapCanvas"), myOptions);

    var contentString = '89/63 ngõ 1194 đường Láng - Đống Đa - Hà Nội<br>'
						+'Điện thoại: 0986010101';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: 'Natame.vn'
    });
    google.maps.event.addListener(marker, 'click', function() {
      infowindow.open(map,marker);
    });
  }

google.maps.event.addDomListener(window, 'load', initialize);