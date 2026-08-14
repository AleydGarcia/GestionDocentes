<?php
$curp = 'PEGJ900615HDFRMN03';
$patterns = [
    'server' => '/^[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]$/i',
    'client_simple' => '/^[A-Za-zÑñ&]{4}[0-9]{6}[HMhm][A-Za-z]{2}[B-DF-HJ-NP-TV-Zb-df-hj-np-tv-z]{3}[A-Za-z0-9][0-9]$/',
    'user_proposed' => '/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM]{1}(AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/i',
];

foreach ($patterns as $name => $pattern) {
    $match = preg_match($pattern, $curp) ? 'MATCH' : 'NO MATCH';
    echo "$name: $pattern -> $match\n";
}
