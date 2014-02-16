<?php
Class Colotar {
    
    private function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

	private function hashify(&$input) {
      $input = md5($input); // First hash the input
      while(strlen($input) < 33) {
            $input .= 0; // Pad $input to exactly 33 characters
      }
      $input = substr($input, 0, 33);
    }
  
  	private function generateColotar($input, $size = 64){
        // Split the hash into 9 seperate values plus a border
        $hashmap = Array();
        $strcounter = 0;
        for($count = 0; $count < 10; $count++) {
            if($count == 9){
                $hashmap[$count] = substr($input, $strcounter, 6);
            } else {
                $hashmap[$count] = substr($input, $strcounter, 3);
                $strcounter += 3;
            }
        }
        unset($input);
        unset($strcounter);
        // Generate image
        $img = imagecreatetruecolor($size, $size);
        $values = $this->hex2rgb($hashmap[9]);
        // Generate border
        imagefilledrectangle($img, 0, 0, $size, $size, imagecolorallocate($img, $values[0], $values[1], $values[2]));
        // Fill the image with the 9 colored squares + border
        $row = 0; // Row incrementor
        $column = 0; // Column incrementor
        foreach($hashmap as $hash) {
            if(strlen($hash) != 6) { // If 6, hash is the border value, ignore it
                $values = $this->hex2rgb($hash);
                $rgbcolor = imagecolorallocate($img, $values[0], $values[1], $values[2]);
                imagefilledrectangle($img, 2 + (20 * $row), 2 + (20 * $column), 20 + (20 * $row), 20 + (20 * $column), $rgbcolor);
                $row++;
                if($row == 3) {
                    $row = 0;
                    $column++;
                }
            }
        }
        imagepng($img);
        imagedestroy($img);
  	}

    // The primary colotar interface
    public function getColotar($input) {
        if(!$input) {
            die("No input string specified.");
        }
        $this->hashify($input);
        $this->generateColotar($input);
    }
  
}
?>