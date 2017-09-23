<?php
$GLOBALS['SITE_ADMIN_URL'] = 'http://' .$_SERVER['HTTP_HOST'].'/ecogypsy/admin/';
$GLOBALS['SITE_APP_URL'] = 'http://' .$_SERVER['HTTP_HOST'].'/ecogypsy/application/index';
$GLOBALS['PAGE_BEFORE_LOGIN'] = array('Admin\Controller\Index\login','Admin\Controller\Index\index');
$GLOBALS['SITE_PATH'] = $_SERVER['DOCUMENT_ROOT'];
$GLOBALS['HOTELIMAGEPATH'] = $_SERVER['DOCUMENT_ROOT'].'ecogypsy/hotel';
define('NODE_API', 'http://localhost:3000/');