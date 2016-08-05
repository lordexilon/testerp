<?php
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
?>
<html>
<head>
   <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title></title>
  
  
  
  <link rel="stylesheet" href="css/jquery.mobile-1.3.2.min.css">
    <link rel="stylesheet" href="css/codiqa.ext.css">
	<link rel="stylesheet" href="themes/mi_css.min.css" />
  <!-- Extra Codiqa features -->

  
  <!-- jQuery and jQuery Mobile -->
  <script src="js/jquery-2.0.3.js"></script>
  <script src="js/jquery.mobile-1.3.2.js"></script>

  <!-- Extra Codiqa features -->
  <script src="js/codiqa.ext.js"></script>
<script>
var currentplace;
function loadplace(place)
{
currentplace=place;
$.mobile.changePage($('#page2'));
document.getElementById('num_table').innerHTML = place;
gettotal(place);
} 

function loadfullplace()
{
document.getElementById('num_table2').innerHTML = currentplace;
$.ajax({
  type: "POST",
  url: "data.php",
  data: { place: currentplace, action: "gettotal" }
})
  .done(function( msg ) {
    document.getElementById('total_ttc2').innerHTML = msg;
		$.ajax({
		type: "POST",
		url: "data.php",
		data: { place: currentplace, action: "getlines" }
		})
		.done(function( msg ) {
			$( "#fullplace" ).html( msg );
			$('#fullplace').listview('refresh');
		});
  });
}

function gettotal(place)
{
$.ajax({
  type: "POST",
  url: "data.php",
  data: { place: place, action: "gettotal" }
})
  .done(function( msg ) {
    document.getElementById('total_ttc').innerHTML = msg;
  });
}


function sendbar(id)
{
$( "#p"+id ).addClass("ui-btn-active");
$.ajax({
  type: "POST",
  url: "../frontend/loadticket.php",
  data: { idc: id, action: "addline", place: currentplace }
})
  .done(function( msg ) {
    gettotal(currentplace);
	$( "#p"+id ).removeClass("ui-btn-active");
  });
} 

function sendbar(id)
{
$( "#p"+id ).addClass("ui-btn-active");
$.ajax({
  type: "POST",
  url: "../frontend/loadticket.php",
  data: { id: id, action: "addline", place: currentplace }
})
  .done(function( msg ) {
    gettotal(currentplace);
	$( "#p"+id ).removeClass("ui-btn-active");
  });
} 

function showproduct(id, qty, price)
{
$( "#idline").val(id);
$( "#price").val(price);
$( "#qty").val(qty);
}

function deleteline()
{
idline=$( "#idline").val();
$( "#popupLogin" ).popup( "close" );
$.ajax({
  type: "POST",
  url: "../frontend/loadticket.php",
  data: { id: idline, action: "deleteline", place: currentplace }
})
  .done(function() {
    loadfullplace();
  });

}

function saveline()
{
idline=$( "#idline").val();
price=$( "#price").val();
qty=$( "#qty").val();
$( "#popupLogin" ).popup( "close" );
$.ajax({
  type: "POST",
  url: "../frontend/loadticket.php",
  data: { id: idline, action: "q", number:qty, place: currentplace }
})
  .done(function( msg ) {
		$.ajax({
		type: "POST",
		url: "../frontend/loadticket.php",
		data: { id: idline, action: "p", number:price, place: currentplace }
		})
		.done(function( msg ) {
			loadfullplace();
		});
  });
}


function addprint() {
$.mobile.changePage($('#page1'));
$.post("../addprint.php", { addprint: "K"+currentplace } );
}

</script>
</head>
<body>
<!-- Home -->
<div data-role="page" id="page1" data-theme="d">
    <div data-role="content" data-theme="d">
        <ul data-role="listview" data-divider-theme="d" data-inset="false">
		<?php
		$sql="SELECT name from ".MAIN_DB_PREFIX."pos_places";
		$resql = $db->query($sql);
		while($row = $db->fetch_array ($resql))
			{
			?>
		    <li data-theme="d">
                <a href="" onClick="loadplace('<?php echo $row[0]; ?>');">
                    Mesa <?php echo $row[0]; ?></a>
            </li>
		<?php } ?>	
        </ul>
    </div>
</div>
<div data-role="page" data-theme="d" id="page2">
    
	<a href="#page3" onclick="loadfullplace();">
	<div data-theme="d" data-role="header">
	<span class="ui-title">Mesa: <span id=num_table></span> Precio: <span id="total_ttc"></span></span>
    </div>
	</a>
      
	  <div data-role="content"  data-theme="d">
        <div data-role="collapsible-set" data-theme="d" data-inset="false">
            
                <?php
				$sql= "SELECT * FROM ".MAIN_DB_PREFIX."categorie";
				$resql = $db->query($sql);
				while($row = $db->fetch_array ($resql)) {
				echo '<div data-role="collapsible"><h3>'.$row['label'].'</h3>';
				
				echo '<ul data-role="listview" data-inset="false">';
				$sql= "SELECT * FROM ".MAIN_DB_PREFIX."product, ".MAIN_DB_PREFIX."categorie_product where fk_product=rowid and fk_categorie=".$row['rowid'];
				$resql2 = $db->query($sql);
				while($row2 = $db->fetch_array ($resql2)) {
				echo '<li><a onclick="sendbar('.$row2[0].');" id="p'.$row2[0].'">'.$row2['label'].'</a></li>';
				}
				echo '</ul>';
				
				echo '</div>';
				}
				
				
				?>
        </div>
    
   
        
    </div>
    <div data-theme="c" data-role="footer" data-position="fixed">
          <div data-theme="c" data-role="navbar" data-iconpos="top">
            <ul>
                <li>
                    <a href="#page1" data-theme="c" data-transition="fade" data-icon="grid">
                        Mesas
                    </a>
                </li>
                <li>
                    <a onclick="addprint();" data-theme="c" data-transition="fade" data-icon="edit">
                        Pedir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div data-role="page" data-theme="d" id="page3">
	<a>
	<div data-theme="d" data-role="header">
	<span class="ui-title">Mesa: <span id=num_table2></span> Precio: <span id="total_ttc2"></span></span>
    </div>
	</a>
      
	<div data-role="content"  data-theme="d">
  
	<ul data-role="listview" id="fullplace">

	</ul>   
	
	
	<div data-role="popup" id="popupLogin" data-theme="a" class="ui-corner-all">
    <form>
        <div style="padding:10px 20px;">
			<center>Precio</center>
            <input type="text" name="price" id="price" value="" data-theme="a">
			<center>Cantidad</center>
            <input type="text" name="qty" id="qty" value="" data-theme="a">
			<input type="hidden" name="idline" id="idline" value="">
            <input type="button" onclick="saveline();" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check" value="Guardar">
			<input type="button" onclick="deleteline();" class="ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check" value="Borrar">
        </div>
    </form>
	</div>
	
	
        
    </div>
    <div data-theme="c" data-role="footer" data-position="fixed">
          <div data-theme="c" data-role="navbar" data-iconpos="top">
            <ul>
                <li>
                    <a href="#page1" data-theme="c" data-transition="fade" data-icon="grid">
                        Mesas
                    </a>
                </li>
                <li>
                    <a onclick="addprint();" data-theme="c" data-transition="fade" data-icon="edit">
                        Pedir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>