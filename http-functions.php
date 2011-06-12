<?php
include('whois/whois.main.php');

//Global variables (including name and version)
function retrieve_site($url){
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,     // return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_FORBID_REUSE   => true,
        CURLOPT_USERAGENT      => APPLICATION_NAME, // user agent
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 60,       // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    //Open connections
    $site           = curl_init( $url );
    $robotstxt      = curl_init( get_base_url($url) . "/robots.txt" );
    $humanstxt      = curl_init( get_base_url($url) . "/humans.txt" );

    curl_setopt_array( $site, $options ); //Main request (source code, headers...)
    curl_setopt_array( $robotstxt, $options ); //Robots.txt handle
    curl_setopt_array( $humanstxt, $options ); //Humans.txt handle

    //create the multiple cURL handle
    $mh = curl_multi_init();

    //add the two handles
    curl_multi_add_handle($mh,$site);
    curl_multi_add_handle($mh,$robotstxt);
    curl_multi_add_handle($mh,$humanstxt);

    $active = null;
    //execute the handles
    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($mh) != -1) {
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }

    //Get the content of the page
    $content    = curl_multi_getcontent( $site );
    $info       = curl_getinfo( $site );

    //Make sure the page exists before going further
    if($info['http_code']!=200){
        return $info['http_code'];
    }

    //Get the headers and meta tags
    $header     = get_headers($url,true);
    $metatags   = parse_meta_tags($content); //Parse meta tags from the source
    //Get information about the robots.txt and humans.txt
    $robots     = curl_multi_getcontent( $robotstxt );
    $humans     = curl_multi_getcontent( $humanstxt );
    $whois      = whois(get_host($url));

    //Put the header and content in an array
    $data = array ( "info" => $info, "source" => $content, "header" => $header, "metatags" => $metatags, "whois" => $whois);

    //Determine if there is a robots.txt file at the root of the server
    $robotsinfo = curl_getinfo( $robotstxt );
    if ( $robotsinfo['http_code'] == 200 ){
        $data['info']['robots']=$robots;
        $data['info']['has_robots']=true;
    }
    else{
        $data['info']['robots']=false;
        $data['info']['has_robots']=false;
    }

    //Determine if there is a humans.txt file at the root of the server
    $humansinfo = curl_getinfo( $humanstxt );
    if ( $humansinfo['http_code'] == 200 ){
        $data['info']['humans']=$humans;
        $data['info']['has_humans']=true;
    }
    else{
        $data['info']['humans']=false;
        $data['info']['has_humans']=false;
    }

    return $data;
}

function whois($url){
    $whois = new Whois();
    return $whois->Lookup($url);
}
?>
