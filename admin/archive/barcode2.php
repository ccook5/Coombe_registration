<?php  


//-----------------------------------------------------------------------------  
// Generate a Code 3 of 9 barcode  
//-----------------------------------------------------------------------------  
function Barcode39 ($barcode, $width)  
{
	$BarcodeFull = "*".strtoupper ($barcode)."*";  
	settype ($BarcodeFull, "string");

	echo("<div style='background-color: white; cellspacing: 0px; cellpadding: 0px; border: 0px; border-collapse: collapse; width: ".$width.";'>");

	for ($i=0; $i<strlen($BarcodeFull); $i++)  
	{  
		$StripeCode = Code39 ($BarcodeFull[$i]);  

		for ($n=0; $n < 10; $n++)  
		{
			switch ($StripeCode[$n])  
			{  
				case 'w':
					echo "<div style='height: 1px; background-color: white; width: ".$width.";'></div>";
					break;  

				case 'W':
					echo "<div style='height: 3px; background-color: white; width: ".$width.";'></div>";
					break;

				case 'b':
					echo "<div style='height: 1px; background-color: black; width: ".$width.";'></div>";
					break;

				case 'B':
					echo "<div style='height: 3px; background-color: black; width: ".$width.";'></div>";
					break;  
			}  
		}
	}
	echo("</div>");
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