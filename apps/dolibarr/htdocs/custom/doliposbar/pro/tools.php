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
	<link href="../frontend/css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
	<link href="../frontend/css/barpos.css" rel="stylesheet">
	<script src="../frontend/js/jquery-1.9.1.min.js"></script>
	<script src="../frontend/js/jquery-ui-1.10.2.custom.min.js"></script>
	<link rel="stylesheet" href="../frontend/css/colorbox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="../frontend/js/jquery.colorbox.js"></script>
	<link href="../frontend/jtable/themes/metro/lightgray/jtable.min.css" rel="stylesheet" type="text/css" /> 
	<script src="../frontend/jtable/jquery.jtable.min.js" type="text/javascript"></script>
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

	$("#cashcontrol").colorbox({ iframe: true, width:'40%', height:'60%', href:'../pro/cash.php?type=0'});
	$("#closecash").colorbox({ iframe: true, width:'40%', height:'60%', href:'../pro/cash.php?type=1'});
	$("#notes2").colorbox({ iframe: true, width:'50%', height:'80%', href:'../pro/notes.php?admin=admin'});
	$("#zoneman").colorbox({ iframe: true, width:'80%', height:'80%', href:'../frontend/manageplaces.php'});
	$("#items").colorbox({ iframe: true, width:'80%', height:'80%', href:'<?php echo $dolibarr_main_url_root; ?>/product/index.php?mainmenu=products&leftmenu='});
	$("#kitchen").colorbox({ iframe: true, width:'80%', height:'80%', href:'../pro/kitchencats.php'});

	
	});
</script>
</head>
<body>
		<div style="position:absolute; top:8%; left:8%; height:80%; width:98%;">
		

	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="cashcontrol">
		<img src='../frontend/img/pay.jpg' width="100%" height="100%" border="1"/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("Arching"); ?></div>
		</div>
	</div>
	
	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="closecash">
		<img src='../frontend/img/posdrawer.jpg' width="100%" height="100%" border="1" id='kithenimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("CashAccount"); ?></div>
		</div>
	</div>

	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="notes2">
		<img src='../frontend/img/postit.jpg' width="100%" height="100%" border="1"/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("Notes"); ?></div>
		</div>
	</div>

	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="zoneman">
		<img src='../frontend/img/places.jpg' width="100%" height="100%" border="1"/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("ZoneManagement"); ?></div>
		</div>
	</div>

	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;">
	</div>
		
	
	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="items">
		<img src='../frontend/img/items.jpg' width="100%" height="100%" border="1" id='receiptimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("Products"); ?></div>
		</div>
	</div>
	
	<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;" id="kitchen">
		<img src='../frontend/img/items.jpg' width="100%" height="100%" border="1" id='receiptimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("Kitchencats"); ?></div>
		</div>
	</div>

	
		<div class='wrapper3' style="width:18%;height:30%;margin-top:1.5%;margin-bottom:1.5%;margin-left:1.5%;margin-right:1.5%;">
		</div>
	
	</div>
	

	


</body>
</html>