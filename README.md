PHP-PayPal-NVP
==============

This provides very simple access to PayPal's NVP API.

PayPal NVP API: https://developer.paypal.com/docs/classic/api/NVPAPIOverview/

Use:
```PHP
use delmicio\PayPal;

$paypal = new PayPal(
    'username-facilitator_api1.site.com', // API USER
    'HTPZYVF3YXFYAXXX', // API PWD (password)
    'AFcWxV21C7fd0v3bYYYRCpSSRl31An.zpkEbPtDU3TXXXXXXXXXX', // API SIGNATURE
    true // SANDBOX API
);

$STARTDATE = date(DATE_ATOM, strtotime('-1 year'));
$fields = array(
    "STARTDATE" => $STARTDATE
);

// https://developer.paypal.com/docs/classic/api/merchant/TransactionSearch_API_Operation_NVP/
$result = $paypal->TransactionSearch($fields);

echo '<pre>'.var_export($result, true).'</pre>'; die();
```
