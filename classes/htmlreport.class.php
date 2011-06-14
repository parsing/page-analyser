<?php

//SERVERREPORT
//Creates a report on server-side information that is not related
//to the page's contents


require_once('report.interface.php');

class HtmlReport implements IReport{
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

    function getSource(){
        return $this->source;
    }

//==============================================================================

    //Returns an array with all the occurences of a tag in the source
    private function getTags($tag){
            //Use DOMDocument to load the source and find tags
            $dom = new DOMDocument();
            $dom->loadHTML($this->source);

            //Create an array with the tags and their information
            $tags = array();
            $raw_tags = $dom->getElementsByTagName($tag);
            foreach($raw_tags as $tag){
                    $text = $tag->nodeValue;
                    //Push the tag in the array
                    array_push($tags, $text);
            }
            return $tags;
    }
    
//==============================================================================

    //Returns the doctype
    function doctype(){
        if (preg_match("/\<\!doctype[^\>]*\>/i", $this->source,$okay)){
            return(htmlentities($okay[0]));
        }
        else
            return false; //Return false for no doctype
    }

    //Returns the contents of the title tag
    function headings($level = 0){
        if ($level != 0){
            return($this->getTags('h' . $level));
        }
        else{
            //Get the h1-h7 tags
            $headings[1] = $this->getTags('h1');
            $headings[2] = $this->getTags('h2');
            $headings[3] = $this->getTags('h3');
            $headings[4] = $this->getTags('h4');
            $headings[5] = $this->getTags('h5');
            $headings[6] = $this->getTags('h6');
            $headings[7] = $this->getTags('h7');
            //Return the array of headings
            return $headings;
        }
    }

    //Returns an array containing the meta tags
    function metaTags(){
        //Use DOMDocument to load the source and find meta tags
        $dom = new DOMDocument();
        $dom->loadHTML($source);

        //Create an array with the tags and their information
        $metatags = array();
        $raw_tags = $dom->getElementsByTagName("meta");
        foreach($raw_tags as $tag){
            $name = $tag->getAttribute("name");
            $content = $tag->getAttribute("content");
            $httpequiv = $tag->getAttribute("http-equiv");
            $scheme = $tag->getAttribute("scheme");
            //Push the tag in the array
            $newtag = array('name' => $name, 'content' => $content, 'http-equiv' => $httpequiv, 'scheme' => $scheme);
            $metatags[$newtag['name']] = $newtag; //add a new key with the name of the tag
        }
        return $metatags;
    }

    //Returns the contents of the title tag
    function titleTag(){
        return($this->getTags('title'));
    }

}
?>
