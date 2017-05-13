<?php
/**
 * @package verifylogin
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/verifyloginrecord.class.php');
class verifyLoginRecord_mysql extends verifyLoginRecord {}
?>