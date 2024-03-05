<?php

use TCPDF;

class MYPDF extends TCPDF {
    public function Header() {
        // Set header data
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Maram TRABLESI');
        $this->SetTitle('HealthGuard');
    
        // set default header data
        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 015', PDF_HEADER_STRING);
        
        $logoPath = '../public/front/img/logo_.png';
        // Set the position for the title, including space between the header and the title
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(33, 36, 57); // Text color: #212439
        $this->Cell(10, 20, 'HealthGuard', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Image($logoPath, 5, 5, 20); // Adjust coordinates and size as needed
        $this->SetFont('helvetica', 'B', 18);
        $this->Ln(30); // Add margin after the header
    }
    

    public function Footer() {
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
