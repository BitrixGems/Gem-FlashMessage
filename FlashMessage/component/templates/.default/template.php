<?
if ( empty( $arResult['MESSAGES'] ) ) {
	return false;
}?>

<?foreach ( $arResult['MESSAGES'] as $aMessage ):?>
<div class='BG_flashMessages BG_flashMessages_<?=$aMessage['type']?>'>
	<?=$aMessage['message']?>
</div>
<?endforeach?>
