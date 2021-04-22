<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: commonController.class.php 1840 2018-07-06 15:47:40Z endymion $
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class cronController {

	private $commonVar;

	function display($file = '') {

		global $mod, $ac, $_G;

		foreach($this->commonVar as $key => $val) {

			$$key = $val;
		}

		$pluginid = $_GET['id'];

		if(!$file) {
			include template($pluginid.'_'.$mod.'_'.$ac, 0, 'source/plugin/'.$pluginid.'/template');
		} else {
			include template($file, 0, 'source/plugin/'.$pluginid.'/template');
		}
	}

	function assign($name, $value='') {

		if(is_array($name)) {
			$this->commonVar = array_merge($this->commonVar, $name);
		} else {
			$this->commonVar[$name] = $value;
		}
	}

	function httpGet($url) {

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	}

	function httpPost($url, $str) {

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $str);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($str))
		);
		$res = curl_exec ($curl);
		curl_close($curl);

		return $res;
	}

	function message($msg, $res, $url = '') {
		include template('message', 0, 'source/plugin/'.$_GET['id'].'/template');
		exit();
	}
}
