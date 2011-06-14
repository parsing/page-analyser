<?php

//SOURCE
//Fetches and combines various reports

require_once('classes/source.class.php');
require_once('classes/serverreport.class.php');

class SiteReport{
    //Regex constants
        private $regexUrl = '/^(http|https|ftp)\:\/\/([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(\/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$/i';

    //Class variables
        private $source;        //The site's source
        private $serverReport;  //The server report

    function __construct($url){

        //Create the server report
            try{
                $this->serverReport = new ServerReport($url);
            }
            catch(Exception $e){
                throw $e;
            }
            
        //Fetch the site's source
            try{
                $this->source = new Source($url);
            }
            catch(Exception $e){
                throw $e;//Expect the HTTP response code as the exception code
            }
            
    }
    

//==============================================================================


    //Retrieves the source code of the page
    function getSource(){
        return $this->source;
    }

    //Retrieves the server report
    function getServerReport(){
        return $this->serverReport;
    }

}
?>