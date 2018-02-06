<?php
/**
 * Created by PhpStorm.
 * User: zhaoyadong
 * Date: 2017/5/19
 * Time: 下午12:02
 */

$files = [
	'cache',
	'code',
	'helper',
];

foreach ($files as $v) {
	include __DIR__ . '/' . $v . '.php';
}
