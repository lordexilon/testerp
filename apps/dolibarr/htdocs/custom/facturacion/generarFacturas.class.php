<?php
//require '../main.inc.php';
include_once DOL_DOCUMENT_ROOT.'/core/class/commoninvoice.class.php';
require_once DOL_DOCUMENT_ROOT .'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT .'/societe/class/client.class.php';
require_once DOL_DOCUMENT_ROOT .'/margin/lib/margins.lib.php';

class factura_preimpresa{

/*	
	var $idDoc;
	var $sSQLTemp;
		
	public function factura_preimpresa($idDoc, $db)
	{
		global $db;
		$this->idDoc=$idDoc;
		$this->db=$db;	
	}
	
	public function getID()
	{
		return $this->idDoc;
	}
	
function generarFacturas()
{
	global $conf, $db;

	$objProvisorio=new factureArg($db);
	$soc=new Societe($db);
	echo "Documento actual" . $this->idDoc."<br>";
	$dataProvisorio=$objProvisorio->fetch($this->idDoc);
	
	
	
	$cantRows=count($dataProvisorio->lines);
	$cant_lineas_fc=$conf->global->CANT_LINEAS_FC;
	echo "Cantidad de lineas por factura: ".$cant_lineas_fc."<br>";
	$cantidadFacturas=ceil($cantRows/$cant_lineas_fc);
	

	$row=0;
	$acum_total_amount=0;
	// generar una factura nueva en 0
	foreach ($objProvisorio->lines as $line)
	{
		$row++;
		if($row > $cant_line            			
as_fc)
		{
			//reemplazar los totales e impuestos de la factura generada
			$this->replaceFactura($rsidFactura, $acum_total); 
			//generar una factura nueva en 0
			echo "Proximo Numero: ".$objProvisorio->getNextNumRef($soc)."<br>";
			
			$rsidFactura=$this->newFactura($objProvisorio);
			$row=0;
			$acum_total=0;
		}
		
	}
	
}

private function newFactura($objProv)
{
	$dataProv=$objProv->fetch($this->idDoc);
	echo $objProv->date."<br>";
	
	$sSQL="insert into llx_facture ( ";
	$sSQL.="facnumber, "; 
	$sSQL.="ref_client, "; 
	$sSQL.="ref_ext, "; 
	$sSQL.="ref_int, "; 
	$sSQL.="type, ";
	$sSQL.="datef, ";
	$sSQL.="datec, ";
	$sSQL.="date_valid, "; 
	$sSQL.="tms, ";
	$sSQL.="remise_percent, "; 
	$sSQL.="remise_absolue, "; 
	$sSQL.="remise,  ";
	$sSQL.="total,  ";
	$sSQL.="tva,  ";
	$sSQL.="localtax1, "; 
	$sSQL.="localtax2 ";
	$sSQL.="total_ttc, ";
	$sSQL.="paye, "; 
	$sSQL.="close_code, "; 
	$sSQL.="close_note, ";
	$sSQL.="fk_soc, ";
	$sSQL.="fk_statut, "; 
	$sSQL.="date_lim_reglement, "; 
	$sSQL.="fk_cond_reglement, "; 
	$sSQL.="fk_mode_reglement, ";
	$sSQL.="fk_projet, "; 
	$sSQL.="facture_source, "; 
	$sSQL.="note, ";
	$sSQL.="note_public, "; 
	$sSQL.="fk_user_author, "; 
	$sSQL.="fk_user_valid, model_pdf";
	$sSQL.=" VALUES (";
	$sSQL.="'".$objProv->getNextNumRef($soc)."', ";
	$sSQL.=$objProv->ref_client.", ";
	$sSQL.=$objProv->ref_ext.", ";
	$sSQL.=$objProv->ref_int.", ";
	$sSQL.=$objProv->type.", ";
	$sSQL.=$objProv->df.", ";
	$sSQL.=$objProv->datec.", ";
	$sSQL.=$objProv->datev.", ";
	$sSQL.=$objProv->datem.", ";
	$sSQL.=$objProv->remise_percent.", ";
	$sSQL.=$objProv->remise_absolue.", ";
	$sSQL.=$objProv->remise.", ";
	$sSQL.=$objProv->total.", ";
	$sSQL.=$objProv->tva.", ";
	$sSQL.=$objProv->localtax1.", "; 
	$sSQL.=$objProv->localtax2.", ";
	$sSQL.=$objProv->total_ttc.", ";
	$sSQL.=$objProv->paye.", ";
	$sSQL.=$objProv->close_code.", ";
	$sSQL.=$objProv->close_note.", ";
	$sSQL.=$objProv->fk_soc.", ";
	$sSQL.=$objProv->fk_statut.", ";
	$sSQL.=$objProv->dlr.", ";
	$sSQL.=$objProv->fk_cond_reglement.", ";
	$sSQL.=$objProv->fk_mode_reglement.", ";
	$sSQL.=$objProv->fk_projet.", ";
	$sSQL.=$objProv->fk_facture_source.", ";
	$sSQL.=$objProv->note_private.", ";
	$sSQL.=$objProv->note_public.", ";
	$sSQL.=$objProv->fk_user_author.", ";
	$sSQL.=$objProv->fk_user_valid.", ";
	$sSQL.=$objProv->model_pdf;
	$this->sSQLTemp=$sSQL;
	return 0;
}

private function replaceFactura ($idFactura, $total)
{
	// $idFactura= id generado al inicializar la factura
	// $total=Total monto factura
}
*/

	var $soc;

	public function setSoc($societ)
	{
		$this->soc=$societ;
	}
	
	public function  genNewFactura($idDoc)
	{
		
		
		global $conf,$user,$langs, $db;
		$object=new factureArg($db);
		$object->fetch($idDoc);
		$cabFactura=new stdClass();
		
		$datos_element=new stdClass();
		$sSQLElement="select * from llx_element_element where fk_target=".$object->id." and targettype='facture'";
		$query=$db->query($sSQLElement);
		$rs=$db->fetch_object($query);
		if($rs)
		{
			$fk_source=$rs->fk_source; //id Pedido
		}
		$aFacturas=array();
		$cabFactura->id=$object->id;
		$cabFactura->facnumber=$object->getNextNumRef($socid, "last");
		$cabFactura->ref_ext=$object->ref_ext;
		$cabFactura->ref=$object->ref;
		$cabFactura->ref_client=$object->ref_client;
		$cabFactura->ref_int=$object->ref_int;
		$cabFactura->type=$object->type;
		$cabFactura->fk_soc=$object->socid;
		$cabFactura->datec=$object->date_creation;
		$cabFactura->datef=$object->date;
		$cabFactura->date_valid=$object->date_validation;
		$cabFactura->tms=$object->datem;
		$cabFactura->paye=$object->paye;
		//$cabFactura->amount;
		$cabFactura->remise_percent=$object->remise_percent;
		$cabFactura->remise_absolue=$object->remise_absolue;
		//$cabFactura->remise;
		$cabFactura->close_code=$object->close_code;
		$cabFactura->close_note=$object->close_note;
		$cabFactura->tva=$object->total_tva;
		//$cabFactura->localtax1;
		//$cabFactura->localtax2;
		$cabFactura->total=$object->total_ht;
		$cabFactura->total_ttc = $object->total_ttc;
		$cabFactura->fk_statut= $object->statut;
		$cabFactura->fk_user_author = $object->fk_user_author;
		$cabFactura->fk_user_valid = $object->fk_user_valid;
		$cabFactura->fk_facture_source=$object->fk_facture_source;
		$cabFactura->fk_project=$object->fk_project;
		//$cabFactura->fk_account=$object->
		//$cabFactura->fk_currency=$object->
		$cabFactura->fk_cond_reglement=$object->cond_reglement_id;
		$cabFactura->fk_mode_reglement=$object->mode_reglement_code;
		$cabFactura->date_lim_reglement=$object->date_lim_reglement;
		$cabFactura->note=$object->note;
		$cabFactura->note_public=$object->note_public;
		$cabFactura->model_pdf=$object->modelpdf;
		//$cabFactura->import_Key=$object->;
		$cabFactura->extraparams=$object->extraparams;
		$item=0;
		
		$aFacturas=array();
		$idActual=$object->id;
		$aFacturas[]=$idActual;
		
		$cant_lineas=$conf->global->CANT_LINEAS_FC;
		
		
		foreach($object->lines as $line=>$valor)
		{
			
			
			++$item;
			
			$idLine=$valor->rowid;
			
			if ($item > $cant_lineas)
			{
				
				$cabFactura->facnumber=$object->getNextNumRef($socid);
				//echo "Se agrega factura con el codigo:".$cabFactura->facnumber."<br>";
				$sSQL ="insert into llx_facture (facnumber, ref_ext,  ref_client, ";
				$sSQL.="ref_int, type, fk_soc, datef, datec, date_valid, tms, paye, ";
				$sSQL.="remise_percent, remise_absolue, close_code, close_note, tva, ";
				$sSQL.="total, total_ttc, fk_statut, fk_user_author, fk_user_valid, fk_facture_source, ";
				$sSQL.="fk_projet,  date_lim_reglement, ";
				$sSQL.="note, note_public, model_pdf, extraparams) ";
				$sSQL.=" VALUES (";
				$sSQL.="'".$cabFactura->facnumber."', ";
				$sSQL.=$this->replace_null($cabFactura->ref_ext).", ";
				
				$sSQL.=$this->replace_null($cabFactura->ref_client).", ";
				$sSQL.=$this->replace_null($cabFactura->ref_int).", ";
				$sSQL.="0, ";
				$sSQL.=$this->replace_null($cabFactura->fk_soc).", ";
				$sSQL.="'".$cabFactura->datec."', ";
				$sSQL.="'".$cabFactura->datef."', ";
				$sSQL.="'".$cabFactura->date_valid."', ";
				$sSQL.="'".$cabFactura->tms."', ";
				$sSQL.=$this->replace_null($cabFactura->paye).", ";
				$sSQL.=$this->replace_null($cabFactura->remise_percent).", ";
				$sSQL.=$this->replace_null($cabFactura->remise_absolue).", ";
				$sSQL.=$this->replace_null($cabFactura->close_code).", ";
				$sSQL.=$this->replace_null($cabFactura->close_note).", ";
				$sSQL.="0, "; //tva
				$sSQL.="0, "; //total
				$sSQL.="0, "; //total_ttc
				$sSQL.=$this->replace_null($cabFactura->fk_statut).", ";
				$sSQL.=$this->replace_null($cabFactura->fk_user_author) .", ";
				$sSQL.=$this->replace_null($cabFactura->fk_user_valid) .", ";
				$sSQL.=$this->replace_null($cabFactura->fk_facture_source).", ";
				$sSQL.=$this->replace_null($cabFactura->fk_project).", ";
				//$sSQL.=$this->replace_null($cabFactura->fk_cond_reglement).", ";
				//$sSQL.=$cabFactura->fk_mode_reglement.", ";
				$sSQL.="'".$cabFactura->date_lim_reglement."', ";
				$sSQL.="'".$this->replace_null($cabFactura->note=$object->note)."', ";
				$sSQL.="'".$this->replace_null($cabFactura->note_public)."', ";
				$sSQL.="'".$cabFactura->model_pdf."', ";
				$sSQL.="'".var_dump($cabFactura->extraparams)."')";
				
				
				//echo $sSQL."<br>";
				$db->query($sSQL);
				$nuevoId="select rowid from llx_facture where facnumber='".$cabFactura->facnumber."'";
				$rs=$db->query($nuevoId);
				$rowSelect=$db->fetch_object($rs);
				//echo "Noewvo RowID: ".$rowSelect->rowid."<br>";
				
				$cabFactura->id=$rowSelect->rowid;
				//echo "ROWID: ".$cabFactura->id." -- Nuevo ID=".$nuevoId."<br>";
				$idActual=$rowSelect->rowid;
				$aFacturas[]=$idActual;
				$item=1;
				
				//Relacion entre facturas creadas y pedidos
				
				
				
			}
			$sSQLDetalle="update llx_facturedet set fk_facture='".$cabFactura->id."' where rowid=".$idLine;
			//echo $sSQLDetalle."<br>";
			$db->query($sSQLDetalle);
		}
		
		foreach ($aFacturas as $factura=>$valor)
		{
			//Calculo total Gravado
			$sqlTotal="select sum(total_ht) as totalGravado from llx_facturedet where fk_facture=".$valor;
			$qryTotal=$db->query($sqlTotal);
			$rs=$db->fetch_object($qryTotal);
			
			if($rs)
				$total=$rs->totalGravado;
			else 
				$total=0;
			
			//Calculo total TVA
			$sqlTotalTVA="select sum(total_tva) as totalTVA from llx_facturedet where fk_facture=".$valor;
			//echo $sqlTotalTVA."<br>";
			$qryTotalTVA=$db->query($sqlTotalTVA);
			$rsTVA=$db->fetch_object($qryTotalTVA);
			if($rsTVA)
				$total_tva=$rsTVA->totalTVA;
			else
				$total_tva=0;
				
			//Calculo total ttc
			$sqlTotalTTC="select sum(total_ttc) as totalTTC from llx_facturedet where fk_facture=".$valor;
			//echo $sqlTotalTTC."<br>";
			$qryTotalTTC=$db->query($sqlTotalTTC);
			$rsTTC=$db->fetch_object($qryTotalTTC);
			if($rsTTC)
				$total_ttc =$rsTTC->totalTTC;
			else
				$total_ttc=0;
				
			
			$sSQLUpd="update llx_facture set total=$total, tva=$total_tva, total_ttc=$total_ttc where rowid=".$valor;
			
			$db->query($sSQLUpd);
			
			$sSQLElementInsert = "insert into llx_element_element (fk_source, sourcetype, fk_target, targettype) ";
			$sSQLElementInsert.= "VALUES ($fk_source, 'commande', $valor, 'facture');";
			//echo $sSQLElementInsert."<br>";
			$db->query($sSQLElementInsert);
				
		}
		
		return $aFacturas;
		
	}
	
	public function getPedido($idFactura)
	{
		global $db;
		$sSQLPedido="select * from llx_element_element where fk_target=$idFactura and targettype='facture'";
		$query=$db->query($sSQLPedido);
		$rs=$db->fetch_object($query);
		if($rs)
		{
			$dev=$rs->fk_source;
		}
		else
			$dev=0;
		
		return $dev;
	}
	
	function replace_null($str)

	{
		if(is_null($str))
			return 'null';
		else
			return $str;				
	}
	
}
?>