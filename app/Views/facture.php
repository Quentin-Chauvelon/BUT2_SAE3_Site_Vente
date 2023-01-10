<?php
// require('fpdf.php');
require (APPPATH  . 'Libraries' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'fpdf.php');

$idCommande = "12";
$idFacture = substr("00" . $idCommande, 0, -3);
$prenom = "Admin";
$nom = "Hotgenre";
$rue = "190 boulevard Jules Verne";
$codePostal = 44000;
$ville = "Nantes";



$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetTextColor(46);

$pdf->SetFont('Helvetica','B',30);
$pdf->Ln(10);
$pdf->Cell(40,10,'Hot Genre');
$pdf->Ln(30);

// $pdf->Image(site_url() . "images/logos/logo hg noir.png", 20, 160, 30, 30, "png", "http://172.26.82.56");
$pdf->SetFont('Helvetica','B',15);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "Envoyé à"));
$pdf->SetFont('Helvetica','B',15);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', " N° de facture                       "), 0, 0, "R");
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(0, 10, $idCommande, 0, 0, "R");
$pdf->Ln(8);

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', $prenom . " " . $nom));
$pdf->SetFont('Helvetica','B',15);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "          Date                       "), 0, 0, "R");
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(0, 10, date("d/m/Y"), 0, 0, "R");
$pdf->Ln(7);

$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', $rue));
$pdf->Ln(1);
$pdf->SetFont('Helvetica','B',15);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "N° de commande                       "), 0, 0, "R");
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(0, 10, $idCommande, 0, 0, "R");

$pdf->Ln(6);
$pdf->Cell(0,10, iconv('UTF-8', 'windows-1252', (string)$codePostal . ", " . $ville));

$pdf->Ln(1);
$pdf->SetFont('Helvetica','B',15);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "      Echéance                       "), 0, 0, "R");
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(0, 10, date("d/m/Y", mktime(0, 0, 0, date("m") + 4, date("d"), date("Y"))), 0, 0, "R");

$pdf->Ln(20);
$pdf->SetFillColor(100);
$pdf->SetTextColor(255);
$pdf->SetFont('Helvetica','B',14);
$pdf->Cell(35, 12, iconv('UTF-8', 'windows-1252', "    Quantité"), 0, 0, "C", true);
$pdf->Cell(85, 12, "Nom", 0, 0, "C", true);
$pdf->Cell(35, 12, "Prix unitaire", 0, 0, "C", true);
$pdf->Cell(35, 12, "Prix", 0, 0, "C", true);

$pdf->Ln(12);
$pdf->SetFillColor(235);
$pdf->SetTextColor(46);
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(35, 12, iconv('UTF-8', 'windows-1252', "    2"), 0, 0, "C", true);
$pdf->Cell(85, 12, "jdklqfj ksfkl jfqjfkldsfkl", 0, 0, "L", true);
$pdf->Cell(35, 12, "29.99" . chr(128), 0, 0, "C", true);
$pdf->Cell(35, 12, "59.98" . chr(128), 0, 0, "C", true);

$pdf->Ln(12);
$pdf->SetFillColor(255);
$pdf->SetTextColor(46);
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(35, 12, iconv('UTF-8', 'windows-1252', "    2"), 0, 0, "C", true);
$pdf->Cell(85, 12, "jdklqfj ksfkl jfqjfkldsfkl", 0, 0, "L", true);
$pdf->Cell(35, 12, "29.99" . chr(128), 0, 0, "C", true);
$pdf->Cell(35, 12, "59.98" . chr(128), 0, 0, "C", true);

$pdf->Ln(12);
$pdf->SetFillColor(235);
$pdf->SetTextColor(46);
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(35, 12, iconv('UTF-8', 'windows-1252', "    2"), 0, 0, "C", true);
$pdf->Cell(85, 12, "jdklqfj ksfkl jfqjfkldsfkl", 0, 0, "L", true);
$pdf->Cell(35, 12, "29.99" . chr(128), 0, 0, "C", true);
$pdf->Cell(35, 12, "59.98" . chr(128), 0, 0, "C", true);

$pdf->Ln(14);
$pdf->SetFont('Helvetica','B',14);
$pdf->Cell(120, 10, "", 0, 0, "C");
$pdf->Cell(35, 10, "Total", 0, 0, "C");
$pdf->Cell(35, 10, "59.98" . chr(128), 0, 0, "C");

header('Content-Type: application/pdf');
$pdf->Output('D', 'test.pdf');
?>