<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
$zone= GETPOST('zone');
$action= GETPOST('action');
if (! $zone) $zone=1;
if ($action=='do_move')
{
$origin=GETPOST('origin');
$destination=GETPOST('destination');
$sql="SELECT rowid from ".MAIN_DB_PREFIX."commande where ref like 'Place-$destination'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
if ($row[0]>0)
	{
	$sql="SELECT rowid from ".MAIN_DB_PREFIX."commande where ref like 'Place-$origin'";
	$resql = $db->query($sql);
	$origin_rowid = $db->fetch_array ($resql);
	$sql="UPDATE ".MAIN_DB_PREFIX."commandedet set fk_commande= $row[0] where fk_commande=$origin_rowid[0]";
	echo $sql;
	$resql = $db->query($sql);
	require_once(DOL_DOCUMENT_ROOT."/commande/class/commande.class.php");
	$result=$user->fetch('','admin');
	$object = new Commande($db);
	$ret=$object->fetch($origin_rowid[0]);
	$result=$object->delete($user);
	}
else
	{
	$sql="UPDATE ".MAIN_DB_PREFIX."commande set ref= 'Place-$destination' where ref= 'Place-$origin'";
	$resql = $db->query($sql);
	}
}

else
{
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>BarPos</title>
	<link href="css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
	<link href="css/barpos.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<link href="jtable/themes/metro/lightgray/jtable.css" rel="stylesheet" type="text/css" /> 
	<script src="jtable/jquery.jtable.min.js" type="text/javascript"></script>
	<style type="text/css">
	div.tablediv{
	background-image:url(img/table.gif);
	-moz-background-size:100% 100%;
	-webkit-background-size:100% 100%;
	background-size:100% 100%;
	height:10%;
	width:10%;
	text-align: center;
	font-size:300%;
	text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
	color:white;
	}
	html, body
	{
    height: 100%;
	}
	</style>
	<script>
	var origin=0;
	function move_table(table)
	{
		if (origin==0) {$("#infotable").text('Seleccione la mesa de destino'); origin=table;}
		else $.post('places.php', { action: 'do_move', origin: origin, destination: table })
		.done(function(data) {
		top.location.href='tpv.php?place='+table;
		});
	}
	</script>
	</head>
	<body style="overflow: hidden">
	
<?php

echo '<div id="infotable" style="position:absolute; top:95%; left:0.5%; height:30%; width:40%; overflow: auto;">';
echo '<a href="manageplaces.php" target="_blank">Editar salones</a>';
echo '</div>';

?>	
	
	<div id="divplace<?php echo $zone; ?>">
<?php
$tablebusy[]=array();
$sql="SELECT ref from ".MAIN_DB_PREFIX."commande where ref like 'Place-%'";
$resql = $db->query($sql);
while($row = $db->fetch_array ($resql)){
    $tablebusy[] = substr($row[0], 6);
}

$sql="SELECT name, left_pos, top_pos from ".MAIN_DB_PREFIX."pos_places where zone=$zone";
$resql = $db->query($sql);
 while($row = $db->fetch_array ($resql))
{
echo '<div class="tablediv" onclick="';
if ($action=='move') echo 'move_table('.$row[0].');"';
else echo 'top.location.href=\'tpv.php?place='.$row[0].'\';"';
echo ' style="position: absolute; left:';
echo $row[1];
echo '%; top:';
echo $row[2];
echo '%;"><font ';
if (in_array($row[0], $tablebusy)) echo "color=red";
echo '>';
echo $row[0]; 
echo '</font></div>';
}
?>
	</div>
	</body>
</html>
<?php
} ?>