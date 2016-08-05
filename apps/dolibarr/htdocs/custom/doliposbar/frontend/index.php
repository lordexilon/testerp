<?php
/* Copyright (C) 2011-2012	   Juanjo Menent   	   <jmenent@2byte.es>
 * Copyright (C) 2012	   	   Ferran Marcet   	   <jmenent@2byte.es>
 * Copyright (C) 2013		   Andreu Bisquerra Gayà<andreu@artadigital.com>
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 *	\file       htdocs/doliposbar/frontend/index.php
 * 	\ingroup	pos
 *  \brief      File to login to point of sales
 */

// Set and init common variables
// This include will set: config file variable $dolibarr_xxx, $conf, $langs and $mysoc objects

$res=@include("../../main.inc.php");
if (! $res) $res=@include("../../../main.inc.php");  

require_once('../backend/class/pos.class.php');
unset($_SESSION['uname']);

$langs->load("admin");
$langs->load("pos@doliposbar");



// Test if user logged
/*
if ( $_SESSION['uid'] > 0 )
{
	header ('Location: '.dol_buildpath('/doliposbar/frontend/disconect.php',1));
	exit;
}
*/

global $user,$conf;

$usertxt=$user->login;
$pwdtxt=$user->pass;

$openterminal=GETPOST("openterminal");

/*
 * View
 */
?> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>doliPOS BAR</title>

<link rel="stylesheet" type="text/css" href="css/login.css" />

	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<script type="text/javascript" src="js/login.js"></script>
	<link href="css/keyboard.css" rel="stylesheet">
	<script src="js/jquery.keyboard.js"></script>
	<link href="css/smoothness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">



</head>

<body onload="window.localStorage.clear();">
<div style="position:absolute; top:10%; left:2%; height:85%; width:6%;">
<img src="img/fork.png" width="100%" height="100%">
</div>
<div style="position:absolute; top:5%; right:2%; height:90%; width:6%;">
<img src="img/knife.png" width="100%" height="100%">
</div>
</div>
<div id="carbonForm">
	<h1><center>
	doliPOS BAR

	</center></h1>
	<h2><center><?php if(GETPOST("err","string")) { echo '<font color="red">'; print GETPOST("err","string"); echo "</font>"; }
	else echo $langs->trans("DoliposBAR");
	?>
	</center></h1>
	<br>
    <form action="verify.php" method="post" id="signupForm">

    <div class="fieldContainer">
		<center>
		 <?php 
		 /* NOT IMPREMENTED DOLIPOSBAR LITE
				$terminals=POS::select_Terminals();
				if(sizeof($terminals))
				{	
				*/
		?>
		<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />
        <div class="formRow">
            <div class="label">
                <label for="name"><?php echo $langs->trans("Login"); ?></label>
            </div>
            
            <div class="field">
                <input type="text" name="txtUsername" id="name" />
            </div>
        </div>
        
        <div class="formRow">
            <div class="label">
                <label for="password"><?php echo $langs->trans("Password"); ?></label>
            </div>
            
            <div class="field">
                <input type="password" name="pwdPassword" id="pass" />
            </div>
        </div>

<?php /*
        <div class="formRow">
            <div class="label">
                <label for="cash"><?php echo $langs->trans("CashS"); ?></label>
            </div>
            
            <div class="field">
                <select type="text" name="txtTerminal" id="terminal">
<?php 
					
					$i=0;
					foreach ($terminals as $terminal)
	    			{
	    				if($conf->browser->phone)
	    				{
	    					if($terminal["tactil"] == 2)
	    					{
	    						print "<option value='".$terminal["rowid"]."'>".$terminal["name"]."</option>\n";
	    					}
	    				}	
	    				else 
	    				{
	    					print "<option value='".$terminal["rowid"]."'>".$terminal["name"]."</option>\n";
	    				}
	      				
	      				$i++;
	    			}


</select>
            </div>
			</div>
 */ ?>
        
        <br>
  
    </div> <!-- Closing fieldContainer -->
    <br>
    <div class="field">
	<center>
        <input type="submit" name="submit" id="login" value="Login" />
	</center>
    </div>
    </form>
    <?php 
	/*
	}
	else
	{
	?>
	    <div class="field">
	<center>
		<?php echo $langs->trans("NotHasTerminal"); ?>
        <input type="button" onclick="../backend/terminal/cash.php?idmenu=12" value="Backend" />
	</center>
    </div>
	<?php
	}
	*/
	?>          
</div>

<h2 id="footer"><a href="../backend/liste.php">Backend</a></h2>

	<script>
	$('#pass')
 .keyboard({ layout: 'international' })
 .addTyping();
 </script>
 <script>
 	$('#name')
 .keyboard({ layout: 'international' })
 .addTyping();
 </script>


</body>
</html>
