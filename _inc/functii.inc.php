<?php

function fara_caractere_speciale($str) {
    return str_replace(["\"", "'", "/", "\\", ".", ",", "="], "", $str);
}

?>
