<?php

//SOURCE
//Fetches a file and returns its contents.
//Source objects are used to fetch HTML files

require_once('classes/report.interface.php');

class Source implements IReport{

    
    //Regex constants
        private $regexUrl = '/^(http|https|ftp)\:\/\/([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$/i';
    //CURL constants
        private $curloptions = array(
            CURLOPT_RETURNTRANSFER => true,     // return the web page
            CURLOPT_HEADER         => false,     // return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_FORBID_REUSE   => true,
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 60,       // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            );

    //Object variables
        private $url;    //The URL to be fetched
        private $source; //The source of the page


    function __construct($url){
        //Set the URL to fetch
            $this->setUrl($url);
        
        //Try to fetch the source, rethrow the eventual error
            try{
                $this->source = $this->fetch();
            }
            catch(Exception $e){
                throw $e;
            }
    }

    function __toString(){
        return (String)$this->getSource();
    }

//==============================================================================

    function getUrl(){
        return $this->url;
    }
    
    function setUrl($url){
        //Make sure this is a valid URL
        if ( preg_match($this->regexUrl,$url) == 1 ){
            $this->url = $url;
        }
        else{
            throw new Exception("Invalid URL",1001);
        }
    }

    function getSource(){
        return $this->source;
    }


//==============================================================================

    function fetch(){
        //Create the CURL handle to request the page
            //Create the handles
                $site = curl_init( $this->url );

            //Set the CURL options for the handle
                curl_setopt_array( $site, $this->curloptions ); //Request the source

        //Execute the request and return the response (the source code)
            $source = curl_exec($site);
            $info   = curl_getinfo($site);
                //If the response code is good, return the source
                    if($info['http_code']==200){
                        return $source;
                    }
                //Otherwise, return the response code
                    else{
                        throw new Exception("Unable to reach the page",$info['http_code']);
                    }
    }


    
}
?>
