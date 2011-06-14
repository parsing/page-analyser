<?php

//SERVERREPORT
//Creates a report on server-side information that is not related
//to the page's contents


require_once('report.interface.php');
require_once('urlreport.class.php');

class ServerReport implements IReport{
    private $url;
    private $source;

    function __construct($url,$source){
        $this->url = $url;//Page URL
        $this->source = $source;//Page source
    }

//==============================================================================

    function getUrl(){
        return $this->url;
    }

    function getUrlReport(){
        return $this->urlReport;
    }

    public function getHeaders(){
        if (!isset($this->headers)){
            $this->headers = get_headers($this->url,true);
        }
        return $this->headers;
    }

//==============================================================================

    //Returns true if the server uses caching
    function cachingEnabled(){
        $headers = $this->getHeaders();
        if (isset($headers['Expires']) || isset($headers['Cache-Control'])){
            return true;
        }
        else
            return false;
    }

    //Returns the page's scripting language (i.e. PHP)
    function poweredBy(){
        $headers = $this->getHeaders();
        if (isset($headers['X-Powered-By'])){
            if (is_array($headers['X-Powered-By']))
                return array_pop($headers['X-Powered-By']);
            else
                return $headers['X-Powered-By'];
        }
        else
            return '';
    }

    //Returns the server type (i.e. Apache)
    function serverType(){
        $headers = $this->getHeaders();
        if (isset($headers['Server'])){
            if (is_array($headers['Server']))
                return array_pop($headers['Server']);
            else
                return $headers['Server'];
        }
        else
            return '';
    }


}
?>
