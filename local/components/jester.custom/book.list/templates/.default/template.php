<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $templateFolder
 * @var array $arResult
 */

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;

//Для экономии времени при выполнении тестового задания. Корректным будет размесить подключения в шапке шаблона сайта

$asset = Asset::getInstance();
$asset->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css');
$asset->addJs('https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js');
?>

<div class="container text-left" id="bookList">
	<?php foreach ($arResult["ITEMS"] as $bookId => $book): ?>
        <div class="row">
            <div class="col-12 p-0">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?= $book["PICTURE_PATH"] ?>" class="img-fluid rounded-start">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h3 class="card-title"><?= $book["UF_NAME"] ?></h3>
                                <p class="card-text">
                                    <small class="text-body-secondary"><?= $book["UF_AUTHOR"] ?></small>
                                </p>
                                <p class="card-text" id="book-<?= $bookId ?>-rating">
                                    <?= Loc::getMessage("JESTER.CUSTOM_RATING_TITLE") ?>
                                    <strong><?= $book["UF_RATING"] ?></strong>
                                </p>
                                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Rating panel">
                                    <form name="book-<?= $bookId ?>-rating-form">
                                        <input type="text" name="book-id" value="<?= $bookId ?>" hidden="hidden">
                                        <div class="btn-group" role="group" aria-label="Rating buttons group">
											<?php foreach ($arResult["MARKS"] as $mark): ?>
                                                <input type="button" class="btn btn-outline-success <?= $arResult["USER_MARKS"][$bookId] == $mark ? "active" : "" ?>"
                                                       value="<?= $mark ?>"
                                                       id="book-<?= $bookId ?>-mark-<?= $mark ?>"/>
											<?php endforeach; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endforeach; ?>
</div>