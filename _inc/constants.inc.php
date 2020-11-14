<?php

function get_root_directory() {
    $dir = __DIR__;
    $expl_dir = explode("\\", $dir);
    $root = [];
    if (count($expl_dir) > 1) {
        $root = $expl_dir[array_key_last($expl_dir) - 1];   // -1 pentru a nu selecta folderul inc
    }
    else {
        $expl_dir = explode("/", $dir);
        $root = $expl_dir[array_key_last($expl_dir) - 1];
    }
    return $root;
}

//define("ROOT", get_root_directory());

?>
