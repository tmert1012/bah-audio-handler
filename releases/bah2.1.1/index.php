<?php 
//    bah2 - bah audio handler - custom streaming internet audio
//    Copyright (C) 2005 Nathan Garretson

//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.

//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.

//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	session_start();
	//error_reporting(E_ALL);
	include("BAH2.conf.php");	
	// logout
	if (isset($_GET["logout"])) {
		unset($_SERVER["PHP_AUTH_USER"]);
		unset($_SERVER["PHP_AUTH_PW"]);
		session_unset();
		session_destroy();
		login();
	}
	// form submission
	else if (isset($_POST["submit"])) {
		if (($_POST["user"] == USER) and ($_POST["password"] == PASSWD)) {
			$_SESSION["user"] = $_POST["user"];
			$_SESSION["password"] = $_POST["password"];
			include("bah2.php");
			writeM3U();
			launchStream();
			printMain();
		} 
		else { login(); }
	}
	// make sure user and pass are defined
	else if ((!isset($_SESSION["user"])) and (!isset($_SESSION["password"]))) {
		login();
	}
	// make sure user and pass are correct
	else if (($_SESSION["user"] == USER) and ($_SESSION["password"] == PASSWD)) {
		include("bah2.php");
		printMain();
	}
	
	
function login() {
	echo "<!DOCTYPE html PUBLIC \"-//W3C/DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">";	
	echo "<html xmlns=\"http://www/w3/org/TR/xhtml1\">";
	echo "<head>";
	echo "<title>bah2 :: login</title>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"bah2.css\" />";
	echo "</head>";
	echo "<body>";
	echo "<div class=\"browse\">";
	echo "<h2><img src=\"icon/Globe.gif\">&nbsp;bah2 :: bah audio handler</h2>";
	echo "<a href=\"http://bah.thesilentnoise.com\">http://bah.thesilentnoise.com</a><br /><br />";
	echo "<form action=\"index.php\" method=\"POST\">";
	echo "<table>";
	echo "<tr>";
	echo "<td>User:</td><td><input type=\"text\" name=\"user\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Password:</td><td><input type=\"password\" name=\"password\"></td>";
	echo "</tr>";
	echo "</table><br />";
	echo "<input type=\"submit\" name=\"submit\" value=\"submit\">&nbsp;";
	echo "<input type=\"reset\" value=\"reset\">";
	echo "</form>";
	echo "<table class=\"littlelegal\"><tr><td>copyright &#169; 2002-2005 nathan garretson.<br />";
	echo "all rights reserved.<br />";
	echo "bah audio handler 2 (formally bitchassholla) is distributed under the terms of the <a href=\"http://www.gnu.org/copyleft/gpl.html\">GNU General Public License</a>.</td></tr></table>";
	echo "</div>";
	echo "</body>";
	echo "</html>";
}

function printMain() {
	echo "<!DOCTYPE html PUBLIC \"-//W3C/DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">";	
	echo "<html xmlns=\"http://www/w3/org/TR/xhtml1\">";
	echo "<head>";
	echo "<title>bah2 :: bah audio handler 2</title>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"bah2.css\" />";
	echo "</head>";
	echo "<body>";
	echo "<div class=\"titlebox\">";
	echo "<p class=\"boxtitle\">bah audio handler 2 ::</p>&nbsp;";
	echo $_SESSION["user"] . "&nbsp;<a href=\"?logout=true\" class=\"reglink\">(logout)</a>";
	echo "</div>";
	echo "<div class=\"browse\">";
	echo "<p class=\"boxtitle\">browse ::</p>&nbsp;" . buildPath();
	echo "<br />";
	echo "<div class=\"listbox\" id=\"browseBox\">";
	fillBrowseWindow();
	echo "</div>";
	echo "</div>";
	echo "<div class=\"mylist\">";
	echo "<p class=\"boxtitle\">mylist ::</p>&nbsp;<a href=\"m3u/" . $_SESSION["user"] . ".m3u\"><img src=\"icon/sound1.gif\">&nbsp;" . $_SESSION["user"] . ".plist</a>";
	echo "<br />";
	echo "<div class=\"listbox\" id=\"browseBox\">";
	displayPlaylist();
	echo "</div>";
	echo "</div>";
	echo "</body>";
	echo "</html>";
}
