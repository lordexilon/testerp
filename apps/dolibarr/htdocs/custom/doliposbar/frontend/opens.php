<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
$langs->load("pos@doliposbar");

?> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>doliPOS BAR</title>

<link rel="stylesheet" type="text/css" href="css/login.css" />

	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<script type="text/javascript" src="js/login.js"></script>
	<link href="css/keyboard.css" rel="stylesheet">
	<script src="js/jquery.keyboard.js"></script>
	<link href="css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">

</head>

<body>


</div>
<div id="carbonForm">

	<h2><center><?php echo $langs->trans("OpenPlaces"); ?></center></h1>
	<br>
    <form action="cash.php" method="post" id="cash">

    <div class="fieldContainer">
		<center>
            <div class="field">
<input type="button"  style=" width: 90%" name="submit" value="<?php echo $langs->trans("DirectSales"); ?>" onclick="top.location.href='tpv.php'"/><br><br>
<?php
$sql="SELECT rowid, ref from ".MAIN_DB_PREFIX."commande where ref like 'Place%' and ref <>'Place-0'";
$resql = $db->query($sql);
while($row = $db->fetch_array ($resql))
{
$placename=str_replace("Place-","",$row[1]);
?>
<input type="button"  style=" width: 90%" name="submit" value="<?php echo $langs->trans("Table")." $placename"; ?>" onclick="top.location.href='tpv.php?place=<?php echo $placename; ?>'"/><br><br>
<?php
}			
?>           	
			</div>
        </div>
        
        <br>
  
    </div> <!-- Closing fieldContainer -->
    <br>
    <div class="field">
	<center>
	</center>
    </div>
    </form>
          
</div>



</body>
</html>
