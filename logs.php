<?php
$filename = 'apache\logs\access.log';
$contents = file($filename);

foreach($contents as $line) {
    echo $line . "\n";
}
?>