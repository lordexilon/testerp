<?php
/* Copyright (C) 2005-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * or see http://www.gnu.org/
 */

/**
 *	\file       htdocs/pos/backend/numerotation/mod_ticket_barx.php
 *	\ingroup    facture
 *	\brief      File containing class for numbering module barx
 */
dol_include_once('/pos/backend/numerotation/modules_ticket.php');

/**	    \class      mod_facture_barx
 *		\brief      Classe du modele de numerotation de reference de facture Barx
 */
class mod_ticket_barx extends ModeleNumRefTickets
{
	var $version='dolibarr';
	var $prefixticket='TK';
	var $prefixticketreturn='TR';
	var $error='';

	/**     \brief      Renvoi la description du modele de numerotation
	 *      \return     string      Texte descripif
	 */
	function info()
	{
		global $langs;
		$langs->load("pos@pos");
		return $langs->trans('BarxNumRefModelDesc1',$this->prefixticket,$this->prefixcreditnote);
	}

	/**     \brief      Renvoi un exemple de numerotation
	 *      \return     string      Example
	 */
	function getExample()
	{
		return $this->prefixticket."0501-0001";
	}

	/**     \brief      Test si les numeros deja en vigueur dans la base ne provoquent pas de
	 *                  de conflits qui empechera cette numerotation de fonctionner.
	 *      \return     boolean     false si conflit, true si ok
	 */
	function canBeActivated()
	{
		global $langs,$conf;

		$langs->load("pos@pos");

		// Check ticket num
		$fayymm=''; $max='';

		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ticketnumber FROM ".$posindice.")) as max";	// This is standard SQL
		$sql.= " FROM ".MAIN_DB_PREFIX."pos_ticket";
		$sql.= " WHERE ticketnumber LIKE '".$this->prefixticket."____-%'";

		$resql=$db->query($sql);
		if ($resql)
		{
			$row = $db->fetch_row($resql);
			if ($row) { $fayymm = substr($row[0],0,6); $max=$row[0]; }
		}
		if ($fayymm && ! preg_match('/'.$this->prefixticket.'[0-9][0-9][0-9][0-9]/i',$fayymm))
		{
			$langs->load("errors");
			$this->error=$langs->trans('ErrorNumRefModel',$max);
			return false;
		}

		// Check credit note num
		$fayymm='';

		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ticketnumber FROM ".$posindice.")) as max";	// This is standard SQL
		$sql.= " FROM ".MAIN_DB_PREFIX."pos_ticket";
		$sql.= " WHERE ticketnumber LIKE '".$this->prefixcreditnote."____-%'";

		$resql=$db->query($sql);
		if ($resql)
		{
			$row = $db->fetch_row($resql);
			if ($row) { $fayymm = substr($row[0],0,6); $max=$row[0]; }
		}

		return true;
	}

	/**     Return next value not used or last value used
	 *      @param     objsoc		Object third party
	 *      @param     ticket		Object ticket
     *      @param     mode         'next' for next value or 'last' for last value
	 *      @return    string       Value
	 */
	function getNextValue($objsoc,$ticket,$mode='next')
	{
		global $db,$conf;

		$prefix=$this->prefixticket;
		
		if ($ticket->type == 1) $prefix=$this->prefixticketreturn;
		else $prefix=$this->prefixticket;

		// D'abord on recupere la valeur max
		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ticketnumber FROM ".$posindice.")) as max";	// This is standard SQL
		$sql.= " FROM ".MAIN_DB_PREFIX."pos_ticket";
		$sql.= " WHERE ticketnumber LIKE '".$prefix."____-%'";

		$resql=$db->query($sql);
		dol_syslog("mod_ticket_barx::getNextValue sql=".$sql);
		if ($resql)
		{
			$obj = $db->fetch_object($resql);
			if ($obj) $max = intval($obj->max);
			else $max=0;
		}
		else
		{
			dol_syslog("mod_ticket_barx::getNextValue sql=".$sql, LOG_ERR);
			return -1;
		}

		if ($mode == 'last')
		{
            $num = sprintf("%04s",$max);

            $ref='';
            $sql = "SELECT ticketnumber as ref";
            $sql.= " FROM ".MAIN_DB_PREFIX."pos_ticket";
            $sql.= " WHERE ticketnumber LIKE '".$prefix."____-".$num."'";

            dol_syslog("mod_ticket_barx::getNextValue sql=".$sql);
            $resql=$db->query($sql);
            if ($resql)
            {
                $obj = $db->fetch_object($resql);
                if ($obj) $ref = $obj->ref;
            }
            else dol_print_error($db);

            return $ref;
		}
		else if ($mode == 'next')
		{
    		$date=time();	// This is ticket date (not creation date)
    		$yymm = strftime("%y%m",$date);
    		$num = sprintf("%04s",$max+1);

    		dol_syslog("mod_ticket_barx::getNextValue return ".$prefix.$yymm."-".$num);
    		return $prefix.$yymm."-".$num;
		}
		else dol_print_error('','Bad parameter for getNextValue');
	}

	/**		Return next free value
	 *     	@param      objsoc      Object third party
	 * 		@param		objforref	Object for number to search
     *      @param      mode        'next' for next value or 'last' for last value
	 *   	@return     string      Next free value
	 */
	function getNumRef($objsoc,$objforref,$mode='next')
	{
		return $this->getNextValue($objsoc,$objforref,$mode);
	}

}

?>