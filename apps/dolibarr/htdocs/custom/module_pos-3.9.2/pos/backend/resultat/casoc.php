<?php
/* Copyright (C) 2012	Juanjo Menent	<jmenent@2byte.es>
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
 *       \file        htdocs/pos/backend/resultat/casoc.php
 *       \brief       Page ticket reporting  by customer
 */

$res=@include("../../../main.inc.php");                                   // For root directory
if (! $res) $res=@include("../../../../main.inc.php");                // For "custom" directory

require_once(DOL_DOCUMENT_ROOT."/core/lib/report.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/tax.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/date.lib.php");

$langs->load("companies");

$sortorder=GETPOST('sortorder','string');
$sortfield=GETPOST('sortfield','string');
if (! $sortorder) $sortorder="asc";
if (! $sortfield) $sortfield="name";

// Security check
$socid = GETPOST('socid','int');
if ($user->societe_id > 0) $socid = $user->societe_id;
if (!$user->rights->pos->stats)
accessforbidden();

// Date range
$year=GETPOST("year");
$month=GETPOST("month");
if (empty($year))
{
	$year_current = strftime("%Y",dol_now());
	$month_current = strftime("%m",dol_now());
	$year_start = $year_current;
} else {
	$year_current = $year;
	$month_current = strftime("%m",dol_now());
	$year_start = $year;
}
$date_start=dol_mktime(0,0,0,GETPOST("date_startmonth"),GETPOST("date_startday"),GETPOST("date_startyear"));
$date_end=dol_mktime(23,59,59,GETPOST("date_endmonth"),GETPOST("date_endday"),GETPOST("date_endyear"));
// Quarter
if (empty($date_start) || empty($date_end)) // We define date_start and date_end
{
	$q=GETPOST("q")?GETPOST("q"):0;
	if ($q==0)
	{
		// We define date_start and date_end
		$month_start=GETPOST("month")?GETPOST("month"):($conf->global->SOCIETE_FISCAL_MONTH_START?($conf->global->SOCIETE_FISCAL_MONTH_START):1);
		$year_end=$year_start;
		$month_end=$month_start;
		if (! GETPOST("month"))	// If month not forced
		{
			if (! GETPOST('year') && $month_start > $month_current)
			{
				$year_start--;
				$year_end--;
			}
			$month_end=$month_start-1;
			if ($month_end < 1) $month_end=12;
			else $year_end++;
		}
		$date_start=dol_get_first_day($year_start,$month_start,false); $date_end=dol_get_last_day($year_end,$month_end,false);
	}
	if ($q==1) { $date_start=dol_get_first_day($year_start,1,false); $date_end=dol_get_last_day($year_start,3,false); }
	if ($q==2) { $date_start=dol_get_first_day($year_start,4,false); $date_end=dol_get_last_day($year_start,6,false); }
	if ($q==3) { $date_start=dol_get_first_day($year_start,7,false); $date_end=dol_get_last_day($year_start,9,false); }
	if ($q==4) { $date_start=dol_get_first_day($year_start,10,false); $date_end=dol_get_last_day($year_start,12,false); }
}


/*
 * View
 */
$helpurl='EN:Module_DoliPos|FR:Module_DoliPos_FR|ES:M&oacute;dulo_DoliPos';
llxHeader('','',$helpurl);

$html=new Form($db);

$nom=$langs->trans("RapportSales").', '.$langs->trans("ByThirdParties");
$period=$html->select_date($date_start,'date_start',0,0,0,'',1,0,1).' - '.$html->select_date($date_end,'date_end',0,0,0,'',1,0,1);
$description=$langs->trans("RulesResult");
$builddate=time();

report_header($nom,$nomlink,$period,$periodlink,$description,$builddate,$exportlink);

// Load table
$catotal=0;

$sql = "SELECT s.rowid as socid, s.nom as name, sum(f.total_ht) as amount, sum(f.total_ttc) as amount_ttc";
	$sql.= " FROM ".MAIN_DB_PREFIX."societe as s";
	$sql.= ", ".MAIN_DB_PREFIX."pos_ticket as f";
	$sql.= " WHERE f.fk_statut in (1,2,3,4)";
   // $sql.= " AND (";
    //$sql.= " f.type = 0";          // Standard
    //$sql.= " OR f.type = 1";       // Credit note
    //$sql.= ")";
	$sql.= " AND f.fk_soc = s.rowid";
	if ($date_start && $date_end) $sql.= " AND f.date_closed >= '".$db->idate($date_start)."' AND f.date_closed <= '".$db->idate($date_end)."'";

$sql.= " AND f.entity = ".$conf->entity;
if ($socid) $sql.= " AND f.fk_soc = ".$socid;
$sql.= " GROUP BY s.rowid, s.nom";
$sql.= " ORDER BY s.rowid";

$result = $db->query($sql);
if ($result)
{
	$num = $db->num_rows($result);
	$i=0;
	while ($i < $num)
	{
		$obj = $db->fetch_object($result);
		$amount[$obj->socid] += $obj->amount_ttc;
		$name[$obj->socid] = $obj->name;
		$catotal+=$obj->amount_ttc;
		$i++;
	}
}
else {
	dol_print_error($db);
}


$i = 0;
print "<table class=\"noborder\" width=\"100%\">";
print "<tr class=\"liste_titre\">";
print_liste_field_titre($langs->trans("Company"),$_SERVER["PHP_SELF"],"name","",'&amp;year='.($year).'&modecompta='.$modecompta,"",$sortfield,$sortorder);
print_liste_field_titre($langs->trans("AmountTTC"),$_SERVER["PHP_SELF"],"amount_ttc","",'&amp;year='.($year).'&modecompta='.$modecompta,'align="right"',$sortfield,$sortorder);
print_liste_field_titre($langs->trans("Percentage"),$_SERVER["PHP_SELF"],"amount_ttc","",'&amp;year='.($year).'&modecompta='.$modecompta,'align="right"',$sortfield,$sortorder);
print_liste_field_titre($langs->trans("OtherStatistics"),$_SERVER["PHP_SELF"],"","","",'align="center" width="20%"');
print "</tr>\n";
$var=true;

if (sizeof($amount))
{
	$arrayforsort=$name;

	if ($sortfield == 'name' && $sortorder == 'asc') {
		asort($name);
		$arrayforsort=$name;
	}
	if ($sortfield == 'name' && $sortorder == 'desc') {
		arsort($name);
		$arrayforsort=$name;
	}
	if ($sortfield == 'amount_ttc' && $sortorder == 'asc') {
		asort($amount);
		$arrayforsort=$amount;
	}
	if ($sortfield == 'amount_ttc' && $sortorder == 'desc') {
		arsort($amount);
		$arrayforsort=$amount;
	}

	foreach($arrayforsort as $key=>$value)
	{
		$var=!$var;
		print "<tr ".$bc[$var].">";

		// Third party
		$fullname=$name[$key];
		if ($key > 0) {
			$linkname='<a href="'.DOL_URL_ROOT.'/societe/soc.php?socid='.$key.'">'.img_object($langs->trans("ShowCompany"),'company').' '.$fullname.'</a>';
		}
		
		print "<td>".$linkname."</td>\n";

		// Amount
		print '<td align="right">';
		$url = dol_buildpath('/pos/backend/liste.php?socid='.$key.'',1);
        if ($key > 0) print '<a href="'.$url.'">';
        else print '<a href="#">';
		
		print price($amount[$key]);
		print '</a>';
		print '</td>';

		// Percent;
		print '<td align="right">'.($catotal > 0 ? round(100 * $amount[$key] / $catotal, 2).'%' : '&nbsp;').'</td>';

        // Other stats
        print '<td align="center">';
        if ($conf->propal->enabled && $key>0) print '&nbsp;<a href="'.DOL_URL_ROOT.'/comm/propal/stats/index.php?socid='.$key.'">'.img_picto($langs->trans("ProposalStats"),"stats").'</a>&nbsp;';
        if ($conf->commande->enabled && $key>0) print '&nbsp;<a href="'.DOL_URL_ROOT.'/commande/stats/index.php?socid='.$key.'">'.img_picto($langs->trans("OrderStats"),"stats").'</a>&nbsp;';
        if ($conf->facture->enabled && $key>0) print '&nbsp;<a href="'.DOL_URL_ROOT.'/compta/facture/stats/index.php?socid='.$key.'">'.img_picto($langs->trans("InvoiceStats"),"stats").'</a>&nbsp;';
        print '</td>';

		print "</tr>\n";
		$i++;
	}

	// Total
	print '<tr class="liste_total"><td>'.$langs->trans("Total").'</td><td align="right">'.price($catotal).'</td><td>&nbsp;</td>';
	print '<td>&nbsp;</td>';
	print '</tr>';

	$db->free($result);
}

print "</table>";
print '<br>';

llxFooter();

$db->close();
?>