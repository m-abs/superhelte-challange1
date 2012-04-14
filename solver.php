<?php
$lab = file_get_contents( "TextFile2.txt" );

$matrix = array();
$len = strlen( $lab );

$x = 0;
$y = 0;

$startY = null;
$startX = null;
for ( $i = 0; $i < $len; $i += 1 ) {
	$c = $lab[$i];
	if ( $c == "\n" ) {
		$y += 1;
		$x = 0;
		continue;
	} else if ( strtolower( $c ) == "i" ) {
		$startX = $x;
		$startY = $y;
	}

	// echo "{$x},{$y} = {$c}\n";
	$matrix[ $x ][ $y ]= $c;
	$x += 1;
}

echo $startX . "\n";

$res = array(
	"d"
);
$history = array( );
$curX = $startX;
$curY = 0;
while ( true ) {
	$c = $matrix[ $curX ][ $curY ];	
	echo "{$curX},{$curY} = {$c}\n";

	// print_r( $res );
	
	if ( !in_array( "" . ( $curX - 1 ) . "x" . ( $curY ) . "", $history ) && isset( $matrix[ $curX - 1 ][ $curY ] ) ) {
		$cl = $matrix[ $curX - 1 ][ $curY ];

		if ( $cl == " " ) {

			$history[] = "" . ( $curX - 1 ) . "x" . ( $curY ) . "";
			$curX -= 1;
			$res[] = "l";
			continue;
		}
	}

	if ( !in_array( "" . ( $curX + 1 ) . "x" . ( $curY ) . "", $history ) && isset( $matrix[ $curX + 1 ][ $curY ] ) ) {
		$cr = $matrix[ $curX + 1 ][ $curY ];


		if ( $cr == " " ) {
			$history[] = "" . ( $curX + 1 ) . "x" . ( $curY ) . "";
			$curX += 1;
			$res[] = "r";
			continue;
		}
	}

	if ( !in_array( "" . ( $curX ) . "x" . ( $curY - 1 ) . "", $history  ) && isset( $matrix[ $curX ][ $curY - 1 ] ) ) {
		$cu = $matrix[ $curX ][ $curY - 1 ];

		if ( $cu == " " ) {
			$history[] = "" . ( $curX ) . "x" . ( $curY - 1 ) . "";
			$curY -= 1;
			$res[] = "u";
			continue;
		}
	}

	if ( !in_array( "" . ( $curX ) . "x" . ( $curY + 1 ) . "", $history  ) && isset( $matrix[ $curX ][ $curY + 1 ] ) ) {
		$cd = $matrix[ $curX ][ $curY + 1 ];

		if ( $cd == " " ) {
			$history[] = "" . ( $curX ) . "x" . ( $curY + 1 ) . "";
			$curY += 1;
			$res[] = "d";
			continue;
		} else if ( strtolower( $cd ) == "u" ) {
			$res[] = "d";
			break;
		}
	}

	echo "fuck\n";
	break;
} 

echo strtoupper( implode( $res ) ) . "\n";

exit(0);
?>
