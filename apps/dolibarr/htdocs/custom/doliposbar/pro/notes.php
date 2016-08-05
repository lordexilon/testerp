<?php
/* Copyright (C) 2013 Andreu Bisquerra Gayà	<andreu@artadigital.com>
 * Released under the MIT license
 */
$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
$langs->load("pos@doliposbar");
$place= GETPOST('place');
$action= GETPOST('action');
$line= GETPOST('line');
$note= GETPOST('note');
$admin= GETPOST('admin');
$text_drop= GETPOST('text_drop');

if ($action=="add" and $admin=="")
{
$sql="SELECT rowid FROM ".MAIN_DB_PREFIX."commande where ref='Place-$place'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
$placeid=$row[0];
	if ($placeid==0) {
	require_once(DOL_DOCUMENT_ROOT."/commande/class/commande.class.php");
	$com = new Commande($db);
	$com->ref            = 'Place-$place';
	$com->socid          = 1;
	$com->date_commande  = mktime();
	$com->note           = '';
	$com->source         = 1;
	$com->remise_percent = 0;
	$result=$user->fetch('','admin');
	$idobject=$com->create($user);
	$db->commit();
	$db->query("UPDATE ".MAIN_DB_PREFIX."commande SET ref='Place-$place' WHERE rowid=$idobject;");
	$db->commit();
	$placeid=$idobject;
	}
if ($line>0){
$db->begin();
$db->query("update ".MAIN_DB_PREFIX."commandedet set description=concat('$note;',description) where fk_commande='$placeid' and rowid='$line'");
$db->commit();
}
else {
$db->begin();
$db->query("update ".MAIN_DB_PREFIX."commande set note_private=concat('$note;',note_private) where rowid='$placeid'");
$db->commit();
}
}

if ($action=="add" and $admin=="admin")
{
dolibarr_set_const($db,"PREDEFINED_NOTES_BAR", $conf->global->PREDEFINED_NOTES_BAR.$note.';','chaine',0,'',$conf->entity);
}

if ($action=="drop" and $admin=="") {
$sql="SELECT rowid FROM ".MAIN_DB_PREFIX."commande where ref='Place-$place'";
$resql = $db->query($sql);
$row = $db->fetch_array ($resql);
$placeid=$row[0];
if ($line>0){
$db->begin();
$db->query("update ".MAIN_DB_PREFIX."commandedet set description=replace(description,'$text_drop;', '') where fk_commande='$placeid' and rowid='$line'");
$db->commit();
}
else {
$db->begin();
$db->query("update ".MAIN_DB_PREFIX."commande set note_private= replace(note_private,'$text_drop;', '') where rowid='$placeid'");
$db->commit();
}
}

if ($action=="drop" and $admin=="admin") {
dolibarr_set_const($db,"PREDEFINED_NOTES_BAR", str_replace ("$text_drop;","", $conf->global->PREDEFINED_NOTES_BAR),'chaine',0,'',$conf->entity);
}

if ($action=="")
{
?> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>doliPOS BAR</title>

<link rel="stylesheet" type="text/css" href="css/login.css" />

	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<script type="text/javascript" src="js/login.js"></script>
	<link href="../frontend/css/keyboard.css" rel="stylesheet">
	<script src="js/jquery.keyboard.js"></script>
	<link href="../frontend/css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
	<script type="text/javascript">
	function addadd() {
	$('#title').html('<input type="button" name="submit" id="login" value="Añadir" onclick="addnote();"/>');
	}
	<?php if ($admin=="admin") { ?>
	function addnote() {
	$.post("notes.php", { place: '<?php echo $place; ?>', line: '<?php echo $line; ?>',action: 'add', note: $("#note").val(), admin: '<?php echo $admin; ?>'  } )
	.done(function() { location.href='notes.php?admin=admin'; });
	}
	<?php } else { ?>
	function addnote() {
	$.post("notes.php", { place: '<?php echo $place; ?>', line: '<?php echo $line; ?>',action: 'add', note: $("#note").val(), admin: '<?php echo $admin; ?>'  } )
	.done(function() { top.location.href='../frontend/tpv.php?place=<?php echo $place; ?>'; });
	}
	<?php } ?>	
	function drop_note(text_drop) {
	$.post("notes.php", { place: '<?php echo $place; ?>', line: '<?php echo $line; ?>',action: 'drop', text_drop: text_drop } )
	.done(function() { location.href='notes.php?place=<?php echo $place; ?>&line=<?php echo $line; ?>'; });
	}
	<?php if ($admin=="admin") { ?>
	function select_note(text_select) {
	$.post("notes.php", { action: 'drop', text_drop: text_select, admin: 'admin' } )
	.done(function() { location.href='notes.php?admin=admin'; });
	}
	<?php } else { ?>
	function select_note(text_select) {
	$("#note").val(text_select);
	addnote();
	}
	<?php } ?>
	</script>

</head>

<body>


</div>
<div id="carbonForm">

	<h2><center>
	<?php if ($admin=="admin") {
	?><div class="field"><?php echo $langs->trans("Notes"); ?></div><br><?php
	} else {
	if ($line>0) { 
	$sql="SELECT description FROM ".MAIN_DB_PREFIX."commandedet where rowid=$line";
	$resql = $db->query($sql);
	$row = $db->fetch_array ($resql);
	$notes=explode(';',$row[0]);
	?><div class="field"><?php echo $langs->trans("Notes"); ?>-<?php echo $langs->trans("Products"); ?></div><br><?php }
	else { ?><div class="field"><?php echo $langs->trans("Notes"); ?> - <?php if ($place==0) echo $langs->trans("DirectSales"); else echo $langs->trans("Table")." ".$place; ?></div><br>
	<?php $sql="SELECT note_private FROM ".MAIN_DB_PREFIX."commande where ref='Place-$place'";
	$resql = $db->query($sql);
	$row = $db->fetch_array ($resql);
	$notes=explode(';',$row[0]);
	} }?>
	</center></h2>
	
	<?php $qtylines=count($notes); if ($qtylines>1 ) { ?>
    <div class="fieldContainer">
	<div class="field"><center><?php echo $langs->trans("ClickDelete"); ?>
	<?php while ($qtylines>1) {
	$qtylines--; ?>
	<input type="button" style="width: 90%" name="submit" value="<?php echo $notes[$qtylines-1];?>" onclick="drop_note('<?php echo $notes[$qtylines-1];?>');"/><br><br>
    <?php }?>
	</center></div>
	</div><br><?php } ?>
	
	<div class="fieldContainer">
	<center><div class="field" id="title"><h2><?php if ($admin=="admin") echo $langs->trans("Addnotes"); else echo $langs->trans("Freenote"); ?></h2></div></center><br>
	<div class="field">
	<center><input style="width: 90%" type="text" id="note" onKeyUp="addadd();" /></center>
    </div>
	</div>

	<br><h2><center><?php echo $langs->trans("PredefinedNotes"); ?></center></h1><br>


    <div class="fieldContainer">
		<center>
            <div class="field">
<?php
$notes = explode(';',$conf->global->PREDEFINED_NOTES_BAR);
$qtylines=count($notes);
if ($qtylines>1 and $admin=="admin") echo $langs->trans("ClickDelete");
while ($qtylines>1)
{
$qtylines--;
?>
<input type="button"  style=" width: 90%" name="submit" value="<?php echo $notes[$qtylines-1];?>" onclick="select_note('<?php echo $notes[$qtylines-1];?>');"/><br><br>
<?php
}			
?>           	
			</div>
        </div>
        
        <br>
  
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