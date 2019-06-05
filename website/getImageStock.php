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

$colname_rs_image = "-1";
if (isset($_GET['ID'])) {
  $colname_rs_image = $_GET['ID'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_rs_image = sprintf("SELECT * FROM tbl_mybooks WHERE picture_id = %s", GetSQLValueString($colname_rs_image, "int"));
$rs_image = mysql_query($query_rs_image, $MAMP) or die(mysql_error());
$row_rs_image = mysql_fetch_assoc($rs_image);
$totalRows_rs_image = mysql_num_rows($rs_image);


header("Content-type: image/jpg");
echo $row_rs_image['image_stk']; 



mysql_free_result($rs_image);
?>
