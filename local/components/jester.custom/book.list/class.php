<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserField\Types\EnumType;
use Bitrix\Main\UserFieldTable;
use Jester\Custom\Highloadblock\HlbWrap;
use Jester\Custom\Log\Logger;

class BookList extends CBitrixComponent implements Controllerable
{
	protected const BOOKS_HLB_NAME = "Books";
	protected const BOOKS_RATING_HLB_NAME = "BookRatings";

	protected Logger $logger;

	public function configureActions(): array
	{
		return [
			'rateBook' => [
				'prefilters' => [],
			],
			'recalculateAverageBookRating' => [
				'prefilters' => [],
			]
		];
	}

	public function rateBookAction(int $bookId, int $mark): void
	{
		if (!check_bitrix_sessid()) {
			throw new SystemException("Session probably expired!");
		}

		$userId = CurrentUser::get()->getId();

		$hlbBookRatings = new HlbWrap(static::BOOKS_RATING_HLB_NAME);

		try {
			$userBookMark = $hlbBookRatings->getList([
				"select" => ["ID"],
				"filter" => [
					"UF_BOOK_ID" => $bookId,
					"UF_USER_ID" => $userId
				]
			])->fetch();

			if ($userBookMark) {
				$hlbBookRatings->delete($userBookMark["ID"]);
			}

			$hlbBookRatings->add([
				"UF_BOOK_ID" => $bookId,
				"UF_USER_ID" => $userId,
				"UF_MARK" => $mark
			]);
		} catch (ObjectPropertyException|ArgumentException|LoaderException|SystemException $e) {
			$this->logger->error($e->getMessage());
		}
	}

	public function recalculateAverageBookRatingAction(): void
	{
		$books = $this->getBooks();
		$booksIDs = array_column($books, "ID");

		try {
			$hlbBookRatings = new HlbWrap(static::BOOKS_RATING_HLB_NAME);
			$bookRatingsResult = $hlbBookRatings->getList([
				"select" => ["UF_BOOK_ID", "UF_MARK"],
				"filter" => [
					"@UF_BOOK_ID" => $booksIDs
				]
			])->fetchAll();

			$booksMarks = [];
			foreach ($bookRatingsResult as $bookMark) {
				$booksMarks[$bookMark["UF_BOOK_ID"]][] = $bookMark["UF_MARK"];
			}

			$hlbBooks = new HlbWrap(static::BOOKS_HLB_NAME);
			foreach ($booksIDs as $bookID) {
				$bookAverageRating = array_sum($booksMarks[$bookID]) / count($booksMarks[$bookID]);
				$hlbBooks->update($bookID, ["UF_RATING" => $bookAverageRating]);

				$this->arResult["ITEMS"][$bookID]["UF_RATING"] = $bookAverageRating;
			}
		} catch (ObjectPropertyException|ArgumentException|LoaderException|SystemException $e) {
			$this->logger->error($e->getMessage());
		}
	}

	/**
	 * @return void
	 */
	public function initLogger(): void
	{
		$this->logger = new Logger();
	}

	/**
	 * @return void
	 */
	public function fillArResult(): void
	{
		try {
			$this->arResult["ITEMS"] = $this->getBooks();
			$this->arResult["MARKS"] = $this->getMarks();
			$this->arResult["USER_MARKS"] = $this->getUserMarks();
		} catch (ObjectPropertyException|ArgumentException|LoaderException|SystemException $e) {
			$this->logger->error($e->getMessage());
		}

	}

	/**
	 * @return array
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getBooks(): array
	{
		$books = [];
		$hlbBooks = new HlbWrap(static::BOOKS_HLB_NAME);

		$booksResult = $hlbBooks->getList([
			"select" => ["ID", "UF_NAME", "UF_AUTHOR", "UF_PICTURE", "UF_RATING"],
		])->fetchAll();

		foreach ($booksResult as $book) {
			$books[$book["ID"]] = $book;
		}


		return $books;
	}

	/**
	 * @return array
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getMarks(): array
	{
		$userFieldEnumItems = [];
		$hlbBookRatings = new HlbWrap(static::BOOKS_RATING_HLB_NAME);


		$hlbBookRatingsId = $hlbBookRatings->getHlbId();

		$userFieldId = UserFieldTable::getList([
			"select" => ["ID"],
			"filter" => [
				"=FIELD_NAME" => "UF_MARK",
				"ENTITY_ID" => "HLBLOCK_{$hlbBookRatingsId}"
			]
		])->fetch()["ID"];

		$userFieldEnumResult = EnumType::getList([
			"select" => ["VALUE"],
			"filter" => ["USER_FIELD_ID" => $userFieldId]
		]);

		while ($userFieldEnum = $userFieldEnumResult->Fetch()) {
			$userFieldEnumItems[] = $userFieldEnum["VALUE"];
		}

		return $userFieldEnumItems;
	}

	/**
	 * @return array
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getUserMarks(): array
	{
		if (!check_bitrix_sessid()) {
			throw new SystemException("Session probably expired!");
		}

		$userMarks = [];
		$userId = CurrentUser::get()->getId();
		$userMarksResult = (new HlbWrap(static::BOOKS_RATING_HLB_NAME))->getList([
			"select" => ["UF_BOOK_ID", "UF_MARK"],
			"filter" => ["UF_USER_ID" => $userId]
		])->fetchAll();

		foreach ($userMarksResult as $userMark) {
			$userMarks[$userMark["UF_BOOK_ID"]] = $userMark["UF_MARK"];
		}

		return $userMarks;
	}

	/**
	 * @return void
	 * @throws SystemException
	 */
	public function executeComponent(): void
	{
		$this->initLogger();
		$this->fillArResult();
		$this->includeComponentTemplate();
	}
}
