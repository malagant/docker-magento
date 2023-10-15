<?php
$file = isset($argv[1]) ? $argv[1] : false;
if (!$file) {
    die('no file specified.' . PHP_EOL);
}
$input = $file . '.csv';
$output = $file . '.htaccess';
$template = 'sport-rewrites.tpl';
$baseUrl = '';
$r = 302;

$row = 1;
$rewrites = '';
if (($handle = fopen($input, "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ";")) !== false) {
        list($from, $to) = $data;
        $rewrites .= 'RewriteRule ^' . $from . '/?$ ' . $baseUrl . $to . ' [R=' . $r . ',L]' . PHP_EOL;
    }
    fclose($handle);
}

$tpl = file_get_contents($template);

file_put_contents($output, str_replace('{{rewrites}}', $rewrites, $tpl));
