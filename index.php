<!DOCTYPE html>
<html>
<head>
    <title>Page analyzer</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>
<?php
    require_once('classes/report.class.php');

    $url = 'http://www.globaltransitinc.com';

    $report = new SiteReport($url);

    $output = $report->getServerReport()->getHeaders();
    echo(nl2br(print_r($output,true))) ;
?>
</body>
</html>