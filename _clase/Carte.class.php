<?php

class Carte {
    private $bd;

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

    function __construct($bd, $arr_carte) {
        // $interog = $bd->prepare("SELECT * FROM carti WHERE id_carte=?");
        // $interog->bind_param("i", $id);
        // $interog->execute();
        // $rez = $interog->get_result();
        // if ($rez->num_rows == 0)
        //     throw new Exception("Nu a fost găsită o carte cu id-ul specificat.");
        
        // $arr_carte = $rez->fetch_assoc();

        $this->bd = $bd;

        $this->id_carte = $arr_carte["id_carte"];
        $this->titlu = $arr_carte["titlu"];
        $this->id_limba = $arr_carte["id_limba"];
        $this->data_publicare = $arr_carte["data_publicare"];
        $this->numar_pagini = $arr_carte["numar_pagini"];
        $this->fisier_imagine = $arr_carte["fisier_imagine"];
        $this->id_serie = $arr_carte["id_serie"];
        $this->link_goodreads = $arr_carte["link_goodreads"];
        $this->numar_exemplare = $arr_carte["numar_exemplare"];
        $this->data_adaugare = $arr_carte["data_adaugare"];

        $rez = $bd->query("
            SELECT COUNT(*) numar_disponibile
            FROM imprumuturi
            WHERE id_carte='$this->id_carte'
            AND predat=0
        ");
        $this->numar_disponibile = $this->numar_exemplare - $rez->fetch_assoc()["numar_disponibile"];
    }

    static function din_bd($bd, $id) {
        $interog = $bd->prepare("SELECT * FROM carti WHERE id_carte=?");
        $interog->bind_param("i", $id);
        $interog->execute();
        $rez = $interog->get_result();
        if ($rez->num_rows == 0)
            throw new Exception("Nu a fost găsită o carte cu id-ul specificat.");
        
        $arr_carte = $rez->fetch_assoc();
        return new Carte($bd, $arr_carte);
    }

    function get_arr_autori() {
        $autori = [];

        $interog = ($this->bd)->prepare("SELECT nume_autor FROM autori_carti WHERE id_carte=?");
        $interog->bind_param("i", $this->id_carte);
        $interog->execute();
        $rez = $interog->get_result();
        while ($linie = $rez->fetch_assoc()) {
            array_push($autori, $linie["nume_autor"]);
        }

        return $autori;
    }

    function get_str_autori() {
        $autori = $this->get_arr_autori();
        $str_autori = "";
        foreach ($autori as $a) {
            $str_autori .= ($a.", ");
        }
        $str_autori = substr($str_autori, 0, strlen($str_autori) - 2);
        return $str_autori;
    }
}

?>
