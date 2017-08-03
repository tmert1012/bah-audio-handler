<!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/transitional.dtd">	
<html xmlns="http://www/w3/org/TR/xhtml1">

<head>
	<title> - bitchassholla! - </title>
	<script language="JavaScript" type="text/javascript" src="bitchass.js"></script>
	<link rel="stylesheet" type="text/css" href="bitchass.css" />
	<?php include("bitchass.php"); 
	
	?>
<!--
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
-->
</head>

		
<body>

<!-- tab layout -->
<div style="background-color:transparent; position:relative; width:600px; height:460px">


<!-- about panel -->
<div class="panel" style="z-index:4" id="p1panel0">
	<div id="top" style="width:585px; height:60px; top:10px; left:5px; position:absolute;">
		<br />
		<b style="font-size:16pt;"><?php echo SERVERNAME; ?></b><br />
		because radio has always sucked, until now.
		<br />
		<br />
		<br />
		How do I use this thing?<br />
		<a href="http://www.bitchassholla.com/help.shtml" class="link">http://www.bitchassholla.com/help.shtml</a>
		<br />
		<br />
		best viewed in 1024x768 or better.
		<div id="footer" style="width:585px; height:60px; left:5px; top:330px; position:absolute; text-align:center; font-size:10pt;">
		- Copyright © 2002 Nathan Garretson - <br />
		All rights Reserved.<br />
		bitchassholla (BAH for short) is distributed under the terms of the GNU General Public License<br />
		</div>
	</div>
</div>
<!-- about tab -->
<div class="tab" style="height:30px; left:0px; top:20px; z-index:4" id="p1tab0">
	<a href="javascript:void(0);" onClick="selectTab(0)" class="link">about</a>
</div>





<!-- browse panel -->
<div class="panel" style="z-index:3" id="p1panel1">
	<div id="bmsgbox" style="width:580px; left:10px; top:15px; position:absolute; background-color:transparent; border:none; font-size:10pt">
		<b style="font-size:10pt; color:green;">Browse:</b> Pick songs then add them to your list.
	</div>
	
	<div id="bwindowlist" class="window">
			<?php 
				displayfiles(MP3DIR);
			?>
	</div>
	
	<form name="bwindow" action="javascript:void(0);">
		<input type="submit" value="add" style="width:60px; height:28px; left:520px; top:160px; position:absolute;" onClick="addSong()" />
		<input type="text" name="sbox" readonly="yes" class="sbox" />
		<input type="hidden" name="currlocation" />
	</form>
	
</div>
<!-- browse tab -->
<div class="tab" style="height:30px; left:100px; top:20px; z-index:3" id="p1tab1">
	<a href="javascript:void(0);" onClick="selectTab(1)" class="link">browse</a>
</div>






<!-- playlist panel -->
<div class="panel" style="z-index:2" id="p1panel2">
	<div id="pmsgbox" style="width:580px; left:10px; top:15px; position:absolute; background-color:transparent; border:none; font-size:10pt">
		<b style="font-size:10pt; color:green;">My List:</b> Here is your playlist, you can save it or remove songs.
	</div>
	<div id="pwindowlist" class="window">
	</div>
	<div id="title1" style="width:60px; left:510px; top:60px; position:absolute;">Filename:</div>
	<div id="title2" style="width:60px; left:510px; top:108px; position:absolute;">Password:</div>
	<form name="pwindow" method="post" action="saveList.php" onSubmit="return prepareToSave();">
		<input type="text" name="fname" style="width:80px; left:510px; top:80px; position:absolute;" />
		<input type="password" name="passwd" style="width:80px; left:510px; top:128px; position:absolute;" />
		<input type="submit" value="save" style="width:60px; height:28px; left:510px; top:166px; position:absolute;" />
		<input type="button" value="remove" style="width:60px; height:28px; left:510px; top:212px; position:absolute;" onclick="return removeSong();" />
		<input type="text" name="sbox" readonly="yes" class="sbox" />
		<input type="hidden" name="outgoingsongs" />
		<input type="hidden" name="currlocation" />
	</form>
	
</div>
<!-- playlist tab -->
<div class="tab" style="height:30px; left:200px; top:20px; z-index:2" id="p1tab2">
	<a href="javascript:void(0);" onClick="selectTab(2)" class="link">my list</a>
</div>






<!-- all lists panel -->
<div class="panel" style="z-index:1" id="p1panel3">
	<div id="mmsgbox" style="width:580px; left:10px; top:15px; position:absolute; background-color:transparent; border:none; font-size:10pt">
		<b style="font-size:10pt; color:green;">All Lists:</b> All the saved playlists. Here you can stop or start streams.
	</div>
	<div id="mwindowlist" class="window">
		<?php displayLists(); ?>
	</div>
	<div id="title2" style="width:60px; left:510px; top:60px; position:absolute;">Password:</div>
	<form name="mwindow" method="post" action="launchStream.php" onSubmit="return preLaunch();">
		<input type="submit" value="launch" style="width:60px; height:28px; left:510px; top:119px; position:absolute" />
		<input type="button" value="view" style="width:60px; height:28px; left:510px; top:163px; position:absolute;" onclick="buildView();" />
		<input type="password" name="passwd" style="width:80px; left:510px; top:80px; position:absolute;" />
		<input type="text" name="sbox" readonly="yes" class="sbox" />
		<input type="hidden" name="currlocation" />
	</form>
</div>
<!-- all lists tab -->
<div class="tab" style="height:30px; left:300px; top:20px; z-index:1" id="p1tab3">
	<a href="javascript:void(0);" onClick="selectTab(3)" class="link">all lists</a>
</div>





<!-- now playing panel -->
<div class="panel" style="z-index:0" id="p1panel4">
	<div id="npmsgbox" style="width:580px; left:10px; top:15px; position:absolute; background-color:transparent; border:none; font-size:10pt">
		<b style="font-size:10pt; color:green;">Now Playing:</b> All the lists now playing, here you can stop streams.
	</div>
	<div id="npwindowlist" class="window">
		<?php displayRunning(); ?>
	</div>
	<div id="title2"style="width:60px; left:510px; top:60px; position:absolute;">Password:</div>
	<form name="npwindow" method="post" action="stopStream.php" onSubmit="return preStop();">
		<input type="submit" value="stop" style="width:60px; height:28px; left:510px; top:119px; position:absolute" />
		<input type="button" value="listen" style="width:60px; height:28px; left:510px; top:163px; position:absolute;" onclick="buildListen();" />
		<input type="password" name="passwd" style="width:80px; left:510px; top:80px; position:absolute;" />
		<input type="text" name="sbox" readonly="yes" class="sbox" />
		<input type="hidden" name="currlocation" />
	</form>
</div>
<!-- now playing tab -->
<div class="tab" style="height:30px; left:400px; top:20px; z-index:0" id="p1tab4">
	<a href="javascript:void(0);" onClick="selectTab(4)" class="link">now playing</a>
</div>





</div>
</body>
</html>
