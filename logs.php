<?php
	/**
		CONTAINS EVENT LOGGING FUNCTIONS
	**/

	//Get client's registration ID
	function phpx_clientID()
	{
		if(isset($_SESSION[$_SESSION['phpx']['project'].'clientID']) && $_SESSION[$_SESSION['phpx']['project'].'clientID']>=0)
			return $_SESSION[$_SESSION['phpx']['project'].'clientID'];
		return -1;
	}

	//Checks if it's first time the client visit the website (on current session)
	function phpx_clientVisited()
	{
		if(isset($_COOKIE[$_SESSION['phpx']['project'].'clientVisited'])) return true;
		return false;
	}

	//Get client's registration ID in DB
	function phpx_clientGetID()
	{
		//If client's ID isn't set or is invalid, get it
		if(phpx_clientID()<0)
		{
			$_SESSION[$_SESSION['phpx']['project'].'clientID']=-1;
			$_SESSION[$_SESSION['phpx']['project'].'clientVisits']=0;
			$_SESSION[$_SESSION['phpx']['project'].'clientBanned']=0;
			$client=phpx_dbGet("phpx_".$_SESSION['phpx']['project'], "SELECT id, visits, banned from client WHERE ip=\"".phpx_getIP()."\" LIMIT 1");
			if(count($client)==1) {
				$_SESSION[$_SESSION['phpx']['project'].'clientID']=$client[0]['id'];
				$_SESSION[$_SESSION['phpx']['project'].'clientVisits']=$client[0]['visits'];
				$_SESSION[$_SESSION['phpx']['project'].'clientBanned']=$client[0]['banned'];
			}
		}
	}

	//Monitor activity by pushing a new log to server
	function phpx_logPush($details, $access_lvl) {
		$client_id=phpx_clientID();
		if($client_id>=0 && isset($details) && isset($access_lvl) && $access_lvl>=0 && $access_lvl<=2) {
			//Get last activity
			$result=phpx_dbGet("phpx_".$_SESSION['phpx']['project'], "SELECT id, details, level FROM activity WHERE client=".$client_id." ORDER BY _date desc LIMIT 1");
			$activity_id=-1;
			foreach ($result as $r) {
				if($r['details']==$details && $r['level']==$access_lvl) {
					$activity_id=$r['id'];
					break;
				}
			}
			//Just update last activity _date
			if($activity_id>0)
				phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "UPDATE activity SET _date=NOW() WHERE id=".$activity_id);
			//Or insert new activity
			else
				phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "INSERT INTO activity(client, details, level) VALUES (".$_SESSION[$_SESSION['phpx']['project'].'clientID'].", \"".$details."\", ".$access_lvl.")");
		}
	}

	//Log client into DB
	function phpx_logClient() {
		phpx_clientGetID();
		//If first time visit
		if(!phpx_clientVisited()) {
			//Remember him
			setcookie($_SESSION['phpx']['project']."clientVisited", 1);
			//Log him for the first time if his IP is not yet in DB
			if(phpx_clientID()<0) {
				phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "INSERT INTO client(ip, visits) VALUES (\"".phpx_getIP()."\", 1)");
				phpx_logPush("First time visit [".phpx_getIP()."]", 0);
			}
			//Else just update the nb of his visits
			else {
				phpx_logPush("Welcoming back [".phpx_getIP()."]", 0);
				$_SESSION[$_SESSION['phpx']['project'].'clientVisits']++;
				phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "UPDATE client SET visits=".$_SESSION[$_SESSION['phpx']['project'].'clientVisits']." WHERE id=".$_SESSION[$_SESSION['phpx']['project'].'clientID']);
			}
		}
	}

	//Clear all logs in DB, I suggest back it up first
	function phpx_logsClear() {
		phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "DELETE FROM activity WHERE id>0");
		phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "ALTER TABLE activity AUTO_INCREMENT=1");
		phpx_logPush("Logs cleared", 2);
	}

	//Create log file
	function phpx_logsDownload() {
		$logs=phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "SELECT * FROM activity ORDER BY _date desc");
		if(count($logs)>0) {
			$tmp=new DateTime();
			$current_date=$tmp->format('Y-m-d H:i:s');
			$log_name=date("Y")."_".date("m")."_".date("d")."__".date("H")."_".date("i")."_".date("s").".html";

			//Set logs headers
			ob_clean();
			$content="<!--\n\tCreated with the PHPX framework\n\thttps://github.com/Malice1313/phpx/\n--><!DOCTYPE HTML>\n<HTML>\n\t<HEAD>\n\t\t<meta charset='utf-8'/>\n\t\t<meta name='viewport' content='width=device-width, initial-scale=1'/>\n\t\t<title>[".$current_date."]</title>\n\t</HEAD>\n\t<BODY>\n\t\t<center>\n\t\t\t<h1>Logs [".$current_date."]</h1>\n\t\t</center>\n\t\t<center>\n\t\t\t<table border='1' cellspacing='0' cellpadding='7'>\n\t\t\t\t<tr bgcolor='#000088'>\n\t\t\t\t\t<td align='center' height='40'><font color='#FFFFFF'>ID</font></td>\n\t\t\t\t\t<td align='center' height='40'><font color='#FFFFFF'>IP</font></td>\n\t\t\t\t\t<td align='center' height='40'><font color='#FFFFFF'>DETAILS</font></td>\n\t\t\t\t\t<td align='center' height='40'><font color='#FFFFFF'>DATE</font></td>\n\t\t\t\t</tr>
				";
			$count=0;
			//Activity list printed in HTML table format
			foreach($logs as $l) {
				if($l["level"]==0)
					$content.="\t\t\t\t<tr>";
				else if($l["level"]==1)
					$content.="\t\t\t\t<tr bgcolor='#FF9300'>";
				else if($l["level"]==2)
					$content.="\t\t\t\t<tr bgcolor='#FF0000'>";
				$content.="\n\t\t\t\t\t<td align='center' width='65'>".$l["client"]."</td>";
				$client=phpx_dbRequest("phpx_".$_SESSION['phpx']['project'], "SELECT ip FROM client WHERE id=".$l["client"]." LIMIT 1");
				if(count($client==1))
					$content.="\n\t\t\t\t\t<td align='center' width='115'>".$client[0]["ip"]."</td>";
				$content.="\n\t\t\t\t\t<td align='left' width='450'>".$l["details"]."</td>";
				$content.="\n\t\t\t\t\t<td align='center'>".$l["_date"];
				$content.="\n\t\t\t\t</tr>";
				$count++;
			}
			//Foot
			$content.="\n\t\t\t\t<tr bgcolor='#000088'>\n\t\t\t\t\t<td align='center' colspan='4' height='40'><font color='#FFFFFF'>TOTAL : [".$count."]</font>\n\t\t\t\t\t</td>\n\t\t\t\t</tr>";
			$content.="\n\t\t\t</table>\n\t\t</center>\n\t</BODY>\n</HTML>";
			if($count==0)
				$content.="<center><h1>NO LOGS</h1></center>";
			phpx_mkdir("logs");
			phpx_mkfile("logs".$log_name, $content);
			phpx_download("logs".$log_name);
			phpx_logPush("Downloading logs", 1);
			return true;
		}
		return false;
	}

?>
