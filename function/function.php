<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function.php 1992 2019-02-27 15:59:47Z endymion $
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function countByCondition($fields, $condition = array()) {

    $sql = "SELECT COUNT(*) FROM ";

    foreach ($fields as $key => $value) {
		if (count($fields) > 1) {
			if ($value['_type']) {
				$_type = $value['_type'];

				$sql .= DB::table($key).' AS '.$key.' ';
			}

			if ($value['_on']) {
				$sql .= ' '.$_type.' JOIN '.DB::table($key).' AS '.$key .' ON '.$value['_on'];
			}
		} else {
			$sql .= DB::table($key).' ';
		}
    }

    if ($condition) {
        $sql .= ' WHERE ';
    }

    $i = 0;
    foreach ($condition as $key => $value) {
        if ($i == 0) {
            if ($key == 'sql') {
                $sql .= $value;
            } else {
                $sql .= $key."='".$value."'";
            }
        } else {
            if ($key == 'sql') {
                $sql .= ' AND '.$value;
            } else {
                $sql .= " AND ".$key."='".$value."'";
            }
        }
        $i++;
    }

    $count = DB::result_first($sql);

    return $count;
}

function sumByCondition($field, $fields, $condition = array()) {

    $sql = "SELECT SUM(".$field.") FROM ";

    foreach ($fields as $key => $value) {
        if (count($fields) > 1) {
			if ($value['_type']) {
				$_type = $value['_type'];

				$sql .= DB::table($key).' AS '.$key.' ';
			}

			if ($value['_on']) {
				$sql .= ' '.$_type.' JOIN '.DB::table($key).' AS '.$key .' ON '.$value['_on'];
			}
		} else {
			$sql .= DB::table($key).' ';
		}
    }

    if ($condition) {
        $sql .= ' WHERE ';
    }

    $i = 0;
    foreach ($condition as $key => $value) {
        if ($i == 0) {
            if ($key == 'sql') {
                $sql .= $value;
            } else {
                $sql .= $key."='".$value."'";
            }
        } else {
            if ($key == 'sql') {
                $sql .= ' AND '.$value;
            } else {
                $sql .= " AND ".$key."='".$value."'";
            }
        }
        $i++;
    }

    $count = DB::result_first($sql);

    return $count;
}

function fetchByCondition($fields, $condition, $page = 0, $page_size = 0, $order = '', $type ="all") {

    $sql = "SELECT ";

    $i = 0;
    foreach ($fields as $key => $value) {
		if (count($fields) > 1) {
			foreach ($value as $k => $v) {

				$k = is_numeric($k) ? $v : $k;

				if (!in_array($k, array('_type', '_on'))) {
					if ($i == 0) {
						$sql .= $key.'.'.$k.' AS '.$v;
					} else {
						$sql .= ', '.$key.'.'.$k.' AS '.$v;
					}
					$i++;
				}
			}
		} else {
            if ($value) {
                $sql .= implode(',', $value);
            } else {
                $sql .= '*';
            }
		}
    }

    $sql .= ' FROM ';

    foreach ($fields as $key => $value) {
        if (count($fields) > 1) {
			if ($value['_type']) {
				$_type = $value['_type'];

				$sql .= DB::table($key).' AS '.$key.' ';
			}

			if ($value['_on']) {
				$sql .= ' '.$_type.' JOIN '.DB::table($key).' AS '.$key .' ON '.$value['_on'];
			}
		} else {
			$sql .= DB::table($key).' ';
		}
    }

    if ($condition) {
        $sql .= ' WHERE ';
    }

    $i = 0;
    foreach ($condition as $key => $value) {
        if ($i == 0) {
            if ($key == 'sql') {
                $sql .= $value;
            } else {
                $sql .= $key."='".$value."'";
            }
        } else {
            if ($key == 'sql') {
                $sql .= ' AND '.$value;
            } else {
                $sql .= " AND ".$key."='".$value."'";
            }
        }
        $i++;
    }

    if ($order) {
        $sql .= ' ORDER BY  '.$order;
    }

    if ($page_size) {
        $sql .= ' LIMIT '.$page.','.$page_size;
    }

    if ($type == 'all') {
        $list = DB::fetch_all($sql);
    }
    if ($type == 'one') {
        $list = DB::fetch_first($sql);
    }
    return $list;
}

function base64_image_upload($base64_content = '') {

	global $_G;

	if (!$base64_content) {
		return false;
	}

    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_content, $result)) {

        $type = $result[2];

		if (!in_array($type, array('jpeg', 'jpg', 'gif', 'png', 'bmp'))) {
			return false;
		}

		$file_path = $_G['setting']['attachdir']."/portal/while_adhb/".date('Ym').'/'.date('d').'/';

        if (!file_exists($file_path)) {
            mkdir($file_path, 0777, true);
        }

		$file_name = random(20).".".$type;

        $fullname = $file_path . $file_name;

        if (file_put_contents($fullname, base64_decode(str_replace($result[1], '', $base64_content)))) {

			$pic = 'while_adhb/'.date('Ym').'/'.date('d').'/'.$file_name;

			if(getglobal('setting/ftp/on')) {
				ftpcmd('upload', 'image/'.$pic);
			}

            return $pic;
        } else {
            return false;
        }
    } else {
        return $base64_content;
    }
}

function imgurl2base64($url, $timeout=30) {

	$dir   = pathinfo($url);
	$host  = $dir['dirname'];
	$refer = $host.'/';

	$ch = curl_init($url);
	curl_setopt ($ch, CURLOPT_REFERER, $refer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);

	$info = curl_getinfo($ch);
	curl_close($ch);

	$base_64 = base64_encode($data);

	$msg = "data:".$info['content_type'].";base64,".$base_64;

	unset($info,$data,$base_64);
	return $msg;
}

function httpGet($url) {

	if (!function_exists('curl_init')) {
		return '';
	}

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

function httpPost($url,$str) {

	if (!function_exists('curl_init')) {
		return '';
	}

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POSTFIELDS, $str);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($str))
	);
	$res = curl_exec ($curl);
	curl_close($curl);

	return $res;
}
