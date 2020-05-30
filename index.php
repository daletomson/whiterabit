<?php
    include($_SERVER['DOCUMENT_ROOT'].'/controller/defaultController.php');
    $uri = explode('?',$_SERVER['REQUEST_URI']);

    if($uri[0] == '/')
        $uri = 'home';
    else
        $uri = str_replace('/' , '' , $uri[0]);

    $controller = new defaultController;
    
    try {
      echo $controller->$uri();
    } catch (\Throwable $th) {
        throw $th;
    }

?>