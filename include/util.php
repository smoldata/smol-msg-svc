<?php

function util_normalize_phone($phone) {
	include dirname(__DIR__) . "/config.php";
	$prefix = '+' . $default_country_code;
	if (substr($phone, 0, 1) == '+') {
		$prefix = '+';
	}
	$phone = preg_replace('/\D/', '', $phone);
	$phone = "$prefix$phone";
	return $phone;
}

function util_uuid() {

	# https://secure.php.net/manual/en/function.uniqid.php#94959

	# 32 bits for "time_low"
	# 16 bits for "time_mid"
		# 16 bits for "time_hi_and_version", four most significant bits holds version number 4
	# 16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low", two most significant bits holds zero and one for variant DCE1.1
	# 48 bits for "node"

	$tl1 = mt_rand(0, 0xffff);
	$tl2 = mt_rand(0, 0xffff);

	$tm = mt_rand(0, 0xffff);
	$th = mt_rand(0, 0xffff);

	$cs = mt_rand(0, 0x3fff) | 0x8000;

	$nd1 = mt_rand(0, 0xffff);
	$nd2 = mt_rand(0, 0xffff);
	$nd3 = mt_rand(0, 0xffff);

	$fmt = "%04x%04x-%04x-%04x-%04x-%04x%04x%04x";

	return sprintf($fmt, $tl1, $tl2, $tm, $th, $cs, $nd1, $nd2, $nd3);
}

function util_ensure_rsp($rsp) {
	if (! $rsp['ok']) {
		if (DEBUG) {
			print_r($rsp);
		}
		exit;
	}
}
