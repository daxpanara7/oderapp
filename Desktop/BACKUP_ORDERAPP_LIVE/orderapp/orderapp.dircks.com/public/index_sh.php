<?php
echo "Test";
$output = exec('/opt/plesk/php/7.3/bin/php  ../artisan optimize');
print_r($output);
echo "test2";
?>