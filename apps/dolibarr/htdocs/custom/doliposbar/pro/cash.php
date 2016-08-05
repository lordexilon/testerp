<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
require_once('../backend/class/pos.class.php');
$langs->load("admin");
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
	<script type="text/javascript">
$(document).ready(function () {

var request = $.ajax({
  url: "../frontend/ajax_pos.php?action=getMoneyCash",
  type: "POST",
  dataType: "json"
});
 
request.done(function(msg) {

$('#cash1').val(Math.round(msg * 100) / 100);
});

	$('#name2')
 .keyboard({ layout: 'international' })
 .addTyping();
 
	});
 </script>



</head>

<body>
<div style="position:absolute; top:10%; left:2%; height:85%; width:6%;">
<img src="img/fork.png" width="100%" height="100%">
</div>
<div style="position:absolute; top:5%; right:2%; height:90%; width:6%;">
<img src="img/knife.png" width="100%" height="100%">
</div>
</div>
<div id="carbonForm">

	<h2><center>
<?php
$cash2 = GETPOST('cash2');
$type= GETPOST('type');
if ($cash2=="") {
if ($type==0) echo $langs->trans("Arqueo"); else echo $langs->trans("CashAccount");
}
else
{
$cash1 = GETPOST('cash1');
require_once(DOL_DOCUMENT_ROOT."/core/lib/functions.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formfile.class.php");
require_once(DOL_DOCUMENT_ROOT ."/core/class/notify.class.php");
require_once("../backend/class/pos.class.php");
$cash['employeeId']=1;
$cash['moneyincash']=$cash2;
$cash['amount_diff']=$cash2-$cash1;
$cash['type']=$type;
$cash['print']=1;
$result = POS::setControlCash($cash);

$sql="SELECT rowid FROM ".MAIN_DB_PREFIX."pos_control_cash order by rowid DESC limit 1";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);


if ($type==0) echo $langs->trans("ErrCloseCashOK"); else echo $langs->trans("NewClose");
}
?></center></h1>
	<br>
    <form action="cash.php" method="post" id="cash">

    <div class="fieldContainer">
		<center>

        <div class="formRow">
            
                <h2><center><?php echo $langs->trans("CashMoney"); ?></center></h1>
            
            
            <div class="field">
                <input type="text" name="cash1" id="cash1" readonly/>
            </div>
        </div>
        
        <div class="formRow">
            <h2><center><?php echo $langs->trans("MoneyInCash"); ?></center></h1>
            
            <div class="field">
                <input type="text" name="cash2" id="cash2" value="<?php if ($cash2!="") echo $cash2;?>"/>
            </div>
        </div>
        
        <br>
  
    </div> <!-- Closing fieldContainer -->
    <br>
    <div class="field">
	<center>
<input type="hidden" name="type" value="<?php echo $type; ?>">
<?php
if ($cash2=="") { ?><input type="submit" name="submit" value="<?php echo $langs->trans("MakeCloseCash"); ?>" /> <?php }
else { 
if ($type==0) {?> <input type="button" name="submit" value="<?php echo $langs->trans("CloseIt"); ?>" onclick="top.location.href='../frontend/tpv.php'"/> <?php }
else {?> <input type="button" name="submit" value="<?php echo $langs->trans("Print"); ?>" onclick="location.href='tpl/closecash.tpl.php?id=<?php echo $row[0];?>'"/> <?php }
} ?>
	</center>
    </div>
    </form>
          
</div>



</body>
</html>
