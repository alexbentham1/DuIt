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

$colname_Active_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Active_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_Active_User = sprintf("SELECT Username FROM Users WHERE Username = %s", GetSQLValueString($colname_Active_User, "text"));
$Active_User = mysql_query($query_Active_User, $MAMP) or die(mysql_error());
$row_Active_User = mysql_fetch_assoc($Active_User);
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
$query_Messages = "SELECT * FROM Main_Messages ORDER BY `Time` DESC";
$Messages = mysql_query($query_Messages, $MAMP) or die(mysql_error());
$row_Messages = mysql_fetch_assoc($Messages);
$totalRows_Messages = mysql_num_rows($Messages);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Welcome to DuIt!</title>
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
<a href="business.php">Business Studies </a><br />
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

<h3> Select your subject area from the left-hand menu. </h3> 

<br />

<h3>Messages</h3>

<br />
<table align="center">
  
  <?php do { ?>
  <tr>
    <td width="100px"><h2><?php echo $row_Messages['First Name']; ?>&nbsp;<?php echo $row_Messages['Surname']; ?></h2></td>
    <td>&nbsp;</td>
    <td width="700px"><?php echo $row_Messages['Post']; ?></td>
  </tr>
  <?php } while ($row_Messages = mysql_fetch_assoc($Messages)); ?>
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

mysql_free_result($Messages);
?>
