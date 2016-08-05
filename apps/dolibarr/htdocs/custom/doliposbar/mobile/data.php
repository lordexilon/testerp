<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../master.inc.php");
require_once(DOL_DOCUMENT_ROOT."/commande/class/commande.class.php");
$action = GETPOST('action');
$place = GETPOST('place');


if ($action=="gettotal")
{
$sql="SELECT total_ttc FROM ".MAIN_DB_PREFIX."commande where ref='Place-$place'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
$total=$row[0];
if (! $total) $total=0;
echo number_format($total, 2, '.', '');
}


if ($action=="getlines")
{
//Get records from database
$sql="SELECT ".MAIN_DB_PREFIX."commande.rowid as id, ".MAIN_DB_PREFIX."commandedet.rowid as iddet, ".MAIN_DB_PREFIX."commandedet.label as label, ".MAIN_DB_PREFIX."commandedet.qty as qty, ".MAIN_DB_PREFIX."commandedet.price as price, ".MAIN_DB_PREFIX."commandedet.remise_percent as remise, ".MAIN_DB_PREFIX."commandedet.total_ttc as total FROM ".MAIN_DB_PREFIX."commande, ".MAIN_DB_PREFIX."commandedet where ".MAIN_DB_PREFIX."commande.rowid=".MAIN_DB_PREFIX."commandedet.fk_commande and ".MAIN_DB_PREFIX."commande.ref='Place-$place'";
$resql = $db->query($sql);

//Add all records to an array
$rows = array();
while($row = $db->fetch_array ($resql))
{
	echo'<li><a href="#popupLogin" onclick="showproduct('.$row["iddet"].', '.$row["qty"].', '.number_format($row["price"], 2, '.', '').');" data-rel="popup" data-position-to="window"  id="p1">'.$row["label"].'<span class="ui-li-count ui-body-inherit">'.$row["qty"].'</span></a></li>';
}
}
 
