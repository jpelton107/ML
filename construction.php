<?php
include("check.php");
include("global.php");

include("navigations.php");

if ($_GET[sent]=="1") {
	$query=mysql_query("INSERT INTO `workers` (`username`, `building`, `ticks`) VALUES ('$CHR[username]', '$_POST[building]', '$_POST[ticks]')");
	print $TEXT[under_construction];

}

else {
	$query=mysql_query("SELECT * FROM `construction` WHERE `location`='".$CHR[location]."'");
	$row=mysql_fetch_array($query);
	$rows=mysql_num_rows($query);
	if ($rows>0) {
		$query=mysql_query("SELECT * FROM `workers` WHERE `username`='".$CHR[username]."'");
		$rows=mysql_num_rows($query);
		if ($rows>0) {
			exit($ERROR[already_working]);
		}
		
		print $TEXT[construction][1].$row[pay].$TEXT[construction][2];
?>
<form action="construction.php?sent=1" method=post>
<input type=hidden name="building" value=<? print $row[building]; ?>>
Ticks: <select name="ticks">
	<option selected value="0">-Choose-</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option> 
       </select> <input type=submit name=submit value="  Begin Work...  ">
</form>
<?
	
	}
	else {
		print $TEXT[construction][null];
	}

}
?>
