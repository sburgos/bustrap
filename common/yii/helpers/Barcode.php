<?php
namespace common\yii\helpers;

class Barcode
{
    /**
     * Pass the barcode as an image directly to the browser. You can
     * pass null to the code and the error image will be returned.
     *
     * @param string $code
     * @param number $w
     * @param number $h
     * @param number $pad
     * @param number $r
     * @param number $g
     * @param number $b
     */
    public static function passthroughJpg($jpg)
    {
    	header('Content-Type: image/jpg');
    	header('Cache-Control: public, max-age=600'); // HTTP/1.1
    	header('Pragma: public');
    	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 600) . ' GMT'); // Date in the past
    	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() + 600) . ' GMT');
    	imagejpeg($jpg);
    	imagedestroy($jpg);
    }

	/**
	 * Get a Datamatrix (ISO/IEC 16022) JPG image resource.
	 *
	 * You can pass null to code to return the error image
	 *
	 * @param string $code		// Code to use for rendering
	 * @param number $w			// Width of each square
	 * @param number $h			// Height of each square
	 * @param number $pad		// Padding to apply to the image
	 * @param number $r			// R for foreground color
	 * @param number $g			// G for foreground color
	 * @param number $b			// B for foreground color
	 * @return NULL|resource
	 */
    public static function getJPG($code, $w = 10, $h = 10, $pad = 5, $r = 0, $g = 0, $b = 0)
    {
    	// Require GD library
        if (!function_exists('imagecreate'))
        	return null;

        // Return the invalid image
        if ($code === null)
        	return null;

        // Make sure it is a trimmed string
        $code = trim((string)$code);

    	// Create a new datamatrix object
    	require_once(dirname(__FILE__) . '/barcode/datamatrix.php');
		$qrcode = new \Datamatrix($code);
		$barcode_array = $qrcode->getBarcodeArray();
		$barcode_array['code'] = $code;

        // calculate image size
        $width = ($barcode_array['num_cols'] * $w) + $pad * 2;
        $height = ($barcode_array['num_rows'] * $h) + $pad * 2;

        // Create the image
		$png = imagecreate($width, $height);
		$bgcol = imagecolorallocate($png, 255, 255, 255);
		imagerectangle($png, 0, 0, $width, $height, $bgcol);
		//imagecolortransparent($png, $bgcol);
		$fgcol = imagecolorallocate($png, $r, $g, $b);

        // Print barcode elements
        $y = $pad;
        for ($r = 0; $r < $barcode_array['num_rows']; ++$r)
        {
            $x = $pad;
            for ($c = 0; $c < $barcode_array['num_cols']; ++$c)
            {
                if ($barcode_array['bcode'][$r][$c] == 1)
                    imagefilledrectangle($png, $x, $y, ($x + $w), ($y + $h), $fgcol);
                $x += $w;
            }
            $y += $h;
        }

        // Return the image resource
        return $png;
    }
    
    public static function getBarcodeHTML($code, $w = 10, $h = 10, $pad = 5, $color='#000000', $bgColor = '#ffffff') 
    {
    	// Return the invalid image
    	if ($code === null)
    		return "ERROR";
    	
    	// Make sure it is a trimmed string
    	$code = trim((string)$code);
    	
    	// Create a new datamatrix object
    	require_once(dirname(__FILE__) . '/barcode/datamatrix.php');
    	$qrcode = new \Datamatrix($code);
    	$barcode_array = $qrcode->getBarcodeArray();
    	$barcode_array['code'] = $code;
    	
    	//set barcode code and type
    	$html = "<div style='border:{$pad}px solid #ffffff;font-size:0;line-height:0;width:" . ($w * $barcode_array['num_cols']) . "px;height:" . ($h * $barcode_array['num_rows']) . "px;'>";
    	
    	// print barcode elements
    	//$y = 0;
    	// for each row
    	for ($r = 0; $r < $barcode_array['num_rows']; ++$r) 
    	{
    		//$x = 0;
    		$html .= "<div>";
    		// for each column
    		for ($c = 0; $c < $barcode_array['num_cols']; ++$c) {
    			$cellColor = $bgColor;
    			if ($barcode_array['bcode'][$r][$c] == 1) {
    				$cellColor = $color;
    			}
    			$html .= "<div style='display:inline-block;background-color:{$cellColor};width:{$w}px;height:{$h}px;'>&nbsp;</div>";
    			//$x += $w;
    		}
    		//$y += $h;
    		$html .= "</div>";
    	}
    	$html .= "</div>";
    	return $html;
    }
}
