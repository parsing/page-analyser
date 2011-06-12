<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Free website report results</title>
    </head>
    <body>
        <?php
            //Make sure our variables are set
            if(isset($_GET['url']))
                $url = $_GET['url'];
            elseif(isset($_POST['url']))
                $url = $_POST['url'];

        //Generate the report
            if(isset($url)){
                include("fetch.php");
                include("display-functions.php");
                $report = generate_report($url);
            }

            //Check if the report is valid. If it's not an array, it's an error code
            if(is_array($report)){
                display_report($report);
                htmlentities(print_r($report));
            }
            else{
                show_error($report);
            }
        ?>
    </body>
</html>
