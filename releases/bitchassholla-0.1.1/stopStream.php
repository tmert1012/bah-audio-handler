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
//update file path
$filename = stripslashes(basename($_POST["sbox"]));
// start out sucess good
$sucess = true;

// checks for bad incoming data
if ($password == "" || $filename == "") {
	$outputpage .= "<b>Can't Stop Stream</b><br /><br />Bad password and/or filename.</center></body></html>";
	$sucess = false;
}
// checks if pid/passwd file exists, checks password, and checks if still running
if (file_exists(BASEDIR . "/pids/" . $filename)) {
	$fcontents = file(BASEDIR . "/pids/" . $filename);
	if (trim($fcontents[0]) != $password && $sucess) { 
		$outputpage .= "<b>Can't Stop Stream</b><br /><br />Invalid password for " . $filename . "</center></body></html>";
		$sucess = false;
	}
	if (trim($fcontents[1]) == "" && $sucess) { 
		$outputpage .= "<b>Can't Stop Stream</b><br /><br />" . $filename . " isn't running.</center></body></html>";
		$sucess = false;
	}
}
if ($sucess) {
	$output = exec("kill -9 " . trim($fcontents[1]));
	if ($output != "") {
		$outputpage .= "<b>Can't Stop Stream</b><br /><br />" . $filename . " isn't running.</center></body></html>";
	}
	else {
	$fppid = fopen(BASEDIR . "/pids/" . $filename, "w");
	fwrite($fppid, $fcontents[0]);
	fclose($fppid);
	$output = exec("rm " . WEBDIR . "/m3u/" . $filename . ".m3u");
	$outputpage .= $filename . " stopped...<br><br><a href='javascript:void(0);' onclick='window.close();'>close window</a></center></body></html>";
	}
}
echo "<html>\n<head>\n<script language=\"JavaScript\">\n";
echo "var working = window.open(\"\", \"working\", \"width=200,height=150,toolbar=no,status=no,scrollbars=no,location=no,menubar=no,directories=no\");\n";
echo "working.document.open(\"text/html\");\n";
echo "working.document.write(\"" . $outputpage . "\");\n";
echo "document.location = \"" . $_POST["currlocation"] . "\";\n";
echo "</script>\n</head>\n</html>";

