<?php
    require_once('classes/report.class.php');

    $url = 'http://nicolasbouliane.com';

    $report = new SiteReport($url);

    print_r($report->getServerReport()->getHeaders());

?>