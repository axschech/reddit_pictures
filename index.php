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
 	Feels So Good, Chuck Mangione
 	Under Pressure, Queen / David Bowie
 	More Than a Feeling, Boston
 	Straus Auto, Awful Waffle
 	(Please don't sue me)
 reddit.com

-->
<head>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript">
	
	var repeater;
	var previous_subreddit='';
	var pass;
	
	$(document).ready(function()
	{

		var songs = ['under_pressure','feels_so_good','more_than_feeling'];
		var length = songs.length;
		var song = 'audio/'+songs[Math.floor((Math.random()*length)+0)];
		var source = '<audio autoplay id="mus">';
	    //source +=  '<source id="audio_player_ogv" src="' + new_audio + '.ogv"  type="audio/ogg" />';
	    source +=  '<source id="mu" src="' + song + '.mp3"  type="audio/mpeg" />';
	    source +=  '</audio>';
	    $('#music').html(source);


	    $('#img_click').click(function(){
	    	if($('#icon').attr('src') == 'images/on.png')
	    	{
	    		$('#mus').prop('muted',true);
	    		$('#icon').attr('src','images/off.png');
	    	}
	    	else
	    	{
	    		$('#mus').prop('muted',false);
	    		$('#icon').attr('src','images/on.png');
	    	}
	    	
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
		doWork();
 
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
			'AdrenalinePorn'
		]

		var r_count = subreddits.length;
		var z = Math.floor((Math.random()*r_count)+0);
		var subreddit = subreddits[z];
		var prepend = 'i.';

		$.ajax({
		url: 'http://www.reddit.com/r/'+subreddit+'/hot.json',
		success: function (data)
		{

			var Data = data.data.children;
			var x = Data.length;
			var y = Math.floor((Math.random()*x)+0);
			var img = Data[y].data.url;
			var returnedImg;
			console.log(img);
			returnedImg = getImage(img);
			console.log(returnedImg);
			setImage(returnedImg,subreddit);
			
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

function setImage(img,sub)
{
	var str;

	var imgObj = new Image();
	imgObj.onload = function() {
		var w = this.width;
		var h = this.height;
		console.log(h);
		if(h>=1920 && img != 'undefined' && localStorage[img]==undefined)
		{
			$('#wait').hide();
			$('body').css('background-image', 'url('+img+')')
			localStorage[img] = true;
			document.title = sub;
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
 repeater = setTimeout(doWork, 30000);
}

</script>
<style type="text/css">
body
{
	background-size:1920px 1080px;
}

#icon
{
	position:absolute;
	top:10px;
	right:10px;
	width:20px;
	height:20px;
	background-color: white;
}
</style>
</head>
<body>
<div id="hover" style="position:absolute;top:0;left:0;width:200px;height:200px;"><button id="fullscreen">Go fullscreen?</button></div>
<img id="dat_img" />
<div id="wait" style="text-align:center"><img src="http://pimphop.com/wp-content/uploads/please-wait-animated-white.gif" /></div>
<div id="music"></div>
<div id="img_click"><img id="icon" src="images/on.png"></div>
</body>
</html>
