<?php
//Global variables (including name and version)
include_once("application-globals.php");
//Functions related to curl
include("http-functions.php");
include("html-functions.php");

//Returns an array containing the report results
function generate_report($url){
    
//Verify that the URL is valid
    if (preg_match( REGEX_URL, $url ) == 0)
        return INVALID_URL;


//Retrieve the source code
    $data = retrieve_site($url);

    //Check if the returned data is valid
    if (!is_array($data))
        return $data; //Data will return the response code only if it's not 200

    //If the page produces an error, end the function, return the http response
    if ($data['info']['http_code'] != 200)
        return( $data['info']['http_code'] );

//Analyze the data
    $report = array(
        //HTTP info
        "url"                   => $data['info']['url'],
        "url_length"            => strlen($data['info']['url']),
        "base_url"              => get_base_url($data['info']['url']),
        "base_url_length"       => strlen(get_base_url($data['info']['url'])),
        "page_depth"            => get_page_depth($data['info']['url']),
        "page_size"             => $data['info']['size_download'],
        "connect_time"          => $data['info']['connect_time'],
        "request_size"          => $data['info']['request_size'],
        "redirects"             => $data['info']['redirect_count'],
        "redirect_time"         => $data['info']['redirect_time'],

        //Server info
        "headers"                => $data['header'],
        "server_type"           => get_server_type($data['header']),
        "server_ip"             => gethostbyname(get_host($data['info']['url'])),
        "whois"                 => $data['whois'],
        "hostbyaddr"            => gethostbyaddr("66.147.240.199"),
        "response"              => $data['info']['http_code'],

        //HTML info
        "doctype"               => get_doctype($data['source']),
        "meta_tags"             => $data['metatags'],
        "has_webmastertools_tag"=> has_webmastertools_tag($data['metatags']),
        "has_canonical_tag"     => has_canonical_tag($data['source']),
        "get_canonical_url"     => get_canonical_url($data['source']),
        "has_description_tag"   => has_description_tag($data['metatags']),
        "description_tag"       => get_description_tag($data['metatags']),
        "description_tag_length"=> strlen(html_entity_decode(get_description_tag($data["metatags"]))),
        "has_favicon"           => has_favicon($data['source']),
        "favicon_url"           => get_favicon_url($data['source']),
        "frame_count"           => count_tags("iframe",$data["source"]) + count_tags("frame",$data["source"]),
        "h1_count"              => count_tags("h1",$data["source"]),
        "h1_content"            => get_tags("h1",$data["source"]),
        "h2_count"              => count_tags("h2",$data["source"]),
        "h2_content"            => get_tags("h2",$data["source"]),
        "h3_count"              => count_tags("h3",$data["source"]),
        "h3_content"            => get_tags("h3",$data["source"]),
        "h4_count"              => count_tags("h4",$data["source"]),
        "h4_content"            => get_tags("h4",$data["source"]),
        "h5_count"              => count_tags("h5",$data["source"]),
        "h5_content"            => get_tags("h5",$data["source"]),
        "h6_count"              => count_tags("h6",$data["source"]),
        "h6_content"            => get_tags("h6",$data["source"]),
        "has_keywords_tag"      => has_keywords_tag($data['metatags']),
        "keywords_tag"          => get_keywords_tag($data['metatags']),
        "has_title_tag"         => has_title_tag($data["source"]),
        "title_tag"             => get_title_tag($data["source"]),
        "title_tag_length"      => strlen(html_entity_decode(get_title_tag($data["source"]))),
        "style_attributes"      => count_style_attributes($data["source"]),
        "style_tags"            => count_tags("style",$data["source"]),
        "stylesheet_count"      => count_stylesheets($data["source"]),
        "stylesheets"           => get_stylesheet_urls($data["source"]),

        //Robots.txt file
        "has_robots"            => $data['info']['has_robots'],
        "robots"                => $data['info']['robots'],
        "robots_has_sitemap"    => has_sitemap($data['info']['robots']),
        "robots_sitemap_url"    => get_sitemap_url($data['info']['robots']),
        "has_humanstxt"         => $data['info']['has_humans'],
        "humanstxt"             => $data['info']['humans'],
        
    );
    return $report;
}
?>
