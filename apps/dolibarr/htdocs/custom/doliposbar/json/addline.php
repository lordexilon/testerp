<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../master.inc.php");
if (! $res) $res=@include("../../../master.inc.php");
require_once(DOL_DOCUMENT_ROOT."/commande/class/commande.class.php");
$id= GETPOST('id');
$place= GETPOST('place');
$qty=GETPOST('q');
$price=GETPOST('p');
$dto=GETPOST('d');
$notes=GETPOST('n');
$result=$user->fetch('','admin');
$user->getrights();

$sql="SELECT rowid FROM ".MAIN_DB_PREFIX."commande where ref='Place-$place'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
$placeid=$row[0];


$prod = new Product($db);
$prod->fetch($id);
$vatmult=$prod->tva_tx/100+1;
$com = new OrderLine($db);
$com->fk_commande=$placeid;
$com->qty=$qty;
$com->tva_tx=$prod->tva_tx;
$com->fk_product=$id;
$com->subprice=$price/$vatmult;
$com->label=$prod->label;
$com->rang='1';
$com->remise_percent=$dto;
$com->remise=$price*$dto/100;
$com->total_ttc=$qty*$price-$com->remise;
$com->total_ht=$com->total_ttc/$vatmult;
$com->fk_parent_line='';
$com->total_tva=$com->total_ttc-$com->total_ht;
$com->pa_ht = '1';
$com->price=$price;
$com->desc=$notes;
$result=$com->insert();




			//UPDATE PRICE
        $fieldtva='total_tva';
        $fieldlocaltax1='total_localtax1';
        $fieldlocaltax2='total_localtax2';
        $sql = 'SELECT qty, total_ht, '.$fieldtva.' as total_tva, total_ttc, '.$fieldlocaltax1.' as total_localtax1, '.$fieldlocaltax2.' as total_localtax2,';
        $sql.= ' tva_tx as vatrate, localtax1_tx, localtax2_tx, localtax1_type, localtax2_type';
        $sql.= ' FROM '.MAIN_DB_PREFIX.'commandedet' ;
        $sql.= ' WHERE fk_commande = '.$placeid;
        $resql = $db->query($sql);
        if ($resql)
        {
            $total_ht  = 0;
            $total_tva = 0;
            $total_localtax1 = 0;
            $total_localtax2 = 0;
            $total_ttc = 0;
            $vatrates = array();
            $vatrates_alllines = array();
            $num = $db->num_rows($resql);
            $i = 0;
            while ($i < $num)
            {
                $obj = $db->fetch_object($resql);
                $total_ht        += $obj->total_ht;
                $total_tva       += $obj->total_tva;
                $total_localtax1 += $obj->total_localtax1;
                $total_localtax2 += $obj->total_localtax2;
                $total_ttc       += $obj->total_ttc;
                if (! empty($conf->global->MAIN_USE_LOCALTAX_TYPE_7))
                {
                	if ($this->total_localtax1 == 0)
                    {
                		global $mysoc;
                    	$localtax1_array=getLocalTaxesFromRate($vatrate,1,$mysoc);
                    	if (empty($obj->localtax1_type))
                    	{
                    		$obj->localtax1_type = $localtax1_array[0];
                    		$obj->localtax1_tx = $localtax1_array[1];
                    	}
						if ($obj->localtax1_type == '7')
						{
							$total_localtax1 += $obj->localtax1_tx;
							$total_ttc       += $obj->localtax1_tx;
						}
					}
                    if ($this->total_localtax2 == 0)
                    {
                		global $mysoc;
                    	$localtax2_array=getLocalTaxesFromRate($vatrate,2,$mysoc);
                    	if (empty($obj->localtax2_type))
                    	{
                    		$obj->localtax2_type = $localtax2_array[0];
                    		$obj->localtax2_tx = $localtax2_array[1];
                    	}

                    	if ($obj->localtax2_type == '7')
						{
							$total_localtax2 += $obj->localtax2_tx;
							$total_ttc       += $obj->localtax2_tx;
						}
                    }
                }

                $i++;
            }

            $db->free($resql);
            $fieldht='total_ht';
            $fieldtva='tva';
            $fieldlocaltax1='localtax1';
            $fieldlocaltax2='localtax2';
            $fieldttc='total_ttc';
            if (empty($nodatabaseupdate))
            {
                $sql = 'UPDATE '.MAIN_DB_PREFIX.'commande SET';
                $sql .= " ".$fieldht."='".price2num($total_ht)."',";
                $sql .= " ".$fieldtva."='".price2num($total_tva)."',";
                $sql .= " ".$fieldlocaltax1."='".price2num($total_localtax1)."',";
                $sql .= " ".$fieldlocaltax2."='".price2num($total_localtax2)."',";
                $sql .= " ".$fieldttc."='".price2num($total_ttc)."'";
                $sql .= ' WHERE rowid = '.$placeid;
                $resql=$db->query($sql);
            }
        }
		//END UPDATE PRICE