<?php

function genereaza_token($n = 10) {
    $pool = "";
    for ($i = ord("A"); $i <= ord("Z"); $i++)
        $pool .= chr($i);
    for ($i = ord("a"); $i <= ord("z"); $i++)
        $pool .= chr($i);
    for ($i = 0; $i <= 9; $i++)
        $pool .= $i;

    $token = '';
    srand(time());
    for ($i = 0; $i < $n; $i++) {
        $token .= $pool[rand(0, strlen($pool) - 1)];
    }

    return $token;
}

?>