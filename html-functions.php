<?php
function get_base_url($url){
    $urlinfo = parse_url($url);
    return( $urlinfo['scheme'] . "://" . $urlinfo['host'] );
}

function get_page_depth($url){
    //Remove the trailing slash
    if (substr($url,-1) == "/")
        $url = substr($url,0,-1);

    //Retrieve the path
    $urlinfo = parse_url($url);
    if (isset($urlinfo['path']))
        $path = explode("/",dirname($urlinfo['path']));
    else
        return 0; //Return 0 if there is no path (root)
    //Return the length of the exploded path
    return( count($path)-1 );
}

function has_canonical_tag($source){
    //Get the canonical link
    if (preg_match( "/\<[\s]*link[^\>]*rel[\s]*=[\"'][\s]*canonical[\s]*[\"'][^\>]*\>/i", $source, $empty))
        return true;
    else
        return false;
}
function get_canonical_url($source){
    //Get the canonical link
    if (preg_match( "/\<[\s]*link[^\>]*rel[\s]*=[\"'][\s]*canonical[\s]*[\"'][^\>]*\>/i", $source, $results)){
        //Retrieve the href part of the link
        preg_match( "/\<[\s]*link[^\>]*href[\s]*=[\"'](.*)[\"'][^\>]*\>/i", $results[0], $url);
        return($url[1]); //Return the favicon URL
    }
    else
        return false; //If no canonical tag was found
}

function has_description_tag($meta_tags){
    if (is_array($meta_tags)){
        if(isset($meta_tags['description']))
            return true;
        else
            return false;
    }
    else
        return false;
}
function get_description_tag($meta_tags){
    if (isset($meta_tags['description'])){
        return $meta_tags['description']['content'];
    }
    else
        return "";
}

function has_doctype($source){
    //Get the doctype
    if (preg_match( "/\<\!doctype[^\>]*\>/i", $source, $empty))
        return true;
    else
        return false;
}
function get_doctype($source){
    //Get the doctype
    if (preg_match("/\<\!doctype[^\>]*\>/i", $source,$okay)){
        return(htmlentities($okay[0]));
    }
    else
        return false; //If no canonical tag was found
}

function has_favicon($source){
    //Get the favicon link
    if (preg_match( "/\<[\s]*link[^\>]*rel[\s]*=[\"'][\s]*icon[\s]*[\"'][^\>]*\>/i", $source, $empty))
        return true;
    else
        return false;
}
function get_favicon_url($source){
    //Get the favicon link
    if (preg_match( "/\<[\s]*link[^\>]*rel[\s]*=[\"'][\s]*icon[\s]*[\"'][^\>]*\>/i", $source, $results)){
        //Retrieve the href part of the link
        preg_match( "/\<[\s]*link[^\>]*href[\s]*=[\"'](.*)[\"'][^\>]*\>/i", $results[0], $favicon);
        return($favicon[1]); //Return the favicon URL
    }
    else
        return false; //If no favicon was found
}

function get_tags($tag,$source){
    //Use DOMDocument to load the source and find tags
    $dom = new DOMDocument();
    $dom->loadHTML($source);

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

function get_host($url){
    $urlinfo = parse_url($url);
    return( $urlinfo['host'] );
}

function has_keywords_tag($meta_tags){
    if (is_array($meta_tags)){
        if(isset($meta_tags['keywords']))
            return true;
        else
            return false;
    }
    else
        return false;
}
function get_keywords_tag($meta_tags){
    if (isset($meta_tags['keywords'])){
        return $meta_tags['keywords'];
    }
    else
        return "";
}

function parse_meta_tags($source){
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

function count_meta_tags($metatags){
    return(count($metatags));
}

function get_server_type($header){
    if (isset($header['Server'])){
        if (is_array($header['Server']))
            return array_pop($header['Server']);
        else
            return $header['Server'];
    }
    else
        return false;
}

function has_sitemap($robots){
    if(preg_match(@"/Sitemap\:(.*)$/i",$robots,$sitemap))
        return true;
    else
        return false;
}
function get_sitemap_url($robots){
    if(preg_match(@"/Sitemap\:(.*)$/i",$robots,$sitemap))
        return trim($sitemap[1]);
    else
        return false;
}

function count_style_attributes($source){
    return(preg_match_all( "/style\s*=/i", $source, $style_attributes));
}

function count_stylesheets($source){
    return(preg_match_all( "/\<[\s]*(link[^\>]*)rel[\s]*=[\"'][\s]*(alternate)?[\s]*stylesheet[\s]*[\"'][^\>]*\>/i", $source, $title_tag));
}
function get_stylesheet_urls($source){
    $stylesheet_urls = array();
    //Get the stylesheet link
    if (preg_match_all( "/\<[\s]*link[^\>]*rel[\s]*=[\"'][\s]*stylesheet[\s]*[\"']?[^\>]*\>/i", $source, $results)){
        //Retrieve the href part of the link
        foreach($results[0] as $result){
            preg_match( "/\<[\s]*link[^\>]*href[\s]*=[\"'](.*?)[\"'][^\>]*\>/i", $result, $stylesheet);
            array_push($stylesheet_urls, $stylesheet[1]); //Return the stylesheet URLs
        }
        //Return an array of CSS stylesheet URLs
        return($stylesheet_urls);
    }
    else
        return false; //If no stylesheet was found
}

function has_title_tag($source){
    return(preg_match_all( "/(\<[\s]*(title[^\/\>]*)\>).*(\<[\s]*(\/title[^\>]*)\>)/i", $source, $title_tag));
}
function get_title_tag($source){
    preg_match_all( "/(\<[\s]*title[^\>]*\>)(.*)(\<[\s]*(\/title[^\>]*)\>)/i", $source, $title_tag);

    return($title_tag[2][0]);
}

function has_webmastertools_tag($meta_tags){
    if (is_array($meta_tags)){
        if(isset($meta_tags['google-site-verification']))
            return true;
        else
            return false;
    }
    else
        return false;
}

function count_tags($tag,$source){
    $tag = trim($tag);
    return(preg_match_all( "/(\<(" . $tag . "[^\>]*)\>)/i", $source, $style_tags));
}

?>
