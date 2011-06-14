<?php

//URLREPORT
//Builds a report on the URL, including whois information, length
//and other information

require_once('classes/report.interface.php');

class UrlReport implements IReport{

    //Class variables
        private $url; //The page url
    
    function __construct($url){
        //Set the URL
            $this->url = $url;
    }

//==============================================================================

    function getUrl(){
        return $this->url;
    }

//==============================================================================

    //Returns the domain name
    function getDomain(){
        $urlinfo = parse_url($this->url);
	return( $urlinfo['host'] );
    }

    //Returns the depth of the requested page
    function pageDepth(){
        //Remove the trailing slash
            if (substr($this->url,-1) == "/")
                $this->url = substr($this->url,0,-1);

        //Retrieve the path
        $urlinfo = parse_url($this->url);
            if (isset($urlinfo['path']))
                $path = explode("/",dirname($urlinfo['path']));//Explode the path
            else
                return 0; //Return 0 if there is no path (root)

        //Return the length of the exploded path
            return( count($path)-1 );
    }



}
?>
