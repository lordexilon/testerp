<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
header('Cache-Control: max-age=604800, public');
header('Pragma: cache');
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.form.class.php");
$langs->load("pos@doliposbar");
session_start();
if(empty($_SESSION['uname']))
{
	header ('Location: index.php');
}
$place= GETPOST('place');
if (GETPOST('message')!="") $message= GETPOST('message').$langs->getCurrencySymbol($conf->currency);
if (! $place) $place=0;
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
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
	var tableopen=false;
	var totalprice=0;
	var totalprice2=0;
	var IdToSend=0;
	var pagecat=1;
	var currentcat=1;
	var pagepro=1;
	var totalcats;
	var numberchange='';
	var categories = new Array();
	<?php echo "var place=".$place.";";
	echo "var voucher_label='".$langs->trans("CheckBar")."';";
	echo "var following='".$langs->trans("following")."';";
	echo "var previous='".$langs->trans("previous")."';";
	echo "var description='".$langs->trans("Description")."';";
	echo "var quantity='".$langs->trans("Quantity")."';";
	echo "var price_label='".$langs->trans("Price")."';";
	echo "var dct_label='".$langs->trans("Dct")."';";
	echo "var table_label='".$langs->trans("Table")."';";
	echo "var totalvat_label='".$langs->trans("TotalVAT")."';";
	echo "var totalttc_label='".$langs->trans("TotalTTC")."';";
	echo "var label_bill='".$langs->trans("Facsim")."';";
	echo "var header_lines='".$langs->trans("Header_lines")."';";
	echo "var barprinter='".$conf->global->BARPRINTERNAME."';";
	echo "var kitchenprinter='".$conf->global->KITCHENPRINTERNAME."';";
	echo "var drawer='".$conf->global->CASHDRAWERENABLE."';";
	echo "var text1='".$conf->global->BARHEADTEXT1."';";
	echo "var text2='".$conf->global->BARHEADTEXT2."';";
	echo "var text3='".$conf->global->BARHEADTEXT3."';";
	echo "var text4='".$conf->global->BARFOOTERTEXT."';";
	echo "var Messages = {noDataAvailable: '$message',};";
	?>
	
	

	$(document).ready(function () {


	$("#pay").click(function() {
	if (totalprice2>0) $.colorbox({ iframe: true, width:'55%', height:'70%', href:'pay.php?place='+place});
	});
	
	$("#opens").colorbox({ iframe: true, width:'35%', height:'70%', href:'opens.php'});


	$("#notes").click(function() {
			var $selectedRows = $('#barposlines').jtable('selectedRows');
			var totalSelectedRows=0;
			$selectedRows.each(function () {
			totalSelectedRows=1;
			$.colorbox({ iframe: true, width:'50%', height:'80%', href:'../pro/notes.php?place='+place+'&line='+$('#barposlines').jtable('selectedRows').data('record').iddet});
			});
			
			if (totalSelectedRows==0){
			$.colorbox({ iframe: true, width:'50%', height:'80%', href:'../pro/notes.php?place='+place});
			}
	});
	
	$("#voucher").click(function() {
 	$('#voucherimg').addClass('gray');
	if (totalprice2>0) {
	$.colorbox({ iframe: true, width:'50%', height:'80%', href:'tpl/order.tpl.php?type=order&id='+place});
	}
	setTimeout(function(){$('#voucherimg').removeClass('gray');},200);
	});
	
	
	$("#posdrawer").click(function() {
 	$('#posdrawer').addClass('gray');
	$.post("../addprint.php", { addprint: "D" } );
	setTimeout(function(){$('#posdrawer').removeClass('gray');},200);
	});

	
	
	 $('#barposlines').jtable({
			selecting: true,
			messages: Messages,
            actions: {
                listAction: 'loadticket.php?place=<?php echo $place; ?>'
            },
            fields: {
                iddet: {
                    key: true,
                    list: false
                },
                label: {
                    title: description,
                    width: '40%'
                },
                qty: {
                    title: quantity,
                    width: '15%'
                },
                price: {
                    title: price_label,
                    width: '15%',
                    create: false,
                    edit: false
                },
                remise: {
                    title: dct_label,
                    width: '15%'
                },
                total: {
                    title: 'Total',
                    width: '15%',
					display: function (data) {
						totalprice+=parseFloat(data.record.total);
						return data.record.total;
					}
                }					
            },
			recordsLoaded: function (event, data) {
                $('#barposlines').scrollTop($('#barposlines')[0].scrollHeight);
				$('#totaldisplay').html(totalprice.toFixed(2));
				$('#namedisplay').html('TOTAL:');
				$('#symbol').html('<?php echo $langs->getCurrencySymbol($conf->currency);?>');
				if (totalprice>0) tableopen=true; else tableopen=false;
				totalprice2=totalprice;
				totalprice=0;
            },

        });
	
	$('#barposlines').jtable('load');

		
	$(window).resize();
	h = $(window).height() *0.98;
	$( "#tabs" ).height(h);
	});
	
	

	
	function sendbar(action, id, position)
	{
	if (position>0) {
	$('#proimg'+position).addClass('gray'); 
	setTimeout(function(){$('#proimg'+position).removeClass('gray');},200);
	}
	$('#barposlines').jtable('load', { action: action, id: id, place:place });
	}
	
	function changer(number)
	{
	if (number>=0 || number=='.') {
	numberchange=numberchange+number;
	$('#totaldisplay').html('');
	$('#symbol').html('');
	$('#namedisplay').html(numberchange);
	}
	if (number=='c') { numberchange='';sendbar();}
	if (number=='q' || number=='p' || number=='d') { if (numberchange>0) $('#barposlines').jtable('load', { action: number, id: $('#barposlines').jtable('selectedRows').data('record').iddet, place:place, number: numberchange }); numberchange='';}
	}
    
	function nextcategories()
	{
	if (pagecat*14<totalcats) {
	pagecat++;
	var countcat=14*pagecat-13;
	var interval=totalcats-countcat;
	if (interval>14) interval=14;
	for (x = 1; x <= interval; x++)
		{
		$("#catdesc"+x).html(categories[countcat][1]);
		$("#catimg"+x).attr("src",categories[countcat][2]);
		countcat++;
		}
	while (x<15)
		{
		$("#catdesc"+x).html('');
		$("#catimg"+x).attr("src",'img/default.gif');
		x++;
		}
	$("#catdesc15").html(previous);
	interval=pagecat*14;
	if (totalcats>interval) $("#catdesc16").html(following); else $("#catdesc16").html('');
	}}
	
	
	function prevcategories()
	{
	if (pagecat>1){
	pagecat--;
	var countcat=14*pagecat-13;
	var interval=totalcats-countcat;
	if (interval>14) interval=14;
	for (x = 1; x <= interval; x++)
		{
		$("#catdesc"+x).html(categories[countcat][1]);
		$("#catimg"+x).attr("src",categories[countcat][2]);
		countcat++;
		}
	while (x<15)
		{
		$("#catdesc"+x).html('');
		$("#catimg"+x).attr("src",'img/default.gif');
		x++;
		}
	if (pagecat>1) $("#catdesc15").html(previous); else $("#catdesc15").html('');
	$("#catdesc16").html(following);
	}}
	
	$(window).resize(function(){
	h = $(window).height() *0.98;
	$( "#tabs" ).height(h);
	});	

	$(function() {
				
		$( "#tabs" ).tabs();

	});
	

	$(function() {
		$( "#selectable" ).selectable();
	});
	
	//Get categories
		$.getJSON('./ajax_pos.php?action=getCategories&parentcategory=0', function(data) 
	{
		var count=0;
		
		$.each(data, function(key, val) 
		{
			count++;
			categories[count]= new Array();
			categories[count][0]=val.id;
			categories[count][1]=val.label;
			categories[count][2]=val.image;
			if (count<15)
			{
			$("#catdesc"+count).html(val.label);
			$("#catimg"+count).attr("src",val.image);
			}
			totalcats=count;
			
			//Get products
			if (count==1) loadproducts(categories[1][0], 1);			
		});
	});
	
	function showproducts(data, page) {
	var count2=0;
	var count=0;
	$.each(data, function(key, val) {
	count2++;
	
	
	if (count2>=page*27-27){
	count++;
	if (count2==page*27-page+1) {$("#prodesc28").html(following); return false;}
	$("#prodesc"+count).html(val.label);
	$("#proimg"+count).attr("src",val.image);
	$("#prodiv"+count).attr("onclick","sendbar('addline', "+val.id+", "+count+");");
	}
	});
	while(count<26){
	count++
	$("#prodesc"+count).html('');
	$("#proimg"+count).attr("src",'img/default.gif');
	$("#prodiv"+count).attr("onclick","sendbar('addlinex');");
	}
	if (count<27) $("#prodesc28").html('');
	if (page>1) $("#prodesc27").html(previous); else $("#prodesc27").html('');
	}
	
	function loadproducts(category, page, position){
	$('#catimg'+position).addClass('gray'); 
	setTimeout(function(){$('#catimg'+position).removeClass('gray');},200);
				currentcat=category;
				pagepro=page;
				var cachedData = window.localStorage[category];
				if (cachedData) showproducts(JSON.parse(cachedData), page);
				else {
				$.getJSON('./ajax_pos.php?action=getProducts&category='+category, function(data) {
				window.localStorage[category] = JSON.stringify(data);
				showproducts(data, page);
				});
			}
		}
	


	</script>
</head>
<body style="overflow: hidden">

<div id="tabs" style="height:470px; background:#<?php echo $conf->global->DOLIPOSFRONTENDCOLOR ?>;">
	<ul>
		<li><a href="#tabs-1"><?php echo $langs->trans("Sales"); ?></a></li>
		<?php
		$sql="SELECT zone from ".MAIN_DB_PREFIX."pos_places group by zone order by zone";
		$resql = $db->query($sql);
		while ($row = $db->fetch_array ($resql)) {
		?><li><a href="places.php?zone=<?php echo $row[0]; ?>"><?php
		$custom_name="DOLIPOSBAR_CUSTOM_ZONE_NAME".$row[0];
		if ($conf->global->$custom_name!="") echo $conf->global->$custom_name;
		else echo $langs->trans("Zone")." ".$row[0];
		?>
		</a></li>
		<?php } ?>
		<li><a href="history.php"><?php echo $langs->trans("History"); ?></a></li>
		<div align="right"><div id="datebar" style="float:right"></div><div id="place" style="float:right"><?php echo $langs->trans("User")." ".$_SESSION['uname']; if (! $place) echo " ".$langs->trans("Table").": ".$langs->trans("DirectSales"); else echo " ".$langs->trans("Table").": ".$place; ?></div></div>
	</ul>

	<div id="tabs-1" style="height:400px; width:100%;">
	<br>
<div id="barposlines" style="position:absolute; top:8%; left:0.5%; height:30%; width:40%; overflow: auto;">
</div>
		


				<div style="position:absolute; top:9%; left:41%; height:30%; width:40%;">
<button type="button" class="calcbutton" onclick="changer(7);">7</button>
<button type="button" class="calcbutton" onclick="changer(8);">8</button>
<button type="button" class="calcbutton" onclick="changer(9);">9</button>
<button type="button" class="calcbutton2" onclick="changer('q');"><?php echo $langs->trans("shortquantity"); ?></button>
<button type="button" class="calcbutton" onclick="changer(4);">4</button>
<button type="button" class="calcbutton" onclick="changer(5);">5</button>
<button type="button" class="calcbutton" onclick="changer(6);">6</button>
<button type="button" class="calcbutton2" onclick="changer('p');"><?php echo $langs->trans("shortprice"); ?></button>
<button type="button" class="calcbutton" onclick="changer(1);">1</button>
<button type="button" class="calcbutton" onclick="changer(2);">2</button>
<button type="button" class="calcbutton" onclick="changer(3);">3</button>
<button type="button" class="calcbutton2" onclick="changer('d');"><?php echo $langs->trans("Dct"); ?></button>
<button type="button" class="calcbutton" onclick="changer(0);">0</button>
<button type="button" class="calcbutton" onclick="changer('.');">.</button>
<button type="button" class="calcbutton" onclick="changer('c');">C</button>

				</div>
				
	<div style="position:absolute; top:39%; left:0.3%; height:59%; width:32%;">
	<?php
	$count=0;
	while ($count<16)
	{
	$count++;
	?>
	<div class='wrapper' <?php if ($count==15) echo 'onclick="prevcategories();"'; else if ($count==16) echo 'onclick="nextcategories();"'; else echo 'onclick="loadproducts(categories[(14*pagecat)-14+'.$count.'][0], 1, '.$count.');"';?> id='catdiv<?php echo $count;?>'>
		<img class='imgwrapper' <?php if ($count==15) echo 'src="img/arrow-prev-top.png"'; if ($count==16) echo 'src="img/arrow-next-top.png"';?> width="100%" id='catimg<?php echo $count;?>'/>
		<div class='description'>
			<div class='description_content' id='catdesc<?php echo $count;?>'><?php if ($count==16) echo $langs->trans("following");?></div>
		</div>
	</div>
	<?php
	}
	?>
	

	
	</div>
	
	
		<div style="position:absolute; top:39%; left:32%; height:59%; width:65%;">
		<?php
	$count=0;
	while ($count<28)
	{
	$count++;
	?>
	<div class='wrapper2' id='prodiv<?php echo $count;?>' <?php if ($count==27) {?> onclick="if ($('#prodesc27').text()==previous) loadproducts(currentcat, pagepro-1);" <?php } if ($count==28) {?> onclick="if ($('#prodesc28').text()==following) loadproducts(currentcat, pagepro+1);" <?php } ?>>
		<img class='imgwrapper' <?php if ($count==27) echo 'src="img/arrow-prev-top.png"'; if ($count==28) echo 'src="img/arrow-next-top.png"';?> width="100%" id='proimg<?php echo $count;?>'/>
		<div class='description'>
			<div class='description_content' id='prodesc<?php echo $count;?>'></div>
		</div>
	</div>
	<?php
	}
	?>
	</div>
	
	
	<div style="position:absolute; top:8%; left:76%; height:30%; width:23%;">
	<img src="./img/logo.png" width="100%" height="30%">
	<div style="width:100%; background-color:#222222; border-radius:8px; margin-bottom: 4px;">
	<center><span style='font-family: digital; font-size: 280%;'><font color="white"><span id="namedisplay">Total:</span></font><font color="red"> <span id="totaldisplay"></span></span><span style='font-family: digital;font-size: 250%;'> <span id="symbol"><?php echo $langs->getCurrencySymbol($conf->currency);?></span></font></span></center>
	</div>
	</div>

	<div style="position:absolute; top:23%; left:75.6%; height:14%; width:11%;">
	<div class='wrapper3' style="width:100%;height:100%;" onclick="productid=">
		<img src='img/posdrawer.jpg' width="100%" height="100%" border="1" id='posdrawer'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("drawer"); ?></div>
		</div>
	</div>
	</div>
	<div style="position:absolute; top:23%; left:87.2%; height:14%; width:11%;">
	<div class='wrapper3' style="width:100%;height:100%;" id="pay">
		<img src='img/pay.jpg' width="100%" height="100%" border="1"/>
		<div class='description2'>
			<div class='description_content'><?php echo $langs->trans("CloseBill"); ?></div>
		</div>
	</div>
	</div>
	



	<div style="position:absolute; top:39%; left:90.5%; height:59%; width:8%;">
	<div class='wrapper3' style="width:99%;height:23%;" onclick="
	$('#deleteimg').addClass('gray');
	var $selectedRows = $('#barposlines').jtable('selectedRows');
	var totalSelectedRows=0;
	$selectedRows.each(function () {
	totalSelectedRows=1;
	sendbar('deleteline',$('#barposlines').jtable('selectedRows').data('record').iddet);
	});
	if (totalSelectedRows==0) sendbar('deleteline');
	setTimeout(function(){$('#deleteimg').removeClass('gray');},200);
	">
		<img src='img/delete.jpg' width="100%" height="100%" border="1" id='deleteimg'/>
		<div class='description2'>
			<div class='description_content'><?php echo $langs->trans("DeleteLine"); ?></div>
		</div>
	</div>
	
	
	<div class='wrapper3' style="width:99%;height:23%;" id="voucher">
		<img src='img/receipt.jpg' width="100%" height="100%" border="1" id='voucherimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("CheckBar"); ?></div>
		</div>
	</div>
	
	<div class='wrapper3' style="width:99%;height:23%;" id="opens">
		<img src='img/tablebusy.jpg' width="100%" height="100%" border="1" />
		<div class='description2'>
			<div class='description_content'><?php echo $langs->trans("OpenPlaces"); ?></div>
		</div>
	</div>

	

	<div class='wrapper3' style="width:99%;height:23%;" onclick="location.href='index.php';">
		<img src='img/dolibarr.jpg' width="100%" height="100%" border="1" id='kitchenimg'/>
		<div class='description2'>
			<div class='description_content' ><?php echo $langs->trans("Logout"); ?></div>
		</div>
	</div>


	</div>


		
	
	</div>
</div>



</body>
</html>
