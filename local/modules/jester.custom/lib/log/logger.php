<?php

namespace Jester\Custom\Log;

use Bitrix\Main\Application;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonoLogger;
use Monolog\Level;
use Monolog\Handler\FirePHPHandler;

class Logger
{
	protected MonoLogger $logger;

	public function __construct()
	{
		$this->logger = new MonoLogger("jester.custom");
	}

	/**
	 * @param string $error
	 * @param array $additionalData
	 * @return void
	 */
	public function error(string $error, array $additionalData = []): void
	{
		$this->logger->pushHandler(
			new StreamHandler(
				Application::getDocumentRoot() . "/logs/errors/jester_custom.log",
				Level::Error)
		);

		$this->logger->error($error, $additionalData);
	}

	/**
	 * @param string $message
	 * @param array $additionalData
	 * @return void
	 */
	public function info(string $message, array $additionalData = []): void
	{
		$this->logger->pushHandler(
			new StreamHandler(
				Application::getDocumentRoot() . "/logs/info/jester_custom.log",
				Level::Info)
		);

		$this->info($message, $additionalData);
	}
}