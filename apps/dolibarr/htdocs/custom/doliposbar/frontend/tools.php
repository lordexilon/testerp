<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
$langs->load("pos@doliposbar");
?>
<!doctype html>
<html lang="es">
<head>
<script>

	$(document).ready(function () {

	element=document.documentElement;
	$("#fullscreen").click(function() {
	element=document.documentElement;
	if(element.requestFullScreen) {
	element.requestFullScreen();
	} else if(element.mozRequestFullScreen) {
	element.mozRequestFullScreen();
	} else if(element.webkitRequestFullScreen) {
	element.webkitRequestFullScreen();
	}
	});

	$("#items").colorbox({ iframe: true, width:'80%', height:'80%', href:'<?php echo $dolibarr_main_url_root; ?>/product/index.php?mainmenu=products&leftmenu='});
	$("#places").colorbox({ iframe: true, width:'80%', height:'80%', href:'manageplaces.php'});
	$("#setup").colorbox({ iframe: true, width:'80%', height:'80%', href:'../../doliposbar/admin/pos.php?optioncss=print'});

	});
</script>
</head>
<body>
		<div style="position:absolute; top:8%; left:8%; height:80%; width:98%;">
	
	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="items">
		<img src='img/items.jpg' width="100%" height="100%" border="1" id='receiptimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("Products"); ?></div>
		</div>
	</div>
	
		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="places">
		<img src='img/places.jpg' width="100%" height="100%" border="1"/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("ZoneManagement"); ?></div>
		</div>
	</div>
	
		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="setup">
	<img src='img/kitchen2.jpg' width="100%" height="100%" border="1"/>
	<div class='description2'>
	<div class='description_content' ><?php echo $langs->trans("DoliposbarSetup"); ?></div>
		</div>
	</div>
	

		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" onclick="location.href='index.php';">
	<img src='img/waiter.jpg' width="100%" height="100%" border="1"/>
	<div class='description2'>
	<div class='description_content' ><?php echo $langs->trans("ChangeEmployee"); ?>/<?php echo $langs->trans("Logout"); ?></div>
		</div>
	</div>
	
		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;">
		</div>
	
		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="fullscreen">
	<img src='img/fullscreen.png' width="100%" height="100%" border="1"/>
	<div class='description2'>
	<div class='description_content' ><?php echo $langs->trans("Fullscreen"); ?></div>
		</div>
	</div>
	
		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" onclick="location.href='<?php echo $dolibarr_main_url_root; ?>';">
	<img src='img/dolibarr.jpg' width="100%" height="100%" border="1"/>
	<div class='description2'>
	<div class='description_content' >Backoffice</div>
		</div>
	</div>
	
		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;">
		</div>
	
	</div>
	

	


</body>
</html>