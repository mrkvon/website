(function($,ol){
  //alert('script works');
  (function(){
    //console.log($('tr'));
    //console.log($('tr').children('th,td'));
    $('tr').children('th,td').on('mouseup',function(e){
      e.stopPropagation();
      //console.log('mouseover');
      $('div.hint').remove();
      var body=$('body');
      var div=$(document.createElement('div'))
      .css({position:"absolute",left:e.pageX,top:e.pageY,'min-width':'50px','background-color':'#dfe',
	   border:'1px solid black','padding':'4px'})
      .addClass('hint')
      .html($(this).html())
      .appendTo(body);
      $(document).on('keyup',function(e) {
	if (e.keyCode == 27) {div.remove();}
      });
      div.on('mouseup',function(e){e.stopPropagation();div.remove();})
    });
  })();
  
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
  
  if($('a.sub_active').html()=="spacetime.activity"){
    console.log('works');
    $.ajax('dbpoints.php',{data:"",type:'POST',async:true,success:function(backpack){
      console.log(backpack);
      var features=[];
      for(var i=0, len=backpack.length;i<len;i++){
	
	
	var point0=[parseFloat(backpack[i].longitude),parseFloat(backpack[i].latitude)];
	//console.log(point0);
	//var point1=[backpack[i].latitude,backpack[i].longitude];
	
	var point=ol.proj.transform(point0,'EPSG:4326','EPSG:3857');
	//var point01=ol.proj.transform(point1,'EPSG:4326','EPSG:3857');
	
	//console.log(point);
	var feature=new ol.Feature();
	feature.setGeometry(new ol.geom.Point(point));
	features.push(feature);
	
      }
      var featuresOverlay = new ol.FeatureOverlay({
	map: map,
	features: features
      });
    }});
  }
  

  
  
})($,ol);
