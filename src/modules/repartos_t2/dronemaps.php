<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	} else {
	$bootstrapjs =  1;	
	$datatablesjs = 1;
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	if (isset($_GET['id'],$_GET['fecha'],$_GET['vj'])){
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html { height: 100% }
  body { 
 height: 100%; 
 margin: 0; 
 padding: 0 ;  
 color: Gray ;
 background-color: WhiteSmoke; 
  }
  #map_canvas { height: 100%}

.mcquicklink {
 margin-left: 20px;
 margin-right: 20px;
 display: inline;
 font-family: sans-serif;
 float: right;
}
.mccategory {
 margin-left: 20px;
 margin-right: 20px;
 display: inline;
 font-family: sans-serif;
 float: right;
}
.mcheading {
 display: inline;
 font-family: sans-serif;
}
</style>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhczrZ1XL_KbEoHlAd9z1cm0N3l-JPrCg&sensor=false"></script>
<!--<script src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">

function mcpherDataPopulate() {
var mcpherData = {   
    "framework":{ "control": { "zoomlevelonselect":13, "resizeboundingbox": true}}, 
    "cJobject":[ 
<?php 
		  $dbres=Db::conectar();
		  $sqlres ="SELECT * FROM `t77_rs` WHERE centro=:centro AND Fecha=:Fecha AND Ruta=:Ruta AND Viaje=:Viaje";
          $selectres=$dbres->prepare($sqlres);
		  $selectres->bindValue('centro',$idcentro);
		  $selectres->bindValue('Fecha',$_GET['fecha']);
		  $selectres->bindValue('Ruta',$_GET['id']);
		  $selectres->bindValue('Viaje',$_GET['vj']);
		  $selectres->execute();
          while ($rowres=$selectres->fetch()) {	  
	$lng = str_replace(",",".",$rowres['Longitud']);
	$lat = str_replace(",",".",$rowres['Latitud']);
	$cliente = $rowres['Cliente'];
	$direccion = $rowres['Direccion'];
    $Sec = $rowres['Sec1'];
    $zona = $rowres['ZNPVTA'];
	$viaje = $rowres['Viaje'];
    $ruta = $rowres['Ruta'];
	$codigo = $rowres['Codigo'];
	$abre = substr($rowres['Abre'],0,5);
	$cierra = substr($rowres['Cierra'],0,5);
	$entrega = $rowres['Entrega'];
	$recojo = $rowres['Recojo'];
    if(($abre.$cierra)=='00:0023:59'){ $stylecolor='053674'; } else { $stylecolor='E02124'; }	
?>
{
"title":<?php echo "'".$Sec.'|'.$cliente."'";?>,
"content": <?php echo '"<b>'.$codigo.'</b>'.$cliente.'<BR><b>Direccion:</b>'.$direccion.'<BR><b>Ventana Horaria: </b>'.$abre.'<b>  - </b>'.$cierra.'<BR><b>Entrega: </b>'.$entrega.'<b>   Recojo: </b>'.$recojo.'"'; ?>,
"lat": <?php echo "$lat";?>,
"lng": <?php echo "$lng";?>,
"color":"red",
"size":"0",
"category":[ {
"Reparto":<?php echo "'".$ruta."'";?>},{
"Viaje":<?php echo "'".$viaje."'";?>},{
"Empresa":""},{
"zona":<?php echo "'".$zona."'";?>}],
"image":<?php echo "'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=".$Sec."|".$stylecolor."|ffffff'";?>,"startcategory":""
}
,
<?php 
		  }
		  Db::desconectar();
		  
?>
  ]};
  
return mcpherData; };

           function initialize() {
                mcpherData = mcpherDataPopulate();

                if (mcpherData.cJobject.length > 0) {
                    mcpherData.cJobject.sort(function(a, b) {
                        return a.title.toLowerCase() < b.title.toLowerCase() ? -1 : (a.title.toLowerCase() > b.title.toLowerCase() ? 1 : 0);
                    });

                    var myOptions = {
                        center: new google.maps.LatLng(mcpherData.cJobject[0].lat, mcpherData.cJobject[0].lng),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    // get parameters if any
                    var qparams = mcpherGetqparams();

                    var cj = mcpherData.cJobject;
                    var bounds = new google.maps.LatLngBounds();
                    for (var i = 0; i < cj.length; i++) {
                        bounds.extend(new google.maps.LatLng(cj[i].lat, cj[i].lng));
                    }

                    if (!qparams['zoom']) qparams['zoom'] = 2;
                    myOptions['zoom'] = parseInt(qparams['zoom']);
                    // create the map
                    gMap = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                    gMap.fitBounds(bounds);

                    // add the excel data
                    for (var i = 0; i < cj.length; i++) {
                        mcpherAddMarker(cj[i]);
                    }
                   
                    // Set up combox
                    
                    category();
                    quickLink(-1);
                    makeFlightGroups();
                    initialCategory();
                }
            }

// flightpath stuff
                    function toType(obj) {
                        return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
                    }

                    function isArray(arg) {
                        return toType(arg) == 'array';
                    }

                    function createFlightGroups() {
                        var flightGroups = [];
                        var cj = mcpherData.cJobject;
                        var fg;
                        for (var i = 0; i < cj.length; i++) {
                            if (fg = cj[i].flightGroup) {
                                if (flightGroups.indexOf(fg) == -1) {
                                    flightGroups.push(fg);
                                }
                                cj[i].flightGroupIndex = flightGroups.indexOf(fg);
                            } else cj[i].flightGroupIndex = -1;
                        }
                        return flightGroups;
                    }

                    function initialCategory() {
                        // sets initial category using the first select element
                        var cj = mcpherData.cJobject;
                        var c, p;
                        var combos = getAllCombos();
                        if (combos.length > 0) {
                            var cats = createCategories(combos[0]);
                            for (var i = 0; i < cj.length; i++) {
                                if (c = cj[i].startCategory) {
                                    if ((p = cats.indexOf(c)) != -1) {
                                        var elem = document.getElementById('selectcategory0');
                                        elem.value = p;
                                        dealWithCategories(elem);
                                        return p;
                                    }
                                }
                            }
                        }
                        return -1;
                    }

                    // this is because IE doesnt have indexOf...
                    if (!Array.indexOf) {
                        Array.prototype.indexOf = function(obj, start) {
                            for (var i = (start || 0); i < this.length; i++) {
                                if (this[i] == obj) {
                                    return i;
                                }
                            }
                            return -1;
                        }
                    }
                    function resetBoundingBox(force) {
                        var z = mcpherData.framework && mcpherData.framework.control 
                                    && mcpherData.framework.control.resizeboundingbox ? 
                                        mcpherData.framework.control.resizeboundingbox : false;
                        if((z || force) && !vMap) {
                            // dont do for vizmap apps - reset bounding box to visible items
                            var cj = mcpherData.cJobject;
                            var bounds = new google.maps.LatLngBounds();
                            for (var i = 0; i < cj.length; i++) {
                                if (cj[i].marker.visible)bounds.extend(new google.maps.LatLng(cj[i].lat, cj[i].lng));
                             }
                            gMap.fitBounds(bounds);                            
                        } 
                    }

                    function makeFlightGroups() {
                        var flightGroups = createFlightGroups();
                        var cj = mcpherData.cJobject;

                        for (var j = 0; j < flightGroups.length; j++) {
                            var flightCoords = [];
                            var color = "#FF0000";
                            for (var i = 0; i < cj.length; i++) {
                                if (j == cj[i].flightGroupIndex) {
                                    flightCoords.push(cj[i].marker.position);
                                    if (cj[i].flightColor) color = cj[i].flightColor;
                                }
                            }
                            if (flightCoords.length) {
                                flightGroups[j].flightPath = new google.maps.Polyline({
                                    path: flightCoords,
                                    strokeColor: color,
                                    strokeOpacity: 1.0,
                                    strokeWeight: 2,
                                    map: gMap
                                });
                            }
                        }
                        return flightGroups;
                    }

                    function mcpherAddMarker(cj) {
                        var p = new google.maps.LatLng(cj.lat, cj.lng);
                        cj.circle = null;
                        if (cj.size) {
                            var circle = {
                                strokeColor: cj.color,
                                strokeOpacity: 0.8,
                                strokeWeight: 1,
                                fillColor: cj.color,
                                fillOpacity: 0.20,
                                map: gMap,
                                center: p,
                                radius: parseFloat(cj.size),
                            };
                            var drawCirle = new google.maps.Circle(circle);
                            cj.circle = drawCirle;

                        }

                        var marker = new google.maps.Marker({
                            position: p,
                            map: gMap,
                            title: cj.title
                        });
                        cj.marker = marker;
                        cj.infowindow = null;
                        if (cj.image) marker.setIcon(cj.image);
                        if (cj.content) {
                            cj.infowindow = new google.maps.InfoWindow({
                                content: cj.content
                            });
                            google.maps.event.addListener(cj.marker, 'click', function() {
                                cj.infowindow.open(gMap, cj.marker);
                                adjustZoom(cj);
                            });
                        }

                        return cj.marker;
                    }
                    function adjustZoom(cj){
                        var z = mcpherData.framework && mcpherData.framework.control 
                                    && mcpherData.framework.control.zoomlevelonselect ? 
                                        parseInt(mcpherData.framework.control.zoomlevelonselect) : null;
                        if (z) {
                            if (gMap.getZoom() == z) {
                                resetBoundingBox(true);
                            }
                            else {
                                gMap.setZoom(18);
                                gMap.setCenter(cj.marker.getPosition());
                            }
                        }
                        
                    }
                    function mcpherGetqparams() {
                        var qparams = new Array();
                        var htmlquery = window.location.search.substring(1);
                        var htmlparams = htmlquery.split('&');
                        for (var i = 0; i < htmlparams.length; i++) {
                            var k = htmlparams[i].indexOf('=');
                            if (k > 0) qparams[htmlparams[i].substring(0, k)] = decodeURI(htmlparams[i].substring(k + 1));
                            return qparams;
                        }
                    }


                    function dealWithQuickLink(selValue) {
                        if (vMap) {
                            var nextSpot = vMap.spots[selValue]; 
                            vMap.gotoAnotherSpot(vMap.currentSpot,nextSpot);
                            if (vMap.provider=='maps') nextSpot.createInfoWindow(0);
                        }
                        else {
                            var cj = mcpherData.cJobject[selValue];
                            if (cj.infowindow) {
                                cj.infowindow.open(gMap, cj.marker);
                            }
                            adjustZoom(cj);
                            return cj.infowindow;
                        }
                    }
                    function findMySpot(cj) {
                        // return the spot to which this cj belongs
                        if(vMap) {
                            for (var i=0; i < vMap.spots.length ; i++ ) {
                                if ( cj.SpotID === vMap.spots[i].spotId) return (vMap.spots[i]);
                            }
                            return null;
                        }
                        else
                            return cj;
                        
                    }

                   
  function dealWithCategories() {
                        var combos = getAllCombos();
                        // start by not showing anything, except where theres no categories
                        var cj = mcpherData.cJobject;
                        for (var j = 0; j < cj.length; j++) {
                            var mySpot = findMySpot(cj[j]);
                            mySpot.marker.setVisible(combos.length == 0);
                            if (mySpot.circle) {
                                    mySpot.circle.setVisible(combos.length == 0);
                                }
                        }
                        // need appear in each category

                        for (var j = 0; j < cj.length  && combos.length > 0; j++) {
                            var show = [];
                            for (var i = 0; i < combos.length ; i++) {
                                var selElem = document.getElementById('selectcategory' + i);
                                if (selElem.value != 0) {
                                    // we have a filter operating
                                    var cats = createCategories(combos[i]);
                                    var target = cats[selElem.value];
                                    if (vMap) {
                                        var c = cj[j];
                                        if (c.hasOwnProperty(combos[i])) {
                                            if(c[combos[i]] == target) show[i]=true ;
                                        }
                                    }
                                    else {
                                        for (var k = 0; k < cj[j].category.length ; k++) {
                                            for (m in cj[j].category[k]) {
                                                if (m == combos[i]) {
                                                    if(cj[j].category[k][m] == target)show[i]=true;
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    show[i]= true;
                                }
                            }
                            // show it?
                            var x=0;
                            for (var k=0; k < combos.length; k++ ) {
                                if (show[k] === true) x++;
                            }
                            if (x == combos.length) {
                                var mySpot = findMySpot(cj[j]);
                                mySpot.marker.setVisible(true);
                            
                                if (mySpot.circle) {
                                    mySpot.circle.setVisible(true);
                                }
                            }
                        }
                        // reset to show only filtered spots
                        quickLink();

                    }
                    
                function quickLink(selCategory) {
                        var comboElem = document.getElementById('comboquicklink');

                        if (comboElem) {
                            if (selCategory == -1) { //first time in
                                var selElem = document.createElement('select');
                                selElem.id = "quickLinks";
                            } else {
                                var selElem = document.getElementById('quickLinks');
                                selElem.options.length = 0;
                            }
                            // depends on type of app
                            var cj = vMap ? vMap.spots :  mcpherData.cJobject ;

                            mcpherAddEvent(selElem, "change", function() {
                                dealWithQuickLink(selElem.value);
                            }, false, true);

                            for (var i = 0; i < cj.length; i++) {
                                // only show visible spots
                                var workit = true;
                                if (cj[i].marker) workit = cj[i].marker.visible;
                                if (workit) { 
                                    var o = document.createElement('option');
                                    o.text = cj[i].title;
                                    o.value = i;
                                    selElem.value = o.value;
                                    try {
                                        selElem.add(o, null);
                                    } catch (error) {
                                        selElem.add(o);
                                    }
                                }
                            }

                            comboElem.appendChild(selElem);
                        }
                        resetBoundingBox();
                    }

                    function createCategories(categoryName) {
                        var cats = [];
                        var cj = mcpherData.cJobject;
                        for (var i = 0; i < cj.length; i++) {
                            // find the matching object
                            if (vMap) {
                                if(cj[i].hasOwnProperty(categoryName)) {
                                    if (cats.indexOf(cj[i][categoryName]) == -1) cats.push(cj[i][categoryName]);
                                }
                            } 
                            else {
                                for (var j = 0; j < cj[i].category.length; j++) {
                                    if (cj[i].category[j].hasOwnProperty(categoryName)) {
                                        if (cats.indexOf(cj[i].category[j][categoryName]) == -1) cats.push(cj[i].category[j][categoryName]);
                                    }
                                }
                            
                            }

                        }
                        cats.sort().splice(0, 0, 'TODOS');
                        return cats;
                    }



                    function category() {

                        var comboElem = document.getElementById('combocategory');
                        if (comboElem) {
                            var combos = getAllCombos();
                            for (var j = 0; j < combos.length; j++) {
                                var selElem = document.createElement('select');
                                selElem.id = "selectcategory" + j;
                                var cats = createCategories(combos[j]);
                                mcpherAddEvent(selElem, "change", function() {
                                    dealWithCategories(selElem);
                                }, false, true);

                                for (var i = 0; i < cats.length; i++) {
                                    var o = document.createElement('option');
                                    o.text = cats[i];
                                    o.value = i;
                                    try {
                                        selElem.add(o, null);
                                    } catch (error) {
                                        selElem.add(o);
                                    }
                                }
                                var d = document.createElement('span');
                                var t = document.createTextNode(combos[j]);
                                d.appendChild(t);
                                d.appendChild(selElem);
                                comboElem.appendChild(d);

                            }
                        }
                    }

                    function getAllCombos() {
                        var combos = [];
                        if (vMap) {
                            for (var i = 0; vMap.framework.spots.categories && i < vMap.framework.spots.categories.length; i++)
                                combos.push(vMap.framework.spots.categories[i]);
                        }
                        else {
                            var cj = mcpherData.cJobject;
                            for (var i = 0; i < cj.length; i++) {
                                if (isArray(cj[i].category)) {
                                    for (var j = 0; j < cj[i].category.length; j++) {
                                        for (k in cj[i].category[j])
                                        if (combos.indexOf(k) == -1) {
                                            combos.push(k);
                                        }
                                    }
                                }
                            }
                        }
                        return combos;
                    }


            function mcpherAddEvent(o, e, f, b, complain) {
                // because IE is different
                if (o.addEventListener) return (o.addEventListener(e, f, b));

                else if (o.attachEvent) return (o.attachEvent('on' + e, f));
                else if (complain) alert('browser doesnt support events');

                return (null);

            }
			
            var mcpherData;
            var gMap;
            var vMap;
        </script>
    </head>
<body onload="initialize()">
 <div id="comboquicklink" class = "mcquicklink">CLIENTES</div>
 <div id="combocategory" class = "mccategory"></div>
 <div id="heading" class ="mcheading"></div>
 <div id="map_canvas" style="width: 100%; height: 98%"></div>
</body>
	<?php } else { echo "no hay variables"; }  }?>