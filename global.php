<?php
error_reporting(0);
$DEFAULT['food_health']="20";
$DEFAULT['str']="5";
$DEFAULT['spd']="5";
$DEFAULT['dex']="5";
$DEFAULT['exp']="";
$DEFAULT['int']="5";
$DEFAULT['gold']="25";
$DEFAULT['weapon']="Club";
$DEFAULT['armor']="0";
$DEFAULT['shield']="0";
$DEFAULT['health']="90";
$DEFAULT['location']="Rome";
$DEFAULT['favor']="0";
$DEFAULT['quest_ticks']="36";
$DEFAULT['quest']['gold']['0']="25";
$DEFAULT['quest']['gold']['1']="100";
$DEFAULT['quest']['favor']="5";
$DEFAULT['train']['str']="5";
$DEFAULT['train']['spd']="5";
$DEFAULT['train']['dex']="5";
$DEFAULT['train']['int']="5";
$DEFAULT['train']['health_decrease']="5";

include("inc/lang.php");
$GLOBAL['senate']['response']['3']="1000";
$GLOBAL['senate']['response']['2']="100";
$GLOBAL['senate']['response']['1']="10";
$GLOBAL['host']	="localhost";
$GLOBAL['user']	=	"root";
$GLOBAL['pass']	=	"";
$GLOBAL['db']	=	"medieval_legends";
$GLOBAL['global_table']="global";
$GLOBAL['title']=".:: Medieval Legends ::.";
include("inc/db_connect.php");
$GLOBAL['round']=$row['round'];

if ($CHR['username'] !== "") {
	$query=mysql_query("SELECT * FROM `users` WHERE `username`='".$CHR[username]."'");
	$row=mysql_fetch_array($query);
	$CHR['str']=$row['str'];
	$CHR['spd']=$row['spd'];
	$CHR['dex']=$row['dex'];
	$CHR['int']=$row['int'];
	$CHR['gold']=$row['gold'];
	$CHR['weapon']=$row['weapon'];
	if ($CHR['weapon']=="0") {
		$CHR['weapon']="";
	}
	$CHR['armor']=$row['armor'];
	if ($CHR['armor']=="0") {
		$CHR['armor']="";
	}
	$CHR['shield']=$row['shield'];
	if ($CHR['shield']=="0") {
		$CHR['shield']="";
	}
	$CHR['health']=$row['health'];
	$CHR['location']=$row['location'];
	$CHR['favor']=$row['favor'];

	$query=mysql_query("SELECT * FROM `weapons` WHERE `name`='".$CHR[weapon]."'");
	$row=mysql_fetch_array($query);
	$CHR[weapon_str]=$row[exp];
}

?>

