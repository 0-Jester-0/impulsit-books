<?php use Bitrix\Main\Config\Option;
use Bitrix\Main\SystemException;
use Jester\Custom\Log\Logger;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 */

$pictureIDs = array_column($arResult["ITEMS"], "UF_PICTURE");

if (!empty($pictureIDs)) {
	$picturesResult = CFile::getList([], ["@ID" => $pictureIDs]);

	$picturesPaths = [];
	while ($pictureData = $picturesResult->Fetch()) {
		$picturesPaths[$pictureData["ID"]] =
			Option::get("main", "upload_dir", "/upload") . "/" . $pictureData["SUBDIR"] . "/" . $pictureData["FILE_NAME"];
	}

	foreach ($arResult["ITEMS"] as &$book) {
		$book["PICTURE_PATH"] = $picturesPaths[$book["UF_PICTURE"]];
	}
} else {
	$logger = new Logger();
	$logger->error("Pictures IDs array is empty!");
}
