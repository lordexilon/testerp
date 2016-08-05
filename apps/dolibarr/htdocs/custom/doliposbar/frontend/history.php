<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
require_once(DOL_DOCUMENT_ROOT."/commande/class/commande.class.php");
$langs->load("pos@doliposbar");
?>
<!doctype html>
<html>
<head>
	<title>Dolipos BAR</title>
	<link href="css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
	<link href="css/barpos.css" rel="stylesheet">
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<link rel="stylesheet" href="css/colorbox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.colorbox.js"></script>
	<link href="jtable/themes/metro/lightgray/jtable.min.css" rel="stylesheet" type="text/css" /> 
	<script src="jtable/jquery.jtable.min.js" type="text/javascript"></script>
<script>
<?php
echo "var reference='".$langs->trans("Reference")."';";
echo "var date='".$langs->trans("Date")."';";
echo "var user='".$langs->trans("User")."';";
?>
$(document).ready(function () {
	 $('#lastinvoices').jtable({
			selecting: true,
            actions: {
                listAction: 'loadhistory.php'
            },
            fields: {
                iddet: {
                    key: true,
                    list: false
                },
                facnumber: {
                    title: reference,
                    width: '25%'
                },
                datec: {
                    title: date,
                    width: '25%'
                },
                user: {
                    title: user,
                    width: '25%',
                    create: false,
                    edit: false
                },
                price: {
                    title: 'Total',
                    width: '25%',
                }					
            },
			recordsLoaded: function (event, data) {
            },

        });
	
	$('#lastinvoices').jtable('load');
	});
	
	$("#all").click(function() {
                  $.fancybox({
				'autoSize': false,
				'height': '70%',
                'type': 'iframe',
                'href': '../backend/liste.php?idmenu=3',
				'width': '80%'
                });		
	});	
	
</script>
</head>
<body>
<div id="lastinvoices" style="position:absolute; top:8%; left:2%; height:90%; width:88%; overflow: auto;">
</div>
<div style="position:absolute; top:8%; left:90.5%; height:59%; width:8%;">
	<div class='wrapper3' style="width:99%;height:23%;" onclick="
	$('#returnimg').addClass('gray');
	var $selectedRows = $('#lastinvoices').jtable('selectedRows');
	var totalSelectedRows=0;
	$selectedRows.each(function () {
	totalSelectedRows=1;
	var id=$('#lastinvoices').jtable('selectedRows').data('record').iddet;
	$.post('loadhistory.php', { action: 'return', id: id })
	.done(function(data) {
	$('#lastinvoices').jtable('load');
	});
	});
	if (totalSelectedRows==0) alert('Seleccione una factura');
	setTimeout(function(){$('#returnimg').removeClass('gray');},200);
	">
		<img src='img/delete.jpg' width="100%" height="100%" border="1" id='returnimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("ReturnTicket"); ?></div>
		</div>
	</div>
	
	
	<div class='wrapper3' style="width:99%;height:23%;" onclick="
	$('#reprintimg').addClass('gray');
	var $selectedRows = $('#lastinvoices').jtable('selectedRows');
	var totalSelectedRows=0;
	$selectedRows.each(function () {
	totalSelectedRows=1;
	var id=$('#lastinvoices').jtable('selectedRows').data('record').iddet;
	<?php if ($conf->global->BARPRINTERSYSTEM==2){?>
	$.post('../addprint.php', { addprint: 'F'+id } );
	<?php } else { ?>
	$.colorbox({ iframe: true, width:'50%', height:'80%', href:'tpl/facture.tpl.php?&id='+id});
	<?php } ?>
	});
	if (totalSelectedRows==0) alert('Seleccione una factura');
	setTimeout(function(){$('#reprintimg').removeClass('gray');},200);
	">
		<img src='img/receipt.jpg' width="100%" height="100%" border="1" id='reprintimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("Print"); ?></div>
		</div>
	</div>
	
	<div class='wrapper3' style="width:99%;height:23%;" id="all">
		<img src='img/dolibarr.jpg' width="100%" height="100%" border="1" />
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("ViewAll"); ?></div>
		</div>
	</div>
	</div>

</body>
</html>