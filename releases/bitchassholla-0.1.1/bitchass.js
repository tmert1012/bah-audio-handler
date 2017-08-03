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

songlist = new Array();

	//function to move among tabs
	function selectTab(n) {
		var panelID = "p1";
		var numDiv = 5;
			
		for (var i=0; i < numDiv; i++) {
			var tabDiv = document.getElementById(panelID+"tab"+i);
			var panelDiv = window.document.getElementById(panelID+"panel"+i);
			z = panelDiv.style.zIndex;
			if (z != numDiv && i == n) { z = numDiv; }
			else { z = (numDiv - i); }
			panelDiv.style.zIndex = z;
			tabDiv.style.zIndex = z;
		}
	}
	
	// basic replace string within string with another
	// code by: dudes name.
	function replace(string, text, by) {
		var strLength = string.length, txtLength = text.length;
		if ((strLength == 0) || (txtLength == 0)) { return string; }
		
		var i = string.indexOf(text);
		if ((!i) && (text != string.substring(0, txtLength))) { return string; }
		if (i == -1) { return string; }
		
		var newstr = string.substring(0,i) + by;
		
		if (i+txtLength < strLength)
			{ newstr += replace(string.substring(i+txtLength,strLength), text, by); }
			
		return newstr;
	}
	
	//add songs to javascript array (playlist), once complete blow them out to a file
	function addSong() {
		if (document.bwindow.sbox.value == "" || document.bwindow.sbox.value.indexOf(".mp3") == -1 ) { 
			document.getElementById("bmsgbox").innerHTML = "<b style=\"font:10pt; color:red;\">Error:</b> No song to add, pick one first."; 
			document.bwindow.sbox.value = "";
			return false; 
		}
		var end = songlist.length - 1;
		for (var i = 0; i <= end; i++) {
			if (songlist[i] == document.bwindow.sbox.value) {
				document.getElementById("bmsgbox").innerHTML = "<b style=\"font:10pt; color:red;\">Error:</b> Song allready in list, pick another.";
				document.bwindow.sbox.value = "";
				return false;
			}
		}
		songlist.push(document.bwindow.sbox.value);
		document.getElementById("bmsgbox").innerHTML =  "<b style=\"font:10pt; color:green;\">Added: </b>" + 
			document.bwindow.sbox.value.substring(document.bwindow.sbox.value.lastIndexOf("/")+1);
		document.bwindow.sbox.value = "";
		refreshPlaylist();
		return true;
	}

	// refreshes playlist window
	function refreshPlaylist() {
		document.getElementById("pwindowlist").innerHTML = "";
		var end = songlist.length - 1;
		for (var i = 0; i <= end; i++) {
			document.getElementById("pwindowlist").innerHTML += 
			"<a href=\"javascript:void(0);\" class=\"file\" onClick=\"document.pwindow.sbox.value = '" +  replace(songlist[i], "'", "\\'") + "'\">" +
			 songlist[i].substring(songlist[i].lastIndexOf("/")+1) + "</a><br>\n";
		}
	}
	
	function buildView() {
		if (!validateData(document.mwindow.sbox.value)) { 
			document.getElementById("mmsgbox").innerHTML = "<b style=\"font:10pt; color:red;\">Error:</b> No list to view, pick a list."; 
			document.mwindow.sbox.value = "";
			return false; 
		}
		else {
			var path = document.location.pathname.substring(0, document.location.pathname.lastIndexOf("/"));
			var viewURL = document.location.protocol + "//" + document.location.hostname + path + "/playlists/" + document.mwindow.sbox.value;
			window.open(viewURL, "", "width=640,height=480,toolbar=no,status=no,scrollbars=yes,location=no,menubar=no,directories=no");
			return true;
		}
	}
	
	function validateData(data) {
		if (data == "") { return false; }
		if (!/[^\w-]/.test(data)) { return true; }
		else { return false; }
	}
		
	function prepareToSave() {
		if (!validateData(document.pwindow.fname.value) || !validateData(document.pwindow.passwd.value)) {
			document.getElementById("pmsgbox").innerHTML =  "<b style=\"font:10pt; color:red;\">Error:</b> Bad filename or password.";
			document.pwindow.sbox.value = "";
			document.pwindow.passwd.value = "";
			document.pwindow.fname.value = "";
			return false;
		}
		if (songlist.length == 0) {
			document.getElementById("pmsgbox").innerHTML =  "<b style=\"font:10pt; color:red;\">Error:</b> No songs to save.";
			document.pwindow.sbox.value = "";
			document.pwindow.passwd.value = "";
			document.pwindow.fname.value = "";
			return false;
		}
		
		document.pwindow.outgoingsongs.value = "";
		for (var index = 0; index < songlist.length; index++) {
			document.pwindow.outgoingsongs.value += songlist[index] + "\n";	
		}
		document.pwindow.currlocation.value = document.location;
		return true;
	}
	
	function preLaunch() {
		if (!validateData(document.mwindow.passwd.value) || document.mwindow.sbox.value == "") {
			document.getElementById("mmsgbox").innerHTML =  "<b style=\"font:10pt; color:red;\">Error:</b> No list to launch, or bad password.";
			document.mwindow.passwd.value = "";
			document.mwindow.sbox.value = "";
			return false;
		}
		document.mwindow.currlocation.value = document.location;
		return true;
	}
	
	function preStop() {
		if (!validateData(document.npwindow.passwd.value) || document.npwindow.sbox.value == "") {
			document.getElementById("npmsgbox").innerHTML =  "<b style=\"font:10pt; color:red;\">Error:</b> No list to stop, or bad password.";
			document.npwindow.passwd.value = "";
			document.npwindow.sbox.value = "";
			return false;
		}
		document.npwindow.currlocation.value = document.location;
		return true;
	}
	
	function removeSong() {
		if (document.pwindow.sbox.value == "" || document.pwindow.sbox.value.indexOf(".mp3") == -1 ) {
			document.getElementById("pmsgbox").innerHTML =  "<b style=\"font:10pt; color:red;\">Error:</b> Bad song title, pick a song to remove.";
			return false;
		}
		var songToRemove = document.pwindow.sbox.value;
		var end = songlist.length - 1;
		var found = false;
		// find location of song
		for (var i = 0; i <= end; i++) {
			if (songlist[i] == songToRemove) { found = true; break; }
		}
		// end position
		if (found && i == end) {
			document.getElementById("pmsgbox").innerHTML =  "<b style=\"font:10pt; color:green;\">Removed:</b> " +  
				songToRemove.substring(songToRemove.lastIndexOf("/")+1);
			songlist.pop();
			refreshPlaylist();
			document.pwindow.sbox.value = "";
			return true;
		}
		// start position, or anywhere else
		else if (found) {
			for (i; i < end; i++) { songlist[i] = songlist[i+1]; }
			document.getElementById("pmsgbox").innerHTML =  "<b style=\"font:10pt; color:green;\">Removed:</b> " + 
				songToRemove.substring(songToRemove.lastIndexOf("/")+1);
			songlist.pop();
			refreshPlaylist();
			document.pwindow.sbox.value = "";
			return true;
		}
		else if (found == false) { 
			document.getElementById("pmsgbox").innerHTML =  "<b style=\"font:10pt; color:red;\">Error:</b> No song in list."; 
			return false;
		}
			
	}
	
	function buildListen() { 
		if (!validateData(document.npwindow.sbox.value)) {
			document.getElementById("npmsgbox").innerHTML = "<b style=\"font:10pt; color:red;\">Error:</b> Pick a song to listen too.";
			return false;
		}
		else {
			document.location = "m3u/" + document.npwindow.sbox.value + ".m3u";
			return true;
		}
	}


