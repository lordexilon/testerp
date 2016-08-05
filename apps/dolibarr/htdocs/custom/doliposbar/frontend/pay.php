<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
$langs->load("pos@doliposbar");
$placenum = GETPOST('place');
$cname = GETPOST('cname');
$customer = GETPOST('customer');
$sql="SELECT rowid FROM ".MAIN_DB_PREFIX."commande where ref='Place-$placenum'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
$place=$row[0];
$sql="SELECT total_ttc FROM ".MAIN_DB_PREFIX."commande where rowid=$place";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>BarPos</title>
	<link href="css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
	<link href="css/barpos.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<script>
	var received=0;
	var print=2;
	var place=<?php echo $place; ?>;
	var placenum=<?php echo $placenum; ?>;
	var customer='<?php echo $customer; ?>';
	function addreceived(price)
	{
	received+=parseFloat(price);
	$('#change1').html(received.toFixed(2));
	if (received><?php echo $row[0]; ?>)
		{
		var change=parseFloat(received-<?php echo $row[0]; ?>);
		$('#change2').html(change.toFixed(2));
		}
	}
	
	function printclick(){
	if (print==2) {
	print=0;
	$("#imgprint").attr("src",'img/unchecked.png');
	}
	else {
	print=2;
	$("#imgprint").attr("src",'img/checked.png');
	}
	}
	</script>
</head>
<body>
<center>
<div style="width:40%; background-color:#222222; border-radius:8px; margin-bottom: 4px;">
<center><span style='font-family: digital; font-size: 480%;'><font color="white">Total: </font><font color="red"><span id="totaldisplay"><?php echo round($row[0] * 100) / 100; ?></span></span><span style='font-family: digital;font-size: 450%;'> €</font></span></center>
</div>
<div style="width:40%; background-color:#333333; border-radius:8px; margin-bottom: 4px;">
<center><span style='font-family: digital; font-size: 300%;'><font color="white"><?php echo $langs->trans("CustomerPay"); ?>: </font><font color="red"><span id="change1">0</span></span><span style='font-family: digital;font-size: 380%;'> €</font></span></center>
</div>
<div style="width:40%; background-color:#333333; border-radius:8px; margin-bottom: 4px;">
<center><span style='font-family: digital; font-size: 300%;'><font color="white"><?php echo $langs->trans("CustomerRet"); ?>: </font><font color="red"><span id="change2">0</span></span><span style='font-family: digital;font-size: 380%;'> €</font></span></center>
</div>
</center>

<div id="customer" style="position:absolute; top:6%; left:8%; height:60%; width:60%;">
<button type="button" class="calcbutton2" onclick="window.location.href='customers.php?place='+placenum"><img src="img/stool.png" height="50%"><br><?php if ($cname!="") echo $cname; else echo $langs->trans("ChangeCustomer"); ?></button>
</div>

<div style="position:absolute; top:40%; left:13%; height:55%; width:87%;">
<button type="button" class="calcbutton" onclick="addreceived(10);">10</button>
<button type="button" class="calcbutton" onclick="addreceived(20);">20</button>
<button type="button" class="calcbutton" onclick="addreceived(50);">50</button>
<button type="button" class="calcbutton2" onclick="window.location.href='save.php?place='+place+'&pay=cash&print='+print+'&customer='+customer"><img src="img/cash.gif" height="50%"><br><?php echo $langs->trans("TicketsCash"); ?></button>
<button type="button" class="calcbutton" onclick="addreceived(1);">1</button>
<button type="button" class="calcbutton" onclick="addreceived(2);">2</button>
<button type="button" class="calcbutton" onclick="addreceived(5);">5</button>
<button type="button" class="calcbutton2" onclick="window.location.href='save.php?place='+place+'&pay=card&print='+print"><img src="img/visa.gif" height="50%"><br><?php echo $langs->trans("TicketsCreditCard"); ?></button>
<button type="button" class="calcbutton" onclick="addreceived(0.10);">0.10</button>
<button type="button" class="calcbutton" onclick="addreceived(0.20);">0.20</button>
<button type="button" class="calcbutton" onclick="addreceived(0.50);">0.50</button>
<button type="button" class="calcbutton2" onclick="printclick();"><img src="img/checked.png" id="imgprint" width="30%"><br><span id="printtext"><?php echo $langs->trans("Print"); ?></span></button>
<button type="button" class="calcbutton" onclick="addreceived(0.01);">0.01</button>
<button type="button" class="calcbutton" onclick="addreceived(0.02);">0.02</button>
<button type="button" class="calcbutton" onclick="addreceived(0.05);">0.05</button>
<button type="button" class="calcbutton2" onclick="window.location.href='pay.php?place='+placenum;"><span style='font-size: 150%;'><?php echo $langs->trans("Clean"); ?></span></button>
</div>

</body>
</html>