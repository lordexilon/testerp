<?php

$res=@include("../../../main.inc.php");                                   // For root directory
if (! $res) $res=@include("../../../../main.inc.php");                // For "custom" directory

require_once(DOL_DOCUMENT_ROOT."/compta/facture/class/facture.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/company.lib.php");
dol_include_once('/pos/backend/class/cash.class.php');
global $langs,$db,$mysoc;

$langs->load("main");
$langs->load("pos@doliposbar");
$langs->load("bills");
header("Content-type: text/html; charset=".$conf->file->character_set_client);
$id=GETPOST('id');
?>
<html>
<head>
<title></title>

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
	
	.note{
		float: right;
		font-size: 12px;
		width: 100%;
		text-align: center;
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

<div class="entete">
	<div class="logo">
	<?php print '<img src="'.DOL_URL_ROOT.'/viewimage.php?modulepart=companylogo&amp;file='.urlencode('/thumbs/'.$mysoc->logo_small).'">'; ?>
	</div>
	<div class="infos">
		<p class="adresse"><?php echo $mysoc->name; ?><br>
		<?php echo $mysoc->idprof1;?><br>
		<?php echo $mysoc->address; ?><br>
		<?php echo $mysoc->zip.' '.$mysoc->town; ?><br><br>
		
		<?php
		
			// Variables
		
			$object=new Facture($db);
			$result=$object->fetch($id,$ref);
						
			$userstatic=new User($db);
			$userstatic->fetch($object->user_valid);
			print $langs->trans("Vendor").': '.$userstatic->firstname.' '.$userstatic->lastname.'<br><br>';
			
			$client=new Societe($db);
			$client->fetch($object->socid);

			
			
			// Recuperation et affichage de la date et de l'heure
			$now = dol_now();
			if ($object->type==0) print $langs->trans("Facsim"); else print $langs->trans("FacsimAvoir");
			print '<p class="date_heure" align="right">'.$object->ref."<br>".dol_print_date($object->date_creation,'dayhourtext').'</p><br>';
		?>
	</div>
</div>

<table class="liste_articles">
	<tr class="titres"><th><?php print $langs->trans("Label"); ?></th><th><?php print $langs->trans("Qty")."/".$langs->trans("Price"); ?></th><th><?php print $langs->trans("Dct"); ?></th><th><?php print $langs->trans("Total"); ?></th></tr>

	<?php
		
		if ($result)
		{
			$object->getLinesArray();
			if (! empty($object->lines))
			{
				$subtotal=0;
				foreach ($object->lines as $line)
				{
					if(empty($line->libelle)) $line->libelle = $line->description;
					if(empty($line->libelle)) $line->libelle = $line->label;
					$totalline= $line->qty*$line->subprice;
					echo ('<tr><td align="left">'.$line->libelle.'</td><td align="right">'.$line->qty." * ".price($line->subprice,"","","","",2).'</td><td align="right">'.$line->remise_percent.'%</td><td class="total">'.price($line->total_ht,"","","","",2).' </td></tr>'."\n");
					$subtotal+=$totalline;
					$subtotaltva[$line->tva_tx] += $line->total_tva;
				}
			}
			else 
			{
				echo ('<p>'.print $langs->trans("ErrNoArticles").'</p>'."\n");
			}
					
		}
		

	?>
</table>

<table class="totaux">
	<?php
		echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalHT").'</th><td nowrap="nowrap">'.number_format($object->total_ht,2,",",".")." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
		
		
		//echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalVAT").'</th><td nowrap="nowrap">'.price($object->total_tva)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
		if(! empty($subtotaltva)){
			foreach($subtotaltva as $tvakey => $tvaval){
				if($tvakey > 0)
					echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalVAT").' '.round($tvakey).'%'.'</th><td nowrap="nowrap">'.number_format($tvaval,2,",",".")." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
			}
		}
		
		if($object->total_localtax1!=0){
			echo '<tr><th nowrap="nowrap">'.$langs->transcountrynoentities("TotalLT1",$mysoc->country_code).'</th><td nowrap="nowrap">'.number_format($object->total_localtax1,2,",",".")." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
		}
		if($object->total_localtax2!=0){
			echo '<tr><th nowrap="nowrap">'.$langs->transcountrynoentities("TotalLT2",$mysoc->country_code).'</th><td nowrap="nowrap">'.number_format($object->total_localtax2,2,",",".")." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
		}
		echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalTTC").'</th><td nowrap="nowrap">'.price($object->total_ttc)." "."</td></tr>\n";
		echo '<tr><td></td></tr>';
		echo '<tr><td></td></tr>';

		$sql = 'select fk_cash from '.MAIN_DB_PREFIX.'pos_facture where fk_facture = '.$object->id;
		$resql = $db->query($sql);
		$obj = $db->fetch_object($resql);
		if (! empty($conf->rewards->enabled)){
			$rewards = new Rewards($db);
			$points = $rewards->getInvoicePoints($object->id);
		}
		if ($object->type==0)
		{
			$sql = "SELECT SUM(amount) as amount from ".MAIN_DB_PREFIX."paiement_facture WHERE  fk_facture=".$object->id;
			$res=$db->query($sql);
			$objp=$db->fetch_object($res);
			$pay = $objp->amount;
			/*
			if (! empty($conf->rewards->enabled)){
				$usepoints= abs($rewards->getInvoicePoints($object->id,1));
				$moneypoints = abs($usepoints*$conf->global->REWARDS_DISCOUNT);//falta fer algo per aci
				echo '<tr><th nowrap="nowrap">'.$langs->trans("CustomerPay").'</th><td nowrap="nowrap">'.price($pay-$moneypoints)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
			}
			else{
				echo '<tr><th nowrap="nowrap">'.$langs->trans("CustomerPay").'</th><td nowrap="nowrap">'.price($pay)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
			}
			*/
			$difpayment=$object->total_ttc - $pay;
			if (! empty($conf->rewards->enabled)){
				if ($moneypoints>0){
					echo '<tr><th nowrap="nowrap">'.$langs->trans("RewardsDiscountDesc",$usepoints).'</th><td nowrap="nowrap">'.price($moneypoints)." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
				}
			}
			
			/*
			if($difpayment<0){
				echo '<tr><th nowrap="nowrap">'.$langs->trans("CustomerRet").'</th><td nowrap="nowrap">'.price(abs($difpayment))." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
			}else{
				echo '<tr><th nowrap="nowrap">'.$langs->trans("CustomerDeb").'</th><td nowrap="nowrap">'.price(abs($difpayment))." ".$langs->trans(currency_name($conf->currency))."</td></tr>\n";
			}
			*/
		}
		if ($points != 0 && ! empty($conf->rewards->enabled))
		{
			echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalPointsInvoice").'</th><td nowrap="nowrap">'.price($points)." ".$langs->trans('Points')."</td></tr>\n";
			$total_points = $rewards->getCustomerPoints($object->socid);
			echo '<tr><th nowrap="nowrap">'.$langs->trans("TotalDispoPoints").'</th><td nowrap="nowrap">'.dol_print_date($now,'day')." ".price($total_points)." ".$langs->trans('Points')."</td></tr>\n";
		}
	?>
</table>

<div class="note"><p><?php print $conf->global->POS_PREDEF_MSG; ?> </p></div>

<script type="text/javascript">

	window.print();
	setTimeout(function(){parent.$('#barposlines').jtable('load', { action: '', id: '', place:0 });}, 1);
	setTimeout(function(){parent.jQuery.colorbox.close();}, 100);

</script>


</body>