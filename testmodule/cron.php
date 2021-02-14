<?php

include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/testmodule.php');
if (Tools::getValue('token') != Tools::encrypt(Configuration::get('PS_SHOP_NAME'))) {
    die('Error: Invalid Token');
}

$day_before = Configuration::get('TESTMODULE_ACCOUNT_DAY_BEFORE');
$duration = Configuration::get('TESTMODULE_ACCOUNT_OFFER_DURATION');
$sendMail = new Testmodule();
$sendMail->userToSendMail($day_before, $duration);
echo 'Cron executed successfully';


require_once dirname(__FILE__) . '/../../index.php';
