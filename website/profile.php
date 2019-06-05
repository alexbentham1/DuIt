<?php require_once('../Connections/MAMP.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login_fail.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$skill1 = $_POST["skills1"];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Users SET `First Name`=%s, Surname=%s, `User ID`=%s, Password=%s, email=%s WHERE Username=%s",
                       GetSQLValueString($_POST['First_Name'], "text"),
                       GetSQLValueString($_POST['Surname'], "text"),
                       GetSQLValueString($_POST['User_ID'], "int"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['Username'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "profile.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Users SET Username=%s WHERE `User ID`=%s",
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['User ID'], "int"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "content.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Users SET `First Name`=%s, Surname=%s, Username=%s, Password=%s, email=%s WHERE `User ID`=%s",
                       GetSQLValueString($_POST['First_Name'], "text"),
                       GetSQLValueString($_POST['Surname'], "text"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['User_ID'], "int"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "../index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$Password = $_POST['Password'];
$passwordcheck = $_POST['passwordcheck'];

if ($_POST['Password'] != $_POST['passwordcheck']){
	echo 	'<script type="text/javascript">
			window.alert("The passwords do not match. Try again.")
			window.location.href="profile.php";
			</script>';
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE Users SET `First Name`=%s, Surname=%s, Username=%s, Password=%s, email=%s WHERE `User ID`=%s",
                       GetSQLValueString($_POST['First_Name'], "text"),
                       GetSQLValueString($_POST['Surname'], "text"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['User_ID'], "int"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "../index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE Skills SET Username=%s, Skill1=%s, email=%s WHERE `User ID`=%s",
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Skill1'], "text"),
					   GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['User_ID'], "int"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "profile.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Active_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Active_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_Active_User = sprintf("SELECT * FROM Users WHERE Username = %s", GetSQLValueString($colname_Active_User, "text"));
$Active_User = mysql_query($query_Active_User, $MAMP) or die(mysql_error());
$row_Active_User = mysql_fetch_assoc($Active_User);
$totalRows_Active_User = mysql_num_rows($Active_User);

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_Recordset1 = sprintf("SELECT * FROM Skills WHERE Username = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $MAMP) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_MAMP, $MAMP);
$query_rs_items = "SELECT * FROM tbl_mybooks";
$rs_items = mysql_query($query_rs_items, $MAMP) or die(mysql_error());
$row_rs_items = mysql_fetch_assoc($rs_items);
$totalRows_rs_items = mysql_num_rows($rs_items);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Your Profile</title>
<link href="../styles/styles1.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
</script>
</head>

<body onload="MM_preloadImages('../images/images/images/Topbar_over_01.jpg','../images/images/images/Topbar_over_02.jpg','../images/images/images/Topbar_over_03.jpg','../images/images/images/Topbar_over_04.jpg')">

<div id="topbar">

<div id="logo">

  <img src="../Logos/Logo v1.png" width="auto" height="90" alt="DuIt" /> 
  
</div>

<div id="profilepic">

<img src="getImageStock.php?ID=<?php echo $row_Active_User['User ID']; ?>" width="85" height="85" alt="IMAGE" />

</div>

<div id="heading_name">
<?php echo $row_Active_User['First Name']; ?>
</div>

<div id="heading_links"> 

  <a href="content.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Content','','../images/images/images/Topbar_over_01.jpg',1)"><img src="../images/images/Topbar_01.jpg" alt="Content" width="125" height="40" id="Content" /></a> 
  
  <a href="profile.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Profile','','../images/images/images/Topbar_over_02.jpg',1)"><img src="../images/images/Topbar_02.jpg" alt="Profile" width="125" height="40" id="Profile" /></a> 
  
  <a href="help.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Help','','../images/images/images/Topbar_over_03.jpg',1)"><img src="../images/images/Topbar_03.jpg" alt="Help" width="125" height="40" id="Help" /></a> 
  
  <a href="<?php echo $logoutAction ?>" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Logout','','../images/images/images/Topbar_over_04.jpg',1)"><img src="../images/images/Topbar_04.jpg" alt="Logout" width="125" height="40" id="Logout" /></a> </div>

</div>


<div id="container_profile">

<br />

<h3>Hi <?php echo $row_Active_User['First Name']; ?> <?php echo $row_Active_User['Surname']; ?></h3>

<br /><br />

<table align="center">
<tr>
<td><b>Username </b></td>
<td></td><td></td>
<td><b>Password</b></td>
<td></td><td></td>

<td><b>Profile Picture</b></td>
</tr>
<tr>
<td>Change Username</td>
<td></td><td></td>
<td>Change Password</td>
<td></td><td></td>
<td>Change Picture</td>
</tr>
<tr>
<td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="left">
    <tr valign="baseline">
      <td><input type="hidden" name="First_Name" value="<?php echo htmlentities($row_Active_User['First Name'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Surname" value="<?php echo htmlentities($row_Active_User['Surname'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="User ID" value="<?php echo htmlentities($row_Active_User['User ID'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="text" name="Username" value="<?php echo htmlentities($row_Active_User['Username'], ENT_COMPAT, 'UTF-8'); ?>" size="25" /></td>
      <td nowrap="nowrap" align="left"><input type="submit" value="Submit" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Password" value="<?php echo htmlentities($row_Active_User['Password'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="email" value="<?php echo htmlentities($row_Active_User['email'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Image" value="<?php echo htmlentities($row_Active_User['Image'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Image_Type" value="<?php echo htmlentities($row_Active_User['Image Type'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="User ID" value="<?php echo $row_Active_User['User ID']; ?>" />
</form>
</td>
<td></td><td></td>
<td><form action="<?=$_SERVER['PHP_SELF'] ?>" method="post" name="form2" id="form2">
  <table align="left">
    <tr valign="baseline">
      <td><input type="hidden" name="First_Name" value="<?php echo htmlentities($row_Active_User['First Name'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Surname" value="<?php echo htmlentities($row_Active_User['Surname'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="User ID" value="<?php echo htmlentities($row_Active_User['User ID'], ENT_COMPAT,'UTF-8'); ?>"</td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Username" value="<?php echo htmlentities($row_Active_User['Username'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
    <td>New Password<br />
Retype</td>
      <td><input type="password" name="Password" value="" size="20" /><br />
		<span id="sprypassword1">
        <input type="password" name="passwordcheck" id="password" />
        <span class="passwordRequiredMsg">Can't be empty.</span></span>
		<input type="submit" value="Submit" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="email" value="<?php echo htmlentities($row_Active_User['email'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2" />
  <input type="hidden" name="User ID" value="<?php echo $row_Active_User['User ID']; ?>" />
</form>
  </td>
  
  <td></td><td></td>
  
  <td>	
  <form action="profile.php" method="POST" enctype="multipart/form-data">
  <input type="file" name="image" /> <input type="submit" name="submit" value="Upload" />
  </form>
  
  <?php
  
  //file properties
  $file = $_FILES['image']['tmp_name'];
  
  
  if (!isset($file))	{
	  
	  
  }
  
  else	{

	  
		$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
		$image_name = addslashes($_FILES['image']['name']);
		$image_size = getimagesize($_FILES['image']['tmp_name']);
		$user_id = $row_Active_User['User ID'];
		
		$sql="DELETE FROM tbl_mybooks WHERE picture_id='$user_id'";
   		$result=mysql_query($sql);
		
		if($image_size == FALSE)	{
			
				echo "That's not an image!";	
			
		}
		
		else	{
		
				if (!$insert = mysql_query("INSERT INTO tbl_mybooks VALUES ('$user_id','$image_name','$image')"))	{
					
						echo "Problem uploading image.";
				
				}
				
				else	{
						
				$last_id = mysql_insert_id();
				?>
				Image uploaded. 
                <?php 	
					
				}
			
		}
	  
  }
  
  
  ?>
  
  </td>

</tr>
</table>

<h3>Changed Username or Password? You'll be asked to log in again. </h3>

<br /><br />

<div id="skills">

<h2>The skills to DuIt!</h2>

<p>You're currently down as an expert in <?php echo $row_Recordset1['Skill1']; ?> <br /><br />
<b>Want to Change?</b><br /><br />
Select your subject from the list.<br />
Press the 'Submit' button.<br />
Press 'Save New Skill'.<br />
</p>


<table>
<tr>
<td>
<form action="profile.php" method="post">

<select name="skill">
<option selected="selected">Choose new Skill</option>
<option>Accounting</option>
<option>Art</option>
<option>Biology</option>
<option>Business Studies</option>
<option>Chemistry</option>
<option>Dance</option>
<option>Drama</option>
<option>Economics</option>
<option>English Literature</option>
<option>English Language</option>
<option>French</option>
<option>Geography</option>
<option>Geology</option>
<option>German</option>
<option>Gvt. & Politics</option>
<option>Health & Social Care</option>
<option>History</option>
<option>ICT</option>
<option>Law</option>
<option>Mathematics</option>
<option>Media Studies</option>
<option>Music</option>
<option>Photography</option>
<option>P.E.</option>
<option>Physics</option>
<option>Psychology</option>
<option>R.E.</option>
<option>Sociology</option>
<option>Spanish</option>
<option>Textiles & Fashion</option>


<input type="submit" name="submit" value="Submit" />

</form>

</td>
</tr>
</table>


  <?php do { ?>

      <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3">
      
    <table align="left">
      <tr valign="baseline">
        <td><input type="hidden" value="<?php echo $row_Active_User['User ID']; ?>"/></td>
      </tr>
      <tr valign="baseline">
        <td><input type="hidden" name="Username" value="<?php echo htmlentities($row_Active_User['Username'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
      <input type="hidden" name="email" value="<?php echo htmlentities($row_Active_User['email'], ENT_COMPAT, 'UTF-8'); ?>" size="32" />
      </tr>
      <tr valign="baseline">
        <td><input type="hidden" name="Skill1" value="<?php echo $_POST["skill"]; ?>" size="20" /></td>
      </tr>
      
      <tr valign="baseline">
      <td>
      <?php echo $_POST["skill"]; ?>
      </td>
      </tr>
      
      <tr valign="baseline">
        <td><input type="submit" value="Save New Skill" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form3" />
    <input type="hidden" name="User ID" value="<?php echo $row_Active_User['User ID']; ?>" />
  </form>
    </tr>
   
<?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>

<br /><br />
 

</div>

</div>

<div id="copyright_fake">
  
</div>

<div id="copyright">

Copyright Alex Bentham, Nottingham Trent University.

</div>

<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>

<?php
mysql_free_result($Active_User);

mysql_free_result($Recordset1);

mysql_free_result($rs_items);
?>
