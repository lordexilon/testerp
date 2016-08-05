<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../master.inc.php");
if (! $res) $res=@include("../../../master.inc.php");
require_once(DOL_DOCUMENT_ROOT."/commande/class/commande.class.php");
$query= GETPOST('query');
$place= GETPOST('place');
$result=$user->fetch('','admin');
$user->getrights();

$sql="SELECT rowid FROM ".MAIN_DB_PREFIX."commande where ref='Place-$place'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
$placeid=$row[0];
if (! $placeid) {
	$com = new Commande($db);
	$com->ref            = 'Place-$place';
	$com->socid          = $conf->global->POS_DEFAULT_THIRD;
	$com->date_commande  = mktime();
	$com->note           = '';
	$com->source         = 1;
	$com->remise_percent = 0;
	$result=$user->fetch('','admin');
	$idobject=$com->create($user);
	$db->commit();
	$db->query("UPDATE ".MAIN_DB_PREFIX."commande SET ref='Place-$place' WHERE rowid=$idobject;");
	$db->commit();
	$placeid=$idobject;
}



$db->begin();
$db->query("DELETE FROM ".MAIN_DB_PREFIX."commandedet WHERE fk_commande = $placeid;");
$db->commit();

if ($query=="delete") {
	$object = new Commande($db);
	$ret=$object->fetch($placeid);
	$result=$object->delete($user);
}