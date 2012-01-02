<?php

function sqldate($timestamp = null) {
    $format = "Y-m-d H:i:s";
    return (empty($timestamp)) ? date($format) : date($format, $timestamp);
}

function ddd($var) {
    Y::dump($var);
}

function dar($var) {
    Y::dar($var);
}
function dd($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    die;
}
