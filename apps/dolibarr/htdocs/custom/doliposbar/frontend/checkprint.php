<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../master.inc.php"); 
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
$langs->load("pos@doliposbar");
$pending_print = explode(',',$conf->global->PENDING_PRINT_BAR);
$id=substr($pending_print[0], 1);

$drop=strlen($id);
$drop++;$drop++;
dolibarr_set_const($db,"PENDING_PRINT_BAR", substr($conf->global->PENDING_PRINT_BAR, $drop),'chaine',0,'',$conf->entity);

function clean_text ($text_to_clean){
    $source = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $destination = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $text_to_clean = utf8_decode($text_to_clean);
    $text_to_clean = strtr($text_to_clean, utf8_decode($source), $destination);
    $text_to_clean = strtolower($text_to_clean);
    return utf8_encode($text_to_clean);
}

$json_data = array();
if ($pending_print[0][0]=="K") {
$printerid=$id[0];
if ($printerid==1) $json_data['printer']=$conf->global->KITCHENPRINTERNAME;
if ($printerid==2) $json_data['printer']=$conf->global->BARPRINTERNAME;
$id=substr($id, 1);
}
if ($pending_print[0][0]=="V" or $pending_print[0][0]=="F" or $pending_print[0][0]=="D") $json_data['printer']=$conf->global->BARPRINTERNAME;

if ($pending_print[0][0]=="F")
{
$sql="SELECT rowid, tva, total_ttc, facnumber, note_public FROM ".MAIN_DB_PREFIX."facture where rowid='$id'";
$resql = $db->query($sql);
$rowfacture = $db->fetch_array ($resql);
$json_data['type'] = $pending_print[0][0];
$json_data['ref'] = $rowfacture['facnumber'];
$json_data['tva'] = number_format($rowfacture['tva'], 2, '.', '');
$json_data['total'] = number_format($rowfacture['total_ttc'], 2, '.', '');
$json_data['datetime']= date("d-m-Y H:i:s");
$json_data['note']= $rowfacture['note_public'];
$sql="SELECT ".MAIN_DB_PREFIX."facture.rowid as id, ".MAIN_DB_PREFIX."facturedet.rowid as iddet, ".MAIN_DB_PREFIX."product.label as label, ".MAIN_DB_PREFIX."facturedet.qty as qty, ".MAIN_DB_PREFIX."facturedet.price as price, ".MAIN_DB_PREFIX."facturedet.remise_percent as remise, ".MAIN_DB_PREFIX."facturedet.total_ttc as total FROM ".MAIN_DB_PREFIX."facture, ".MAIN_DB_PREFIX."facturedet, ".MAIN_DB_PREFIX."product where ".MAIN_DB_PREFIX."facture.rowid=".MAIN_DB_PREFIX."facturedet.fk_facture and ".MAIN_DB_PREFIX."product.rowid=".MAIN_DB_PREFIX."facturedet.fk_product and ".MAIN_DB_PREFIX."facture.rowid=$id";
$result= $db->query($sql);
$rows = array();
}

if ($pending_print[0][0]=="V" or $pending_print[0][0]=="K")
{
$sql="SELECT ";
if ($pending_print[0][0]=="K") $sql.=MAIN_DB_PREFIX."commande.note_private as note, ";
$sql.="rowid, tva, total_ttc FROM ".MAIN_DB_PREFIX."commande where ref='Place-$id'";
$resql = $db->query($sql);
$rowcommande = $db->fetch_array ($resql);
$json_data['place'] = $id;
$json_data['voucher_label']=$langs->trans("CheckBar");
$id=$rowcommande['rowid'];
$json_data['note'] = $rowcommande['note'];
$json_data['type'] = $pending_print[0][0];
$json_data['ref'] = $id;
$json_data['tva'] = number_format($rowcommande['tva'], 2, '.', '');
$json_data['total'] = number_format($rowcommande['total_ttc'], 2, '.', '');
$json_data['datetime']= date("d-m-Y H:i:s");
$sql="SELECT ";
if ($pending_print[0][0]=="K") $sql.=MAIN_DB_PREFIX."commandedet.description as note, ";
$sql.=MAIN_DB_PREFIX."commande.rowid as id, ".MAIN_DB_PREFIX."commandedet.rowid as iddet, ".MAIN_DB_PREFIX."product.label as label, ".MAIN_DB_PREFIX."commandedet.qty as qty, ".MAIN_DB_PREFIX."commandedet.price as price, ".MAIN_DB_PREFIX."commandedet.remise_percent as remise, ".MAIN_DB_PREFIX."commandedet.total_ttc as total FROM ".MAIN_DB_PREFIX."commande, ".MAIN_DB_PREFIX."commandedet, ".MAIN_DB_PREFIX."product, ".MAIN_DB_PREFIX."categorie_product where ".MAIN_DB_PREFIX."commande.rowid=".MAIN_DB_PREFIX."commandedet.fk_commande and ".MAIN_DB_PREFIX."product.rowid=".MAIN_DB_PREFIX."commandedet.fk_product and ".MAIN_DB_PREFIX."commandedet.fk_product=".MAIN_DB_PREFIX."categorie_product.fk_product and ".MAIN_DB_PREFIX."commande.rowid=$id";
if ($pending_print[0][0]=="K")
{
$kitchenprint = explode(',',$conf->global->KITCHEN_PRINT_BAR);
for($i = 0; $i<count($kitchenprint); $i++){
	$kitchenprint[$i]=substr($kitchenprint[$i], 1);
	if ($printerid==1)
		{
		if ($kitchenprint[$i]>0 and $i==0) $sql.=" and (".MAIN_DB_PREFIX."categorie_product.fk_categorie=$kitchenprint[$i]";
		if ($kitchenprint[$i]>0 and $i>0) $sql.=" or ".MAIN_DB_PREFIX."categorie_product.fk_categorie=$kitchenprint[$i]";
		}
	if ($printerid==2)
		{
		if ($kitchenprint[$i]>0 and $i==0) $sql.=" and (".MAIN_DB_PREFIX."categorie_product.fk_categorie<>$kitchenprint[$i]";
		if ($kitchenprint[$i]>0 and $i>0) $sql.=" and ".MAIN_DB_PREFIX."categorie_product.fk_categorie<>$kitchenprint[$i]";
		}
	}
$sql.=")";
}
$result = $db->query($sql);
$rows = array();
}

if ($pending_print[0][0]=="D") { $json_data['type'] = "D"; $row['price']=0; $rows[] = $row; $json_data['lines'] = $rows;}

if ($pending_print[0][0]=="V" or $pending_print[0][0]=="F")
{
$json_data['text1']=$conf->global->BARHEADTEXT1;
$json_data['text2']=$conf->global->BARHEADTEXT2;
$json_data['text3']=$conf->global->BARHEADTEXT3;
$json_data['text4']=$conf->global->BARFOOTERTEXT;
}

if ($pending_print[0][0]=="V" or $pending_print[0][0]=="K" or $pending_print[0][0]=="F")
{
while($row = $db->fetch_array ($result))
{
	$row['price']=number_format($row['price'], 2, '.', '');
	$row['total']=number_format($row['total'], 2, '.', '');
	$row['label']=substr($row['label'],0,23);
	$row['label']=clean_text($row['label']);
	$row['label']=str_pad($row['label'], 25, ' ', STR_PAD_RIGHT);
	if ($row['qty']<10) $row['qty'] ="  ".$row['qty'];
	if ($row['qty']>=10 and $row['qty']<100) $row['qty']=" ".$row['qty'];
	if ($row['total']<10) $row['total']="       ".$row['total'];
	if ($row['total']>=10 and $row['total']<100) $row['total']="      ".$row['total'];
	if ($row['total']>=100 and $row['total']<1000) $row['total']="     ".$row['total'];
	if ($row['total']>=1000 and $row['total']<10000) $row['total']="   ".$row['total'];
	if ($row['total']>=10000 and $row['total']<100000) $row['total']="  ".$row['total'];
	if ($row['total']>=100000 and $row['total']<1000000) $row['total']=" ".$row['total'];
	if ($pending_print[0][0]=="K") {
		if (strpos($row['note'],'PrintedOrder') !== false) continue;
		else $db->query("update ".MAIN_DB_PREFIX."commandedet set description=REPLACE(description, 'PrintOrder', 'PrintedOrder') where rowid=".$row['iddet']);
		$row['note']=str_replace("PrintOrder;", "", $row['note']);
		}
    $rows[] = $row;
}
if (count($rows)==0) exit;
$json_data['lines'] = $rows;
}


if ($pending_print[0][0]=="V" or $pending_print[0][0]=="F" or $pending_print[0][0]=="K")print json_encode($json_data); else echo "null";
?>