<?php
$db = new \PDO('pgsql:host=db-lm;port=5432;dbname=appdb-main;user=appdb-main;password=appdb-main');
$fh = fopen(__DIR__ . '/user_schema.sql', 'r');
while ($line = fread($fh, 4096)) {
    $db->exec($line);
}
fclose($fh);
