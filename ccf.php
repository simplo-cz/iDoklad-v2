<?php
$cid = 'Your client ID from user settings';
$cs = 'Your client secret from user settings';

include_once 'v2/iDoklad.php';
            
use simplo\iDoklad\iDoklad;
use simplo\iDoklad\auth\iDokladCredentials;
use simplo\iDoklad\iDokladException;

try{
    $iDoklad = new iDoklad($cid, $cs, '');
    $iDoklad->setCredentialsCallback(function($credentials){
        file_put_contents('credentials.json', $credentials->toJson());
    });
    if(!file_exists('credentials.json') && empty($_GET['code'])){
        $iDoklad->authCCF();
    } 
    $credentials = new iDokladCredentials(file_get_contents('credentials.json'), true);
    $iDoklad->setCredentials($credentials);
    $request = new \simplo\iDoklad\request\iDokladRequest('IssuedInvoices');
    $response = $iDoklad->sendRequest($request);
    echo '<pre>';
    print_r($response);
    echo '</pre>';
} catch (iDokladException $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
}