<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: commonController.class.php 1984 2019-01-21 13:31:51Z endymion $
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class commonController extends cronController {

	function __construct() {
		global $_G, $config, $member;

		$config = $_G['cache']['plugin'][$_GET['id']];
		
		$this->assign('config', $config);
	}
}
