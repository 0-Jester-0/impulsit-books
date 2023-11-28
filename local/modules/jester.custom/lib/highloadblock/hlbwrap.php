<?php

namespace Jester\Custom\Highloadblock;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\Result;
use Bitrix\Main\Entity\AddResult;
use Bitrix\Main\Entity\DeleteResult;
use Bitrix\Main\Entity\UpdateResult;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\SystemException;

class HlbWrap
{
	/** @var string  */
	protected string $entityName = "";

	/** @var string  */
	protected string $dataClass = "";

	protected const MODULE_NAME  = "highloadblock";

	/**
	 * @param string $entityName
	 */
	public function __construct(string $entityName)
	{
		$this->entityName = $entityName;
	}

	/**
	 * @return string
	 */
	public function getEntityName(): string
	{
		return $this->entityName;
	}

	/**
	 * @return string|null
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getDataClass(): string|null
	{
		if (Loader::includeModule(static::MODULE_NAME)) {
			$arHlBlock = $this->getHlbInfo();
			$entity = HighloadBlockTable::compileEntity($arHlBlock);
			return $entity->getDataClass();
		}

		return null;
	}

	/**
	 * @return int
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getHlbId(): int
	{
		if (Loader::includeModule(static::MODULE_NAME)) {
			$arHlBlock = $this->getHlbInfo();
			return intval($arHlBlock["ID"]);
		}

		return 0;
	}

	/**
	 * @return bool|array
	 * @throws LoaderException
	 * @throws SystemException
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 */
	protected function getHlbInfo(): bool|array
	{
		if (Loader::includeModule(static::MODULE_NAME)) {
			return HighloadBlockTable::getList([
				"filter" => [
					"NAME" => $this->entityName,
				],
			])->fetch();
		}

		return [];
	}

	/**
	 * @param array $parameters
	 * @return Result
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getList(array $parameters = []): Result
	{
		return static::getDataClass()::getList($parameters);
	}

	/**
	 * @param array $data
	 * @return AddResult
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function add(array $data): AddResult
	{
		return static::getDataClass()::add($data);
	}

	/**
	 * @param mixed $primary
	 * @param array $data
	 * @return UpdateResult
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function update(mixed $primary, array $data): UpdateResult
	{
		return static::getDataClass()::update($primary, $data);
	}

	/**
	 * @param mixed $primary
	 * @return DeleteResult
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function delete(mixed $primary): DeleteResult
	{
		return static::getDataClass()::delete($primary);
	}
}