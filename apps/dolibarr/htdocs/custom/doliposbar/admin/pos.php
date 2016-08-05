<?php
/* Copyright (C) 2008-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011 	   Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2012 	   Ferran Marcet        <fmarcet@2byte.es>
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
 */

/**
 *	\file       htdocs/cashdesk/admin/cashdesk.php
 *	\ingroup    cashdesk
 *	\brief      Setup page for cashdesk module
 */

$res=@include("../../main.inc.php");                                   // For root directory
if (! $res) $res=@include("../../../main.inc.php");                // For "custom" directory

//require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php"); //V3.2
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
dol_include_once("/doliposbar/backend/class/ticket.class.php");

dol_include_once("/doliposbar/core/class/html.form.class.php");


// Security check
if (!$user->admin)
accessforbidden();

$langs->load("admin");
$langs->load("pos@doliposbar");


/*
 * Actions
 */

if (GETPOST('action','string') == 'updateMask')
{
    $maskconstticket=GETPOST('maskconstticket');
    $maskconstticketcredit=GETPOST('maskconstticketcredit');
    $maskticket=GETPOST('maskticket');
    $maskcredit=GETPOST('maskcredit');
    $maskconstfacsim=GETPOST('maskconstfacsim');
    $maskconstfacsimcredit=GETPOST('maskconstfacsimcredit');
    $maskfacsim=GETPOST('maskfacsim');
    $maskfacsimcredit=GETPOST('maskfacsimcredit');
	$maskconstclosecash=GETPOST('maskconstclosecash');
    $maskconstclosecasharq=GETPOST('maskconstclosecasharq');
    $maskclosecash=GETPOST('maskclosecash');
    $maskclosecasharq=GETPOST('maskclosecasharq');
    if ($maskconstticket) dolibarr_set_const($db,$maskconstticket,$maskticket,'chaine',0,'',$conf->entity);
    if ($maskconstticketcredit) dolibarr_set_const($db,$maskconstticketcredit,$maskcredit,'chaine',0,'',$conf->entity);
    if ($maskconstfacsim) dolibarr_set_const($db,$maskconstfacsim,$maskfacsim,'chaine',0,'',$conf->entity);
    if ($maskconstfacsimcredit) dolibarr_set_const($db,$maskconstfacsimcredit,$maskfacsimcredit,'chaine',0,'',$conf->entity);
    if ($maskconstclosecash) dolibarr_set_const($db,$maskconstclosecash,$maskclosecash,'chaine',0,'',$conf->entity);
    if ($maskconstclosecasharq) dolibarr_set_const($db,$maskconstclosecasharq,$maskclosecasharq,'chaine',0,'',$conf->entity);
}

dolibarr_set_const($db,"DOLIPOSBARPRO", 1,'chaine',0,'',$conf->entity);

if (GETPOST("action") == 'set')
{
	$db->begin();
	$res = dolibarr_set_const($db,"POS_SERVICES", GETPOST("POS_SERVICES"),'chaine',0,'',$conf->entity);

	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_PLACES", GETPOST("POS_PLACES"),'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_USE_TICKETS", 0,'chaine',0,'',$conf->entity);
			
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_STOCK", 1,'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_MAX_TTC", GETPOST("POS_MAX_TTC"),'chaine',0,'',$conf->entity);

	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_PRINT", GETPOST("POS_PRINT"),'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_MAIL", GETPOST("POS_MAIL"),'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_FACTURE", 1,'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"REWARDS_POS", GETPOST("REWARDS_POS"),'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_DEFAULT_CASH", GETPOST("POS_DEFAULT_CASH"),'chaine',0,'',$conf->entity);

	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_DEFAULT_BANK", GETPOST("POS_DEFAULT_BANK"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_DEFAULT_THIRD", GETPOST("POS_DEFAULT_THIRD"),'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"POS_DEFAULT_WAREHOUSE", GETPOST("POS_DEFAULT_WAREHOUSE"),'chaine',0,'',$conf->entity);
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"BARPRINTERNAME", GETPOST("BARPRINTERNAME"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"KITCHENPRINTERNAME", GETPOST("KITCHENPRINTERNAME"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"CASHDRAWERENABLE", GETPOST("CASHDRAWERENABLE"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"BARHEADTEXT1", GETPOST("BARHEADTEXT1"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"BARHEADTEXT2", GETPOST("BARHEADTEXT2"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"BARHEADTEXT3", GETPOST("BARHEADTEXT3"),'chaine',0,'',$conf->entity);	

	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"BARFOOTERTEXT", GETPOST("FOOTERTEXT"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
	$res = dolibarr_set_const($db,"DOLIPOSFRONTENDCOLOR", GETPOST("DOLIPOSFRONTENDCOLOR"),'chaine',0,'',$conf->entity);	
	
	if (! $res > 0) $error++;
 	if (! $error)
    {
        $db->commit();
        $mesg = "<font class=\"ok\">".$langs->trans("SetupSaved")."</font>";
    }
    else
    {
        $db->rollback();
        $mesg = "<font class=\"error\">".$langs->trans("Error")."</font>";
    }
}

if ($_GET["action"] == 'setmod')
{
    dolibarr_set_const($db, "TICKET_ADDON",$_GET["value"],'chaine',0,'',$conf->entity);
}
if ($_GET["action"] == 'setmodfacsim')
{
	dolibarr_set_const($db, "FACSIM_ADDON",$_GET["value"],'chaine',0,'',$conf->entity);
}
if ($_GET["action"] == 'setmodclosecash')
{
	dolibarr_set_const($db, "CLOSECASH_ADDON",$_GET["value"],'chaine',0,'',$conf->entity);
}

/*
 * View
 */
$helpurl='EN:Module_DoliPos|FR:Module_DoliPos_FR|ES:M&oacute;dulo_DoliPos';
llxHeader('',$langs->trans("POSSetup"),$helpurl);
dol_include_once('/doliposbar/backend/class/utils.class.php');

$html=new Form($db);

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("POSSetup"),$linkback,'setup');
print '<br>';

print_titre($langs->trans("DesktopVersion"));

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td colspan="2">'.$langs->trans("DownloadDesktopVersion").'</td>';
print '</tr>'."\n";
print '<tr></tr>';
print '<tr><td rowspan=2">'.$langs->trans("InstructionsDownload").'</td><td rowspan="2"><div class="inline-block divButAction"><a class="butAction" style="background: none repeat scroll 0 0 red; color:white;float:left" target="_blank" href="https://www.dropbox.com/sh/bbndwov9b6alwe0/AABWCrMqXSd3ufKbSysx1UYpa?dl=0">'.$langs->trans("Download").'</a></div></td></tr>';
print '</table>';
print '<br>';

if($conf->global->POS_USE_TICKETS == 1){
print_titre($langs->trans("TicketsNumberingModule"));

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Name").'</td>';
print '<td>'.$langs->trans("Description").'</td>';
print '<td nowrap>'.$langs->trans("Example").'</td>';
print '<td align="center" width="60">'.$langs->trans("Status").'</td>';
print '<td align="center" width="16">'.$langs->trans("Infos").'</td>';
print '</tr>'."\n";

clearstatcache();

$var=true;
foreach ($conf->file->dol_document_root as $dirroot)
{
    $dir = $dirroot . "/doliposbar/backend/numerotation/";

    if (is_dir($dir))
    {
        $handle = opendir($dir);
        if (is_resource($handle))
        {
            while (($file = readdir($handle))!==false)
            {
                if (! is_dir($dir.$file) || (substr($file, 0, 1) <> '.' && substr($file, 0, 3) <> 'CVS'))
                {
                    $filebis = $file;
                    $classname = preg_replace('/\.php$/','',$file);
                    // For compatibility
                    if (! is_file($dir.$filebis))
                    {
                        $filebis = $file."/".$file.".modules.php";
                        $classname = "mod_ticket_".$file;
                    }
                    //print "x".$dir."-".$filebis."-".$classname;
                    if (! class_exists($classname) && is_readable($dir.$filebis) && (preg_match('/mod_/',$filebis) || preg_match('/mod_/',$classname)) && substr($filebis, dol_strlen($filebis)-3, 3) == 'php')
                    {
                        // Chargement de la classe de numerotation
                        require_once($dir.$filebis);

                        $module = new $classname($db);

                        // Show modules according to features level
                        if ($module->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) continue;
                        if ($module->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) continue;

                        if ($module->isEnabled())
                        {
                            $var = !$var;
                            print '<tr '.$bc[$var].'><td width="100">';
                            echo preg_replace('/mod_ticket_/','',preg_replace('/\.php$/','',$file));
                            print "</td><td>\n";

                            print $module->info();

                            print '</td>';

                            // Show example of numbering module
                            print '<td nowrap="nowrap">';
                            $tmp=$module->getExample();
                            if (preg_match('/^Error/',$tmp)) print $langs->trans($tmp);
                            else print $tmp;
                            print '</td>'."\n";

                            print '<td align="center">';
                            //print "> ".$conf->global->FACTURE_ADDON." - ".$file;
                            if ($conf->global->TICKET_ADDON == $file || $conf->global->TICKET_ADDON.'.php' == $file)
                            {
                                print img_picto($langs->trans("Activated"),'on');
                            }
                            else
                            {
                                print '<a href="'.$_SERVER["PHP_SELF"].'?action=setmod&amp;value='.preg_replace('/\.php$/','',$file).'">'.img_picto($langs->trans("Disabled"),'off').'</a>';
                            }
                            print '</td>';

                            $facture=new Ticket($db);
                            $facture->initAsSpecimen();

                            // Example for standard invoice
                            $htmltooltip='';
                            $htmltooltip.=''.$langs->trans("Version").': <b>'.$module->getVersion().'</b><br>';
                            $facture->type=0;
                            $nextval=$module->getNextValue($mysoc,$facture);
                            if ("$nextval" != $langs->trans("NotAvailable"))	// Keep " on nextval
                            {
                                $htmltooltip.=$langs->trans("NextValueForTickets").': ';
                                if ($nextval)
                                {
                                    $htmltooltip.=$nextval.'<br>';
                                }
                                else
                                {
                                    $htmltooltip.=$langs->trans($module->error).'<br>';
                                }
                            }
                            

                            print '<td align="center">';
                            print $html->textwithpicto('',$htmltooltip,1,0);

                            if ($conf->global->TICKET_ADDON.'.php' == $file)  // If module is the one used, we show existing errors
                            {
                                if (! empty($module->error)) dol_htmloutput_mesg($module->error,'','error',1);
                            }

                            print '</td>';

                            print "</tr>\n";

                        }
                    }
                }
            }
            closedir($handle);
        }
    }
}

print '</table>';

print "<br>";
}
if($conf->global->POS_FACTURE == 1){
print_titre($langs->trans("FacsimNumberingModule"));

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Name").'</td>';
print '<td>'.$langs->trans("Description").'</td>';
print '<td nowrap>'.$langs->trans("Example").'</td>';
print '<td align="center" width="60">'.$langs->trans("Status").'</td>';
print '<td align="center" width="16">'.$langs->trans("Infos").'</td>';
print '</tr>'."\n";

clearstatcache();

$var=true;
foreach ($conf->file->dol_document_root as $dirroot)
{
	$dir = $dirroot . "/doliposbar/backend/numerotation/numerotation_facsim/";

	if (is_dir($dir))
	{
		$handle = opendir($dir);
		if (is_resource($handle))
		{
			while (($file = readdir($handle))!==false)
			{
				if (! is_dir($dir.$file) || (substr($file, 0, 1) <> '.' && substr($file, 0, 3) <> 'CVS'))
				{
					$filebis = $file;
					$classname = preg_replace('/\.php$/','',$file);
					// For compatibility
					if (! is_file($dir.$filebis))
					{
						$filebis = $file."/".$file.".modules.php";
						$classname = "mod_facsim_".$file;
					}
					//print "x".$dir."-".$filebis."-".$classname;
					if (! class_exists($classname) && is_readable($dir.$filebis) && (preg_match('/mod_/',$filebis) || preg_match('/mod_/',$classname)) && substr($filebis, dol_strlen($filebis)-3, 3) == 'php')
					{
						// Chargement de la classe de numerotation
						require_once($dir.$filebis);

						$module = new $classname($db);

						// Show modules according to features level
						if ($module->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) continue;
						if ($module->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) continue;

						if ($module->isEnabled())
						{
							$var = !$var;
							print '<tr '.$bc[$var].'><td width="100">';
							echo preg_replace('/mod_facsim_/','',preg_replace('/\.php$/','',$file));
							print "</td><td>\n";

							print $module->info();

							print '</td>';

							// Show example of numbering module
							print '<td nowrap="nowrap">';
							$tmp=$module->getExample();
							if (preg_match('/^Error/',$tmp)) print $langs->trans($tmp);
							else print $tmp;
							print '</td>'."\n";

							print '<td align="center">';
							//print "> ".$conf->global->FACTURE_ADDON." - ".$file;
							if ($conf->global->FACSIM_ADDON == $file || $conf->global->FACSIM_ADDON.'.php' == $file)
							{
								print img_picto($langs->trans("Activated"),'on');
							}
							else
							{
								print '<a href="'.$_SERVER["PHP_SELF"].'?action=setmodfacsim&amp;value='.preg_replace('/\.php$/','',$file).'">'.img_picto($langs->trans("Disabled"),'off').'</a>';
							}
							print '</td>';

							$facture=new Ticket($db);
							$facture->initAsSpecimen();

							// Example for standard invoice
							$htmltooltip='';
							$htmltooltip.=''.$langs->trans("Version").': <b>'.$module->getVersion().'</b><br>';
							$facture->type=0;
							$nextval=$module->getNextValue($mysoc,$facture);
							if ("$nextval" != $langs->trans("NotAvailable"))	// Keep " on nextval
							{
								$htmltooltip.=$langs->trans("NextValueForFacsims").': ';
								if ($nextval)
								{
									$htmltooltip.=$nextval.'<br>';
								}
								else
								{
									$htmltooltip.=$langs->trans($module->error).'<br>';
								}
							}


							print '<td align="center">';
							print $html->textwithpicto('',$htmltooltip,1,0);

							if ($conf->global->FACSIM_ADDON.'.php' == $file)  // If module is the one used, we show existing errors
							{
								if (! empty($module->error)) dol_htmloutput_mesg($module->error,'','error',1);
							}

							print '</td>';

							print "</tr>\n";

						}
					}
				}
			}
			closedir($handle);
		}
	}
}

print '</table>';

print "<br>";
}


if ($conf->global->DOLIPOSBARPRO==1) {
	print_titre($langs->trans("CloseCashNumberingModule"));

	print '<table class="noborder" width="100%">';
	print '<tr class="liste_titre">';
	print '<td>'.$langs->trans("Name").'</td>';
	print '<td>'.$langs->trans("Description").'</td>';
	print '<td nowrap>'.$langs->trans("Example").'</td>';
	print '<td align="center" width="60">'.$langs->trans("Status").'</td>';
	print '<td align="center" width="16">'.$langs->trans("Infos").'</td>';
	print '</tr>'."\n";

	clearstatcache();

	$var=true;
	foreach ($conf->file->dol_document_root as $dirroot)
	{
		$dir = $dirroot . "/doliposbar/backend/numerotation/numerotation_closecash/";

		if (is_dir($dir))
		{
			$handle = opendir($dir);
			if (is_resource($handle))
			{
				while (($file = readdir($handle))!==false)
				{
					if (! is_dir($dir.$file) || (substr($file, 0, 1) <> '.' && substr($file, 0, 3) <> 'CVS'))
					{
						$filebis = $file;
						$classname = preg_replace('/\.php$/','',$file);
						// For compatibility
						if (! is_file($dir.$filebis))
						{
							$filebis = $file."/".$file.".modules.php";
							$classname = "mod_closecash_".$file;
						}
						//print "x".$dir."-".$filebis."-".$classname;
						if (! class_exists($classname) && is_readable($dir.$filebis) && (preg_match('/mod_/',$filebis) || preg_match('/mod_/',$classname)) && substr($filebis, dol_strlen($filebis)-3, 3) == 'php')
						{
							// Chargement de la classe de numerotation
							require_once($dir.$filebis);

							$module = new $classname($db);

							// Show modules according to features level
							if ($module->version == 'development'  && $conf->global->MAIN_FEATURES_LEVEL < 2) continue;
							if ($module->version == 'experimental' && $conf->global->MAIN_FEATURES_LEVEL < 1) continue;

							if ($module->isEnabled())
							{
								$var = !$var;
								print '<tr '.$bc[$var].'><td width="100">';
								echo preg_replace('/mod_closecash_/','',preg_replace('/\.php$/','',$file));
								print "</td><td>\n";

								print $module->info();

								print '</td>';

								// Show example of numbering module
								print '<td nowrap="nowrap">';
								$tmp=$module->getExample();
								if (preg_match('/^Error/',$tmp)) print $langs->trans($tmp);
								else print $tmp;
								print '</td>'."\n";

								print '<td align="center">';
								//print "> ".$conf->global->FACTURE_ADDON." - ".$file;
								if ($conf->global->CLOSECASH_ADDON == $file || $conf->global->CLOSECASH_ADDON.'.php' == $file)
								{
									print img_picto($langs->trans("Activated"),'on');
								}
								else
								{
									print '<a href="'.$_SERVER["PHP_SELF"].'?action=setmodclosecash&amp;value='.preg_replace('/\.php$/','',$file).'">'.img_picto($langs->trans("Disabled"),'off').'</a>';
								}
								print '</td>';

								$facture=new Ticket($db);
								$facture->initAsSpecimen();

								// Example for standard invoice
								$htmltooltip='';
								$htmltooltip.=''.$langs->trans("Version").': <b>'.$module->getVersion().'</b><br>';
								$facture->type_control=1;
								$nextval=$module->getNextValue($mysoc,$facture);
								if ("$nextval" != $langs->trans("NotAvailable"))	// Keep " on nextval
								{
									$htmltooltip.=$langs->trans("NextValueForclosecash").': ';
									if ($nextval)
									{
										$htmltooltip.=$nextval.'<br>';
									}
									else
									{
										$htmltooltip.=$langs->trans($module->error).'<br>';
									}
								}


								print '<td align="center">';
								print $html->textwithpicto('',$htmltooltip,1,0);

								if ($conf->global->CLOSECASH_ADDON.'.php' == $file)  // If module is the one used, we show existing errors
								{
									if (! empty($module->error)) dol_htmloutput_mesg($module->error,'','error',1);
								}

								print '</td>';

								print "</tr>\n";

							}
						}
					}
				}
				closedir($handle);
			}
		}
	}

	print '</table>';

	print "<br>";
	
}




print_titre($langs->trans("OtherOptions"));

// Mode
$var=true;
print '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="set">';

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameters").'</td><td>'.$langs->trans("Value").'</td>';
print "</tr>\n";


/* NOT IMPLEMENTET IN DOLIPOS BAR
$var=! $var;
print '<tr '.$bc[$var].'><td>';
print $langs->trans("POSUseTickets");
print '<td colspan="2">';
if($conf->global->POS_FACTURE == 0)
	$disable=true;
else
	$disable=false;
print $html->selectyesno("POS_USE_TICKETS",$conf->global->POS_USE_TICKETS,1,$disable);
if($disable)print '<input type="hidden" name="POS_USE_TICKETS" value="'.$conf->global->POS_USE_TICKETS.'">';
print "</td></tr>\n";*/

$var=! $var;
print '<tr '.$bc[$var].'><td>';
print $langs->trans("POSFactureTicket");
print '<td colspan="2">';
if($conf->global->POS_USE_TICKETS == 0)
	$disable=true;
else
	$disable=false;
print $html->selectyesno("POS_FACTURE",$conf->global->POS_FACTURE,1,$disable);
if($disable) print '<input type="hidden" name="POS_FACTURE" value="'.$conf->global->POS_FACTURE.'">';
print "</td></tr>\n";

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("POSMaxTTC").'</td>';
print '<td><input type="text" class="flat" name="POS_MAX_TTC" value="'. ($_POST["POS_MAX_TTC"]?$_POST["POS_MAX_TTC"]:$conf->global->POS_MAX_TTC) . '" size="8"> '.$langs->trans("Currency".$conf->currency).'</td>';
print '</tr>';

/* NOT IMPLEMENTET IN DOLIPOS BAR
$var=! $var;
print '<tr '.$bc[$var].'><td>';
print $langs->trans("POSSellStock");
print '<td colspan="2">';;
print $html->selectyesno("POS_STOCK",$conf->global->POS_STOCK,1);
print "</td></tr>\n"; */


// Cash acount
print '<tr><td valign="top">'.$langs->trans("PaymentCash").'</td>';
print '<td colspan="3">';
$sql = "SELECT rowid, label, bank";
$sql.= " FROM ".MAIN_DB_PREFIX."bank_account";
$sql.= " WHERE courant = '2'";
$sql.= " ORDER BY label";
$result=$db->query($sql);
        if ($result)
        {

            
                print '<select class="flat" name="POS_DEFAULT_CASH">';

			while($row = $db->fetch_array ($result))
                {
                        print '<option value="'.$row[0].'" ';
						if ($conf->global->POS_DEFAULT_CASH==$row[0]) print 'SELECTED';
						print '>'.$row[1].'</option>';
                }
                print "</select>";
        }
		
        else
            {
                print $langs->trans("NoActiveBankAccountDefined");
            }
 print '</td></tr>';       



 
 
 // Bank acount
print '<tr><td valign="top">'.$langs->trans("PaymentBank").'</td>';
print '<td colspan="3">';
$sql = "SELECT rowid, label, bank";
$sql.= " FROM ".MAIN_DB_PREFIX."bank_account";
$sql.= " WHERE courant = '1'";
$sql.= " ORDER BY label";
$result=$db->query($sql);
        if ($result)
        {

            
                print '<select class="flat" name="POS_DEFAULT_BANK">';

			while($row = $db->fetch_array ($result))
                {
                        print '<option value="'.$row[0].'" ';
						if ($conf->global->POS_DEFAULT_BANK==$row[0]) print 'SELECTED';
						print '>'.$row[1].'</option>';
                }
                print "</select>";
        }
		
        else
            {
                print $langs->trans("NoActiveBankAccountDefined");
            }
 print '</td></tr>';  
 

// THIRD
 print '<tr><td valign="top">'.$langs->trans("CashDeskThirdPartyForSell").'</td>';
 print '<td colspan="3">';
 $sql = "SELECT rowid, nom";
 $sql.= " FROM ".MAIN_DB_PREFIX."societe";
 $sql.= " ORDER BY nom";
 $result=$db->query($sql);
 if ($result)
 {
 
 
 	print '<select class="flat" name="POS_DEFAULT_THIRD">';
 
 	while($row = $db->fetch_array ($result))
 	{
 		print '<option value="'.$row[0].'" ';
 		if ($conf->global->POS_DEFAULT_THIRD==$row[0]) print 'SELECTED';
 		print '>'.$row[1].'</option>';
 	}
 	print "</select>";
 }
 
 print '</td></tr>'; 
 
 

// WAREHOUSE
if ($conf->stock->enabled)
{
 print '<tr><td valign="top">'.$langs->trans("CashDeskIdWareHouse").'</td>';
 print '<td colspan="3">';
 $sql = "SELECT rowid, label";
 $sql.= " FROM ".MAIN_DB_PREFIX."entrepot";
 $sql.= " ORDER BY label";
 $result=$db->query($sql);
 if ($result)
 {
 
 
 	print '<select class="flat" name="POS_DEFAULT_WAREHOUSE">';
 
 	while($row = $db->fetch_array ($result))
 	{
 		print '<option value="'.$row[0].'" ';
 		if ($conf->global->POS_DEFAULT_WAREHOUSE==$row[0]) print 'SELECTED';
 		print '>'.$row[1].'</option>';
 	}
 	print "</select>";
 }
 
 print '</td></tr>'; 
}
 


// MAIN PRINTER
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("BARPRINTERNAME").'</td>';
print '<td><input type="text" class="flat" name="BARPRINTERNAME" value="'. ($_POST["BARPRINTERNAME"]?$_POST["BARPRINTERNAME"]:$conf->global->BARPRINTERNAME) . '" size="18"></td>';
print '</tr>';

 

// KITCHEN PRINTER
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("KITCHENPRINTERNAME").'</td>';
print '<td><input type="text" class="flat" name="KITCHENPRINTERNAME" value="'. ($_POST["KITCHENPRINTERNAME"]?$_POST["KITCHENPRINTERNAME"]:$conf->global->KITCHENPRINTERNAME) . '" size="18"></td>';
print '</tr>';


//CASH DRAWER
$var=! $var;
print '<tr '.$bc[$var].'><td>';
print $langs->trans("CashDrawerEnable");
print '<td colspan="2">';
print $html->selectyesno("CASHDRAWERENABLE",$conf->global->CASHDRAWERENABLE,1);
print "</td></tr>\n";


// HEAD TEXT
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("HEADTEXT").' 1</td>';
print '<td><input type="text" class="flat" name="BARHEADTEXT1" value="'. ($_POST["BARHEADTEXT1"]?$_POST["BARHEADTEXT1"]:$conf->global->BARHEADTEXT1) . '" size="18"></td>';
print '</tr>'; 


// HEAD TEXT 2
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("HEADTEXT").' 2</td>';
print '<td><input type="text" class="flat" name="BARHEADTEXT2" value="'. ($_POST["BARHEADTEXT2"]?$_POST["BARHEADTEXT2"]:$conf->global->BARHEADTEXT2) . '" size="18"></td>';
print '</tr>'; 


// HEAD TEXT 
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("HEADTEXT").' 3</td>';
print '<td><input type="text" class="flat" name="BARHEADTEXT3" value="'. ($_POST["BARHEADTEXT3"]?$_POST["BARHEADTEXT3"]:$conf->global->BARHEADTEXT3) . '" size="18"></td>';
print '</tr>'; 


// FOOTER TEXT
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("FOOTERTEXT").'</td>';
print '<td><input type="text" class="flat" name="FOOTERTEXT" value="'. ($_POST["FOOTERTEXT"]?$_POST["FOOTERTEXT"]:$conf->global->BARFOOTERTEXT) . '" size="18"></td>';
print '</tr>'; 


// FRONTEND COLOR
print '
<STYLE type="text/css">
OPTION.COLORCCCCCC{background-color:#CCCCCC; color:black}
OPTION.COLOR888888{background-color:#888888; color:white}
OPTION.COLORFFFFFF{background-color:#FFFFFF; color:black}
OPTION.COLORC3D2FF{background-color:#C3D2FF; color:black}
OPTION.COLORC6FFBB{background-color:#C6FFBB; color:black}
OPTION.COLORFCFFB2{background-color:#FCFFB2; color:black}
OPTION.COLOR000000{background-color:black; color:white}
OPTION.COLORFFC967{background-color:#FFC967; color:black}
OPTION.COLORFF7E7E{background-color:#FF7E7E; color:black}
</STYLE>';
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("FrontendColor").'</td>';
print '<td>
<SELECT name="DOLIPOSFRONTENDCOLOR" default="'.$conf->global->DOLIPOSFRONTENDCOLOR.'">';
$arr = array('CCCCCC', '888888', 'FFFFFF', 'C3D2FF', 'FF7E7E', 'C6FFBB', 'FCFFB2', 'FFC967', '000000');
foreach ($arr as &$value) {
print '<OPTION class="COLOR'.$value.'" value="'.$value.'" ';
if ($conf->global->DOLIPOSFRONTENDCOLOR==$value) print 'selected';
print'>'.$langs->trans('COLOR'.$value).'</OPTION>';
}
print '
</SELECT>
</td>';
print '</tr>';  /*


/* Not implemented Dolipos BAR
if ($conf->service->enabled)
{
    $var=! $var;
    print '<tr '.$bc[$var].'><td>';
    print $langs->trans("POSShowServices");
    print '<td colspan="2">';;
    print $html->selectyesno("POS_SERVICES",$conf->global->POS_SERVICES,1);
    print "</td></tr>\n";
}

	$var=! $var;
	print '<tr '.$bc[$var].'><td>';
	print $langs->trans("POSShowPlaces");
	print '<td colspan="2">';;
	print $html->selectyesno("POS_PLACES",$conf->global->POS_PLACES,1);
	print "</td></tr>\n";
	
	$var=! $var;
	print '<tr '.$bc[$var].'><td>';
	print $langs->trans("POSSellStock");
	print '<td colspan="2">';;
	print $html->selectyesno("POS_STOCK",$conf->global->POS_STOCK,1);
	print "</td></tr>\n";

	$var=! $var;
	print '<tr '.$bc[$var].'><td>';
	print $langs->trans("POSPrintTicket");
	print '<td colspan="2">';;
	print $html->selectyesno("POS_PRINT",$conf->global->POS_PRINT,1);
	print "</td></tr>\n";
	
	$var=! $var;
	print '<tr '.$bc[$var].'><td>';
	print $langs->trans("POSMailTicket");
	print '<td colspan="2">';
	print $html->selectyesno("POS_MAIL",$conf->global->POS_MAIL,1);
	print "</td></tr>\n";
	
	$var=! $var;
	print '<tr '.$bc[$var].'><td>';
	print $langs->trans("POSRewards");
	if (! empty($conf->rewards->enabled))
	{
		print '<td colspan="2">';
		print $html->selectyesno("REWARDS_POS",$conf->global->REWARDS_POS,1);
	}
	else 
	{
		print '<td colspan="2">'.$langs->trans("NoRewardsInstalled").' '.$langs->trans("GetRewards","http://www.dolistore.com/search.php?orderby=position&orderway=desc&search_query=2rewards&submit_search=Buscar").'</td>';
	}
	print "</td></tr>\n";
	*/
	

print '</table>';
print '<br>';

print '<center><input type="submit" class="button" value="'.$langs->trans("Save").'"></center>';

print "</form>\n";

dol_htmloutput_mesg($mesg);

$db->close();

llxFooter();
?>