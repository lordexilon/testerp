<?php
/* Copyright (C) 2011 Juanjo Menent           <jmenent@2byte.es>
 * Copyright (C) 2011 Jorge Donet
 * Copyright (C) 2012 Ferran Marcet           <fmarcet@2byte.es> 
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU  *General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 *	\file       htdocs/pos/ajax_pos.php
 *	\ingroup    ticket
 *	\brief      Tickets home page
 *	\version    $Id: ajax_pos.php,v 1.2 2011-06-30 11:00:41 jdonet Exp $
*/
$res=@include("../../main.inc.php");                                   // For root directory
if (! $res) $res=@include("../../../main.inc.php");                // For "custom" directory

require_once(DOL_DOCUMENT_ROOT."/core/lib/functions.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formfile.class.php");
require_once(DOL_DOCUMENT_ROOT ."/core/class/notify.class.php");
require_once(DOL_DOCUMENT_ROOT ."/doliposbar/backend/class/pos.class.php");



		$cash['employeeId']=1;
		$cash['moneyincash']=100;
		$cash['amount_diff']=40;
		$cash['type']=1;
		$cash['print']=1;
		$result = POS::setControlCash($cash);
		echo json_encode($result);

