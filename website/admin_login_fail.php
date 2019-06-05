<?php require_once('../Connections/MAMP.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "content.php";
  $MM_redirectLoginFailed = "login_fail.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_MAMP, $MAMP);
  
  $LoginRS__query=sprintf("SELECT Username, Password FROM Users WHERE Username=%s AND Password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $MAMP) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DuIt - get it done!</title>
<link href="../styles/styles1.css" rel="stylesheet" type="text/css" />
</head>

<body onload="MM_preloadImages('../Logos/Logo v1 pressed.png')">

<div id="container_index">

<div id="title">

<div id="logo_main">

<br />
<br />

<img src="../Logos/Logo v1.png" alt="DuIt" width="300" height="auto" id="Image2" /></a>

</div>

<h2>Get it done!</h2>

</div>

<div id="login">

<h2>Login Failed!</h2>

<p>Check your Username and Password and try again.</p>

<form ACTION="<?php echo $loginFormAction; ?>" method="POST" name="login" id="login">

<p>Username: <input name="username" type="text" id="username" size="20" maxlength="40" /></p>

<p>Password: <input type="password" name="password" size="20" maxlength="20" /></p>

<p><input type="submit" name="submit" value="Login" /></p>
</form>

<p><a href="admin_login_help.php" </a>Can't Log In?</p>

<br />
<br />


</div>

</div>

</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
