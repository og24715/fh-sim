<?
	require 'connect.php';
	require 'function.php';

	$d = date("Y j F, g:i a");
	oauthTweet( $d );
