<?php

class Helper_VerifyReceipt {

    /**
     * appStore验证
     * @param $receipt
     * @param bool $isSandbox
     * @return array
     * @throws Exception
     */
    public static function getReceiptData( $receipt, $isSandbox = true ) {
        $isSandbox = Common::getConfig( 'applePaymentSandbox' );
        if ( $isSandbox ) {
            $endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';
        } else {
            $endpoint = 'https://buy.itunes.apple.com/verifyReceipt';
        }

        $postData = json_encode(
            array( 'receipt-data' => $receipt )
        );
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
        );
        $response = self::_curlPost( $endpoint, $headers, $postData );
        $data = json_decode( $response );
        if ( !is_object( $data ) ) {
            throw new Exception( 'Invalid response data' );
        }

        if ( !isset( $data->status ) || $data->status != 0 ) {
            throw new Exception( 'Invalid receipt' );
        }
        return array(
            'quantity' => $data->receipt->quantity,
            'product_id' => $data->receipt->product_id,
            'transaction_id' => $data->receipt->transaction_id,
            'purchase_date' => $data->receipt->purchase_date,
            'app_item_id' => $data->receipt->app_item_id,
            'bid' => $data->receipt->bid,
            'bvrs' => $data->receipt->bvrs
        );
    }


    /**
     * payPal购买验证
     * @param string $payKey
     * @param bool $isSandbox
     * @throws Exception
     * @return mix
     */
    public static function AdaptivePayments( $payKey, $isSandbox = true ) {
        $isSandbox = Common::getConfig( 'payPalPaymentSandbox' );
        if ( $isSandbox ) {
            $endpoint = 'https://svcs.sandbox.paypal.com/AdaptivePayments/PaymentDetails';
        } else {
            $endpoint = 'https://svcs.paypal.com/AdaptivePayments/PaymentDetails';
        }
        if ( empty( $payKey ) ) {
            throw new Exception( 'Invalid pay_key' );
        }
        $postData = "payKey={$payKey}&requestEnvelope.errorLanguage=en_US";
        $info = Common::getConfig( 'payPalConfig' );
        $headers = array(
            "X-PAYPAL-SECURITY-USERID: {$info['userId']}",
            "X-PAYPAL-SECURITY-PASSWORD: {$info['password']}",
            "X-PAYPAL-SECURITY-SIGNATURE: {$info['signature']}",
            "X-PAYPAL-REQUEST-DATA-FORMAT: NV",
            "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
            "X-PAYPAL-APPLICATION-ID: {$info['appId']}"
        );
        $response = self::_curlPost( $endpoint, $headers, $postData );
        $data = json_decode( $response, true );
        return $data;
    }

    /**
     * creditCard  购买验证
     * @param $paymentId
     * @throws Exception
     * @return array()
     */
    public static function restApi( $paymentId ) {
        if ( empty( $paymentId ) ) {
            throw new Exception( 'Invalid payment_id' );
        }
        $isSandbox = Common::getConfig( 'creditCardPaymentSandbox' );
        $accessToken = self::_getaccessToken( $isSandbox );
        if ( $isSandbox ) {
            $endpoint = "https://api.sandbox.paypal.com/v1/payments/payment/{$paymentId}";
        } else {
            $endpoint = "https://api.paypal.com/v1/payments/payment/{$paymentId}";
        }
        $headers = array(
            "Content-Type:application/json",
            "Authorization: {$accessToken}",
        );

        $response = self::_curlPost( $endpoint, $headers, '' );
        $data = json_decode( $response );
        return $data;
    }

    /**
     * Google play 购支付买验证
     * @param $signtureData
     * @return int
     */
    public static function googlePlayPayment($signtureData) {
        preg_match( '/\{(.*?)\}/', $signtureData, $match );
        $info = json_decode( $match[0], true );
        $signture = str_replace( ' ', '+', $signtureData );
        $public_key = Common::getConfig( 'googlePlayPublicKey' );
        $openSslFriendlyKey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split( $public_key, 64, "\n" ) . "-----END PUBLIC KEY-----";
        $public_key_handle = openssl_pkey_get_public( $openSslFriendlyKey );
        $result = openssl_verify( $match[0], base64_decode( $signture ), $public_key_handle );
        return $result;
    }

    /**
     * curl 请求
     * @param $endpoint
     * @param $headers
     * @param $postData
     * @param $userInfo
     * @return mixed $response
     * @throws Exception
     */
    private static function _curlPost( $endpoint, $headers, $postData, $userInfo = '' ) {
        $ch = curl_init( $endpoint );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
        if ( $userInfo ) {
            curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $userInfo );
        }
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false ); //solve SSL certificate problem

        $response = curl_exec( $ch );
        $errno = curl_errno( $ch );
        $errmsg = curl_error( $ch );
        curl_close( $ch );
        if ( $errno != 0 ) {
            throw new Exception( $errmsg, $errno );
        }
        return $response;
    }

    private static function _getaccessToken( $isSandbox = true ) {
        if ( $isSandbox ) {
            $endpoint = 'https://api.sandbox.paypal.com/v1/oauth2/token';
        } else {
            $endpoint = 'https://api.paypal.com/v1/oauth2/token';
        }
        $info = Common::getConfig( 'payPalConfig' );
        $userInfo = $info['userId'] . ':' . $info['password'];
        $headers = array(
            "Accept: application/json",
            "Accept-Language: en_US",
        );
        $postData = 'grant_type=client_credentials';
        $response = self::_curlPost( $endpoint, $headers, $postData, $userInfo );
        $data = json_decode( $response );
        return $data['token_type'] . ' ' . $data['access_token'];
    }

}
