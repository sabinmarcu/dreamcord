<?php

/**
 * Description of url
 * @package Helpers
 * @author Sabin Marcu
 */
class urlHelper{

    public static function linkTo($to = "", $get = 1, $ssl = 0) {

	if (strpos ( $to, "http" ) === false) {
		$string = ($ssl == 1 ? "https://" : "http://");
		if (isset ( $_SERVER ['HTTP_X_FORWARDED_HOST'] ))
			$string .= $_SERVER ['HTTP_X_FORWARDED_HOST'];
		else
			$string .= $_SERVER ['HTTP_HOST'];
		$string .= Oski::app()->getProp('baseURL');
		if (substr ( $to, 0, 1 ) !== "/")
			$string .= "/";
		$string .= $to;
	} else  $string = $to;

	if (substr ( $string, strlen ( $string ) - 1 ) != "/" && $string)
		$string .= "/";

	if ($get && count ( $_GET )) {
		$i = 0;
		foreach ( $_GET as $key => $value )
			if (! in_array ( $key, array ("server_error", "engines" ) )) {
				if ($i == 0)
					$string .= "?";
				if ($i ++)
					$string .= "&";
				$string .= $key . "=" . $value;
			}
		if (substr($string, strlen($string) - 1) === "/") $string = substr($string, 0, strlen($string) - 1);
	}

	return $string;
    }

    public static function eLinkTo($to = "", $get = 1, $ssl = 0) {
	echo linkTo ( $to = "", $get = 1, $ssl = 0 );
    }

    public static function linkToF($link) {
	$link = self::linkTo($link, 0);
	return substr ( $link, 0, strlen ( $link ) - 1 );
    }
}
?>
