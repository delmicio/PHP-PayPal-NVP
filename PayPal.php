<?php
namespace delmicio;

class PayPal {

    private $USER;
    private $PWD;
    private $SIGNATURE;
    private $VERSION;
    private $SANDBOX;
    private $ENDPOINT;

    public function __construct(
        $USER, 
        $PWD, 
        $SIGNATURE,  
        $SANDBOX = false,
        $VERSION = "204.0"
    ) {

        $this->VERSION   = $VERSION;
        $this->USER      = $USER;
        $this->PWD       = $PWD;
        $this->SIGNATURE = $SIGNATURE;
        $this->VERSION   = $VERSION;
        $this->SANDBOX   = $SANDBOX;

        $this->ENDPOINT = 'https://api-3t.paypal.com/nvp';
        if ($this->SANDBOX) {
            $this->ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
        }

    }

    private function encodeNvpString($fields) {
        $nvpstr = "";
        foreach ($fields as $key=>$value) {
            $nvpstr .= sprintf("%s=%s&", urlencode(strtoupper($key)), urlencode($value));
        }
        return $nvpstr;
    }

    private function decodeNvpString($nvpstr) {
        $pairs  = explode("&", $nvpstr);
        $fields = array();
        foreach ($pairs as $pair) {
            $items = explode("=", $pair);
            $fields[strtoupper(urldecode($items[0]))] = urldecode($items[1]);
        }
        return $fields;
    }

    private function nvpAction($method, $requestFields) {

        $requestFields["METHOD"]    = $method;
        $requestFields["USER"]      = $this->USER;
        $requestFields["PWD"]       = $this->PWD;
        $requestFields["SIGNATURE"] = $this->SIGNATURE;
        $requestFields["VERSION"]   = $this->VERSION;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->ENDPOINT);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->encodeNvpString($requestFields));

        $responseFields = $this->decodeNvpString(curl_exec($ch));
        curl_close($ch);

        return $responseFields;
    }

    public function __call($method, $args) {
        return $this->nvpAction($method, $args[0]);
    }

}

?>
