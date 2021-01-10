<?php

session_start();
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_clase/Carte.class.php";
require_once "../_clase/PHPExcel.php";
require_once "../_functii/functii.php";

if (!isset($_SESSION["id_utilizator"]))
    header("Location: ../index");
else {
    $id_utilizator = $_SESSION["id_utilizator"];

    $excel = new PHPExcel();
    $excel->getProperties()->setCreator("Templul Cărtilor")
                           ->setLastModifiedBy("Templul Cărtilor")
                           ->setTitle("Împrumuturi ".$_SESSION["prenume"]." ".$_SESSION["nume"])
                           ->setSubject("Împrumuturi Templul Cărtilor")
                           ->setDescription("Istoricul împrumuturilor utilizatorului ".$_SESSION["email"]." la Templul Cărților")
                           ->setKeywords("imprumuturi xls excel templul cartilor");

    $excel->setActiveSheetIndex(0)->setCellValue("A1", "Titlu");
    $excel->setActiveSheetIndex(0)->setCellValue("B1", "Autor(i)");
    $excel->setActiveSheetIndex(0)->setCellValue("C1", "Început");
    $excel->setActiveSheetIndex(0)->setCellValue("D1", "Termen");
    $excel->setActiveSheetIndex(0)->setCellValue("E1", "Predat?");
    $excel->setActiveSheetIndex(0)->setCellValue("F1", "Zile întârziere la data de ".date("d.m.Y"));
    $excel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
    $excel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
    $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $excel->getActiveSheet()->getColumnDimension('F')->setWidth(35);

    $rez = $bd->query("SELECT * FROM imprumuturi WHERE id_utilizator = '$id_utilizator' ORDER BY data_inceput");
    $ind_linie_excel = 1;
    while ($imp = $rez->fetch_object()) {
        $ind_linie_excel++;
        $carte = Carte::din_bd($bd, $imp->id_carte);

        $zile_intarziere = max($imp->data_predare != null ? zile_diferenta($imp->data_predare, $imp->termen_predare) : zile_diferenta(date("Y-m-d"), $imp->termen_predare), 0);

        $excel->setActiveSheetIndex(0)->setCellValue("A".$ind_linie_excel, $carte->titlu);
        $excel->setActiveSheetIndex(0)->setCellValue("B".$ind_linie_excel, $carte->get_str_autori());
        $excel->setActiveSheetIndex(0)->setCellValue("C".$ind_linie_excel, date("d.m.Y", strtotime($imp->data_inceput)));
        $excel->setActiveSheetIndex(0)->setCellValue("D".$ind_linie_excel, date("d.m.Y", strtotime($imp->termen_predare)));
        $excel->setActiveSheetIndex(0)->setCellValue("E".$ind_linie_excel, $imp->data_predare != null ? date("d.m.Y", strtotime($imp->data_predare)) : "");
        $excel->setActiveSheetIndex(0)->setCellValue("F".$ind_linie_excel, $zile_intarziere);
        if ($zile_intarziere > 0)
            $excel->getActiveSheet()->getStyle("F".$ind_linie_excel)->getFont()->getColor()->setRGB("FF0000");
        else if ($imp->data_predare != null)
            $excel->getActiveSheet()->getStyle("F".$ind_linie_excel)->getFont()->getColor()->setRGB("008000");
    }

    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Disposition: attachment; filename="imprumuturi.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    ob_end_clean();
    $objWriter->save("php://output");
}

?>
