<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;

Loader::includeModule("jester.custom");

require_once Application::getDocumentRoot() . '/local/modules/jester.custom/vendor/autoload.php';
