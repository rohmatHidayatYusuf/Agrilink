<?php
/**
 * Redirector ke MVC AuthController
 */
header("Location: ../../index.php?c=auth&a=login" . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
exit;