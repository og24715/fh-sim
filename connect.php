<?
require 'vendor/autoload.php';

// Set default HTTP status code.
$code = 200;

// Set your timezone.
date_default_timezone_set('Asia/Tokyo');

try {

	$ck = 'S3PhvuYsYGhn5sHQjqlte5Sut';
	$cs = 'D16OTeKoYXxXsgENpm4ZhDLJbMyeThDvIFRbsS7fwvbpyubN81';
	$at = '759001177157578752-4GWndSHgwTblsOW1oTn2AhkmvFFFXsJ';
	$as = 'zYtZi2jXepJCdXWZ1uCiZXhAYXXEWsue93wXO2dDy8rgX';

	// Generate your TwistOAuth object.
	$to = new TwistOAuth( $ck, $cs, $at, $as );
		
	// Get tweets on your home timeline within 5.
	// This method may throw TwistException.

} catch (TwistException $e) {

	// Set error message.
	$error = $e->getMessage();

	// Overwrite HTTP status code.
	// The exception code will be zero when it thrown before accessing Twitter, we need to change it into 500.
	$code = $e->getCode() ?: 500;

}