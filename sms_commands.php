<?php

function sms_command($usr_id, $rx_msg, $rx_id, $cmd) {

	// Call the command handler function
	$cmd_func = "sms_command_{$cmd['id']}";
	if (function_exists($cmd_func)) {
		$tx_msg = call_user_func($cmd_func, $usr_id, $cmd['args']);
	} else {
		$tx_msg = sms_unknown_command();
	}

	// Send the response (there should always be a response)
	if (! empty($tx_msg)) {
		msg_tx($usr_id, $tx_msg, $rx_id, "send now");
	} else {
		msg_tx($usr_id, xo('err_command_empty_tx'), $rx_id, "send now");
	}
}

function sms_command_help($usr_id, $cmd) {

	if (empty($cmd)) {
		// List the commands: /help
		return xo('cmd_help');
	}

	// Learn more about a command: /help [command]
	$help_msg = xo("cmd_help_{$cmd}");
	$cmd_func = "sms_command_{$cmd}";

	if (! empty($help_msg)) {
		// Ok, found the help message!
		return $help_msg;
	} else if (function_exists($cmd_func)) {
		// Hey, this means we forgot to write the help message!
		return xo('err_command_no_help', $cmd);
	} else {
		// Huh?
		return xo('err_command_unknown', $cmd);
	}
}

function sms_command_stop($usr_id) {

	// Staaaahp, no more chat messages: /stop

	// Note: Twilio has its own built-in "STOP" message, which doesn't get
	// passed along. Which is sort of why command syntax uses a slash
	// prefix, beyond existing chat conventions.

	usr_set_context($usr_id, 'stopped');
	return xo('cmd_stop');
}

function sms_command_start($usr_id) {

	// Rejoin the chat: /start

	usr_set_context($usr_id, 'chat');
	return xo('cmd_start');
}

function sms_command_name($usr_id, $new_name = null) {

	// Change your name: /name [new name]

	$rsp = usr_set_name($usr_id, $new_name);
	if ($rsp == OK) {
		return xo('cmd_name_changed', $new_name);
	} else {
		return xo($rsp);
	}
}

function sms_command_mute($usr_id, $mute_name = null) {

	// Stop getting messages from a particular user: /mute [name]

	$rsp = usr_set_mute($usr, $mute_name, true);
	if ($rsp == OK) {
		return xo('cmd_muted', $mute_name, $mute_name);
	} else {
		return xo($rsp);
	}
}

function sms_command_unmute($usr_id, $mute_name = null) {
	
	// Start getting messages from a particular user: /unmute [name]

	$rsp = usr_set_mute($usr, $mute_name, false);
	if ($rsp == OK) {
		return xo('cmd_unmuted', $mute_name);
	} else {
		return xo($rsp);
	}
}

function sms_command_about($usr_id) {

	// Get info about the chat: /about

	include(__DIR__ . '/config.php');

	$active_users = usr_get_active();
	$user_count = count($active_users);

	return xo('cmd_about', $user_count, $website_url);
}

function sms_command_invite($usr_id, $invite_phone) {

	// Invite a friend to join the chat: /invite [phone]

	$rsp = usr_invite($usr, $invite_phone);
	if ($rsp == OK) {
		return xo('cmd_invited');
	} else {
		return xo($rsp);
	}
}
	
function sms_command_login($usr_id, $login_code) {

	// Login from the website: /login [code]

	include(__DIR__ . '/config.php');

	$rsp = usr_complete_login($usr, $login_code);
	if ($rsp == OK) {
		return xo('cmd_login_success', $website_url);
	} else {
		return xo($rsp);
	}
}

function sms_command_hold($usr_id, $msg_id) {

	// TODO: write this /hold [msg id]
}

function sms_command_ban($usr_id, $user) {

	// TODO: write this /ban [user]
}

function sms_command_unban($usr_id, $user) {

	// TODO: write this /unban [user]
}