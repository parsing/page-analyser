<?php
class Node{
    function __construct($title,$value,$message,$importance=INFO_TYPE){
        $this->title = $title;
        $this->value = $value;
        $this->message = $message;
        $this->importance = $importance;
    }
}
function show_error($error_type){
    //Produce an error message and description
    switch($error_type){
    case HTTP_NOT_FOUND:
        $error = "The server returned a 404 error";
        $errordescription = "A 404 error means that we could reach the server, but that there is no file at this address. Do not let crawlers reach missing pages; redirect them to a relevant page with a 301 redirect instead.";
        break;
    case HTTP_SERVER_ERROR:
        $error = "The server returned a 500 error";
        $errordescription = "A 500 error is an internal server error that was returned by your server when it tried to process your request. This error is thrown by your server software. Investigating the server logs might give you a better idea of the problem.";
        break;
    case HTTP_FORBIDDEN:
        $error = "The server returned a 403 error";
        $errordescription = "A 403 error occured because ".APPLICATION_NAME." was not authorized to access this page. Make sure our site does not block our user agent.";
        break;
    case HTTP_INVALID_URL:
        $error = "The URL you have entered is invalid";
        $errordescription = "The address you gave us is invalid. Make sure you didn't make a typing mistake and that the URL you have entered is registered.";
        break;
    case HTTP_UNAUTHORIZED:
        $error = "The server returned a 401 error";
        $errordescription = "A server will return a 401 error when a user is trying to access a page that requires authentication with the wrong credentials.";
        break;
    case HTTP_GATEWAY_TIMEOUT:
        $error = "The server returned a 502 error";
        $errordescription = "A 502 error means that the server is not responding timely to our requests. This might be caused by a server outage or connectivity issues with your ISP.";
        break;
    case HTTP_SERVER_UNAVAILABLE:
        $error = "The server returned a 503 error";
        $errordescription = "A 503 error is returned when the server is unable to handle the request. This is generally caused by server maintenance or overload.";
        break;
    default:
        $error = "The server returned a ".$error_type." error";
        $errordescription = "We were not able to connect to the server. Please make sure your server is available and returning requests from our server.";
    }

    show_box($error,$errordescription,ERROR_TYPE);
}

function show_box($node){
    ?>
    <div style ="border:1px solid black" class="<?php echo("level-".$node->importance)?>">
        <h3><?php echo($node->title)?></h3>
        <h4><?php echo($node->value)?></h4>
        <span><?php echo($node->message)?></span>
    </div>
    <?php
}

function display_report($report){
    //URL-related nodes
    $url_nodes = array();
        //URL
        $title = "URL";
            $value = $report['url'];
            $message = "This is your page's URL. Make sure that every variation of it redirects to this one. If multiple URLs show the same content without redirecting or using the canonical meta tag, Google might think your site has duplicate content.";
            array_push($url_nodes,new Node($title, $value, $message));
        //Domain name and length
        $title = "Domain name";
            $value = $report['base_url'];
            $message = "Your domain name is ".$report['base_url_length']." characters long. Altough the length of a domain name does not affect search rankings, it is advisable to choose a short and easily remembered domain name.";
            array_push($url_nodes,new Node($title, $value, $message));
        //Page depth
        $title = "Page depth";
            $value = $report['page_depth'];
            if($report['page_depth']>3){
                $message = "Your page is ".$report['page_depth']." levels deep. It is advisable to keep your pages under 3 levels deep.";
                $importance=WARNING_TYPE;
            }
            else{
                $message = "Your page is under 3 levels deep. This helps crawl robots find your pages.";
                $importance=WARNING_TYPE;
            }
        //Domain name and length
        $title = "WHOIS";
            $value = $report['whois'];
            $message = "";
            array_push($url_nodes,new Node($title, $value, $message));

        array_push($url_nodes,new Node($title, $value, $message, $importance));


    foreach($url_nodes as $node){
        show_box($node);
    }
}
?>
