<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
$langs->load("pos@doliposbar");
$action= GETPOST('action');
$id= GETPOST('id');
$kitchenprint =str_replace('C', '', $conf->global->KITCHEN_PRINT_BAR);
$kitchenprint = explode(',',$kitchenprint);


if ($action=="add")
{
dolibarr_set_const($db,"KITCHEN_PRINT_BAR", $conf->global->KITCHEN_PRINT_BAR.'C'.$id.',','chaine',0,'',$conf->entity);
}

if ($action=="remove")
{
dolibarr_set_const($db,"KITCHEN_PRINT_BAR", str_replace('C'.$id.',', '', $conf->global->KITCHEN_PRINT_BAR),'chaine',0,'',$conf->entity);
}



if ($action=="")
{
?> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>doliPOS BAR</title>

<link rel="stylesheet" type="text/css" href="css/login.css" />

	<script src="../frontend/js/jquery-1.9.1.min.js"></script>
	<script src="../frontend/js/jquery-ui-1.10.2.custom.min.js"></script>
	<script type="text/javascript" src="../frontend/js/login.js"></script>
	<link href="../frontend/css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
	<script type="text/javascript">
	
	var kitchenprint = new Array();
    <?php
    for($i = 0; $i<count($kitchenprint); $i++){
        echo 'kitchenprint['.$i.']="'.$kitchenprint[$i].'";';
        }
    ?>
	
	function categoryclick(id)
	{
	if (kitchenprint.indexOf(id)>=0) categoryremove(id);
	else categoryadd(id);
	}
	
	function categoryadd(id)
	{
	$('#c'+id).css('color', 'red');
	$.post("kitchencats.php", { action: "add", id: id } );
	kitchenprint.push(id);
	}
	
	function categoryremove(id)
	{
	$('#c'+id).css('color', '');
	$.post("kitchencats.php", { action: "remove", id: id } );
	kitchenprint[kitchenprint.indexOf(id)]='';
	}
	
	
	$(document).ready(function () {
	
	
	$.getJSON('../frontend/ajax_pos.php?action=getCategories&parentcategory=0', function(data) 
	{
		var count=0;
		
		$.each(data, function(key, val) 
		{
			if (kitchenprint.indexOf(val.id)>=0) $('#printcats').append('<input type="button" style="width: 90%; color:red" name="submit" id="c'+val.id+'" value="'+val.label+'" onclick="categoryclick('+val.id+');"/><br><br>');
			else $('#printcats').append('<input type="button" style="width: 90%" name="submit" id="c'+val.id+'" value="'+val.label+'" onclick="categoryclick('+val.id+');"/><br><br>');
		});
	});
	});
	
	</script>

</head>

<body>


</div>
<div id="carbonForm">

	<h2><center>
	<?php echo $langs->trans("KitchenCats"); ?>
	</center></h2>
	
    <div class="fieldContainer">
	<div class="field"><center><?php echo $langs->trans("KitchenCatsSelect"); ?>
	<br><br>
	<div id="printcats"></div>
	</center></div>


	

  
    </div> <!-- Closing fieldContainer -->
    <br>
    <div class="field">
	<center>
	</center>
    </div>
    </form>
          
</div>
</body>
</html>
<?php } ?>