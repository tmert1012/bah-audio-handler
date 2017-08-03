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
//update file path
$filename = WEBDIR . "/playlists/" . $_POST["sbox"];
//set mountpoint
$mountpoint = substr($filename, strrpos($filename, "/") + 1, strlen($filename));
//fix password
$password = basename($_POST["passwd"]);
// start output page
$outputpage = "<html><head><title>working...</title></head><body><center>";
// start out sucess as good, cause im black.
$sucess = true;

// checks for bad incoming data
if ($password == "" || $_POST["sbox"]  == "") {
	$outputpage .= "<b>Can't Launch Playlist</b><br /><br />Bad password and/or filename.</center></body></html>";
	$sucess = false;
}
// checks to make sure that the playlist passed in exists..
if (!file_exists($filename) && $sucess) {
	$outputpage .= "<b>Can't Launch Playlist</b><br /><br />" . $mountpoint . " doesnt exist</center></body></html>";
	$sucess = false;
}
// checks if playlist pid/passwd file exists, if password is good, and if playlist is allready running
if (file_exists(BASEDIR . "/pids/" . $mountpoint)) {
	$fcontents = file(BASEDIR . "/pids/" . $mountpoint);
	if (trim($fcontents[0]) != $password && $sucess) { 
		$outputpage .= "<b>Can't Launch Playlist</b><br /><br />Invalid password for " . $mountpoint . "</center></body></html>";
		$sucess = false;
	}
	if (trim($fcontents[1]) != "" && $sucess) { 
		$outputpage .= "<b>Can't Launch Playlist</b><br /><br />" . $mountpoint . " allready running.</center></body></html>";
		$sucess = false;
	}
}
else { 
	$outputpage .= "<b>Can't Launch Playlist</b><br /><br />" . $mountpoint . "'s data file doesnt exist.</center></body></html>";
	$sucess = false;
}

if ($sucess) { 
	//set location
	$hostname = substr($_POST["currlocation"], 0, strlen($_POST["currlocation"]) - 10);
	// get server ip
	$ip = substr($hostname, 7, strlen($hostname));
	//launch stream
	$pidinfo = exec(ICESDIR . "/ices -B -F \"$filename\" -b 128 -n \"bitchassholla -- ($mountpoint)\" -m $mountpoint -p 8000 -P hackme -r -s -h $ip");
	// get pidinfo
	$pidinfo = substr($pidinfo, strrpos($pidinfo, ":") + 1, (strrpos($pidinfo, ")") - strrpos($pidinfo, ":") + 1) -2);

	// write array (in hidden field) out to filename
	$fp = fopen(BASEDIR . "/pids/" . $mountpoint , "a");
	$fp2 = fopen(WEBDIR . "/m3u/" . $mountpoint . ".m3u", "w");
	if (!$fp || !$fp2) {
		$outputpage .= "<b>Can't Launch Playlist</b><br>System error of some kind. Please retry. If this error continues please contact the admin</center></body></html>";
	}
	else {	
		fwrite($fp, trim($pidinfo) . "\n");
		fclose($fp);
		fwrite($fp2, $hostname . ":8000/" . $mountpoint . "\n");
		fclose($fp2);
		$outputpage .= "launched " . $mountpoint . "...<br><br><a href='". $hostname . "/m3u/" . $mountpoint . ".m3u'>" . $hostname . ":8000/" . $mountpoint . "</a><br /><br />you can copy this link to winamp or realplayer to listen.</center></body></html>";
	}
}

echo "<html>\n<head>\n<script language=\"JavaScript\">\n";
echo "var working = window.open(\"\", \"working\", \"width=250,height=150,toolbar=no,status=no,scrollbars=no,location=no,menubar=no,directories=no\");\n";
echo "working.document.open(\"text/html\");\n";
echo "working.document.write(\"" . $outputpage . "\");\n";
echo "document.location = \"" . $_POST["currlocation"] . "\";\n";
echo "</script>\n</head>\n</html>";



?>
