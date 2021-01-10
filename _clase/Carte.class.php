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

    function __construct($bd, $obj_carte) {

        $this->bd = $bd;

        $this->id_carte = $obj_carte->id_carte;
        $this->titlu = $obj_carte->titlu;
        $this->id_limba = $obj_carte->id_limba;
        $this->data_publicare = $obj_carte->data_publicare;
        $this->numar_pagini = $obj_carte->numar_pagini;
        $this->fisier_imagine = $obj_carte->fisier_imagine;
        $this->id_serie = $obj_carte->id_serie;
        $this->link_goodreads = $obj_carte->link_goodreads;
        $this->numar_exemplare = $obj_carte->numar_exemplare;
        $this->data_adaugare = $obj_carte->data_adaugare;

        $rez = $bd->query("
            SELECT COUNT(*) numar_disponibile
            FROM imprumuturi
            WHERE id_carte='$this->id_carte'
            AND data_predare IS NULL
        ");
        $this->numar_disponibile = $this->numar_exemplare - $rez->fetch_object()->numar_disponibile;
    }

    static function din_bd($bd, $id) {
        $interog = $bd->prepare("SELECT * FROM carti WHERE id_carte=?");
        $interog->bind_param("i", $id);
        $interog->execute();
        $rez = $interog->get_result();
        if ($rez->num_rows == 0)
            throw new Exception("Nu a fost găsită o carte cu id-ul specificat.");
        
        $obj_carte = $rez->fetch_object();
        return new Carte($bd, $obj_carte);
    }

    function get_arr_autori() {
        $autori = [];

        $interog = ($this->bd)->prepare("SELECT nume_autor FROM autori_carti WHERE id_carte=?");
        $interog->bind_param("i", $this->id_carte);
        $interog->execute();
        $rez = $interog->get_result();
        while ($linie = $rez->fetch_object()) {
            array_push($autori, $linie->nume_autor);
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

    function get_nota_medie() {
        $interog = $this->bd->prepare("SELECT AVG(valoare_nota) nota_medie FROM note WHERE id_carte=?");
        $interog->bind_param("i", $this->id_carte);
        $interog->execute();
        $rez = $interog->get_result();
        $linie = $rez->fetch_object();
        return $linie->nota_medie;
    }
}

?>
