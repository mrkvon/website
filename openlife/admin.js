(function($,ol){
  var map = new ol.Map({
    view: new ol.View({
      center: [0,0],
      zoom: 1
    }),
    layers: [
      new ol.layer.Tile({
	source: new ol.source.OSM()
      })
    ],
    target: 'map'
  });

  var geolocation = new ol.Geolocation();
  //console.log(geolocation);
  geolocation.setTracking(true);
  // take the projection to use from the map's view
  //console.log(map.getView());
  geolocation.bindTo('projection', map.getView());
  
  $('#map').on('click',function(e){
    var click_position=ol.proj.transform(map.getEventCoordinate(e),'EPSG:3857','EPSG:4326');
    
    var pointlines=$('div.spacetime_point');
    //console.log(pointlines.length);
    for(var i=0,len=pointlines.length;i<len;i++){
      var ptln=$(pointlines[i]);
      //console.log(ptln);
      if(ptln.children('input.st_active').is(':checked')){
	//console.log(i);
	ptln.children('input.st_latitude').val(click_position[1]);
	ptln.children('input.st_longitude').val(click_position[0]);
	ptln.children('input.st_precision').val(null);
	ptln.children('input.st_time').val(null);
      }
    }
  });

  $('#location_now_button').on('mouseup',function(e){
    geolocation.setTracking(true);
    
    var position_=geolocation.getPosition();
    //alert(position);
    var position=ol.proj.transform(position_,'EPSG:3857','EPSG:4326');
    var precision=geolocation.getAccuracy();
    
    map.setView(new ol.View({
      center: position_,
      zoom: 16
    }));
    
    var now = new Date(); 
    var now_utc = (("0000"+now.getUTCFullYear()).slice(-4))+(("00"+(1+parseInt(now.getUTCMonth()))).slice(-2))+
    (("00"+now.getUTCDate()).slice(-2))+(("00"+now.getUTCHours()).slice(-2))+(("00"+now.getUTCMinutes()).slice(-2))+
    (("00"+now.getUTCSeconds()).slice(-2));
    
    var pointlines=$('div.spacetime_point');
    //console.log(pointlines.length);
    for(var i=0,len=pointlines.length;i<len;i++){
      var ptln=$(pointlines[i]);
      //console.log(ptln);
      if(ptln.children('input.st_active').is(':checked')){
	//console.log(i);
	ptln.children('input.st_latitude').val(position[1]);
	ptln.children('input.st_longitude').val(position[0]);
	ptln.children('input.st_precision').val(precision);
	ptln.children('input.st_time').val(now_utc);
      }
    }
    
//     $('#location_time').val(now_utc);
//     
//     $('#location_latitude').val(position[0]);
//     $('#location_longitude').val(position[1]);
//     $('#location_precision').val(precision);
    geolocation.setTracking(false);
    geolocation.setTracking(true);
  });
  
  
  
  
  /*********add a spacetime line to form************/
  $('#add_st_point').on('mouseup',function(e){
    var main=$(document.createElement('div'))
      .addClass('spacetime_point')
      .appendTo($('#st_points'));
    var check=$(document.createElement('input'))
      .addClass('st_active')
      .attr({type:'checkbox'}).appendTo(main);
    var lat=$(document.createElement('input'))
      .attr({type:'text',name:'latitude[]',placeholder:'latitude'})
      .addClass('st_latitude')
      .appendTo(main);
    var lon=$(document.createElement('input'))
      .attr({type:'text',name:'longitude[]', placeholder:'longitude'})
      .addClass('st_longitude')
      .appendTo(main);
    var pre=$(document.createElement('input')).attr({type:'text', name:'precision[]',placeholder:'precision [m]'})
      .addClass('st_precision')
      .appendTo(main);
    var time=$(document.createElement('input'))
      .attr({type:'text',name:'time[]',placeholder:'time (yyyymmddhhiiss)'})
      .addClass('st_time')
      .appendTo(main);
    var com=$(document.createElement('input')).attr({type:'text',name:'comment[]',placeholder:'comment'})
      .appendTo(main);
    var rem=$(document.createElement('button')).attr({type:'button'})
      .appendTo(main).append(document.createTextNode('-'));
    (function(rem){
      rem.on('mouseup',function(){rem.parent('div').remove();});
    })(rem);
  });
  
//   <div>
// <input type="checkbox">done
// <input id="spacetime_latitude" type="text" name="latitude[]" placeholder="latitude" />
// <input id="spacetime_longitude" type="text" name="longitude[]" placeholder="longitude" />
// <input id="spacetime_precision" type="text" name="precision[]" placeholder="precision [m]" />
// <input type="text" name="time[]" placeholder="time (yyyymmddhhiiss)" />
// <input type="text" name="comment[]" placeholder="comment" />
// <button id="add_st_point" type="button">+</button>
// <input type="submit" name="spacetime_add" value="add" />
// </div>

})($,ol);
