<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../master.inc.php");
if (! $res) $res=@include("../../../master.inc.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");

$langcode=($conf->global->MAIN_LANG_DEFAULT);
if ($langcode=="auto") $langcode = GETPOST("lang");
$langs->setDefaultLang($langcode);
$langs->load("pos@doliposbar");
$langs->load("main");
$langs->load("orders");

function addrow($text){
global $langs, $lang;
$row["n"]=$text;
$row["v"]=$langs->trans($text);
$lang[] = $row;
}

$row["n"]="langcode";
$row["v"]=$langcode;
$lang[] = $row;

$row["n"]="decimals";
if ($conf->currency=="EUR") $row["v"]=2;
else $row["v"]=0;
$lang[] = $row;

$row["n"]="money";
$row["v"]=$conf->currency;
$lang[] = $row;

addrow("Zone");
$row["n"]="Zone1";
$custom_name="DOLIPOSBAR_CUSTOM_ZONE_NAME1";
if ($conf->global->DOLIPOSBAR_CUSTOM_ZONE_NAME1!="") $row["v"]=$conf->global->$custom_name;
else $row["v"]=$langs->trans("Zone")." 1";
$lang[] = $row;
$row["n"]="Zone2";
$custom_name="DOLIPOSBAR_CUSTOM_ZONE_NAME2";
if ($conf->global->DOLIPOSBAR_CUSTOM_ZONE_NAME2!="") $row["v"]=$conf->global->$custom_name;
else $row["v"]=$langs->trans("Zone")." 2";
$lang[] = $row;
$row["n"]="Zone3";
$custom_name="DOLIPOSBAR_CUSTOM_ZONE_NAME3";
if ($conf->global->DOLIPOSBAR_CUSTOM_ZONE_NAME3!="") $row["v"]=$conf->global->$custom_name;
else $row["v"]=$langs->trans("Zone")." 3";
$lang[] = $row;
$row["n"]="Zone4";
$custom_name="DOLIPOSBAR_CUSTOM_ZONE_NAME4";
if ($conf->global->DOLIPOSBAR_CUSTOM_ZONE_NAME4!="") $row["v"]=$conf->global->$custom_name;
else $row["v"]=$langs->trans("Zone")." 4";
$lang[] = $row;


addrow("Kitchen");
addrow("drawer");
addrow("CheckBar");
addrow("CloseBill");
addrow("Notes");
addrow("OpenPlaces");
addrow("Tools");
addrow("History");
addrow("ChangeEmployee");
addrow("shortquantity");
addrow("shortprice");
addrow("Dct");
addrow("DirectSales");
addrow("Joinormove");
addrow("Back");
addrow("Selectsource");
addrow("Selectdestination");
addrow("CustomerPay");
addrow("CustomerRet");
addrow("Description");
addrow("Price");
addrow("Print");
addrow("TicketsCash");
addrow("TicketsCreditCard");
addrow("Facsim");
addrow("Order");
addrow("Table");
addrow("Header_lines");
addrow("TotalVAT");
addrow("DoliposPROdemo1");
addrow("DoliposPROdemo2");
addrow("DoliposPROdemo3");



print json_encode($lang);