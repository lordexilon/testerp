<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../master.inc.php");
if (! $res) $res=@include("../../../master.inc.php");  
require_once(DOL_DOCUMENT_ROOT."/commande/class/commande.class.php");
require_once('../backend/class/pos.class.php');
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';
$langs->load("pos@doliposbar");
$place = GETPOST('place');
$placenoid = GETPOST('placenoid');
$pay = GETPOST('pay');
$customer = GETPOST('customer');
$print = GETPOST('print');
$now=dol_now();
?><!doctype html>
<html>
<head>


	
<?php

if ($placenoid!="")
{
$sql="SELECT rowid FROM ".MAIN_DB_PREFIX."commande where ref='Place-$placenoid'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
$place=$row[0];
}

$object = new Commande($db);
$res=$object->fetch($place);

$user = new User($db);
$user->fetch('', 'admin');		
$facture=new Facturesim($db);
if ($pay=="cash") $object->mode_reglement_id=4; else $object->mode_reglement_id=1;
$facture->createFromOrder($object);
$user->getrights();
if ($conf->stock->enabled) $result=$facture->validate($user, '', $conf->global->POS_DEFAULT_WAREHOUSE);
else $result=$facture->validate($user, '', '');
$object->delete('admin');


$payment=new Paiement($db);
$payment->datepaye=$now;
if ($pay=="cash") $fk_bank=$conf->global->POS_DEFAULT_CASH; else $fk_bank=$conf->global->POS_DEFAULT_BANK;
$payment->bank_account=$fk_bank;
$payment->amounts[$facture->id]=$object->total_ttc;
if ($pay=="cash") $payment->paiementid =4; else $payment->paiementid =1;
$payment->num_paiement='';
$payment_id = $payment->create($user,1);
$payment->addPaymentToBank($user, 'payment', 'Dolipos BAR', $fk_bank, '', '');
$facture->update_note($langs->trans("Table")." ".str_replace("Place-", "", $object->ref),'_public');
$facture->set_paid($user);


$sql="SELECT * FROM ".MAIN_DB_PREFIX."facture order by rowid DESC limit 0,1";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);

$sql="INSERT INTO ".MAIN_DB_PREFIX."pos_facture values ('',NULL ,NULL , $row[0],NULL )";
$db->query($sql);

if ($print==1) {
dolibarr_set_const($db,"PENDING_PRINT_BAR", $conf->global->PENDING_PRINT_BAR.'F'.$row[0].',','chaine',0,'',$conf->entity);
}
if ($print==2) {
?>
	<meta charset="utf-8">
	<title>BarPos</title>
	<link href="css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
	<link href="css/barpos.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.min.js"></script>
	<script>
	$(document).ready(function () {              
	<?php echo "window.location.href='tpl/facture.tpl.php?id=".$row[0]."';"; ?>
	});
	</script>
<?php
}
?>
</head>
<body>
</body>
</html>