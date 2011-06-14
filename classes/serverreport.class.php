<?php
require_once('report.interface.php');

class ServerReport implements IReport{
    private $url;
    
    
    function getUrl(){
        return $this->url;
    }
}
?>
