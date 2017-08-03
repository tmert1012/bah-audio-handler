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
		
	function displayfiles($indir) {
		if (is_dir($indir)) {
			$wdir = opendir($indir);
			while ($file = readdir($wdir)) {
				if (is_dir("$indir/$file")) {
					if ($file != "." && $file != "..") {
						$tempfile = str_replace("'","\'","$indir/$file");
						echo "<a href=\"javascript:void(0);\" class=\"dir\">$file</a><br />\n";
						displayfiles("$indir/$file");
					}
				}
				else { 
					$tempfile = str_replace("'","\'","$indir/$file");
					echo "&nbsp;&nbsp;<a href=\"javascript:void(0);\" class=\"file\" onclick=\"document.bwindow.sbox.value='$tempfile'\">$file</a><br />\n"; 
					$GLOBALS["$songtotal"]++;
				}
			}
			closedir($wdir);
		}
	}

	function displayLists() {
		$wdir = opendir(WEBDIR . "/playlists/");
			while ($file = readdir($wdir)) {
				if ($file != "." && $file != "..")
					echo "&nbsp;&nbsp;<a href=\"javascript:void(0);\" class=\"file\" onclick=\"document.mwindow.sbox.value='$file'\">$file</a><br />\n";
			}
		closedir($wdir);
	}
	
	function displayRunning() {
		$wdir = opendir(BASEDIR . "/pids/");
			while ($file = readdir($wdir)) {
				if ($file != "." && $file != "..") {
					$fcontents = file(BASEDIR . "/pids/" . $file);
					if (trim($fcontents[1]) != "")
						echo "&nbsp;&nbsp;<a href=\"javascript:void(0);\" class=\"file\" onclick=\"document.npwindow.sbox.value='$file'\">$file</a><br />\n";
				}
			}
		closedir($wdir);
	}
	
?>
