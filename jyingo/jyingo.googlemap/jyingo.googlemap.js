jyingo.extend('googlemap', function(self, settings, shared) {
	
	var _map;
	var _markers = [];
	var _color;
	
	function fitMap()
	{
		_map.fitBounds(_markers.reduce(function(bounds, marker) {
    		return bounds.extend(marker.getPosition());
			}, new google.maps.LatLngBounds()));
	}

  function addMarker(position)
  {
  
   var loc = new  google.maps.LatLng(position.lat, position.lng)
   var marker = null;
   if (_color)
   {
   	
 		  var pinImage = new google.maps.MarkerImage("//chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + _color,
		        new google.maps.Size(21, 34),
		        new google.maps.Point(0,0),
		        new google.maps.Point(10, 34));
		  var pinShadow = new google.maps.MarkerImage("//chart.apis.google.com/chart?chst=d_map_pin_shadow",
		        new google.maps.Size(40, 37),
		        new google.maps.Point(0, 0),
		        new google.maps.Point(12, 35));
	
	      	
	    marker = new google.maps.Marker({
	            map: _map,
	            icon: pinImage,
	            shadow: pinShadow,
	            position: position
	        });
	        
	        	
   	
   } else {
   	
    marker = new google.maps.Marker({
            map: _map,
            position: loc
        });
        	
   }
   
  
    if (position.addr)
    {
    	
       
        var infowindow = new google.maps.InfoWindow({
			      content: position.addr
			  });
			  

         google.maps.event.addListener(marker, 'click', function() {
			    infowindow.open(_map,marker);
			  });
			  	
    }
  
    _markers.push(marker);
  
  }     
	self.proto({
		
		initialize : function(params)
		{
			 _color = params.color;
			 var markers = params.markers;
			 if (markers && markers.length)
			  setTimeout(this.create_delegate(this.init), 0, markers);

		},
		
		init : function(markers)
		{
			 var mapOptions = {
          zoom: 7
       };
          			
			 _map = new google.maps.Map(this.get_element(),
            mapOptions);
            
			  for (var i = 0; i < markers.length; i++)
			   addMarker(markers[i]);

			
			fitMap();			
			
		}
		
		
	});
	
	
	return new self();
});