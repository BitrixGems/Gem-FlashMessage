<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ivariable
 * Date: 06.03.11
 * Time: 17:08
 *
 */
class BitrixGem_FlashMessage extends BaseBitrixGem {

	protected $aGemInfo = array(
		'TYPE'			=> 'functional',
		'GEM'			=> 'FlashMessage',
		'AUTHOR'		=> 'Александр Клименков',
		'AUTHOR_LINK'	=> 'https://github.com/anyx',
		'DATE'			=> '09.10.2012',
		'VERSION'		=> '0.2',
		'NAME' 			=> 'FlashMessage',
		'DESCRIPTION' 	=> "Создание/управление/отображение флеш-сообщений в публичной и административной частях сайта (сообщения, которые должны отобразиться пользователю только один раз, например \"Модуль успешно установлен\", \"Форма успешно заполнена и отправлена и т.п.\".) В состав гема входит компонент для отображения сообщений в публичной части сайта.",
		'DESCRIPTION_FULL' => '',
		'CHANGELOG'		=> 'Рефакторинг',
		'REQUIRED_MIN_MODULE_VERSION' => '1.2.0',
	);

	protected $aMessages = array(
		'ADMIN' 	=> array(),
		'PUBLIC' 	=> array(),
	);

	const SESSION_CONTAINER_NAME = 'BitrixGem_FlashMessage_MESSAGES';

	/**
	 *
	 */
	public function event_main_OnProlog_initMessages() {
		if ( !empty($_SESSION[ self::SESSION_CONTAINER_NAME ]) && is_array($_SESSION[ self::SESSION_CONTAINER_NAME ]) ) {
			$this->aMessages = $_SESSION[ self::SESSION_CONTAINER_NAME ];
		}
		$_SESSION[ self::SESSION_CONTAINER_NAME ] = array();
	}

	public function event_main_OnProlog_setMessager() {
		if( defined('ADMIN_SECTION') ) {
			global $adminChain;
			$adminChain = new CiVFlashMessageGem_AdminChain_Decorator( $adminChain, $this );
		}
	}

	public function installGem(){
		CopyDirFiles( $this->getGemFolder().'/component/', $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/bitrixgems/flashmessage/", true, true);
		return parent::installGem();
	}

	public function unInstallGem(){
		DeleteDirFilesEx( "/bitrix/components/bitrixgems/flashmessage/" );
		return parent::unInstallGem();
	}

	/**
	 * @param $sMessage
	 * @param string $sType ERROR|OK
	 * @param string $sAdmin ADMIN|PUBLIC|ALL
	 * @return void
	 */
	public function addFlash( $sMessage, $sType = 'OK', $sArea = 'ADMIN' ){
		$this->aMessages[ $sArea ][] = array(
			'message' 	=> $sMessage,
			'area'		=> $sArea,
			'type'		=> $sType,
		);

		$_SESSION[ self::SESSION_CONTAINER_NAME ] = $this->aMessages;
	}

	public function getFlashQueue(){
		return $this->aMessages;
	}

	public function setFlashQueue( $aMessages ){
		$this->aMessages = $aMessages;
	}
	
	public function getFlash( $sArea = 'ADMIN' ){
		return $this->aMessages[ $sArea ];
	}
}


class CiVFlashMessageGem_AdminChain_Decorator{

	protected $oAdminChain;
	protected $oGem;

	public function __construct( $oAdminChain, $oGem ){
		$this->oAdminChain = $oAdminChain;
		$this->oGem = $oGem;
	}

	public function Show(){
		$mResult = $this->oAdminChain->Show();
		$aMessages = $this->oGem->getFlash('ADMIN');
		if( !empty( $aMessages ) ){
			foreach( $aMessages as $aMessage ){
				echo '<div style="margin-left:10px">';
				CAdminMessage::ShowMessage(
					array(
						'TYPE'=> $aMessage['type'] ,
						'MESSAGE' => $aMessage['message'],
						'HTML' => true
					)
				);
				echo '</div>';
			}
		}
		return $mResult;
	}

	public function __call($method, $args) {
		return call_user_func_array(
			array($this->oAdminChain, $method),
			$args
		);
	}

	public function __get($sParamName) {
		return $this->oAdminChain->{$sParamName};
	}

	public function __set($sParamName, $mValue) {
		return $this->oAdminChain->{$sParamName} = $mValue;
	}
}

if( !function_exists('BG_AddFlashMessage') ){
	function BG_AddFlashMessage( $sMessage, $sType = 'OK', $sArea = 'ADMIN' ){
		BitrixGems::getGem('FlashMessage')->addFlash( $sMessage, $sType, $sArea );
	}
}