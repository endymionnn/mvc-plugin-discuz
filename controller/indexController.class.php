<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: addController.class.php 1992 2019-02-27 15:59:47Z endymion $
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class indexController extends commonController {

	function index() {

		global $_G, $config, $member;

		$this->display();
	}
}
