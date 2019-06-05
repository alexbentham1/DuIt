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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO accounting (`Time`, Post_ID, Username, User_ID, Post) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Time'], "date"),
                       GetSQLValueString($_POST['Post_ID'], "int"),
                       GetSQLValueString($_POST['Username'], "text"),
					   GetSQLValueString($_POST['User_ID'], "int"),
                       GetSQLValueString($_POST['Post'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($insertSQL, $MAMP) or die(mysql_error());

  $insertGoTo = "accounting.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_Active_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Active_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_Active_User = sprintf("SELECT Username FROM Users WHERE Username = %s", GetSQLValueString($colname_Active_User, "text"));
$Active_User = mysql_query($query_Active_User, $MAMP) or die(mysql_error());
$row_Active_User = mysql_fetch_assoc($Active_User);
$totalRows_Active_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $totalRows_Active_User = $_SESSION['MM_Username'];
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

mysql_select_db($database_MAMP, $MAMP);
$query_Recordset1 = "SELECT * FROM accounting ORDER BY Post_ID DESC";
$Recordset1 = mysql_query($query_Recordset1, $MAMP) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

if(isset($_GET['ID']))	{
	
$ID = mysql_real_escape_string($_GET['ID']);

$delete_record = "DELETE FROM accounting WHERE Post_ID = {$ID}";

mysql_query($delete_record) or die(mysql_error());
	
header("location: accounting.php");
	
exit();
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DuIt - Accounting</title>
<link href="../styles/styles1.css" rel="stylesheet" type="text/css" />
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


<div id="container">

<div id="navbar">

<a href="accounting.php">Accounting</a><br />
<a href="art.php">Art </a><br />
<a href="biology.php">Biology </a><br />
<a href="business_studies.php">Business Studies </a><br />
<a href="chemistry.php">Chemistry </a><br />
<a href="dance.php">Dance </a><br />
<a href="drama.php">Drama </a> <br />
<a href="economics.php">Economics </a><br />
<a href="english_lit.php">English Literature </a><br />
<a href="english_lang.php">English Language </a><br />
<a href="french.php">French </a><br />
Geography <br />
Geology <br />
German <br />
Government and Politics <br />
Health and Social Care <br />
History <br />
<a href="ICT.php">ICT </a><br />
Law <br />
Mathematics <br />
Media Studies <br />
Music <br />
Photography <br />
P.E. <br />
Physics <br />
Psychology <br />
R.E. <br />
Sociology <br />
Spanish <br />
Textiles and Fashion <br />

</div>

<div id="content">

<h3> Accounting </h3>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td><input type="hidden" name="Time" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Post_ID" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="Username" value="<?php echo $row_Active_User['Username']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td><input type="hidden" name="User_ID" value="<?php echo $row_Active_User['User ID']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"></td>
      <td><input type="text" name="Post" value="" size="80" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Post" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<table border="0">
  <?php do { ?>
    <tr>
    <td><img src="getImageStock.php?ID=<?php echo $row_Recordset1['User_ID']; ?>" width="40" height="40" alt="IMAGE" /></td>
      <td><?php echo $row_Recordset1['Username']; ?></td>
      <td>&nbsp;&nbsp;&nbsp;</td>
      
      <td width="500px"><?php echo $row_Recordset1['Post']; ?></td>

      <td>
      <script language="javascript">
	  var A_user = <?php echo $row_Active_User['User ID']; ?>;
	  var Post = <?php echo $row_Recordset1['User_ID']; ?>;
	  
	  
	  if(Post==A_user)
	  {
		document.write("<a href=accounting.php?ID=<?php echo $row_Recordset1['Post_ID']; ?>>Delete</a>");
	  }
	  
	  else{}
	  
	  </script>
      
	  </td>

    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
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
?>
