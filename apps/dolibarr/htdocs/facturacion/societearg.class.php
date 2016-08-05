<?php

/* Copyright (C) 2002-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
* Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
* Copyright (C) 2003      Brian Fraval         <brian@fraval.org>
* Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
* Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
* Copyright (C) 2008      Patrick Raguin       <patrick.raguin@auguria.net>
* Copyright (C) 2010-2011 Juanjo Menent        <jmenent@2byte.es>
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/**
 *	\file       htdocs/societe/class/societe.class.php
*	\ingroup    societe
*	\brief      File for third party class
*/
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';


/**
 *	Class to manage third parties objects (customers, suppliers, prospects...)
 */
class Societearg extends Societe
{
	public $element='societe';
	public $table_element = 'societe';
	public $fk_element='fk_soc';
	protected $childtables=array("propal","commande","facture","contrat","facture_fourn","commande_fournisseur");    // To test if we can delete object
	protected $ismultientitymanaged = 1;	// 0=No test on entity, 1=Test with field entity, 2=Test with link by societe

	var $id;
	var $name;
	var $nom;      // TODO obsolete
	var $firstname;
	var $particulier;
	var $civility_id;
	var $address;
	var $adresse;  // TODO obsolete
	var $cp;       // TODO obsolete
	var $zip;
	var $ville;    // TODO obsolete
	var $town;
	var $status;   // 0=activity ceased, 1= in activity

	var $state_id;
	var $state_code;
	var $state;
	var $departement_id;     // deprecated
	var $departement_code;   // deprecated
	var $departement;        // deprecated

	var $pays_id;   // deprecated
	var $pays_code; // deprecated
	var $pays;	    // deprecated
	var $country_id;
	var $country_code;
	var $country;

	var $tel;        // deprecated
	var $phone;
	var $fax;
	var $email;
	var $url;

	//! barcode
	var $barcode;               // value
	var $barcode_type;          // id
	var $barcode_type_code;     // code (loaded by fetch_barcode)
	var $barcode_type_label;    // label (loaded by fetch_barcode)
	var $barcode_type_coder;    // coder (loaded by fetch_barcode)

	// 4 professional id (usage depend on country)
	var $idprof1;	// IdProf1 (Ex: Siren in France)
	var $idprof2;	// IdProf2 (Ex: Siret in France)
	var $idprof3;	// IdProf3 (Ex: Ape in France)
	var $idprof4;	// IdProf4 (Ex: RCS in France)

	var $prefix_comm;

	var $tva_assuj;
	var $tva_intra;

	// Local taxes
	var $localtax1_assuj;
	var $localtax2_assuj;

	var $capital;
	var $typent_id;
	var $typent_code;
	var $effectif_id;
	var $forme_juridique_code;
	var $forme_juridique;

	var $remise_percent;
	var $mode_reglement_id;
	var $cond_reglement_id;
	var $remise_client;  // TODO obsolete
	var $mode_reglement; // TODO obsolete
	var $cond_reglement; // TODO obsolete

	var $client;					// 0=no customer, 1=customer, 2=prospect, 3=customer and prospect
	var $prospect;					// 0=no prospect, 1=prospect
	var $fournisseur;				// 0=no supplier, 1=supplier

	var $code_client;
	var $code_fournisseur;
	var $code_compta;
	var $code_compta_fournisseur;

	var $note;
	//! code statut prospect
	var $stcomm_id;
	var $statut_commercial;

	var $price_level;

	var $datec;
	var $date_update;

	var $commercial_id;  // Id of sales representative to link (used for thirdparty creation). Not filled by a fetch, because we can have several sales representatives.
	var $default_lang;

	var $ref_int;
	var $import_key;

	var $logo;
	var $logo_small;
	var $logo_mini;

	var $oldcopy;

	/**
	 *    Constructor
	 *
	 *    @param	DoliDB		$db		Database handler
	 */
	public function __construct($db)
	{
		global $conf;

		$this->db = $db;

		$this->client = 0;
		$this->prospect = 0;
		$this->fournisseur = 0;
		$this->typent_id  = 0;
		$this->effectif_id  = 0;
		$this->forme_juridique_code  = 0;
		$this->tva_assuj = 1;
		$this->status = 1;

		return 1;
	}

function getCompanyUrl($withpicto=0,$option='',$maxlen=0)
{
        global $conf,$langs;

        $name=$this->name?$this->name:$this->nom;

$result='';
$lien=$lienfin='';

if ($option == 'customer' || $option == 'compta')
{
if (($this->client == 1 || $this->client == 3) && empty($conf->global->SOCIETE_DISABLE_CUSTOMERS))  // Only customer
{
$lien = '<a href="'.DOL_URL_ROOT.'/facturacion/fiche.php?socid='.$this->id;
}
elseif($this->client == 2 && empty($conf->global->SOCIETE_DISABLE_PROSPECTS))   // Only prospect
{
$lien = '<a href="'.DOL_URL_ROOT.'/comm/prospect/fiche.php?socid='.$this->id;
}
}
else if ($option == 'prospect' && empty($conf->global->SOCIETE_DISABLE_PROSPECTS))
{
$lien = '<a href="'.DOL_URL_ROOT.'/comm/prospect/fiche.php?socid='.$this->id;
}
else if ($option == 'supplier')
{
$lien = '<a href="'.DOL_URL_ROOT.'/fourn/fiche.php?socid='.$this->id;
}
// By default
if (empty($lien))
 {
 $lien = '<a href="'.DOL_URL_ROOT.'/societe/soc.php?socid='.$this->id;
 }

 // Add type of canvas
 $lien.=(!empty($this->canvas)?'&amp;canvas='.$this->canvas:'').'">';
 $lienfin='</a>';

            		if ($withpicto) $result.=($lien.img_object($langs->trans("ShowCompany").': '.$name,'company').$lienfin);
            		if ($withpicto && $withpicto != 2) $result.=' ';
            		$result.=$lien.($maxlen?dol_trunc($name,$maxlen):$name).$lienfin;

            		return $result;
            		}


}

?>
