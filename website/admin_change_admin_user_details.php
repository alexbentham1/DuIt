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


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Admin_Users SET `First Name`=%s, Surname=%s, Username=%s, Password=%s WHERE `ID`=%s",
                       GetSQLValueString($_POST['First_Name'], "text"),
                       GetSQLValueString($_POST['Surname'], "text"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['User_ID'], "int"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "admin_users.php";
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
$query_Active_User = sprintf("SELECT * FROM Admin_Users WHERE Username = %s", GetSQLValueString($colname_Active_User, "text"));
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

$colname_all_users = "-1";
if (isset($_GET['ID'])) {
  $colname_all_users = $_GET['ID'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_all_users = sprintf("SELECT * FROM Users WHERE `User ID` = %s", GetSQLValueString($colname_all_users, "int"));
$all_users = mysql_query($query_all_users, $MAMP) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);
$totalRows_all_users = mysql_num_rows($all_users);

mysql_select_db($database_MAMP, $MAMP);
$query_all_admin_users = "SELECT * FROM Admin_Users ORDER BY ID ASC";
$all_admin_users = mysql_query($query_all_admin_users, $MAMP) or die(mysql_error());
$row_all_admin_users = mysql_fetch_assoc($all_admin_users);
$totalRows_all_admin_users = mysql_num_rows($all_admin_users);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DuIt - Administrator Access</title>
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

<body onload="MM_preloadImages('../images/images/images/Topbar_over_01.jpg','../images/images/images/Topbar_over_05.jpg','../images/images/images/Topbar_over_04.jpg')">

<div id="topbar">

<div id="logo">

  <img src="../Logos/Logo v1.png" width="auto" height="90" alt="DuIt" /> 
  
</div>

<div id="profilepic">


</div>

<div id="heading_name">
Administrator - <?php echo $row_Active_User['First Name']; ?>&nbsp;<?php echo $row_Active_User['Surname']; ?>
</div>

<div id="heading_links"> 

  <a href="admin.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Content','','../images/images/images/Topbar_over_01.jpg',1)"><img src="../images/images/Topbar_01.jpg" alt="Content" width="125" height="40" id="Content" /></a> 
  
  <a href="admin_users.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Users','','../images/images/images/Topbar_over_05.jpg',1)"><img src="../images/images/Topbar_05.jpg" alt="Users" width="125" height="40" id="Users" /></a> 

  
  <a href="<?php echo $logoutAction ?>" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Logout','','../images/images/images/Topbar_over_04.jpg',1)"><img src="../images/images/Topbar_04.jpg" alt="Logout" width="125" height="40" id="Logout" /></a> </div>

</div>


<div id="container_profile">
<br />
<h3>Enter the new user details here.</h3>
<br />
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">First Name:</td>
      <td><input type="text" name="First_Name" value="<?php echo htmlentities($row_all_admin_users['First Name'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Surname:</td>
      <td><input type="text" name="Surname" value="<?php echo htmlentities($row_all_admin_users['Surname'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">User ID:</td>
      <td><?php echo $row_all_admin_users['ID']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Username:</td>
      <td><input type="text" name="Username" value="<?php echo htmlentities($row_all_admin_users['Username'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Password:</td>
      <td><input type="text" name="Password" value="<?php echo htmlentities($row_all_admin_users['Password'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Details" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="User ID" value="<?php echo $row_all_admin_users['ID']; ?>" />
</form>
<p>&nbsp;</p>
</div>

</div>

<div id="copyright_fake">
  
</div>

<div id="copyright">

Copyright Alex Bentham, Nottingham Trent University.

</div>

</body>
</html>

<?php
mysql_free_result($Active_User);

mysql_free_result($Recordset1);

mysql_free_result($rs_items);

mysql_free_result($all_users);

mysql_free_result($all_admin_users);
?>
