<?php

//URLREPORT
//Builds a report on the URL, including whois information, length
//and other information

require_once('classes/report.interface.php');
require_once('classes/whois/whois.main.php');//Whois information

class UrlReport implements IReport{

    //Class variables
        private $url;   //The page url
        private $whois; //The WHOIS information
    
    function __construct($url){
        //Set the URL
            $this->url = $url;
    }

//==============================================================================

    function getUrl(){
        return $this->url;
    }

    function getWhois(){
        //Fetch the WHOIS data if the WHOIS variable is currently empty
        if ( !isset($this->whois) ){
            $whois = new Whois();
            $text = $whois->Lookup($this->getDomain());
            $this->whois = implode("\r\n",$text['rawdata']);
            return $text;
        }
        //Return the whois
        return $this->whois;
    }

//==============================================================================

    //Returns the domain name
    function getDomain(){
        $urlinfo = parse_url($this->getUrl());
	return( $urlinfo['host'] );
    }


    //Returns the depth of the requested page
    function pageDepth(){
        //Remove the trailing slash
            if (substr($this->getUrl(),-1) == "/")
                $this->url = substr($this->getUrl(),0,-1);

        //Retrieve the path
        $urlinfo = parse_url($this->getUrl());
            if (isset($urlinfo['path']))
                $path = explode("/",dirname($urlinfo['path']));//Explode the path
            else
                return 0; //Return 0 if there is no path (root)

        //Return the length of the exploded path
            return( count($path)-1 );
    }



}
?>
