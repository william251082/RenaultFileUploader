<?php
$db = new PDO('sqlite:' . realpath(__DIR__) . '/rfileuploader.db');
$fh = fopen(__DIR__ . '/admin-fixtures.sql', 'r');
while ($line = fread($fh, 4096)) {
    $db->exec($line);
}
fclose($fh);