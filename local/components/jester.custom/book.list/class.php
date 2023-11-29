<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\{
	ArgumentException,
	Engine\Contract\Controllerable,
	Engine\CurrentUser,
	ErrorCollection,
	LoaderException,
	ObjectPropertyException,
	SystemException,
	UserField\Types\EnumType,
	UserFieldTable,
	Error,
	Engine\ActionFilter
};

use Jester\Custom\{
	Highloadblock\HlbWrap,
	Log\Logger
};

class BookList extends CBitrixComponent implements Controllerable
{
	protected const BOOKS_HLB_NAME = "Books";
	protected const BOOKS_RATING_HLB_NAME = "BookRatings";

	protected Logger $logger;

	protected ErrorCollection $errorCollection;

	/**
	 * @return array[][]
	 */
	public function configureActions(): array
	{
		return [
			'rateBook' => [
				'prefilters' => [
					new ActionFilter\Authentication(),
				],
			],
			'recalculateAverageBookRating' => [
				'prefilters' => [],
			]
		];
	}

	/**
	 * Проставление оценки на книгу (добавление записи о выставленной оценке в Hlb BookRatings)
	 *
	 * Оценка удаляется при условии, что нажатая пользователем кнопка была ранее выставленной им оценкой
	 *
	 * Удаление оценки можно вынести в отдельный метод и триггерить событием по нажатию уже активной кнопки
	 * однако для экономии времени удаление оценки было включено в этот метод, тк тоже влияет на формирование итогового рейтинга
	 *
	 * @param int $bookId
	 * @param int $mark
	 * @return void
	 * @throws SystemException
	 */
	public function rateBookAction(int $bookId, int $mark): void
	{
		global $USER;

		if (!$USER->IsAuthorized()) {
			throw new SystemException("Session probably expired!");
		}

		$userId = CurrentUser::get()->getId();

		$hlbBookRatings = new HlbWrap(static::BOOKS_RATING_HLB_NAME);

		try {
			$userBookMark = $hlbBookRatings->getList([
				"select" => ["ID", "UF_MARK"],
				"filter" => [
					"UF_BOOK_ID" => $bookId,
					"UF_USER_ID" => $userId
				]
			])->fetch();

			if ($userBookMark) {
				$hlbBookRatings->delete($userBookMark["ID"]);
				if ($userBookMark["UF_MARK"] == $mark) {
					return;
				}
			}

			$hlbBookRatings->add([
				"UF_BOOK_ID" => $bookId,
				"UF_USER_ID" => $userId,
				"UF_MARK" => $mark
			]);
		} catch (ObjectPropertyException|ArgumentException|LoaderException|SystemException $e) {
			$this->errorCollection->setError(new Error($e->getMessage()));
		}
	}

	/**
	 * Пересчёт средней оценки книги после (обновление поля "Рейтинг" для выбранной книги в Hlb "Books")
	 *
	 * Вызывается после проставления/удаления очередной оценки для некоторой книги
	 *
	 * Была идея делать пересчёт рейтинга для всех книг, но мне показалось довольно неочевидным для пользователя, что
	 * при проставлении оценки для одной книги рейтинг меняется у всех
	 *
	 * @param int $bookId
	 * @return float
	 */
	public function recalculateAverageBookRatingAction(int $bookId): float
	{
		$bookAverageRating = 0;

		try {
			$hlbBookRatings = new HlbWrap(static::BOOKS_RATING_HLB_NAME);
			$bookMarks = $hlbBookRatings->getList([
				"select" => ["UF_BOOK_ID", "UF_MARK"],
				"filter" => [
					"UF_BOOK_ID" => $bookId
				]
			])->fetchAll();

			$booksMarks = [];
			foreach ($bookMarks as $bookMark) {
				$booksMarks[$bookMark["UF_BOOK_ID"]][] = $bookMark["UF_MARK"];
			}

			$hlbBooks = new HlbWrap(static::BOOKS_HLB_NAME);

			if (!empty($booksMarks[$bookId])) {
				$bookAverageRating = array_sum($booksMarks[$bookId]) / count($booksMarks[$bookId]);
				$hlbBooks->update($bookId, ["UF_RATING" => $bookAverageRating]);
			}
		} catch (ObjectPropertyException|ArgumentException|LoaderException|SystemException $e) {
			$this->errorCollection->setError(new Error($e->getMessage()));
		}

		return $bookAverageRating;
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
	 * Метод получения информации о книгах
	 *
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
	 * Метод получения диапазона оценок из пользовательского поля типа "Список" для формирования группы кнопок
	 * в шаблоне компонента
	 *
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
	 * Метод получения оценок текущего авторизованного пользователя
	 *
	 * @return array
	 * @throws ArgumentException
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getUserMarks(): array
	{
		global $USER;

		if (!$USER->IsAuthorized()) {
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
	 * @param $arParams
	 * @return void
	 */
	public function onPrepareComponentParams($arParams): void
	{
		$this->errorCollection = new ErrorCollection();
	}

	/**
	 * @return Error[]
	 */
	public function getErrors(): array
	{
		return $this->errorCollection->toArray();
	}

	/**
	 * @param string $code
	 * @return Error
	 */
	public function getErrorByCode(string $code): Error
	{
		return $this->errorCollection->getErrorByCode($code);
	}

	/**
	 * @return void
	 */
	public function executeComponent(): void
	{
		CJSCore::Init(["ajax", "jquery"]);
		$this->initLogger();
		$this->fillArResult();
		$this->includeComponentTemplate();
	}
}
