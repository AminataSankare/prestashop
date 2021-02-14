<?php

Configuration::updateValue('PS_WEBSERVICE', 1);
$apiAccess = new WebserviceKey();
$apiAccess->key = 'GENERATE_A_COMPLEX_VALUE_WITH_32_CHARACTERS';
$apiAccess->save();

$permissions = [
    'customers' => ['GET' => 1, 'HEAD' => 1],
    'orders' => ['GET' => 1, 'HEAD' => 1],
  ];
  
WebserviceKey::setPermissionForAccount($apiAccess->id, $permissions);