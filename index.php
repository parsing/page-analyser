<!DOCTYPE html>
<html>
<head>
    <title>Page analyzer</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>
<?php
    require_once('classes/report.class.php');

    $url = 'http://nicolasbouliane.com';

    $report = new SiteReport($url);

    print_r( $report->getHtmlReport()->doctype()) ;

?>
</body>
</html>