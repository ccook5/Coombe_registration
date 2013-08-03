<?php  
/*===========================================================================*/  
/*      PHP Barcode Image Generator v1.0 [9/28/2000]  
        Copyright (C)2000 by Charles J. Scheffold -cs@sid6581.net  

		---
		UPDATE 5/10/2005 by C.Scheffold  
		Changed FontHeight to -2 if no text is to be displayed (this eliminates  
		the whitespace at the bottom of the image)  
		---  
		UPDATE 03/12/2005 by C.Scheffold  
		Added '-' character to translation table  
        ---  
        UPDATE 09/21/2002 by Laurent NAVARRO -ln@altidev.com - http://www.altidev.com  
        Updated to be compatible with register_globals = off and on  
        ---  
        UPDATE 4/6/2001 - Important Note! This script was written with the assumption  
        that "register_globals = On" is defined in your PHP.INI file! It will not   
        work as-is      and as described unless this is set. My PHP came with this   
        enabled by default, but apparently many people have turned it off. Either   
        turn it on or modify the startup code to pull the CGI variables in the old   
        fashioned way (from the HTTP* arrays). If you just want to use the functions   
        and pass the variables yourself, well then go on with your bad self.  
        ---  
          
        This code is hereby released into the public domain.  
        Use it, abuse it, just don't get caught using it for something stupid.  
  
  
        The only barcode type currently supported is Code 3 of 9. Don't ask about   
        adding support for others! This is a script I wrote for my own use. I do   
        plan to add more types as time permits but currently I only require   
        Code 3 of 9 for my purposes. Just about every scanner on the market today  
        can read it.  
  
  
        PARAMETERS:  
        -----------  
        $barcode        = [required] The barcode you want to generate  
  
  
        $type           = (default=0) It's 0 for Code 3 of 9 (the only one supported)  
          
        $width          = (default=160) Width of image in pixels. The image MUST be wide  
                                  enough to handle the length of the given value. The default  
                                  value will probably be able to display about 6 digits. If you  
                                  get an error message, make it wider!  
  
  
        $height         = (default=80) Height of image in pixels  
          
        $format         = (default=jpeg) Can be "jpeg", "png", or "gif"  
          
        $quality        = (default=100) For JPEG only: ranges from 0-100  
  
  
        $text           = (default=1) 0 to disable text below barcode, >=1 to enable  
  
  
        NOTE: You must have GD-1.8 or higher compiled into PHP  
        in order to use PNG and JPEG. GIF images only work with  
        GD-1.5 and lower. (http://www.boutell.com)  
  
        ANOTHER NOTE: If you actually intend to print the barcodes   
        and scan them with a scanner, I highly recommend choosing   
        JPEG with a quality of 100. Most browsers can't seem to print   
        a PNG without mangling it beyond recognition.   
  
  
        USAGE EXAMPLES FOR ANY PLAIN OLD HTML DOCUMENT:  
        -----------------------------------------------  
  
        <IMG SRC="barcode.php?barcode=HELLO&quality=75">  
        <IMG SRC="barcode.php?barcode=123456&width=320&height=200">  
                  
*/  
/*=============================================================================*/  
  
  
//-----------------------------------------------------------------------------  
// Startup code  
//-----------------------------------------------------------------------------  
  
if(isset($_GET["text"]))    $text    = $_GET["text"];  
if(isset($_GET["format"]))  $format  = $_GET["format"];  
if(isset($_GET["quality"])) $quality = $_GET["quality"];  
if(isset($_GET["width"]))   $width   = $_GET["width"];  
if(isset($_GET["height"]))  $height  = $_GET["height"];  
if(isset($_GET["type"]))    $type    = $_GET["type"];  
if(isset($_GET["barcode"])) $barcode = $_GET["barcode"];  
  
if (!isset ($text))   $text    =   1;
if (!isset ($type))   $type    =   1;
if (empty ($quality)) $quality = 100;
if (empty ($width))   $width   = 160;
if (empty ($height))  $height  =  80;
 
if (!empty ($format)) {
	$format = strtoupper ($format);  
} else {
	$format = "JPEG";  
}

Barcode39 ($barcode, $width, $height, $quality, $format, $text);  

//-----------------------------------------------------------------------------  
// Generate a Code 3 of 9 barcode  
//-----------------------------------------------------------------------------  
function Barcode39 ($barcode, $width, $height, $quality, $format, $text)
{
	switch ($format)
	{
		default:
			$format = "JPEG";
			break;
		case "JPEG":
			header ("Content-type: image/jpeg");
			break;  
		case "PNG":
			header ("Content-type: image/png");
			break;  
		case "GIF":  
			header ("Content-type: image/gif");  
			break;  
	}

	$im = ImageCreate ($width, $height)  
		or die ("Cannot Initialize new GD image stream");

	$White = ImageColorAllocate ($im, 255, 255, 255);  
	$Black = ImageColorAllocate ($im, 0, 0, 0);  
	$Red   = ImageColorAllocate ($im, 255, 0, 0);
	//ImageColorTransparent ($im, $White);  
	ImageInterLace ($im, 1);

	$NarrowBar = 1;
	$WideBar   = 3;
	$QuietBar  = 1;

	$font_id     = 1;
	$font_height = imagefontheight($font_id); 

	if (($NarrowBar == 0) || ($NarrowBar == $WideBar) || ($WideBar == 0) || ($QuietBar == 0))
	{
		ImageString ($im, $font_id, 0, 0, "Image is", $Black);
		ImageString ($im, $font_id, 0, $font_height, "too small!", $Black);
		OutputImage ($im, $format, $quality);  
		exit;
	}

	$CurrentBarX = 10;
	$BarcodeFull = "*".strtoupper ($barcode)."*";  
	settype ($BarcodeFull, "string");

//	ImageString($im, $font_id, 0,$height-$font_height, $barcode, $Black);

	for ($i=0; $i<strlen($BarcodeFull); $i++)  
	{
		$StripeCode = Code39 ($BarcodeFull[$i]);  

		for ($n=0; $n < 10; $n++)  
		{
			switch ($StripeCode[$n])
			{  
				case 'w':
					ImageFilledRectangle($im, 0, $CurrentBarX, $width, $CurrentBarX+$NarrowBar,  $White);
					$CurrentBarX += $NarrowBar;
					break;  

				case 'W':
					ImageFilledRectangle($im, 0, $CurrentBarX, $width, $CurrentBarX+$WideBar,  $White);
					$CurrentBarX += $WideBar;
					break;

				case 'b':
					ImageFilledRectangle($im, 0, $CurrentBarX, $width, $CurrentBarX+$NarrowBar,  $Black);
					$CurrentBarX += $NarrowBar;
					break;

				case 'B':
					ImageFilledRectangle($im, 0, $CurrentBarX, $width, $CurrentBarX+$WideBar,  $Black);
					$CurrentBarX += $WideBar;
					break;
			}
		}
	}

	OutputImage ($im, $format, $quality);  
}
  
  
//-----------------------------------------------------------------------------  
// Output an image to the browser  
//-----------------------------------------------------------------------------  
function OutputImage ($im, $format, $quality)  
{  
	switch ($format)  
	{  
		case "JPEG":   
			ImageJPEG ($im, NULL, $quality);  
			break;  
		case "PNG":  
			ImagePNG ($im);  
			break;  
		case "GIF":  
			ImageGIF ($im);  
			break;  
	}  
}  

//-----------------------------------------------------------------------------  
// Returns the Code 3 of 9 value for a given ASCII character  
//-----------------------------------------------------------------------------  
function Code39 ($asc)  
{
	$code39_data['0'] = 'bwbWBwBwbw';
	$code39_data['1'] = 'BwbWbwbwBw';
	$code39_data['2'] = 'bwBWbwbwBw';
	$code39_data['3'] = 'BwBWbwbwbw';
	$code39_data['4'] = 'bwbWBwbwBw';
	$code39_data['5'] = 'BwbWBwbwbw';
	$code39_data['6'] = 'bwBWBwbwbw';
	$code39_data['7'] = 'bwbWbwBwBw';
	$code39_data['8'] = 'BwbWbwBwbw';
	$code39_data['9'] = 'bwBWbwBwbw';
	$code39_data['A'] = 'BwbwbWbwBw';
	$code39_data['B'] = 'bwBwbWbwBw';
	$code39_data['C'] = 'BwBwbWbwbw';
	$code39_data['D'] = 'bwbwBWbwBw';
	$code39_data['E'] = 'BwbwBWbwbw';
	$code39_data['F'] = 'bwBwBWbwbw';
	$code39_data['G'] = 'bwbwbWBwBw';
	$code39_data['H'] = 'BwbwbWBwbw';
	$code39_data['I'] = 'bwBwbWBwbw';
	$code39_data['J'] = 'bwbwBWBwbw';
	$code39_data['K'] = 'BwbwbwbWBw';
	$code39_data['L'] = 'bwBwbwbWBw';
	$code39_data['M'] = 'BwBwbwbWbw';
	$code39_data['N'] = 'bwbwBwbWBw';
	$code39_data['O'] = 'BwbwBwbWbw';
	$code39_data['P'] = 'bwBwBwbWbw';
	$code39_data['Q'] = 'bwbwbwBWBw';
	$code39_data['R'] = 'BwbwbwBWbw';
	$code39_data['S'] = 'bwBwbwBWbw';
	$code39_data['T'] = 'bwbwBwBWbw';
	$code39_data['U'] = 'BWbwbwbwBw';
	$code39_data['V'] = 'bWBwbwbwBw';
	$code39_data['W'] = 'BWBwbwbwbw';
	$code39_data['X'] = 'bWbwBwbwBw';
	$code39_data['Y'] = 'BWbwBwbwbw';
	$code39_data['Z'] = 'bWBwBwbwbw';
	$code39_data['-'] = 'bWbwbwBwBw';
	$code39_data['.'] = 'BWbwbwBwbw';
	$code39_data[' '] = 'bWBwbwBwbw';
	$code39_data['*'] = 'bWbwBwBwbw';
	$code39_data['$'] = 'bWbWbWbwbw';
	$code39_data['/'] = 'bWbWbwbWbw'; 
	$code39_data['+'] = 'bWbwbWbWbw';
	$code39_data['%'] = 'bwbWbWbWbw';

	if (isset($code39_data[$asc])) {
		return $code39_data[$asc];
	} else {
		return $code39_data[' '];
	}
}

?>