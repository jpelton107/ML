<?php
include("check.php");
include("global.php");

include("navigations.php");

print "<b>".$CHR[location]."</b> gossip.<br><br><hr>";
$query=mysql_query("SELECT * FROM `gossip` WHERE `city`='".$CHR[location]."'  ORDER BY `date` DESC LIMIT 0,50");
$num=mysql_num_rows($query);
for($i=0;$i<$num;$i++) {
	$ATTACK[username]=mysql_result($query, $i, "attack");
	$DEFEND[username]=mysql_result($query, $i, "defend");
	$DATE=mysql_result($query, $i, "date");
	$SUCCESS=mysql_result($query, $i, "success");
	$rand=rand(1, 3);
	if ($SUCCESS=="1") {
		print "<font color=green>";
		if ($rand=="1") {
			print "It is said that <b>".$ATTACK[username]."</b> made an attempt at assulting <b>".$DEFEND[username]."</b>.  This attempt, however, was a success.  ".$DEFEND[username]." was robbed of 10% of his gold.";
		}
		elseif ($rand=="2") {
			print "Rumor is spreading that <b>".$DEFEND[username]."</b> was attacked.  Many people believe that <b>".$ATTACK[username]."</b> was the one who led the assult.";
		}
		else {
			print "The people of ".$CHR[location]." fear <b>".$ATTACK[username]."</b>.  They say he robbed <b>".$DEFEND[username]."</b>.";
		}
		print "<br><br></font>";
	}
	else {
		print "<font color=red>I've heard people tell me that <b>".$DEFEND[username]."</b> was attacked by <b>".$ATTACK[username]."</b>.  They say he defeated ".$ATTACK[username].".";
		print "<br><br></font>";
	}
}

?>
