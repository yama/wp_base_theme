<?php

if(!is_file(__DIR__ . '/app/routes.php')) {
    return;
}

echo include_once(__DIR__ . '/app/routes.php');
