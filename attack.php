<?php
include("check.php");
include("global.php");

include("navigations.php");

if ($_GET[sent]=="1") { 
	if ($_POST[atk_user]=="0") {
		exit("moron...");
	}
	/*								 */
	/* CHECK TO SEE IF THE USER THEY WANT TO ATTACK, IS ALREADY BUSY */
	/*								 */

	$query=mysql_query("SELECT * FROM `travel` WHERE `username`='".$_POST[atk_user]."'");
	if (mysql_num_rows($query)>0) {
		exit($ERROR[already_attacking]);
	}
	$query=mysql_query("SELECT * FROM `train` WHERE `username`='".$_POST[atk_user]."'");
	if (mysql_num_rows($query)>0) {
		exit($ERROR[defender_in_training]);
	}
	$query=mysql_query("SELECT * FROM `workers` WHERE `username`='".$_POST[atk_user]."'");
	if (mysql_num_rows($query)>0) {
		exit ($ERROR[working]);
	}


	$query=mysql_query("SELECT * FROM `travel` WHERE `username`='".$CHR[username]."'");
	if (mysql_num_rows($query)>0) {
		exit($ERROR[already_attacking]);
	}
	$query=mysql_query("SELECT * FROM `train` WHERE `username`='".$CHR[username]."'");
	if (mysql_num_rows($query)>0) {
		exit($ERROR[already_training]);
	}

	$query=mysql_query("SELECT * FROM `users` WHERE `username`='".$_POST[atk_user]."'");
	if (!$query) {
		exit(mysql_error());
	}
	$row=mysql_fetch_array($query);
	$ATK[str]=$row[str];
	$ATK[armor]=$row[armor];
	$ATK[shield]=$row[shield];
	if (!$ATK[armor]) {
		$ATK[armor_str]="0";
	}
	else {
		$query=mysql_query("SELECT * FROM `weapons` WHERE `name`='".$ATK[armor]."'");
		$row=mysql_fetch_array($query);
		$ATK[armor_str]=$row[exp];
	}
	if (!$ATK[shield]) {
		$ATK[shield_str]="0";
	}
	else {
		$query=mysql_query("SELECT * FROM `weapons` WHERE `name`='".$ATK[shield]."'");
		$row=mysql_fetch_array($query);
		$ATK[shield_str]=$row[exp];
	}

	//--------- TOTAL DEFENSE ----------//
	$ATK[total_def]=$ATK[str]+$ATK[armor_str]+$ATK[sheld_str];

	//--------- TOTAL OFFENSE ----------//
	$CHR[attack]=$CHR[str]+$CHR[weapon_str];

	//--------- TOTAL DAMAGE ----------//
	$DAMAGE=$CHR[attack]-$ATK[total_def];

	// SUBTRACT favor
	$CHR[favor]--;

	/* IF THE ATTACKERS DAMAGE IS MORE THAN THE DEFENDERS DAMAGE, THEN THE ATTACKER GAINS "5%" MORE STRENGTH */
	if ($CHR[attack] > $ATK[total_def]) {
		// ADD/TAKE THE GOLD
		$DMG[gold]=$CHR[gold]+round($row[gold]*0.1);
		$ATK[gold]=$row[gold]-round($row[gold]*0.1);

		// ADD THE STRENGTH TO ATKER
		$DMG[ATK_str]=$CHR[str]+round($ATK[str]*0.05);

		// ADD THE STRENGTH TO DEFENDER
		$DMG[DEF_str]=$row[str]+round($CHR[str]*0.025);
		
		$DMG[TOTAL]=$DMG[ATK_str]-$DMG[DEF_str];
		// UPADTE HEALTH
		$DMG[health]=$row[health]-$DMG[TOTAL];
		$CHR[health]=$CHR[health]-$DMG[TOTAL];

		//
		// GET QUEST INFORMATION 
		//
		$query=mysql_query("SELECT * FROM `quests` WHERE `username`='".$CHR[username]."'");
		$rows=mysql_num_rows($query);
		$row=mysql_fetch_array($query);
		if ($rows > 0) {
			$VICTIM=explode(":", $row[victim]);
			if ($VICTIM[0]==$_POST[atk_user]) {
				include("inc/successful_quest.php");
			}
			$query=mysql_query("DELETE FROM `quests` WHERE `username`='".$CHR[username]."' LIMIT 1");
		}


		//-----------------------------------
		// UPDATE DATABASE
		//-----------------------------------
		$query=mysql_query("UPDATE `users` SET `gold`='".$DMG[gold]."', `health`='".$CHR[health]."', `favor`='".$CHR[favor]."' WHERE `username`='".$CHR[username]."'");
		$query=mysql_query("INSERT INTO `gossip` (`attack`, `defend`, `success`, `date`, `city`) VALUES ('".$CHR[username]."', '".$_POST[atk_user]."', '1', '', '".$CHR[location]."')");
		$query=mysql_query("UPDATE `users` SET `gold`='".$ATK[gold]."', `str`='".$DMG[DEF_str]."', `health`='".$DMG[health]."' WHERE `username`='".$_POST[atk_user]."'");
		print $TEXT[successful_attack];
	}


//----------------------------------------------------
//----------------------------------------------------
//----------------------------------------------------



	/* IF THE ATTACK IS FAILED, THEN YOU GAIN 0 GOLD; ALL YOU REALLY GET IS EXTRA STRENGTH, BUT YOU LOSE HEALTH */
	else {
		// ADD THE STRENGTH TO ATKER
		$DMG[ATK_str]=$CHR[str]+round($ATK[str]*0.025);

		// ADD THE STRENGTH TO DEFENDER
		$DMG[DEF_str]=$row[str]+round($CHR[str]*0.05);
		

		$DMG[sustained_by_attacker]=$ATK[total_def]-$CHR[attack];
		// UPADTE HEALTH
		$CHR[health]=$CHR[health]-$DMG[sustained_by_attacker];

		//
		// GET QUEST INFORMATION 
		//
		$query=mysql_query("SELECT * FROM `quests` WHERE `username`='".$CHR[username]."'");
		$rows=mysql_num_rows($query);
		$row=mysql_fetch_array($query);
		if ($rows > 0) {
			$VICTIM=explode(":", $row[victim]);
			if ($VICTIM[0]==$_POST[atk_user]) {
				include("inc/unsuccessful_quest.php");
			}
			$query=mysql_query("DELETE FROM `quests` WHERE `username`='".$CHR[username]."' LIMIT 1");
		}


		//-----------------------------------
		// UPDATE DATABASE
		//-----------------------------------
		$query=mysql_query("UPDATE `users` SET `str`='".$DMG[ATK_str]."', `health`='".$CHR[health]."', `favor`='".$CHR[favor]."'  WHERE `username`='".$CHR[username]."'");
		$query=mysql_query("INSERT INTO `gossip` (`attack`, `defend`, `success`, `date`, `city`) VALUES ('".$CHR[username]."', '".$_POST[atk_user]."', '0', '', '".$CHR[location]."')");
		$query=mysql_query("UPDATE `users` SET `str`='".$DMG[DEF_str]."' WHERE `username`='".$_POST[atk_user]."'");
		print $TEXT[attack_unsuccessful];
	}
}
else {
?>

<form action="attack.php?sent=1" method=post>
Attack: <select name="atk_user">
	<option value="0">-Select-</option>
<?
$query=mysql_query("SELECT * FROM `users` WHERE `location`='".$CHR[location]."'");
$num=mysql_num_rows($query);
for($i=0;$i<$num;$i++) {
	$ATK[user]=mysql_result($query, $i, "username");
	if ($ATK[user]==$CHR[username]) { }
	else {
		print "<option value=$ATK[user]>$ATK[user]</option>";
	}
}
?>
	</select><br><br>
<input type=submit name=submit value="  Attack Now...  ">
</form>

<?
}
?>