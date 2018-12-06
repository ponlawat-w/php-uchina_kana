<?php
require_once(__DIR__ . '/config.php');

$CONNECTION = mysqli_connect(
    CONFIG['mysql_host'],
    CONFIG['mysql_username'],
    CONFIG['mysql_password'],
    CONFIG['mysql_database']
);

require_once(__DIR__ . '/libs/db.php');
require_once(__DIR__ . '/libs/io.php');
require_once(__DIR__ . '/libs/kana.php');

db_sql('SET CHARSET utf8mb4');
