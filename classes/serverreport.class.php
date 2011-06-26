<?php

//SERVERREPORT
//Creates a report on server-side information that is not related
//to the page's contents


require_once('report.interface.php');
require_once('urlreport.class.php');

class ServerReport implements IReport{
    private $url;
    private $urlReport;
    private $headers;

    function __construct($url){
        $this->url = $url;

        //Generate the URL report
            try{
                $this->urlReport = new UrlReport($url);
            }
            catch(Exception $e){
                throw $e;
            }
    }

//==============================================================================
    
    function getUrl(){
        return $this->url;
    }

    function getUrlReport(){
        return $this->urlReport;
    }

    function getHeaders(){
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

    //Returns an array with the redirects leading to the page
    function redirects(){
        $headers = $this->getHeaders();//The returned HTTP headers
        $output = array();//The list of redirects

        //The first value in the array is the requested URL and its response code
            $output[0] = array('location'=>$this->getUrl(),'response'=>$headers[0]);

        //Counts the number of redirects
            $i = 0;

        //Append each redirect to the array
            //If Location is an array, append each url and its response code to $output
                if ( is_array($headers['Location']) ){
                    do{
                        $i++;
                        $redirect = array('location'=>$headers['Location'][$i-1],'response'=>$headers[$i]);
                        $output[$i] = $redirect;
                    }while(array_key_exists($i+1, $headers));
                }
            //If Location is a value, append its url and response code to $output
                else{
                    $i++;
                    $redirect = array('location'=>$headers['Location'],'response'=>$headers[$i]);
                    $output[$i] = $redirect;
                }

        //Add more semantic keys to the array for the start and destination urls
            $output['end'] = $output[$i];
            $output['start'] = $output[0];

        //Append the number of redirects to the array
            $output['count'] = $i;

        //Return the array
            return $output;
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
