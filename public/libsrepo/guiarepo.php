<?php
    require_once "public/fpdf/mc_table.inc.php";

    class PDF extends PDF_MC_Table{
        // Cabecera de página
            public function __construct($nguia,$fecha_emision,$ruc,$razondest,$direccdest,$raztransp,$ructransp,$dirtransp)
            {
                parent::__construct();
                $this->nguia = $nguia;
                $this->fecha_emision = $fecha_emision;
                $this->ruc = $ruc;
                $this->razondest = $razondest;
                $this->direccdest = $direccdest;
                $this->raztransp = $raztransp;
                $this->ructransp = $ructransp;
                $this->dirtransp = $dirtransp;
            }
            function Header(){
                $this->SetFillColor(229, 229, 229);
                $this->Image('public/img/logo.png',12,12,50);
                //cabecera
                $this->SetFont('Arial','B',10);
                $this->SetXY(53,15);
                $this->Cell(90,6,"SERVICIOS PETROLEROS Y",0,1,"C");
                $this->SetXY(53,20);
                $this->Cell(90,6,"CONSTRUCCIONES SEPCON S.A.C",0,1,"C");
                $this->SetFont('Times','B',7);
                $this->SetXY(53,25);
                $this->Cell(90,6,"URB. SAN BORJA - LIMA -LIMA - SAN BORJA",0,1,"C");
                $this->SetXY(53,28);
                $this->Cell(90,6,utf8_decode("Teléfono Píloto : (51-1) 201-6870"),0,1,"C");
                $this->SetXY(53,31);
                $this->Cell(90,6,utf8_decode("AV. SAN BORJA NORTE N° 445"),0,1,"C");
                $this->RoundedRect(135, 15, 65, 31, 1, '1234', 'D'); //retangulode cabecera
                $this->SetFont('Arial','B',12);
                $this->SetXY(135,18);
                $this->Cell(65,6,"R.U.C. 20504898173",0,1,"C");
                $this->SetXY(135,28);
                $this->SetFont('Helvetica','B',10);
                $this->Cell(65,8,"GUIA DE REMISION - REMITENTE",1,1,"C",true);
                $this->SetXY(135,38);
                $this->SetFont('Arial','B',12);
                $this->Cell(65,6,"0001 - ".$this->nguia,0,1,"C"); //pasa parametro
                $this->SetFont('Times',"",7);
                $this->SetXY(13,40);
                $this->Cell(20,6,utf8_decode("Fecha de Emisión:"),0,0);
                $this->Cell(180,6,$this->fecha_emision,0,1);
                //fin de cabecera
                $this->SetFont('Arial','',4.5);
                $this->RoundedRect(13, 50, 92, 20, 1, '1234', 'D'); //
                $this->SetXY(13,50);
                $this->Cell(92,5,utf8_decode("NOMBRE O RAZÓN SOCIAL DEL DESTINATARIO"),1,0,"C",true);
                $this->SetXY(15,55);
                $this->Cell(15,5,utf8_decode($this->razondest),0,1);
                $this->SetX(15);
                $this->Cell(15,5,utf8_decode("DIRECCIÓN: "),0,0);
                $this->Cell(15,5,utf8_decode($this->direccdest),0,1);
                $this->SetX(15);
                $this->Cell(15,5,"R.U.C",0,0);
                $this->Cell(15,5,$this->ruc,0,0);
                $this->RoundedRect(108, 50, 92, 20, 1, '1234', 'D'); //
                $this->SetXY(108,50);
                //$this->SetFont('Arial','',5);
                $this->Cell(92,5,"EMPRESA DE TRANSPORTES",1,1,"C",true);
                $this->SetXY(110,55);
                $this->Cell(25,5,utf8_decode("NOMBRE O RAZÓN SOCIAL:"),0,0);
                $this->Cell(15,5,utf8_decode($this->raztransp),0,1);
                $this->SetX(110);
                $this->Cell(15,5,utf8_decode("DIRECCIÓN: "),0,0);
                $this->Cell(15,5,utf8_decode($this->dirtransp),0,1);
                $this->SetX(110);
                $this->Cell(15,5,"R.U.C",0,0);
                $this->Cell(15,5, $this->ructransp,0,1);
                $this->RoundedRect(13, 72, 92, 20, 1, '1234', 'D');
                $this->SetXY(13,72);
                $this->SetFont('Arial','',7);
                $this->Cell(92,5,"PUNTO DE PARTIDA",1,1,"C",true);
    
                $this->RoundedRect(108, 72, 92, 20, 1, '1234', 'D');
                $this->SetXY(108,72);
                $this->Cell(92,5,"PUNTO DE LLEGADA",1,1,"C",true);
    
                $this->RoundedRect(13, 94, 187, 20, 1, '1234', 'D');
                $this->SetXY(13,94);
                $this->Cell(187,5,"UNIDAD DE TRANSPORTE Y CONDUCTOR",1,1,"C",true);
                $this->SetXY(15,100);
                $this->Cell(92,5,utf8_decode("MARCA Y N° DE PLACA:"),0,0);
                $this->Cell(92,5,utf8_decode("FECHA DE INICIO DEL TRASLADO:"),0,1);
                $this->SetX(15);
                $this->Cell(92,5,utf8_decode("REPRESENTANTE:"),0,0);
                $this->Cell(92,5,utf8_decode("N°(S) DE LICENCIA(S) DE CONDUCIR:"),0,0);
    
    
                // Salto de línea
                $this->Ln(1);
                $this->SetFillColor(0, 0, 0);
                $this->SetTextColor(255,255,255);
                $this->SetXY(13,116); //detalle del documento
                $this->SetFont('Arial','',6);
                $this->SetWidths(array(10,15,15,147));
                $this->SetAligns(array("C","C","C","C"));
                $this->SetCellHeight(3);
                $this->SetFill(true);
                $this->Row(array('ITEM',utf8_decode('CANTIDAD'),'UNIDAD',utf8_decode('DESCRIPCION')));
            }
    
            function Footer(){
                $this->SetFillColor(229, 229, 229);
                $this->SetY(-60);
                $this->RoundedRect(13, 250, 187, 30, 1, '1234', 'D');
                $this->SetXY(13,250);
                $this->SetFont('Arial','',8);
                $this->Cell(140,6,"MOTIVO DEL TRASLADO",0,1,"C");
                $this->SetFont('Arial','',6);
                $this->SetXY(15,256);
                $this->Cell(15,4,utf8_decode("Venta:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(15,261);
                $this->Cell(15,4,utf8_decode("Venta sujeta"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(15,263);
                $this->Cell(15,4,utf8_decode("a confirmar"),0,1);
                $this->SetXY(15,266);
                $this->Cell(15,4,utf8_decode("Compra:"),0,0);
                $this->Cell(5,4,"",1,0,"C");
                $this->SetXY(15,271);
                $this->Cell(15,4,utf8_decode("Salida a obra:"),0,0);
                $this->Cell(5,4,"",1,0,"C");
    
                $this->SetXY(37,256);
                $this->Cell(25,4,utf8_decode("Consignación:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(37,261);
                $this->Cell(25,4,utf8_decode("Devolución:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(37,266);
                $this->Cell(25,4,utf8_decode("Entre establecimientos:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(37,268);
                $this->Cell(25,4,utf8_decode("de la misma empresa:"),0,0);
                $this->SetXY(37,274);
                $this->Cell(15,4,utf8_decode("Otros:"),0,0);
                $this->Cell(90,4,utf8_decode(""),"B",0);
    
                $this->SetXY(70,256);
                $this->Cell(25,4,utf8_decode("Para Transformación:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(70,261);
                $this->Cell(25,4,utf8_decode("Recojo"),0,1);
                $this->SetXY(70,263);
                $this->Cell(25,4,utf8_decode("Bienes Transformados"),0,0);
                $this->Cell(5,4,"",1,0,"C");
                $this->SetXY(70,268);
                $this->Cell(25,4,utf8_decode("Emisor Itinerante"),0,0);
                $this->Cell(5,4,"",1,0,"C");
    
                $this->SetXY(105,256);
                $this->Cell(20,4,utf8_decode("Zona Primaria:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(105,261);
                $this->Cell(20,4,utf8_decode("Importación:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
                $this->SetXY(105,267);
                $this->Cell(20,4,utf8_decode("Exportación:"),0,0);
                $this->Cell(5,4,"",1,1,"C");
    
                $this->Line(145,250,145,280);
                $this->SetXY(147,275);
                $this->Cell(51,6,"RECIBI CONFORME","T",0,"C");
    
                $this->SetXY(13,280); //detalle del documento
                $this->SetFont('Arial','',10);
                $this->Cell(187,6,"** SOMOS AGENTE DE RETENCION DEL IGV DE ACUERDO A R.S. 225 2000",0,0,"C");
            }
        }
    

?>