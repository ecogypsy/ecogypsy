<?php
$GLOBALS['SITE_ADMIN_URL'] = 'http://' .$_SERVER['HTTP_HOST'].'/ecogypsy/admin/';
$GLOBALS['SITE_APP_URL'] = 'http://' .$_SERVER['HTTP_HOST'].'/ecogypsy/application/';
$GLOBALS['PAGE_BEFORE_LOGIN'] = array('Admin\Controller\Index\login','Admin\Controller\Index\index');
$GLOBALS['SITE_PATH'] = $_SERVER['DOCUMENT_ROOT'];
$GLOBALS['HOTELIMAGEPATH'] = $_SERVER['DOCUMENT_ROOT'].'ecogypsy/hotel';
$GLOBALS['LOCATIONIMAGEPATH'] = $_SERVER['DOCUMENT_ROOT'].'ecogypsy/location';
$GLOBALS['SITE_HTTP_PATH'] = 'http://' .$_SERVER['HTTP_HOST'].'/ecogypsy';
define('NODE_API', 'http://localhost:3000/');