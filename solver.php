<?php

if ( !isset( $argv[1] ) ) {
	die( "labyrinth file needed as first param" );
}

$filename = $argv[1];
if ( !file_exists( $filename ) ) {
	die( "File: {$filename} doesn't exist" );
}

// Read the file as text and make sure we have valid chars. (No BOM no \r and so on)
$lab = preg_replace( "/[^a-zA-Z \n]/", "", file_get_contents( $filename ) );

$matrix = array();
$len = strlen( $lab );

$x = 0;
$y = 0;

$startY = null;
$startX = null;
// Build a 2D array of the labyrinth
for ( $i = 0; $i < $len; $i += 1 ) {
	$c = $lab[$i];
	if ( $c == "\n" ) {
		// End of line reached go to next line
		$y += 1;
		$x = 0;
		continue;
	} else if ( strtolower( $c ) == "i" ) {
		// Entrance is marked by the letter I, these are our start cordinates.
		$startX = $x;
		$startY = $y;
	}

	$matrix[ $x ][ $y ]= $c;
	$x += 1;
}

$res = array( );
$history = array( );
// Recursive function for walking the labyrinth, each call to walker is represents a step
// curX = x cordinate
// curY = y cordinate
// history = the path we have walked so far, is meant to prevent going back there we came from
// res = steps so far
// direction = l, r, u or d for the direction this step is
//
// Returns:
//	false for invalid path
//	array( ... ) for valid path
function walker( $curX, $curY, $history, $res, $direction ) {
	global $matrix;

	// Current char:
	$c = $matrix[ $curX ][ $curY ];

	// Add position to the copy of history
	$history[] = "" . ( $curX ) . "x" . ( $curY ) . "";

	// Add direction to the copy of res
	$res[] = $direction;

	// Can we walk to the left? Check if the position to the left is a place we have been before and if not if it even exists
	if ( !in_array( "" . ( $curX - 1 ) . "x" . ( $curY ) . "", $history ) && isset( $matrix[ $curX - 1 ][ $curY ] ) ) {
		$cl = $matrix[ $curX - 1 ][ $curY ];

		if ( $cl == " " ) {
			// Good it's a space try to walk in that direction
			$tmp = walker( $curX - 1, $curY, $history, $res, "l" );
			if ( $tmp ) {
				// Cool we got a valid response, this means we are done and can return
				return $tmp;
			}
		}
	}

	// Can we walk to downwards? Check if the position to downwards is a place we have been before and if not if it even exists
	if ( !in_array( "" . ( $curX ) . "x" . ( $curY + 1 ) . "", $history  ) && isset( $matrix[ $curX ][ $curY + 1 ] ) ) {
		$cd = $matrix[ $curX ][ $curY + 1 ];

		if ( $cd == " " ) {
			// Good it's a space try to walk in that direction
			$tmp = walker( $curX, $curY + 1, $history, $res, "d" );
			if ( $tmp ) {
				// Cool we got a valid response, this means we are done and can return
				return $tmp;
			}
		} else if ( strtolower( $cd ) == "u" ) {
			// Cool we've reached a 'u', this means we have found the exist now return. 
			$res[] = "d";
			return $res;
		}
	}

	// Can we walk to upwards? Check if the position to upwards is a place we have been before and if not if it even exists
	if ( !in_array( "" . ( $curX ) . "x" . ( $curY - 1 ) . "", $history  ) && isset( $matrix[ $curX ][ $curY - 1 ] ) ) {
		$cu = $matrix[ $curX ][ $curY - 1 ];

		if ( $cu == " " ) {
			// Good it's a space try to walk in that direction
			$tmp = walker( $curX, $curY - 1, $history, $res, "u" );
			if ( $tmp ) {
				// Cool we got a valid response, this means we are done and can return
				return $tmp;
			}
		}
	}

	// Can we walk to the right? Check if the position to the right is a place we have been before and if not if it even exists
	if ( !in_array( "" . ( $curX + 1 ) . "x" . ( $curY ) . "", $history ) && isset( $matrix[ $curX + 1 ][ $curY ] ) ) {
		$cr = $matrix[ $curX + 1 ][ $curY ];


		if ( $cr == " " ) {
			// Good it's a space try to walk in that direction
			$tmp = walker( $curX + 1, $curY, $history, $res, "r" );
			if ( $tmp ) {
				// Cool we got a valid response, this means we are done and can return
				return $tmp;
			}
		}
	}

	//echo "{$curX}x{$curY} == fail\n";
	//echo implode( ",", $history ) . "\n";
	//echo strtoupper( implode( $res ) ) . "\n";
	return false;
}

$res = walker( $startX, 0, $history, $res, "d" );
if ( $res ) {
	echo strtoupper( implode( $res ) ) . "\n";
} else {
	echo "We've failed you, my lord\n";
}
exit(0);
?>
