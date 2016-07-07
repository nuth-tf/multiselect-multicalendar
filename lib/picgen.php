<?php
if (session_id() == '') { session_start(); }

	if(isset($_GET['image']) ) {


	  	if($_GET['image'] == 'captcha') {
	  		if(isset($_SESSION['cptch'])) {
	  			$_GET['image'] = $_SESSION['cptch'];
	  		} else {
	  			$_SESSION['cptch'] = rand(10,99).substr($letters,rand(1,20),1).substr($letters,rand(1,20),1).rand(10,99);
				$_SESSION['c_s_id'] = md5($_SESSION['cptch']);

	  		}


	  	}

		$text = $_GET['image'];
		$usedfont = $_GET['font'];
		$fontsize = $_GET['size'];
		$tmp_fontcolor = $_GET['color'];
		$bgcolor = $_GET['rgb_bg'];

		header("Content-Type: image/png");

	    $font = '../fonts/'.$usedfont.'.ttf';

	    // Calc the text size
	    $box = ImageTTFbbox($fontsize, 0, $font, $text);

	    // Calc some props for the image
	    $width = $box[2] - $box[0];
	    $height = $box[1] - $box[7];
	    // 10 px empty space on each side
	    $imagewidth = $width + 20;
	    $imageheight = $height + 5;

	    // Create the image
	    $pic = ImageCreate($imagewidth, $imageheight);

	    // Set the colors
	    // Backgroundcolor

		$rgb = explode(',',$bgcolor);
	    $bgcolor = ImageColorAllocate($pic,$rgb[0],$rgb[1],$rgb[2] );

	    // Fontcolor
	    switch($tmp_fontcolor)
	    {
	        case "red":
	            $fontcolor = ImageColorAllocate($pic, 255, 0, 0);
	        break;
	        case "blue":
	            $fontcolor = ImageColorAllocate($pic, 0, 0, 255);
	        break;
	        case "green":
	            $fontcolor = ImageColorAllocate($pic, 0, 255, 0);
	        break;
	        case "lightgreen":
	            $fontcolor = ImageColorAllocate($pic, 141, 167, 132);
	        break;
	        case "forestgreen":
	            $fontcolor = ImageColorAllocate($pic, 52, 114, 53);
	        break;
	        case "yellow":
	            $fontcolor = ImageColorAllocate($pic, 255, 255, 0);
	        break;
	        case "orange":
	            $fontcolor = ImageColorAllocate($pic, 236, 116, 10);
	        break;
	        case "pink":
	            $fontcolor = ImageColorAllocate($pic, 255, 128, 255);
	        	break;
	        case "lblue":
	            $fontcolor = ImageColorAllocate($pic, 0, 255, 255);
	        	break;
	        case "grey":
	            $fontcolor = ImageColorAllocate($pic, 192, 192, 192);
	        	break;
	        case "darkgrey":
	            $fontcolor = ImageColorAllocate($pic, 50, 50, 50);
	       		break;
	        case "black":
	            $fontcolor = ImageColorAllocate($pic, 0, 0, 0);
	        	break;
	        case "white":
	            $fontcolor = ImageColorAllocate($pic, 255, 255, 255);
	        	break;
	        case '2853B6':
	        	$fontcolor = ImageColorAllocate($pic, 40, 83, 182);
	        	break;
	        case '347235':
	            $fontcolor = ImageColorAllocate($pic, 52, 114, 53);
	        	break;
	        case '41663D':
	            $fontcolor = ImageColorAllocate($pic, 65, 102, 61);
	        	break;

	    }

	    // Set the fontstart positions
	    $xstart = 1;
	    $ystart = $imageheight - 5;

	    // Write the text
	    ImageTTFText($pic, $fontsize, 0, $xstart, $ystart, $fontcolor, $font, $text);

	    // Finish image
	    ImagePng($pic);

	    // Clean up memory
	    ImageDestroy($pic);
		return $pic;
    }
?>