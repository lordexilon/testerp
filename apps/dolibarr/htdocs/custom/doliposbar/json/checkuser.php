<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */

$res=@include("../../master.inc.php");
if (! $res) $res=@include("../../../master.inc.php");               // For "custom" directory
include_once('../frontend/class/auth.class.php');
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");


$username = GETPOST("username");
$password = GETPOST("password");



$auth = new Auth($db);
$retour = $auth->verif ($username, $password);

if ( $retour >= 0 ) echo '{ "access":"ok"}';



?>