<?php
require_once ('KISA_SEED_CBC.php');

# PHP Notice:  Undefined offset 오류 무시하세요

$SEED_KEY = "rClo7QA4gdgyITHAPWrfXw==";
$SEED_IV = "WPAYSTDWPAY00000";

$res = encrypt($SEED_IV, $SEED_KEY, "TESTuserId");
echo $res;
echo "\n";
$res2 = decrypt($SEED_IV, $SEED_KEY, "rw8yiQE00dA5Bzi4J+GVDg==");
echo $res2;
exit;

function getBytesLikeJava($base64String) {
	$decodedData = base64_decode($base64String);
	$byteArray = [];
	for ($i = 0; $i < strlen($decodedData); $i++) {
		$byteArray[] = ord($decodedData[$i]);
		if ($byteArray[$i] > 127) {
			$byteArray[$i] -= 256;
		}
	}
	return $byteArray;
}

function getBytes($str) {
	$byteArray = unpack('C*', $str);
	$byteValues = [];
	foreach ($byteArray as $byte) {
		$byteValues[] = $byte; 
	}
	return $byteValues;
}

function encrypt($bszIV, $bszUser_key, $str) {
	$planBytes = getBytes($str);
	$keyBytes = getBytesLikeJava($bszUser_key);
	$IVBytes = getBytes($bszIV);
	$message_offset = 0;
	$message_length = count($planBytes);
	$bszChiperText = KISA_SEED_CBC::SEED_CBC_Encrypt($keyBytes, $IVBytes, $planBytes, $message_offset, $message_length);// []byte, []byte, []byte, int, int
	$string = '';
	foreach ($bszChiperText as $byte) {
		$string .= chr($byte);
	}
	$base64EncodedString = base64_encode($string);
	return $base64EncodedString;
}

function decrypt($bszIV, $bszUser_key, $str) {
	$planBytes = getBytesLikeJava($str);
	$keyBytes = getBytesLikeJava($bszUser_key);
	$IVBytes = getBytes($bszIV);
	$message_offset = 0;
	$message_length = count($planBytes);
	$bszPlainText = null;
	$bszPlainText = KISA_SEED_CBC::SEED_CBC_Decrypt($keyBytes, $IVBytes, $planBytes, $message_offset, $message_length);
	return implode(array_map('chr', $bszPlainText));
}

function encryptSHA256($hashParam) {
    $hash = hash('sha256', $hashParam);
    return $hash;
}
