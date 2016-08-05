<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
$id = GETPOST('id');
$action = GETPOST('action');
$left = GETPOST('left');
$top = GETPOST('top');
$place = GETPOST('place');
$zone = GETPOST('zone');
$after = GETPOST('after');
$result=$user->fetch('','admin');
$user->getrights();


if ($action=="update")
{
if ($left>95) $left=95;
if ($top>95) $top=95;
if ($left>3 or $top>4)
{
$db->begin();
$db->query("update ".MAIN_DB_PREFIX."pos_places set left_pos=$left, top_pos=$top where name='$place'");
$db->commit();
}
else
{
$db->begin();
$db->query("delete from ".MAIN_DB_PREFIX."pos_places where name='$place'");
$db->commit();
}
}

if ($action=="updatename")
{
$db->begin();
$db->query("update ".MAIN_DB_PREFIX."pos_places set name='$after' where name='$place'");
$db->commit();
}

if ($action=="add")
{
$sql="SELECT name from ".MAIN_DB_PREFIX."pos_places";
$resql = $db->query($sql);
$data = array();
$i=0;
while ($row = $db->fetch_array ($resql)) {
    $data[$i++]= $row[0];
}
$data[$i++]= 0;
$nextplace=max(array_values($data));
$nextplace++;
$db->begin();
$db->query("insert into ".MAIN_DB_PREFIX."pos_places (name, left_pos, top_pos, zone) values ('$nextplace', '25', '25', $zone)");
$db->commit();
}


if ($action=="updatezonename")
{
dolibarr_set_const($db,"DOLIPOSBAR_CUSTOM_ZONE_NAME$zone", $after,'chaine',0,'',$conf->entity);
}

?>