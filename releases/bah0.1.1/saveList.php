<?php
/*
bitchassholla - web based mp3 streaming audio management application.
Copyright (C) 2002 Nathan Garretson

This file is part of bitchassholla.

bitchassholla is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

bitchassholla is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with bitchassholla; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Contact: Visit the "contact" section of http://www.bitchassholla.com. 
*/
include("BAH.conf.php");
// start building output box
$outputpage = "<html><head><title>working...</title></head><body><center>";
//fix password
$password = stripslashes(basename($_POST["passwd"]));
// fix filename
$filename = stripslashes(basename($_POST["fname"]));
// start out sucess good
$sucess = true;

// checks for bad incoming data
if ($password == "" || $filename == "") {
	$outputpage .= "<b>Can't Save Playlist</b><br /><br />Bad password and/or filename.</center></body></html>";
	$sucess = false;
}
// checks if pid/passwd file exists, checks password, and checks if still running
if (file_exists(BASEDIR . "/pids/" . $filename)) {
	$fcontents = file(BASEDIR . "/pids/" . $filename);
	if (trim($fcontents[0]) != $password) { 
		$outputpage .= "<b>Can't Save Playlist</b><br /><br />Invalid password for " . $filename . "</center></body></html>";
		$sucess = false;
	}
	if (trim($fcontents[1]) != "" && $sucess) { 
		$outputpage .= "<b>Can't Save Playlist</b><br /><br />" . $filename . " is still running.</center></body></html>";
		$sucess = false;
	}
}
if ($sucess) {
	// replace windows newline with unix newline
	$songlist = str_replace("\r\n","\n", $_POST["outgoingsongs"]);
	
	// open file to write playlist
	$fplist = fopen(WEBDIR . "/playlists/" . $filename, "w");
	// open file to write password/pids
	$fppid = fopen(BASEDIR . "/pids/" . $filename, "w");
	if (!$fplist || !$fppid) {
		$outputpage .= "<b>Can't Save Playlist</b><br>System error of some kind. Please retry saving. If this error continues please contact the admin</center></body></html>";
	}
	else {	
		fwrite($fplist, $songlist);
		fwrite($fppid, $password . "\n");
		fclose($fppid);
		fclose($fplist);
		$outputpage .= "now saving " . $filename . "...<br><br><a href='javascript:void(0);' onclick='window.close();'>close window</a></center></body></html>";
	}
}
echo "<html>\n<head>\n<script language=\"JavaScript\">\n";
echo "var working = window.open(\"\", \"working\", \"width=200,height=150,toolbar=no,status=no,scrollbars=no,location=no,menubar=no,directories=no\");\n";
echo "working.document.open(\"text/html\");\n";
echo "working.document.write(\"" . $outputpage . "\");\n";
echo "document.location = \"" . $_POST["currlocation"] . "\";\n";
echo "</script>\n</head>\n</html>";

?>


