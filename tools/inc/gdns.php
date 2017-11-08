<?php
/* tools/inc/gdns.php
 * Functions for interacting with Google's DNS ove TCP service.
 *
 * In general, gdns functions return an array containing:
 *    idx 0: boolean indicating success or failure
 *    idx 1: an array or string of the desired response, depending on the function invoked
 */

function gdnsExecute($domain, $qtype) {
	$ch = curl_init("https://dns.google.com/resolve?name=" . $domain . "&type=" . $qtype);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	
	$response = curl_exec($ch);
	$err = curl_error($ch);
	
	if($err) {
		trigger_error("Detected CURL error when querying GDNS", E_USER_WARNING);
		return [false, $err];
	} else {
		$json = json_decode($response, true);
		
		if(!$json) {
			trigger_error("Detected JSON response invalid from GDNS", E_USER_WARNING);
			return [false, $response];
		}
		
		if($json["Status"] != 0) {
			trigger_error("Detected DNS error response code from GDNS - got " . $json["Status"], E_USER_WARNING);
			return [false, $json];
		}
		
		return [true, json_decode($response, true)];
	}
}

function gdnsGetSOA($domain) {
	$ret = gdnsExecute($domain, "SOA");
	
	if($ret[0]) {
		if(array_key_exists("Answer", $ret[1])) {
			foreach($ret[1]["Answer"] as $retAns) {
				$thisAns = rtrim($retAns["name"], ".");
				if(strcmp($thisAns, $domain) != 0) {
					return [true, $thisAns];
				}
			}
			return [true, rtrim($ret[1]["Answer"][0]["name"], ".")];
		} elseif(array_key_exists("Authority", $ret[1])) {
			foreach($ret[1]["Authority"] as $retAns) {
				$thisAns = rtrim($retAns["name"], ".");
				if(strcmp($thisAns, $domain) != 0) {
					return [true, $thisAns];
				}
			}
			return [true, rtrim($ret[1]["Authority"][0]["name"], ".")];
		} else {
			return [false, $ret[1]];
		}
	} else {
		return $ret;
	}
}

?>