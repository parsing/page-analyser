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

    echo(nl2br(print_r(( $report->getServerReport()->getUrlReport()->getWhois()),true))) ;

?>
</body>
</html>