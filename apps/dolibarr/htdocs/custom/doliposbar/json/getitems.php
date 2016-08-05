<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../master.inc.php");
if (! $res) $res=@include("../../../master.inc.php");


require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once(DOL_DOCUMENT_ROOT."/categories/class/categorie.class.php");



function getitems($id)
{

		global $db,$conf;
		$objs = array();
		$retarray=array();
			
		$sql = "SELECT o.rowid as id, o.price_ttc as price, o.label as label, c.fk_product";
		$sql.= " FROM ".MAIN_DB_PREFIX."categorie_product as c";
		$sql.= ", ".MAIN_DB_PREFIX."product as o";
		$sql .= " WHERE c.fk_categorie = ".$id;
		$sql .= " AND c.fk_product = o.rowid";

		$resql = $db->query($sql);
		if ($resql)
		{
			$num = $db->num_rows($resql);
			$i = 0;
			
			while ($i < $num)
			{
				$objp = $db->fetch_object($resql);
			
				$val["id"]= $objp->id;
				$val["label"]= $objp->label;
				$val["price"]= $objp->price;
				$val["type"]="pro";
				
				echo "INSERT INTO DOLIPOSBAR.\"productos\"  values (".$val["id"].", ".$id.", '".$val["label"]."', ".$val["price"].")\r\n";
				//echo "<br>";
				$i++;
			}

		}
}






$objCat = new Categorie($db);
$cats = $objCat->get_full_arbo(0);

$retarray=array();
foreach($cats as $key => $val)
	{
	echo "INSERT INTO DOLIPOSBAR.\"cats\"  values (".$val["rowid"].", ".$val["fk_parent"].", '".$val["label"]."')\r\n";
	//echo "<br>";
	getitems($val["rowid"]);
	}





				

				
				
				
				
?>