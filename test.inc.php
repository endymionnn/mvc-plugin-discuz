<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: while_adhb.inc.php 1992 2019-02-27 15:59:47Z endymion $
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('PLUGIN_A', $_G['siteurl'].'plugin.php?id='.$_GET['id']);

require_once libfile('function/home');
require_once DISCUZ_ROOT . 'source/plugin/'.$_GET['id'].'/function/function.php';
require_once DISCUZ_ROOT . 'source/plugin/'.$_GET['id'].'/controller/cronController.class.php';
require_once DISCUZ_ROOT . 'source/plugin/'.$_GET['id'].'/controller/commonController.class.php';

$mod_array   = array(
	'index',
);
$mod         = !in_array($_G['mod'], $mod_array) ? 'index' : $_G['mod'];
$_GET['mod'] = $mod;

require_once DISCUZ_ROOT .'source/plugin/'.$_GET['id'].'/controller/'.$mod.'Controller.class.php';

$ac_array = array(
	'index'    => array('index'),
);

$ac = in_array($_GET['ac'], $ac_array[$mod]) ? $_GET['ac'] : 'index';
$_GET['ac'] = $ac;

parse_str($_SERVER['QUERY_STRING'], $getarr);

unset($getarr['id'], $getarr['mod'], $getarr['ac']);

$plugin = $mod.'Controller';

$obj = new $plugin;

call_user_func_array(array($obj, $ac), $getarr);
