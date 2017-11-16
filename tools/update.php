<?php
/* tools/update.php
 * 
 */

include "inc/setup.php";
include "inc/gdns.php";

$allDomains = $mysqli->query("SELECT * FROM `Reputation`");
while($row = $allDomains->fetch_assoc()) {
	if(strlen($row["Subdomain"]) > 0) {
		$parsedRow = tld_extract($row["Subdomain"] . "." . $row["Domain"]);
	} else {
		$parsedRow = tld_extract($row["Domain"]);
	}
	
	if(strlen($parsedRow->getFullHost()) > 60) {
		$disp = "... " . substr($parsedRow->getFullHost(), strlen($parsedRow->getFullHost()) - 60, strlen($parsedRow->getFullHost()));
	} else {
		$disp = $parsedRow->getFullHost();
	}
	echo "Working on " . $disp . PHP_EOL;
	
	$gdnsResA = gdnsGetGeneral($parsedRow->getFullHost(), "A");
	if($gdnsResA[0]) {
		foreach($gdnsResA[1] as $rawResult) {
			if(filter_var($rawResult, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
				$mysqli->query("INSERT INTO `DNS_A` (Subdomain, Domain, IPv4, Current) VALUES ('".$row["Subdomain"]."', '".$row["Domain"]."', '".$rawResult."', 1)");
				echo "   " . $rawResult . PHP_EOL;
			}
		}
	} else {
		echo "   No A records" . PHP_EOL;
	}
	
	$gdnsResAAAA = gdnsGetGeneral($parsedRow->getFullHost(), "AAAA");
	if($gdnsResAAAA[0]) {
		foreach($gdnsResAAAA[1] as $rawResult) {
			if(filter_var($rawResult, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
				$mysqli->query("INSERT INTO `DNS_AAAA` (Subdomain, Domain, IPv6, Current) VALUES ('".$row["Subdomain"]."', '".$row["Domain"]."', '".$rawResult."', 1)");
				echo "   " . $rawResult . PHP_EOL;
			}
		}
	} else {
		echo "   No AAAA records" . PHP_EOL;
	}
	
	$gdnsResCNAME = gdnsGetGeneral($parsedRow->getFullHost(), "CNAME");
	if($gdnsResCNAME[0]) {
		foreach($gdnsResCNAME[1] as $rawResult) {
			$parsedRes = tld_extract($rawResult);
			if($parsedRes->isValidDomain()) {
				$ans = rtrim($parsedRes->getFullHost(), ".");
				$mysqli->query("INSERT INTO `DNS_CNAME` (Subdomain, Domain, CNAME, Current) VALUES ('".$row["Subdomain"]."', '".$row["Domain"]."', '".$ans."', 1)");
				echo "   " . $ans . PHP_EOL;
			}
		}
	} else {
		echo "   No CNAME records" . PHP_EOL;
	}
	
	$gdnsResMX = gdnsGetGeneral($parsedRow->getFullHost(), "MX");
	if($gdnsResMX[0]) {
		foreach($gdnsResMX[1] as $rawResult) {
			$arrRes = explode(" ", $rawResult);
			if(count($arrRes) == 1) {
				$parsedRes = tld_extract(rtrim($arrRes[0], "."));
			} else {
				$parsedRes = tld_extract(rtrim($arrRes[1], "."));
			}
			$ans = $parsedRes->getFullHost();
			$mysqli->query("INSERT INTO `DNS_MX` (Subdomain, Domain, MX_Subdomain, MX_Domain, Current) VALUES ('".$row["Subdomain"]."', '".$row["Domain"]."', '".$parsedRes["subdomain"]."', '".$parsedRes->getRegistrableDomain()."', 1)");
			echo "   " . $ans . PHP_EOL;
		}
	} else {
		echo "   No MX records" . PHP_EOL;
	}
	
	$gdnsResNS = gdnsGetGeneral($parsedRow->getFullHost(), "NS");
	if($gdnsResNS[0]) {
		foreach($gdnsResNS[1] as $rawResult) {
			$parsedRes = tld_extract(rtrim($rawResult, "."));
			if($parsedRes->isValidDomain()) {
				$ans = $parsedRes->getFullHost();
				$mysqli->query("INSERT INTO `DNS_NS` (Subdomain, Domain, NS_Subdomain, NS_Domain, Current) VALUES ('".$row["Subdomain"]."', '".$row["Domain"]."', '".$parsedRes["subdomain"]."', '".$parsedRes->getRegistrableDomain()."', 1)");
				echo "   " . $ans . PHP_EOL;
			}
		}
	} else {
		echo "   No NS records" . PHP_EOL;
	}
	//sleep(1);
}

echo round((memory_get_peak_usage() / (1000*1000)), 2) . " MB memory allocated (peak)" . PHP_EOL;

include "inc/exit.php";
?>