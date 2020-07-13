<?php
include('config.php');
echo "##by: Tra\n";
echo "Modified By: Rz\n";
Awal:
	echo "$yellow??$white Masukkan reff : " ;
	$ref = trim(fgets(STDIN));
	echo "$yellow??$white Berapa kali reff? ";
	$time = trim(fgets(STDIN));
	
	// $ref = @file_get_contents("ref_tzpromo.txt");
	// if(file_exists("ref_tzpromo.txt")){
	// 	echo "Use Old Referral?(Y/n)	";
	// 	$r = trim(fgets(STDIN));
	// 	if(strtolower($r)=="n"){
	// 		unlink("ref_tzpromo.txt");
	// 		goto Awal;
	// 	}
	// }else{
	// 	echo "?REF	";
	// 	$refs = trim(fgets(STDIN));
	// 	@file_put_contents("ref_tzpromo.txt", $refs);
	// }
	for ($i=1; $i <= $time ; $i++) { 
		@unlink("cookie_tzpromo.txt");
		echo "$yellow??$white Nomor : ";
		$no = trim(fgets(STDIN));
		addRef($no);
		login($no);
		echo "$yellow??$white OTP : ";
		$otp = trim(fgets(STDIN));
		verifyOtp($no, $otp);
		$res = register(getDetailCards(getCards()), $no);
		echo "$okegreen**$white Result : $res\n";
	}
	
function addRef($no){
	global $ref;
	$body = "{\"referralCode\":\"$ref\",\"phone\":\"$no\",\"country\":\"ID\"}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://membership.usetada.com/api/referral/add');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
	$headers = array();
	$headers[] = 'Host: membership.usetada.com';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'Authorization: Bearer 6STk6mSi6NcGNI7rGnVuIWHz0OXcIW7jlgijImMoyhAQWPzPcxeME7qcvE2c619ISnhY0Du5wTpFv0mgsuY0eczT4SqwlbSmsslJwKNJgLEAAsfQLG8ZPayjM9pxeQUEv35oY3BA4ZFHtzq91B0C0zr8azSFFuJQSIxuNec0GLUhs4ROscqVMCgKD2ZtFfrIXRYXFcrLVTjttmEOlGwPXdIisj3reHWGdV8nfcMXqvkvZoQRu4iPuAWVG6Q9yeTk';
	$headers[] = 'Accept-Language: en-us';
	$headers[] = 'Content-Type: application/json;charset=utf-8';
	$headers[] = 'Content-Length:'.strlen($body).'';
	$headers[] = 'Origin: https://membership.usetada.com';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1';
	$headers[] = 'Connection: close';
	$headers[] = 'Referer: https://membership.usetada.com/referral/'.$ref.'';
	$headers[] = 'Cookie: _ga=GA1.1.323137868.1594570264; _ga_B6DQELPYPE=GS1.1.1594570264.1.0.1594570264.0';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function login($no){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://tzpromo.usetada.com/api/auth/check-number');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"countryCode\":\"ID\",\"phoneNumber\":\"$no\",\"merchantId\":2309,\"senderType\":\"sms\",\"referenceId\":null}");
	$headers = array();
	$headers[] = 'Host: tzpromo.usetada.com';
	$headers[] = 'Content-Type: application/json;charset=utf-8';
	$headers[] = 'Origin: https://tzpromo.usetada.com';
	$headers[] = 'Connection: close';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
	$headers[] = 'Referer: https://tzpromo.usetada.com/';
	$headers[] = 'Accept-Language: en-us';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie_tzpromo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie_tzpromo.txt");
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function verifyOtp($no, $otp){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://tzpromo.usetada.com/api/auth/verify-otp');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"phone\":\"$no\",\"otp\":\"$otp\",\"type\":\"membership\"}");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie_tzpromo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie_tzpromo.txt");
	$headers = array();
	$headers[] = 'Host: tzpromo.usetada.com';
	$headers[] = 'Content-Type: application/json;charset=utf-8';
	$headers[] = 'Origin: https://tzpromo.usetada.com';
	$headers[] = 'Connection: close';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
	$headers[] = 'Referer: https://tzpromo.usetada.com/passcode';
	$headers[] = 'Accept-Language: en-us';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result;
}

function getCards(){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://tzpromo.usetada.com/api/cards');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie_tzpromo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie_tzpromo.txt");
	$headers = array();
	$headers[] = 'Host: tzpromo.usetada.com';
	$headers[] = 'Origin: https://tzpromo.usetada.com';
	$headers[] = 'Content-Length: 0';
	$headers[] = 'Connection: close';
	$headers[] = 'If-None-Match: W/\"218-3ZW6aFb1w+PAzC1+lDaIN57d2DA\"';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
	$headers[] = 'Accept-Language: en-us';
	$headers[] = 'Referer: https://tzpromo.usetada.com/cards';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return @json_decode($result, true)['nextId'];
}

function getDetailCards($card){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://tzpromo.usetada.com/api/cards/detail');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"id\":\"$card\"}");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie_tzpromo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie_tzpromo.txt");
	$headers = array();
	$headers[] = 'Host: tzpromo.usetada.com';
	$headers[] = 'Content-Type: application/json;charset=utf-8';
	$headers[] = 'Origin: https://tzpromo.usetada.com';
	$headers[] = 'Connection: close';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
	$headers[] = 'Referer: https://tzpromo.usetada.com/cards';
	$headers[] = 'Accept-Language: en-us';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return @json_decode($result, true)['id'];
}

function register($card, $no){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://tzpromo.usetada.com/api/cards/register');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"cardId\":\"$card\",\"data\":[{\"key\":\"phone\",\"value\":\"$no\"},{\"key\":\"name\",\"value\":\"hhuu\"}]}");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie_tzpromo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie_tzpromo.txt");
	$headers = array();
	$headers[] = 'Host: tzpromo.usetada.com';
	$headers[] = 'Content-Type: application/json;charset=utf-8';
	$headers[] = 'Origin: https://tzpromo.usetada.com';
	$headers[] = 'Connection: close';
	$headers[] = 'Accept: application/json, text/plain, */*';
	$headers[] = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';
	$headers[] = 'Referer: https://tzpromo.usetada.com/cards/register/5f0b3f2d0641f28209cda3c7';
	$headers[] = 'Accept-Language: en-us';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
