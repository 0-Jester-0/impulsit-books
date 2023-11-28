<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Книги");
?>

<?php $APPLICATION->IncludeComponent(
	"jester.custom:book.list",
	".default",
	[]
);?>


<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>