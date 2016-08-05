<?php
$res=@include("../../../main.inc.php");                                   // For root directory
//include("../../../core/class/html.form.class.php");  
dol_include_once('/doliposbar/backend/class/ticket.class.php');
dol_include_once('/doliposbar/backend/class/cash.class.php');
require_once(DOL_DOCUMENT_ROOT."/core/lib/company.lib.php");
global $langs, $db;

$langs->load("main");
$langs->load("pos@doliposbar");
header("Content-type: text/html; charset=".$conf->file->character_set_client);
$id=GETPOST('id');
$terminal=GETPOST('terminal');
?>
<html>
<head>

<style type="text/css">

	body {
		font-size: 1.5em;
		position: relative;
	}

	.entete {
/* 		position: relative; */
	}

		.adresse {
/* 			float: left; */
			font-size: 12px;
		}

		.date_heure {
			position: absolute;
			top: 0;
			right: 0;
			font-size: 16px;
		}

		.infos {
			position: relative;
		}


	.liste_articles {
		width: 100%;
		border-bottom: 1px solid #000;
		text-align: center;
	}

		.liste_articles tr.titres th {
			border-bottom: 1px solid #000;
		}

		.liste_articles td.total {
			text-align: right;
		}

	.totaux {
		margin-top: 20px;
		width: 30%;
		float: right;
		text-align: right;
	}

	.lien {
		position: absolute;
		top: 0;
		left: 0;
		display: none;
	}

	@media print {

		.lien {
			display: none;
		}

	}

</style>

</head>

<body>

<?php

		// Cash
		
		$sql = "select ref, fk_user, date_c";
    	$sql .=" from ".MAIN_DB_PREFIX."pos_control_cash";
    	$sql .=" where rowid = ".$id;
    	$result=$db->query($sql);
		
		if ($result)
		{
			$objp = $db->fetch_object($result);
        	$date = $objp->date_c;
        	$fk_user = $objp->fk_user;
        	$ref = $objp->ref;
        }

	?>



<div class="entete">
	<div class="logo">
	<?php print '<img src="'.DOL_URL_ROOT.'/viewimage.php?modulepart=companylogo&amp;file='.urlencode('/thumbs/'.$mysoc->logo_small).'">'; ?>
	</div>
	<div class="infos">
		<p class="adresse"><?php echo $mysoc->name; ?><br>
		<?php echo $mysoc->address; ?><br>
		<?php echo $mysoc->zip.' '.$mysoc->town; ?></p>
		<?php
			print '<p>'.$langs->trans("CloseCashReport").': '.$ref.'<br>';			
			$userstatic=new User($db);
			$userstatic->fetch($fk_user);
			print $langs->trans("User").': '.$userstatic->nom.'</p>';
			print '<p class="date_heure">'.dol_print_date($db->jdate($date),'dayhourtext').'</p>';
		?>
	</div>
</div>
<p><?php print $langs->trans("TicketsCash"); ?></p>
<table class="liste_articles">
	<tr class="titres"><th><?php print $langs->trans("Ticket"); ?></th><th><?php print $langs->trans("Total"); ?></th></tr>

	<?php

		// Cash
		
		$sql = "select t.ticketnumber, t.total_ttc, t.type";
    	$sql .=" from ".MAIN_DB_PREFIX."pos_ticket as t";
    	$sql .=" where t.fk_control = ".$id." and t.fk_mode_reglement=4 and t.fk_statut > 0";
    	
    	$sql .= " union select f.facnumber, f.total_ttc, f.type";
    	$sql .=" from ".MAIN_DB_PREFIX."pos_facture as pf,".MAIN_DB_PREFIX."facture as f ";
    	$sql .=" where pf.fk_control_cash = ".$id." and f.fk_mode_reglement=4 and pf.fk_facture = f.rowid and f.fk_statut > 0";
    	
    	$result=$db->query($sql);
		
		if ($result)
		{
			$num = $db->num_rows($result);
			if($num>0)
			{
	            $i = 0;
	            $subtotalcash=0;
	            while ($i < $num)
	            {
	            	$objp = $db->fetch_object($result);
	            	//if($objp->type > 0)$objp->total_ttc= $objp->total_ttc * -1;
	            	echo ('<tr><td align="left">'.$objp->ticketnumber.'</td><td align="right">'.price($objp->total_ttc).'</td></tr>'."\n");
	            	$i++;
	            	$subtotalcash+=$objp->total_ttc;
	            }
			}
			else
			{
				echo ('<tr><td align="left">'.$langs->Trans("NoTickets").'</td></tr>'."\n");
			}	
		}

	?>
</table>

<table class="totaux">
	<?php
	$sql = "select t.ticketnumber, t.type, l.total_ht, l.tva_tx, l.total_tva, l.total_localtax1, l.total_localtax2";
	$sql .=" from ".MAIN_DB_PREFIX."pos_ticket as t left join ".MAIN_DB_PREFIX."pos_ticketdet as l on l.fk_ticket= t.rowid";
	$sql .=" where t.fk_control = ".$id." and t.fk_cash=".$terminal." and t.fk_mode_reglement=".$cash->fk_modepaycash." and t.fk_statut > 0";
	 
	$sql .= " union select f.facnumber, f.type, fd.total_ht, fd.tva_tx, fd.total_tva, fd.total_localtax1, fd.total_localtax2";
	$sql .=" from ".MAIN_DB_PREFIX."pos_facture as pf,".MAIN_DB_PREFIX."facture as f left join ".MAIN_DB_PREFIX."facturedet as fd on fd.fk_facture= f.rowid";
	$sql .=" where pf.fk_control_cash = ".$id." and pf.fk_cash=".$terminal." and f.fk_mode_reglement=".$cash->fk_modepaycash. " and pf.fk_facture = f.rowid and f.fk_statut > 0";
	 
	$result=$db->query($sql);
	
	if ($result)
	{
		$num = $db->num_rows($result);
		if($num>0)
		{
			$i = 0;
			$subtotalcashht=0;
			while ($i < $num)
			{
				$objp = $db->fetch_object($result);
				$i++;
				if($objp->type == 1){
					$objp->total_ht= $objp->total_ht * -1;
					$objp->total_tva= $objp->total_tva * -1;
					$objp->total_localtax1= $objp->total_localtax1 * -1;
					$objp->total_localtax2= $objp->total_localtax2 * -1;
				}
				
				$subtotalcashht+=$objp->total_ht;
				$subtotalcashtva[$objp->tva_tx] += $objp->total_tva;
				$subtotalcashlt1 += $objp->total_localtax1;
				$subtotalcashlt2 += $objp->total_localtax2;
			}
		}
		
	}
	if(! empty($subtotalcashht))echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalHT").'</th><td nowrap="nowrap">'.price($subtotalcashht)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	if(! empty($subtotalcashtva)){foreach($subtotalcashtva as $tvakey => $tvaval){
		if($tvakey > 0)
			echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalVAT").' '.round($tvakey).'%'.'</th><td nowrap="nowrap">'.price($tvaval)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	}
	}
	if($subtotalcashlt1)
		echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalLT1ES").'</th><td nowrap="nowrap">'.price($subtotalcashlt1)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	if($subtotalcashlt2)
		echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalLT2ES").'</th><td nowrap="nowrap">'.price($subtotalcashlt2)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	
	echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalCash").'</th><td nowrap="nowrap">'.price($subtotalcash)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	?>
</table>

<br><br>
<p><?php print $langs->trans("TicketsCreditCard"); ?></p>
<table class="liste_articles">
	<tr class="titres"><th><?php print $langs->trans("Ticket"); ?></th><th><?php print $langs->trans("Total"); ?></th></tr>

	<?php

		// Credit card
		$sql = "select t.ticketnumber, t.total_ttc, t.type";
    	$sql .=" from ".MAIN_DB_PREFIX."pos_ticket as t";
    	$sql .=" where t.fk_control = ".$id."  and t.fk_mode_reglement=1 and t.fk_statut > 0";
    	 
    	$sql .= " union select f.facnumber, f.total_ttc, f.type";
    	$sql .=" from ".MAIN_DB_PREFIX."pos_facture as pf,".MAIN_DB_PREFIX."facture as f ";
    	$sql .=" where pf.fk_control_cash = ".$id." and f.fk_mode_reglement=1 and pf.fk_facture = f.rowid and f.fk_statut > 0";
    	 
    	$result=$db->query($sql);
		
		if ($result)
		{
			$num = $db->num_rows($result);
			if($num>0)
			{
	            $i = 0;
	            $subtotalcard=0;
	            while ($i < $num)
	            {
	            	$objp = $db->fetch_object($result);
	            	//if($objp->type > 0)$objp->total_ttc= $objp->total_ttc * -1;
	            	echo ('<tr><td align="left">'.$objp->ticketnumber.'</td><td align="right">'.price($objp->total_ttc).'</td></tr>'."\n");
	            	$i++;
	            	$subtotalcard+=$objp->total_ttc;
	            }
			}
			else
			{
				echo ('<tr><td align="left">'.$langs->Trans("NoTickets").'</td></tr>'."\n");
			}	
		}

	?>
</table>

<table class="totaux">
	<?php
	$sql = "select t.ticketnumber, t.type, l.total_ht, l.tva_tx, l.total_tva, l.total_localtax1, l.total_localtax2";
	$sql .=" from ".MAIN_DB_PREFIX."pos_ticket as t left join ".MAIN_DB_PREFIX."pos_ticketdet as l on l.fk_ticket= t.rowid";
	$sql .=" where t.fk_control = ".$id." and t.fk_cash=".$terminal." and t.fk_mode_reglement=".$cash->fk_modepaybank." and t.fk_statut > 0";
	
	$sql .= " union select f.facnumber, f.type, fd.total_ht, fd.tva_tx, fd.total_tva, fd.total_localtax1, fd.total_localtax2";
	$sql .=" from ".MAIN_DB_PREFIX."pos_facture as pf,".MAIN_DB_PREFIX."facture as f left join ".MAIN_DB_PREFIX."facturedet as fd on fd.fk_facture= f.rowid";
	$sql .=" where pf.fk_control_cash = ".$id." and pf.fk_cash=".$terminal." and f.fk_mode_reglement=".$cash->fk_modepaybank. " and pf.fk_facture = f.rowid and f.fk_statut > 0";
	
	$result=$db->query($sql);
	
	if ($result)
	{
		$num = $db->num_rows($result);
		if($num>0)
		{
			$i = 0;
			$subtotalcardht=0;
			while ($i < $num)
			{
				$objp = $db->fetch_object($result);
				$i++;
				if($objp->type == 1){
					$objp->total_ht= $objp->total_ht * -1;
					$objp->total_tva= $objp->total_tva * -1;
					$objp->total_localtax1= $objp->total_localtax1 * -1;
					$objp->total_localtax2= $objp->total_localtax2 * -1;
				}
				
				$subtotalcardht+=$objp->total_ht;
				$subtotalcardtva[$objp->tva_tx] += $objp->total_tva;
				$subtotalcardlt1 += $objp->total_localtax1;
				$subtotalcardlt2 += $objp->total_localtax2;
			}
		}
		
	}
	if(! empty($subtotalcardht))echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalHT").'</th><td nowrap="nowrap">'.price($subtotalcardht)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	if(! empty($subtotalcardtva)){foreach($subtotalcardtva as $tvakey => $tvaval){
		if($tvakey > 0)
			echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalVAT").' '.round($tvakey).'%'.'</th><td nowrap="nowrap">'.price($tvaval)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	}
	}
	if($subtotalcardlt1)
		echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalLT1ES").'</th><td nowrap="nowrap">'.price($subtotalcardlt1)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	if($subtotalcardlt2)
		echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalLT2ES").'</th><td nowrap="nowrap">'.price($subtotalcardlt2)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
		
	echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalCard").'</th><td nowrap="nowrap">'.price($subtotalcard)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n\n\n";
	echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalPOS").'</th><td nowrap="nowrap">'.price($subtotalcard+$subtotalcash)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
	?>
</table>
<br><br>
<table class="totaux">
	<?php
		//echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalPOS").'</th><td nowrap="nowrap">'.price($subtotalcard+$subtotalcash)." ".getCurrencySymbol($conf->currency)."</td></tr>\n";
	?>
</table>

<script type="text/javascript">

	window.print();

</script>


</body>