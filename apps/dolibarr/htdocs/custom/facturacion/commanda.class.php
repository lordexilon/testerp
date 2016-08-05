<?php
//require '../main.inc.php';
//require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
//require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/commande/class/commande.class.php';


class comanda extends Commande
{

	function getFactureUrl($withpicto=0,$option=0,$max=0,$short=0)
		{
			global $conf, $langs;
		
			$result='';
		
			if (! empty($conf->expedition->enabled) && ($option == 1 || $option == 2)) $url = DOL_URL_ROOT.'/expedition/shipment.php?id='.$this->id;
			else $url = DOL_URL_ROOT.'/facturacion/fiche.php?id='.$this->id;
		
			if ($short) return $url;
		
			$linkstart = '<a href="'.$url.'">';
			$linkend='</a>';
		
			$picto='order';
			$label=$langs->trans("ShowOrder").': '.$this->ref;
		
			if ($withpicto) $result.=($linkstart.img_object($label,$picto).$linkend);
			if ($withpicto && $withpicto != 2) $result.=' ';
			$result.=$linkstart.$this->ref.$linkend;
			return $result;
		}
}
?>