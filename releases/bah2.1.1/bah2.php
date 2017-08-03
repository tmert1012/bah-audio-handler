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

//	bah2 main file - included by index.php
//	nathan garretson


// check if there is exisiting path information
if (isset($_SESSION["workingpath"])) { $stack = $_SESSION["workingpath"]; }
else { resetPath(); }

// check to see if exisiting song (playlist) information
if (isset($_SESSION["songs"])) { $songstack = $_SESSION["songs"]; }
else { 
	$songstack = loadList($_SESSION["user"] . ".plist"); 
	$_SESSION["songs"] = $songstack;
}

// add song
if (isset($_GET["addsong"])) {
	$song = urldecode($_GET["addsong"]);
	$song = str_replace("\'","'",$song);
	$path = buildPath() . $song;
	  if (validPath($path)) {
	    $songstack[] = $path;
	    $_SESSION["songs"] = $songstack;
	    writeFile($_SESSION["user"] . ".plist");
	  }
}

// remove song
if (isset($_GET["removesong"])) {
	if (is_numeric($_GET["removesong"])) {
	  $length = count($songstack);
	  if ($_GET["removesong"] > -1 and $_GET["removesong"] <= $length) {
		$array_start = array_slice($songstack, 0, $_GET["removesong"]);
		$array_end = array_slice($songstack, $_GET["removesong"] + 1);
		$songstack = array_merge($array_start, $array_end);
		$_SESSION["songs"] = $songstack;
		writeFile($_SESSION["user"] . ".plist");
	  }
	}
}

// BROWSE
// resets and opens root dir
if (isset($_GET["rootdir"])) {
	resetPath();
}

// opens a new dir	
else if (isset($_GET["opendir"])) {
	$dir = urldecode($_GET["opendir"]);
	$dir = str_replace("\'","'",$dir);
	$path = buildPath();
	if (validPath($path . $dir)) {
	  $stack[] = $dir . "/";
	  $_SESSION["workingpath"] = $stack;
	}
}

// goes back one dir
else if (isset($_GET["backdir"])) {
	array_pop($stack);
	$_SESSION["workingpath"] = $stack;
}


//  build path from session variable path
function buildPath() {
	$stack = array();
	if (isset($_SESSION["workingpath"])) { $stack = $_SESSION["workingpath"];}
	// build path into temp var
	$temp = "";
	foreach ($stack as $currdir) {
	  $temp = $temp . $currdir;
	}
	return (MP3DIR . "/" . $temp); 
}

// check to see if path is valid
function validPath($path) {
	if (ereg("\.\.", $path, $trash)) { return false; }
	if (is_dir($path)) { return true; }
	if (is_file($path)) { return true; }
	return false;
}

// reset session variable path
function resetPath() {
	$stack = array();
	$_SESSION["workingpath"] = $stack;
}

// reset session variable song list
function emptyList() {
	$songstack = array();
	$_SESSION["songs"] = $songstack;
}

// fill the browse windows with songs and directories
function fillBrowseWindow() {
	$indir = buildPath();
	echo "<a href=\"?rootdir=true\"><img src=\"icon/fld.gif\">&nbsp;/</a><br />\n";
	echo "<a href=\"?backdir=true\"><img src=\"icon/fld.gif\">&nbsp;..</a><br />\n";
	if (is_dir($indir)) {
	  $wdir = opendir($indir);
	  while ($file = readdir($wdir)) {
	    if (is_dir("$indir" . "/" . "$file")) {
	      if ($file != "." && $file != "..") {
		$tempfile = urlencode($file);
	  	echo "<a href=\"?opendir=$tempfile\"><img src=\"icon/fld.gif\" />&nbsp;$file</a><br />\n";
	      }
	    }
	    else { 
	      $tempfile = urlencode($file);
	      echo "<a href=\"?addsong=$tempfile\"><img src=\"icon/sound2.gif\" />&nbsp;$file</a><br />\n"; 
	    }
	  }
	closedir($wdir);
	}
}

// fill playlist window
function displayPlaylist() {
	$stack = array();
	if (isset($_SESSION["songs"])) { $stack = $_SESSION["songs"];}
	foreach ($stack as $index => $currsong) {
	  $currsong = substr($currsong, (strrpos($currsong, "/") + 1));
	  echo "<a href=\"?removesong=$index\"><img src=\"icon/sound2.gif\" />&nbsp;$currsong</a><br />\n"; 
	}
}

// write session variable songs to file
function writeFile($listname) {
	if (!$file = fopen(WEBDIR . "/playlists/" . $listname, "w")) {
		// generate error message
		exit;
	}
	if (isset($_SESSION["songs"])) { $stack = $_SESSION["songs"];}
	foreach ($stack as $currsong) {
	  if (!fwrite($file, $currsong . "\n")) { fclose($file); exit; }
	}
	fclose($file);
}	

// write M3U file
function writeM3U() {
	if (!$file = fopen(WEBDIR . "/m3u/" . $_SESSION["user"] . ".m3u", "w")) {
		// generate error message
		exit;
	}
	if (defined('EXT_IP')) {
		if (!fwrite($file, "http://" . EXT_IP . ":" . PORT . "/" . $_SESSION["user"] . "\n")) { fclose($file); exit; }
		fclose($file);
	}
	else {
		if (!fwrite($file, "http://" . IP . ":" . PORT . "/" . $_SESSION["user"] . "\n")) { fclose($file); exit; }
		fclose($file);
	}
}

// loads user playlist from disk
function loadList($listname) {
	if (validPath(WEBDIR . "/playlists/" . $listname)) {
		$stack = file(WEBDIR . "/playlists/" . $listname);
		foreach ($stack as $key=>$value) { $stack[$key]=trim($value); }
		return $stack;
	}
}

// starts user stream
function launchStream() {
	$playlist = WEBDIR . "/playlists/" . $_SESSION["user"] . ".plist";
	$icescmd =  ICESCMD . " -B -F " . $playlist . " -P " . ICECAST_PASSWD . " -m /" . $_SESSION["user"] . " -t http -h " . IP . " -p " . PORT . " -b " . BITRATE . " -R " . "-n 'bah2 - " . $_SESSION["user"] . "'" ;
	$pid = exec($icescmd);
	errorLog("launchStream()", $pid);
}

// basic write to error log function
function errorLog($function, $msg) {
	$file = fopen(LOGDIR . "/bah2error.log", "a");
	fwrite($file, $function . " :: " . $msg . "\n");
	fclose($file);
}
		
?>
