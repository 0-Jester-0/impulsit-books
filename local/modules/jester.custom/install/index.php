<?php B_PROLOG_INCLUDED === true || die();

IncludeModuleLangFile(__FILE__);

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class jester_custom extends CModule
{
	const MODULE_ID = "jester.custom";
	var $MODULE_ID = "jester.custom";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $PARTNER_NAME;
	var $PARTNER_URI;

	/**
	 * jester.custom constructor.
	 *
	 * Конструктор модуля:
	 * Инициализация ключевых переменных для правильного отображения в административной панели
	 */
	public function __construct()
	{
		$arModuleVersion = [];
		include(dirname(__FILE__) . "/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("JESTER.CUSTOM.MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("JESTER.CUSTOM.MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("JESTER.CUSTOM.PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("JESTER.CUSTOM.PARTNER_URI");
	}

	/**
	 * Метод для установки модуля
	 * Регистрируется модуль
	 *
	 * @return bool
	 */
	public function doInstall(): bool
	{
		try {
			Main\ModuleManager::registerModule($this->MODULE_ID);
		} catch (\Exception $e) {
			global $APPLICATION;
			$APPLICATION->ThrowException($e->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Метод деинсталяции модуля
	 * Снятие модуля с регистрации
	 *
	 * @return bool
	 */
	public function doUninstall(): bool
	{
		try {
			Main\ModuleManager::unRegisterModule($this->MODULE_ID);
		} catch (\Exception $e) {
			global $APPLICATION;
			$APPLICATION->ThrowException($e->getMessage());

			return false;
		}

		return true;
	}
}