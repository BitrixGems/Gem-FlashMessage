<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ( !CModule::IncludeModule( 'iv.bitrixgems' ) ) {
	ShowError( GetMessage( 'BG_NOT_INSTALLED' ) );
}

$oGem = BitrixGems::getGem( 'FlashMessage' );

if ( !empty( $oGem ) ) {
	$arResult['MESSAGES'] = $oGem->getFlash( !empty( $arParams['AREA'] ) ? $arParams['AREA'] : 'PUBLIC' );

	$this->includeComponentTemplate();
}

