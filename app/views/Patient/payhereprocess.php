<?php

$amount = 3000;
$merchant_id = "1228628";
$order_id = uniqid();
$merchant_secret = "MzkxMDUxMDYzNzIxMTExNDMyOTMyMDQ1NTQ0ODU3MzM1MTk3MDU4NA==";
$currency = "LKR";

$hash = strtoupper(
    md5(
        $merchant_id . 
        $order_id . 
        number_format($amount, 2, '.', '') . 
        $currency .  
        strtoupper(md5($merchant_secret)) 
    ) 
);

$array = [];

$array["first_name"] = "Amrah";
$array["last_name"] = "Slamath";
$array["email"] = "amrah@gmail.com";
$array["phone"] = "07712345672";
$array["address"] = "No. 123, ABC road";
$array["city"] = "Colombo";
$array["country"] = "Sri Lanka";
$array["delivery_address"] = "No. 173, ABD road";
$array["delivery_city"] = "Jaffna";
$array["delivery_country"] = "Sri Lanka";
$array["items"] = "Appointment";
$array["amount"] = $amount;
$array["merchant_id"] = $merchant_id;
$array["order_id"] = $order_id;
$array["merchant_secret"] = $merchant_secret;
$array["currency"] = $currency;
$array["hash"] = $hash;

$jsonObj = json_encode($array); 


echo $jsonObj;

?>