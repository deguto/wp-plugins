// CREDITS:
// Snowmaker
// By Peter Gehrig
// Copyright (c) 2003 Peter Gehrig. All rights reserved.
// Permission given to use the script provided that this notice remains as is.
// Additional scripts can be found at http://www.24fun.com
// info@24fun.com
// 11/27/2003

// IMPORTANT:
// If you add this script to a script-library or script-archive
// you have to add a highly visible link to
// http://www.24fun.com on the webpage
// where this script will be featured

///////////////////////////////////////////////////////////////////////////
// CONFIGURATION STARTS HERE
///////////////////////////////////////////////////////////////////////////

// Set the number of snowflakes (more than 30 - 40 not recommended)
var snowmax = 30;

// Set the colors for the snow. Add as many colors as you like
var snowcolor = new Array('#fff', '#fff', '#fff', '#fff');

// Set the fonts, that create the snowflakes. Add as many fonts as you like
var snowtype = new Array('Times');

// Set the letter that creates your snowflake (recommended: *)
var snowletter = '.';

// Set the speed of sinking (recommended values range from 0.3 to 2)
var sinkspeed = 0.2;

// Set the maximal-size of your snowflaxes
var snowmaxsize = 120;

// Set the minimal-size of your snowflaxes
var snowminsize = 20;

// Set the snowing-zone
// Set 1 for all-over-snowing
// Set 2 for left-side-snowing
// Set 3 for center-snowing
// Set 4 for right-side-snowing
var snowingzone = 1;

///////////////////////////////////////////////////////////////////////////
// CONFIGURATION ENDS HERE
///////////////////////////////////////////////////////////////////////////

// Do not edit below this line
var snow = new Array();
var marginbottom;
var marginright;
var timer;
var i_snow = 0;
var x_mv = new Array();
var crds = new Array();
var lftrght = new Array();
var browserinfos = navigator.userAgent;
var ie5 = document.all && document.getElementById && !browserinfos.match(/Opera/);
var ns6 = document.getElementById && !document.all;
var opera = browserinfos.match(/Opera/);
var browserok = ie5 || ns6 || opera;

function randommaker(range)
{
	return Math.floor(range * Math.random());
}

function initsnow()
{
  if (ie5 || opera)
  {
    marginbottom = document.body.clientHeight;
    if(window.document.body.scrollHeight){
	    marginbottom = window.document.body.scrollHeight;
    }
    marginright  = document.body.clientWidth;
  }else if (ns6) {
    marginbottom = window.innerHeight;
    if(document.body.offsetHeight){
	    marginbottom = document.body.scrollHeight;
    }
    marginright  = window.innerWidth;
  }

	var snowsizerange = snowmaxsize - snowminsize;

	for (var i = 0; i <= snowmax; i++)
	{
		crds[i] = 0;
		lftrght[i] = Math.random() * 15;
		x_mv[i] = 0.03 + Math.random() / 10;
		snow[i] = document.getElementById('s' + i);
		snow[i].style.fontFamily = snowtype[randommaker(snowtype.length)];
		snow[i].size = randommaker(snowsizerange) + snowminsize;
		snow[i].style.fontSize = snow[i].size;
		snow[i].style.color = snowcolor[randommaker(snowcolor.length)];
		snow[i].sink = sinkspeed * snow[i].size / 5;

		if (snowingzone == 1)
			snow[i].posx = randommaker(marginright - snow[i].size);

		if (snowingzone == 2)
			snow[i].posx = randommaker(marginright / 2 - snow[i].size);

		if (snowingzone == 3)
			snow[i].posx = randommaker(marginright / 2 - snow[i].size) + marginright / 4;

		if (snowingzone == 4)
			snow[i].posx = randommaker(marginright / 2 - snow[i].size) + marginright / 2;

		snow[i].posy = randommaker(2 * marginbottom - marginbottom - 2 * snow[i].size);
		snow[i].style.left = snow[i].posx;
		snow[i].style.top = snow[i].posy;
	}

	movesnow();
}

function movesnow()
{
	for (var i = 0; i <= snowmax; i++)
	{
		crds[i] += x_mv[i];
		snow[i].posy += snow[i].sink;
		snow[i].style.left = snow[i].posx + lftrght[i] * Math.sin(crds[i]);
		snow[i].style.top = snow[i].posy;

		if ((snow[i].posy >= (marginbottom - 2 * snow[i].size)) || (parseInt(snow[i].style.left) > (marginright - 3 * lftrght[i])))
		{
			if (snowingzone == 1)
				snow[i].posx = randommaker(marginright - snow[i].size);

			if (snowingzone == 2)
				snow[i].posx = randommaker(marginright / 2 - snow[i].size);

			if (snowingzone == 3)
				snow[i].posx = randommaker(marginright / 2 - snow[i].size) + marginright / 4;

			if (snowingzone == 4)
				snow[i].posx = randommaker(marginright / 2 - snow[i].size) + marginright / 2;

			snow[i].posy = 0;
		}
	}

	var timer = setTimeout('movesnow();', 20); //Wert von 10 bis 100
}

for (var i = 0; i <= snowmax; i++)
	document.write('<span id="s' + i +'" style="z-index: 999; position: absolute; top: -' + snowmaxsize + '">' + snowletter + '</span>');

if (browserok)
	window.onload = initsnow;
