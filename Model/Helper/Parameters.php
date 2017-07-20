<?php

class Helper_Parameters {

	public static function generateCallbackParametersSig($callbackParameters) {
		ksort($callbackParameters);
		$paramsJoined = '';
		foreach ($callbackParameters as $key => $value) {
			$paramsJoined .= $key . $value;
		}
		$paramsJoined .= Common::getConfig('callbackDataHashKey');
		return md5($paramsJoined);
	}

}

?>