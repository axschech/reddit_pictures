<html>
<!--
Title:Pictures from Reddit, a New Tab Page
Author: Alex Schechter
Purpose: Developing an HTML5 / Javascript web application using random images from sub reddits that provide good wallpaper images
Notes:
 Idea was taken from the following android app: http://goo.gl/gmrXOD
 Programmed for Google Chrome. please use it for the best experience:
 	http://google.com/chrome
 Music Credits:
 	8tracks! | http://8tracks.com | http://8tracks.com/developers/

 reddit: http://reddit.com
 imgur: http://imgur.com

 source: http://github.com/axschech/reddit_pictures

-->
<head>
<title>Please wait</title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js"></script>
<script src="js/cast_support.js"></script>

<script type="text/javascript">



	var repeater;
	var previous_subreddit='';
	var pass;


	function setAudio(nope)
	{
		var song='';
		if(nope!==true)
		{
			var choices = ['classical','trance','jazz'];
			var dat = Math.floor((Math.random()*choices.length)+0);
			var choice = choices[dat];
			var id;
			var track_id;
			var link = 'http://8tracks.com/mix_sets/tags:'+choice+':all:popular?format=json&api_key=ecd347469947fd49f56fa0f6adffdf0e78880776&per_page=100';
			console.log(link);
			var token;
			var new_link;

			// $.ajax({
			// 			url:curl http://8tracks.com/sets/new.json
			$.ajax({
				url: 'http://8tracks.com/sets/new.json&api_key=ecd347469947fd49f56fa0f6adffdf0e78880776',
				success: function(data)
				{
					token = data.play_token;
		
				}
			}).done(function()
			{
				$.ajax({
						url: link,
						success: function (data) {
							
							var length = data.mixes.length;
							var num = Math.floor((Math.random()*length)+0);
							id = data.mixes[num].id;
							new_link = 'http://8tracks.com/sets/'+token+'/play.json?mix_id='+id+'&api_key=ecd347469947fd49f56fa0f6adffdf0e78880776';
							
						}
					}).done(function(){
						$.ajax({
							url:new_link,
							success: function(data) {
								song = data.set.track.url;
								track_id = data.set.track.id;
								var source = '<audio autoplay id="mus">';
							    //source +=  '<source id="audio_player_ogv" src="' + new_audio + '.ogv"  type="audio/ogg" />';
							    source +=  '<source id="mu" src="'+song+'"  type="audio/mpeg" />';
							    source +=  '</audio>';
							    $('#music').html(source);

							    $('#mus').bind('timeupdate', function(){
							    	console.log(this.currentTime);
							    	if(this.currentTime>30)
							    	{
							    		var report = 'http://8tracks.com/sets/111696185/report.json?track_id='+track_id+'&mix_id='+id+'&api_key=ecd347469947fd49f56fa0f6adffdf0e78880776';
							    		$.ajax({
							    			url:report,
							    			success : function (data) {


							    				console.log(data);
							    				$('#mus').unbind('timeupdate');
							    			}
							    		});
							    	}
							    });

							    $('#mus').bind('ended', function(){
							    // done playing
							     setAudio();
							    });
							},
							error: function() {
								//console.log(new_link);
							}	

						});
					});			
			});
		
		
		}
		else
		{
			var source = '<audio autoplay id="mus">';
		    //source +=  '<source id="audio_player_ogv" src="' + new_audio + '.ogv"  type="audio/ogg" />';
		    source +=  '<source id="mu" src=""  type="audio/mpeg" />';
		    source +=  '</audio>';
		    $('#music').html(source);
		}

			
	}				
					

	$(document).ready(function()
	{
		

		$(document).keyup(function(e)
		{
			if(e.keyCode==32)
			{
				var music = $('#mus').prop('paused');
				var m = document.getElementById('mus');
				console.log(music);
				console.log(typeof music);
				if(music == false)
				{
					m.pause();
				}
				else
				{
					m.play();
				}
				//console.log('got it!');
			}
			else if(e.keyCode==78)
			{
				setAudio();
			}
		});

		
		$('#myModal').modal();
		$('#mkay').click(function() {
			console.log('button clicked');
			var check = $('#storage').prop('checked');
			if(check)
			{
				localStorage.clear();
				console.log('cleared storage');
			}
			else
			{
				console.log('did not clear storage');
			}

			var play = $('#autoplay').prop('checked');

			if(play)
			{
				setAudio();
	        	doWork();
			}
			else
			{
				setAudio(nope=true);
		    	$('#icon').attr('src','images/mute.png');
		    	doWork();
			}
			$('#myModal').modal('hide');
		});

		// $('div#dialog-confirm').bind('dialogclose', function(event) {
		// 	var check = $('#storage').prop('checked');
		// 	if(check)
		// 	{
		// 		localStorage.clear();
		// 		console.log('cleared storage');
		// 	}
		// 	else
		// 	{
		// 		console.log('did not clear storage');
		// 	}
			
		// });
	    $('#img_click').click(function(){
	    	if($('#icon').attr('src') == 'images/mute.png')
	    	{
	    		console.log('here');
	    		$('#icon').attr('src','images/on.png');
	    		setAudio();
	    	}
	    	else if($('#icon').attr('src') == 'images/on.png')
	    	{
	    		$('#mus').prop('muted',true);
	    		$('#icon').attr('src','images/off.png');
	    	}
	    	else if($('#icon').attr('src') == 'images/off.png')
	    	{
	    		
	    		$('#icon').attr('src','images/on.png');
	    		$('#mus').prop('muted',false);
	    	}
	    	
	    	console.log($('#icon').attr('src'));
	    });


		$('#fullscreen').hide();
		$('#hover').mouseover(function()
		{
			$('#fullscreen').show();
		});
		$('#hover').mouseout(function()
		{
			$('#fullscreen').hide();
		});
		$('#fullscreen').click(function()
		{
			if($(this).html() == 'Go fullscreen?')
			{
				document.documentElement.webkitRequestFullscreen();
				$(this).html('Cancel fullscreen?');
			}
			else
			{
				document.webkitCancelFullScreen();
				$(this).html('Go fullscreen?');
			}
		});
		
 
	});

function reddit_test()
{

		var subreddits = 
		[
			'earthporn',
			'spaceporn',
			'skyporn',
			'winterporn',
			'weatherporn',
			'infrastructureporn',
			'bigwallpapers',
			'AerialPorn',
			'AgriculturePorn',
			'FractalPorn',
			'FoodPorn',
			'CityPorn',
			'AdrenalinePorn',
			'ArchitecturePorn',
			'AutumnPorn',
			'avporn',
			'boatporn',
			'BonsaiPorn',
			'BotanicalPorn',
			'carporn',
			'churchporn',
			'CityPorn',
			'ClimbingPorn',
			'ComicBookPorn',
			'ConcertPorn',
			'CulinaryPorn',
			'desertporn',
			'DesignPorn',
			'DessertPorn',
			'DestructionPorn',
			'EarthPorn',
			'F1Porn',
			'FirePorn',
			'futureporn',
			'GamerPorn',
			'GeekPorn',
			'geologyporn',
			'HistoryPorn',
			'Houseporn',
			'InfrastructurePorn',
			'InstrumentPorn',
			'MachinePorn',
			'MotorcyclePorn',
			'MoviePosterPorn',
			'RidesPorn',
			'RoomPorn',
			'ruralporn',
			'seaporn',
			'SkyPorn',
			'stadiumporn',
			'StreetArtPorn',
			'waterporn',
			'WeatherPorn',
			'winterporn'
		]

		var r_count = subreddits.length;
		var z = Math.floor((Math.random()*r_count)+0);
		var subreddit = subreddits[z];
		var prepend = 'i.';

		$.ajax({
		url: 'http://www.reddit.com/r/'+subreddit+'/hot.json?limit=100',
		success: function (data)
		{
			$('#text').fadeOut();

			var Data = data.data.children;
			var x = Data.length;
			var y = Math.floor((Math.random()*x)+0);
			var img = Data[y].data.url;
			var title = Data[y].data.title;
			var returnedImg;
			console.log(img);
			returnedImg = getImage(img);
			console.log(returnedImg);
			setImage(returnedImg,subreddit,title);
			
		}

	});
}


function getImage(img)
{
	var newImg;
	if(img.indexOf("imgur") != -1 && img.indexOf("gallery") <= 1)
	{
		if(img.indexOf('i.imgur') <= 1)
		{
			var http = 'http://';
			var split = img.split(http);
			console.log(split);
			newImg = http + 'i.' + split[1] + '.jpg';
			return newImg;
		}
		else if(img.indexOf('.jpg') != -1)
		{
			return img;
		}
		else
		{
			reddit_test();
		}
	}
	else if(img.indexOf('.jpg') != -1)
	{
		return img;
	}
	else
	{
		reddit_test();
	}
}

function setImage(img,sub,t)
{
	var str;

	var imgObj = new Image();
	imgObj.onload = function() {
		var w = this.width;
		var h = this.height;
		console.log(h);
		if(h>=1920 && w>=1080 && img != 'undefined' && localStorage[img]==undefined)
		{
			$('#wait').hide();
			$('body').css('background-image', 'url('+img+')')
			localStorage[img] = true;
			document.title = sub;
			$('#text').html(t);
			$('#text').fadeIn('slow');
			setTimeout(function() { $('#text').fadeOut('slow'); }, 10000);
		}
		else
		{
			reddit_test();
		}
	}
	console.log(img);
	if(img != 'undefined' && typeof img !== undefined && img != 'http://axschech.info/undefined' && img !== undefined)
	{
		imgObj.src=img;
	}
	
	//console.log(img);
	//$('#dat_img').attr('src',img);
	
	
}

function getSize(img)
{
	var str;
	var imgObj = new Image();
	imgObj.onload = function() {
		str = this.width + 'x' + this.height;
		return str;
	}
	console.log(img);
	imgObj.src=img;
}

function doWork() 
{

 reddit_test();
 repeater = setTimeout(doWork, 60000);
}

</script>
<style type="text/css">
body
{
	background-size:1920px 1080px;
}

#icon
{
	position: relative;
	width:20px;
	height:20px;
	margin:5px;
}

.img_click
{
	padding:5px;
	position:absolute;
	top:10px;
	right:10px;
	width:30px;
	height:30px;
	background-color: white;
}

.ui-dialog-titlebar-close 
{
  visibility: hidden;
}
</style>
</head>
<body>
<div id="hover" style="position:absolute;top:0;left:0;width:200px;height:200px;"><button id="fullscreen" class="btn btn-lg btn-primary" style="margin:15px">Go fullscreen?</button></div>
<img id="dat_img" />
<div id="wait" style="text-align:center"><img src="http://pimphop.com/wp-content/uploads/please-wait-animated-white.gif" /></div>
<div id="text" style="z-index:100; background-color:white; text-align:center; font-size:20px; width:300px; margin:10px auto auto auto;"></div>
<div id="music"></div>
<div class="img_click"><img id="icon" src="images/on.png"></div>


<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
	  	<div class="modal-header">
	    	<h4 class="modal-title"><div style="width:80%";>Hold on there, Jethro! <span style="float:right;">Keyboard Shortcuts:</span></div> </h4>
	  	</div>
		<div class="modal-body">
		<div class="container" style="padding:0px">
		<div class="col-md-3" style="padding:0px">
	     <p>This page runs best in <a href="http://google.com/chrome" target="_blank">Google Chrome</a></p>
		  <br />
			  <p>This website features <b>autoplay</b>, with music provided by <a href="http://8tracks.com" target="_blank">8tracks</a></p> 
			  <p> Select <b>cancel</b> to <b>prevent</b> sound from playing. Press okay to hear music!</p>

			  <p>Also, move your mouse to the top left corner of the window to use "fullscreen mode"</p>
			 <input id="autoplay" type="checkbox"> Allow autoplay?</input> <b>(recommended)</b>
			 <br />
			 <div title="This site uses LocalStorage to keep track of pictures that have been shown">
		  		<input id="storage" type="checkbox"> Clear <a href="http://www.html5rocks.com/en/features/storage" target="_blank"> <u>Local Storage?</u> </a>
	 		 </div>
	 	</div>
	 	<div class="col-md-3" style="padding:0">
	 		Space: Pause <br /><br />
	 		N: Next	<br /><br />
	 		More coming soon! <br /><br />
	 	</div>
	 	</div>
		</div>
		<div class="modal-footer">
			<button id="mkay" type="button" class="btn btn-primary">Okay</button> 
		</div>
  </div>
</div>


<!-- <div id="dialog-confirm" title="Hold on there Jethro!">
  <p>This page runs best in <a href="http://google.com/chrome" target="_blank">Google Chrome</a></p>
  <br />
  <p>This website features <b>autoplay</b>. </p> 
  <p> Select <b>cancel</b> to <b>prevent</b> sound from playing. Press okay to hear music!</p>

  <p>Also, move your mouse to the top left corner of the window to use "fullscreen mode"</p>
 <div title="This site uses LocalStorage to keep track of pictures that have been shown"> <input id="storage" type="checkbox"><a href="http://www.html5rocks.com/en/features/storage" target="_blank"> Clear <u>Local Storage?</u> </a></div>
</div> -->
 
</body>
</html>
