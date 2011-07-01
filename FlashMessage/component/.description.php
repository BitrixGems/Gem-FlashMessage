<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
	"NAME" => GetMessage("BG_FLASHMESSAGE_COMPONENT"),
	"DESCRIPTION" => GetMessage("BG_FLASHMESSAGE_DESCRIPTION"),
	"ICON" => "/images/cat_detail.gif",
	"COMPLEX" => "N",
	"PATH" => array(
		"ID" => "utility",
		"CHILD" => array(
			"ID" => "flashmessage",
			"NAME" => GetMessage("BG_FLASHMESSAGE")
		)
	),
);
?>