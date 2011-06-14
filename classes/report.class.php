<?php
require_once('classes/source.class.php');
require_once('classes/serverreport.class.php');

class SiteReport{

    private $source; //The site's source

    function __construct($url){

        //Create the server report
            try{
                $server_report = new ServerReport($url);
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


    function getSource(){
        return $this->source;
    }

}
?>