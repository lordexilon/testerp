<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
@$res=require_once("../master.inc.php");
if (! $res) $res=@include("../../main.inc.php");  
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
$addprint = GETPOST('addprint');
dolibarr_set_const($db,"PENDING_PRINT_BAR", $conf->global->PENDING_PRINT_BAR.$addprint.',','chaine',0,'',$conf->entity);
?>