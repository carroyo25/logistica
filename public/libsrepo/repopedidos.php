<?php
require_once "public/fpdf/mc_table.inc.php";

class PDF extends PDF_MC_Table{
    //Construct
    public function __construct($numero,$fecha,$proyecto,$costo,$area,$concepto,$mmto,$condicion,$transporte,$usuario,$solicitante,$estado,$especificacion,$tipo,$mensaje,$aprueba) {
        parent::__construct();
        $this->numero           = $numero;
        $this->fecha            = $fecha;
        $this->proyecto         = $proyecto;
        $this->costo            = $costo;
        $this->area             = $area;
        $this->concepto         = $concepto;
        $this->mmto             = $mmto;
        $this->transporte       = $transporte;
        $this->usuario          = $usuario;
        $this->solicitante      = $solicitante;
        $this->cargosol         = "";
        $this->estado           = $estado;
        $this->especificacion   = $especificacion;
        $this->mmto             = "";
        $this->condicion        = $condicion;
        $this->aprobado         = "";
		$this->cargoaprob       = "";
		$this->tipo				= $tipo;
		$this->mensaje			= $mensaje;
		$this->aprueba			= $aprueba;
    }
    //page header
    function Header(){
		$titulo = $this->tipo == "B" ? "PEDIDO DE COMPRA":"PEDIDO DE SERVICIO";

        $this->Rect(10,10,30,20); //marco de la imagen
        $this->Rect(40,10,130,20); //marco del titulo
        $this->Rect(10,10,190,20); //marco general

        $this->SetFillColor(229, 229, 229);
        $this->Rect(70,24,70,5,"F"); //fondo de mensaje
        $this->Image('public/img/logo.png',12,12,25);
	    $this->SetFont('Arial','B',12);
		$this->SetTextColor(0,0,0);
	 	$this->SetFillColor(229, 229, 229);
	    $this->Cell(190,7,$titulo,0,1,'C');
	    $this->SetFont('Arial','B',10);
	    $this->Cell(190,6,utf8_decode('N° ').date("Y",strtotime($this->fecha))."-".$this->numero,0,1,'C');
	    $this->Cell(190,7,$this->mensaje,0,0,'C');
	    $this->SetXY(170,11);
	    $this->SetFont('Arial','',7);
	    $this->MultiCell(30,7,utf8_decode('PSPC-410-X-PR-001-FR-001 Revisión: 0 Emisión: 06/05/2019 '),0,'L',false);

	    $this->SetXY(170,32);
	    $this->Cell(30,4,"Solicitado",1,1,"C");
	    $this->SetXY(170,36);
	    $this->Cell(10,4,utf8_decode("Día"),1,0,"C");
	    $this->Cell(10,4,"Mes",1,0,"C");
	    $this->Cell(10,4,utf8_decode("Año"),1,1,"C");
	    $this->SetXY(170,40);
	    $this->Cell(10,4,date("d",strtotime($this->fecha)),1,0,"C");
	    $this->Cell(10,4,date("m",strtotime($this->fecha)),1,0,"C");
	    $this->Cell(10,4,date("Y",strtotime($this->fecha)),1,1,"C");
	    $this->SetXY(170,44);
	    $this->Cell(30,4,"Tipo Transporte",1,1,"C");
	    $this->SetXY(170,48);
	    $this->Cell(30,4,utf8_decode($this->transporte),1,1,"C");

	    $this->SetXY(10,32);
	    $this->Cell(30,5,"Proyecto",1,0);
	    $this->Cell(130,5,utf8_decode($this->proyecto),1,1);
	    $this->Cell(30,5,"Area/Costo",1,0);
	    $this->Cell(80,5,utf8_decode($this->area),1,0);
	    $this->Cell(20,5,utf8_decode("Ped.MMTO N°"),1,0);
	    $this->Cell(30,5,$this->mmto,1,1);
	    $this->Cell(30,5,"Objeto del Pedido",1,0);
	    $this->Cell(80,5,utf8_decode($this->concepto),1,0);
	    $this->Cell(20,5,utf8_decode("Condición"),1,0);
	    $this->Cell(30,5,utf8_decode($this->condicion),1,1);
	    $this->Cell(30,5,"Concepto",1,0);
	    $this->Cell(130,5,utf8_decode($this->especificacion),1,1);

	    // Salto de línea
    	$this->Ln(1);

    	$this->Rect(10,53,190,7,"F"); //fondo de mensaje
    	$this->SetWidths(array(10,15,70,8,10,17,15,15,15,15));
    	$this->SetAligns(array("C","C","C","C","C","C","C","C","C","C"));
    	$this->Row(array('Item',utf8_decode('Código'),utf8_decode('Descripción'),'Und.Med.','Cant.','Stock Proyecto','Stock Acumulado','Orden Atendida',utf8_decode('N° Parte'),'Equipo'));
    }
    // Page footer
    function Footer(){
        	$this->SetFillColor(229, 229, 229);
            $this->SetY(-50);
            $this->SetFont('Arial','',5);
		    $this->SetWidths(array(64,64,62));
    		$this->SetAligns(array("C","C","C"));
		    $this->Row(array("ELABORADO POR","COMFORMIDAD DE ELABORACION","APROBACION DE PEDIDO"));
		    $this->Row(array());
		    $this->Cell(64,8,utf8_decode($this->usuario),1,0,"C");
			$this->SetFont('Arial','B',18);

			if ($this->mensaje == "VISTA PREVIA")
				$this->SetTextColor(249,149,2);
			else if($this->mensaje == "EMITIDO")
				$this->SetTextColor(30,144,255);
			else if($this->mensaje == "APROBADO")
				$this->SetTextColor(30,144,255);

		    $this->Cell(64,8,$this->mensaje,0,0,"C");
		    $this->Cell(62,8,$this->mensaje,0,1,"C");
		    $this->SetTextColor(0,0,0);
		    $this->SetFont('Arial','',5);
		    $this->Cell(64,4,"IMPRESO POR",1,1,"C",true);
		    $this->Cell(64,4,"","LR",1,"C");
		    $this->Cell(64,4,"","BLR",0,"C");
		    $this->Cell(64,4,utf8_decode($this->solicitante),0,0,"C"); //emitido
		    $this->Cell(64,4,utf8_decode($this->aprueba),0,1,"C"); //autorizado
		    $this->Cell(64,4,"EMITIDO",1,0,"C",true);
		    $this->Cell(64,4,utf8_decode($this->cargosol),0,0,"C"); //
		    $this->Cell(64,4,utf8_decode($this->cargoaprob),0,1,"C");
		    $this->Cell(32,4,date("Y-m-d H:i:s"),"BL",0,"C");
		    $this->Cell(32,4,utf8_decode('Página ').$this->PageNo()." de ".'/{nb}',"BR",0);
		    $this->Rect(74,250.5,64,28);
		    $this->Rect(138,250.5,62,28);
    }
}
?>