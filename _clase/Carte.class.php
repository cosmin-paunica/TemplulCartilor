<?php

class Carte {
    public $id_carte;
    public $titlu;
    public $id_limba;
    // public $nume_limba;
    public $data_publicare;
    public $numar_pagini;
    public $fisier_imagine;
    public $id_serie;
    // public $nume_serie;
    public $link_goodreads;
    public $numar_exemplare;
    public $numar_disponibile;
    public $data_adaugare;

    function __construct($arr) {
        $this->id_carte = $arr["id_carte"];
        $this->titlu = $arr["titlu"];
        $this->id_limba = $arr["id_limba"];
        $this->data_publicare = $arr["data_publicare"];
        $this->numar_pagini = $arr["numar_pagini"];
        $this->fisier_imagine = $arr["fisier_imagine"];
        $this->id_serie = $arr["id_serie"];
        $this->link_goodreads = $arr["link_goodreads"];
        $this->numar_exemplare = $arr["numar_exemplare"];
        $this->numar_disponibile = $arr["numar_disponibile"];
        $this->data_adaugare = $arr["data_adaugare"];
    }

    function get_autori($bd) {
        $autori = [];

        $interog = $bd->prepare("SELECT nume_autor FROM autori_carti WHERE id_carte=?");
        $interog->bind_param("i", $this->id_carte);
        $interog->execute();
        $rez = $interog->get_result();
        while ($linie = $rez->fetch_assoc()) {
            array_push($autori, $linie["nume_autor"]);
        }

        return $autori;
    }

    function get_str_autori($bd) {
        $autori = $this->get_autori($bd);
        $str_autori = "";
        foreach ($autori as $a) {
            $str_autori .= ($a.", ");
        }
        $str_autori = substr($str_autori, 0, strlen($str_autori) - 2);
        return $str_autori;
    }
}

?>