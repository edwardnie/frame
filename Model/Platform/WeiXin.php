<?php

class Platform_WeiXin {
	private static $_singletonObjects = null;
	private $_cacheInstance = null;
	private $_dbInstance = null;
	private $_weiXin = array();

	public function  __construct( $exit = true ) {
		$this->_weiXin = Common::getConfig( 'weiXin' );
		if ( $exit ) {
			$this->_checkSign() || exit( 'check sign error' );
			if ( $_GET['echostr'] ) {
				exit( $_GET['echostr'] );
			}
			$xml = file_get_contents( "php://input" );
			$xml = new SimpleXMLElement( $xml );
			$xml || exit;
			foreach ( $xml as $key => $value ) {
				$this->_data[$key] = strval( $value );
			}
		}
	}

	/**
	 * @param bool $exit
	 * @return null|Platform_WeiXin
	 */
	public static function getInstance( $exit = true ) {
		if ( self::$_singletonObjects == null ) {
			self::$_singletonObjects = new self( $exit );
		}
		return self::$_singletonObjects;
	}

	/**
	 * 微信请求数据
	 * @return string
	 */
	public function request() {
		if ( $this->_data['MsgType'] == 'text' ) {
			$this->_saveMessage( $this->_data['FromUserName'], $this->_data['Content'], $this->_data['CreateTime'] );
			$this->_getCache()->delete( 'wx_msg' );
		}
		return $this->_data;
	}

	/**
	 * 回应微信
	 * @param $content
	 * @param string $type
	 */
	public function response( $content, $type = 'text' ) {
		$this->_data = array( 'ToUserName' => $this->_data['FromUserName'], 'FromUserName' => $this->_data['ToUserName'], 'CreateTime' => time(), 'MsgType' => $type, );
		$this->$type( $content );
		$xml = new SimpleXMLElement( '<xml></xml>' );
		$this->_data2xml( $xml, $this->_data );
		exit( $xml->asXML() );
	}

	/**
	 * 获取access_token
	 * @param $config
	 */
	public function getAccessToken() {
//		$cache = $this->_getCache();
//		$accessToken = $cache->get( 'wei_access_token' );
//		if ( $accessToken ) {
//			return $accessToken;
//		}
		$appId = $this->_weiXin['appId'];
		$secret = $this->_weiXin['appSecret'];
		echo $url = $this->_weiXin['weiXinApi'] . "token?grant_type=client_credential&appid={$appId}&secret={$secret}";
		$response = Helper_Request::curlPost( $url, '', false );
		$response = json_decode( $response, true );
		//$cache->set( 'wei_access_token', $response['access_token'], 0, (integer)$response['expires_in'] );
		return $response['access_token'];
	}

	/**
	 * 获取用户信息 有可能要循环调用，建议不要用单例模式
	 * @param $openid
	 * @return mixed
	 */
	public function getUserInfo( $openid ) {
		$cache = $this->_getCache();
		$userInfo = $cache->get( 'user_info_' . $openid );
		if ( $userInfo ) {
			return $userInfo;
		}
		$accessToken = $this->getAccessToken();
		//TODO 微信的用户名和头像有可能会更改，所以有存库 要考虑后期操作

		$url = $this->_weiXin['weiXinApi'] . "user/info?access_token={$accessToken}&openid={$openid}&lang=zh_CN";
		$response = Helper_Request::curlPost( $url, '', false );
		$response = json_decode( $response, true );
		$cache->set( 'user_info_' . $openid, $response, 0, 86400 );
		return $response;
	}

	public function getMsg() {
		$data = $this->_getCache()->get( 'wx_msg' );
		if ( $data ) {
			return $data;
		}
		$sql = "SELECT * FROM `wx_msg` ORDER BY `time` DESC";
		$data = $this->_getDb()->fetchArray( $sql );
		foreach ( $data as $k => $item ) {
			$userInfo = $this->getUserInfo( $item['openid'] );
			foreach ( $userInfo as $keys => $val ) {
				$data[$k][$keys] = $val;
			}
		}
		if ( is_array( $data ) ) {
			$this->_getCache()->set( 'wx_msg', $data, 0, 86400 );
		}
		return $data;
	}

	private function _saveMessage( $openid, $content, $time ) {
		$sql = "INSERT INTO `wx_msg` (`openid`, `content`, `time`) VALUES ('{$openid}', '{$content}','{$time}')";
		$this->_getDb()->query( $sql );
		//        if ( $this->_getDb()->affectedRows() < 0 ) {
		//            throw new Exception( 'insert error' );
		//        }
		return true;
	}

	private function _getDb() {
		if ( $this->_dbInstance === null ) {
			$this->_dbInstance = Common::getDatabaseEngine();
		}
		return $this->_dbInstance;
	}


	private function _getCache() {
		if ( $this->_cacheInstance === null ) {
			$this->_cacheInstance = Common::getCache();
		}
		return $this->_cacheInstance;
	}

	private function text( $content ) {
		$this->_data['Content'] = $content;
	}

	private function music( $music ) {
		list( $music['Title'], $music['Description'], $music['MusicUrl'], $music['HQMusicUrl'] ) = $music;
		$this->_data['Music'] = $music;
	}

	private function news( $news ) {
		$articles = array();
		foreach ( $news as $key => $value ) {
			list( $articles[$key]['Title'], $articles[$key]['Description'], $articles[$key]['PicUrl'], $articles[$key]['Url'] ) = $value;
			if ( $key >= 9 ) {
				break;
			}
		}
		$this->_data['ArticleCount'] = count( $articles );
		$this->_data['Articles'] = $articles;
	}

	private function _data2xml( $xml, $data, $item = 'item' ) {
		foreach ( $data as $key => $value ) {
			is_numeric( $key ) && $key = $item;
			if ( is_array( $value ) || is_object( $value ) ) {
				$child = $xml->addChild( $key );
				$this->_data2xml( $child, $value, $item );
			} else {
				if ( is_numeric( $value ) ) {
					$child = $xml->addChild( $key, $value );
				} else {
					$child = $xml->addChild( $key );
					$node = dom_import_simplexml( $child );
					$node->appendChild( $node->ownerDocument->createCDATASection( $value ) );
				}
			}
		}
	}


	private function _checkSign() {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$tmpArr = array( $this->_weiXin['weiXinToken'], $timestamp, $nonce );
		sort( $tmpArr, SORT_STRING );
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if ( $tmpStr == $signature ) {
			return true;
		} else {
			return false;
		}
	}


}
