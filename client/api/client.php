<?php

date_default_timezone_set('Asia/Calcutta');

$config = include('config.php');
require_once('HttpRequest.php');


try
{
    $available_methods = $config['available_methods'];
   
    if(!array_key_exists($_REQUEST['action'], $available_methods))
    {
        throw new Exception('Invalid Request');
    }
    //print_r($_REQUEST);
    $hr = new HttpRequest();
    $hr->setRequestUrl($config['host']);
    $hr->setRequestMethod($available_methods[$_REQUEST['action']]['method']);
    $hr->setRequestData($_REQUEST);
    $hr->execute();
    $content = $hr->getResponse();
    
    //$content = call_user_func(array($obj, $_REQUEST['action']), $_REQUEST);
    header('content-type: application/json');
    echo $content;
}
catch(Exception $e)
{
    echo $e->getMessage();    
}


?>
