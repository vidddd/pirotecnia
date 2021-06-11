<?php
namespace PM\LibroBundle\Printer;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class PrintInformeMensualFPDF {

	protected $em;

	public function __construct(Doctrine $doctrine)
	{
		$this->em  = $doctrine->getEntityManager();
	}

	public function getFicheroFPDF($mes, $mesn, $ano) {

		$anteriores = $this->em->getRepository('PMLibroBundle:Libro')->getExistenciasAnteriores($mesn, $ano );
		$extact = $this->em->getRepository('PMLibroBundle:Libro')->getExistenciasActuales($mesn, $ano );
     /*
		$clase11g = $this->em->getRepository('PMLibroBundle:Libro')->getMensualClase($mesn, $ano, 5 );
		$clase13g = $this->em->getRepository('PMLibroBundle:Libro')->getMensualClase($mesn, $ano, 7 );
		$clase14g = $this->em->getRepository('PMLibroBundle:Libro')->getMensualClase($mesn, $ano, 8 );
		$clase14s = $this->em->getRepository('PMLibroBundle:Libro')->getMensualClase($mesn, $ano, 9 );
     */
		$clase11g = $this->em->getRepository('PMLibroBundle:Libro')->getTotalClase(5);
		$clase13g = $this->em->getRepository('PMLibroBundle:Libro')->getTotalClase(7);
		$clase14g = $this->em->getRepository('PMLibroBundle:Libro')->getTotalClase(8);
		$clase14s = $this->em->getRepository('PMLibroBundle:Libro')->getTotalClase(9);
		$pdf = $this->printFPDF($mes,$mesn, $ano,$anteriores,$extact,$clase11g,$clase13g,$clase14g,$clase14s);
		return $pdf->Output('Libro-Mensual.pdf','I');
	}

	static function printFPDF($mes,$mesn,$ano,$anteriores,$extact,$clase11g,$clase13g,$clase14g,$clase14s)
	{
		$pdf = new ComunesMensualFPDF();
		$pdf->SetMargins(15,10);
		$pdf->AddPage(); $pdf->Ln(10);
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(160,5,utf8_decode('PARTE INFORMATIZADO CORRESPONDIENTE A EL MES DE '.$mes.' DE '.$ano.' QUE PRESENTA PIROTECNIA MANCHEGA, S.L. A LA INTERVENCION DE ARMAS DE LA GUARDIA CIVIL DE MORA (TOLEDO)'),0,'L');
    $pdf->Ln(10);
		$margen = 20;		$pdf->SetFont('Arial','',10);
		$pdf->Cell($margen,8);$pdf->Cell(50,8,'ADJUNTAMOS LOS SIGUIENTES DOCUMENTOS:',0,0,'L');
		$pdf->Ln(8);
		//$pdf->Cell($margen,8);$pdf->Cell(50,8,'- LIBRO PARTE MENSUAL - '.$mes.' '.$ano,0,0,'L');	$pdf->Ln(8);
		$pdf->Cell($margen,8);$pdf->Cell(50,8,'- LIBRO DIARIO DIVISIONES DE RIESGO CONJUNTAS - '.$mes.' '.$ano,0,0,'L');
		$pdf->Ln(8);
		$pdf->Cell($margen,8);$pdf->Cell(50,8,'- LIBRO DIARIO - DIVISION DE RIESGO 1.1G - '.$mes.' '.$ano,0,0,'L');
		$pdf->Ln(8);
		$pdf->Cell($margen,8);$pdf->Cell(50,8,'- LIBRO DIARIO - DIVISION DE RIESGO 1.3G - '.$mes.' '.$ano,0,0,'L');
		$pdf->Ln(8);
		$pdf->Cell($margen,8);$pdf->Cell(50,8,'- LIBRO DIARIO - DIVISION DE RIESGO 1.4G - '.$mes.' '.$ano,0,0,'L');
		$pdf->Ln(8);
		$pdf->Cell($margen,8);$pdf->Cell(50,8,'- LIBRO DIARIO - DIVISION DE RIESGO 1.4S - '.$mes.' '.$ano,0,0,'L');
		$pdf->Ln(20);

		$pdf->SetFillColor(224,235,255);$pdf->SetTextColor(0);	$pdf->SetDrawColor(0,0,0);	$pdf->SetLineWidth(.2);
		$pdf->SetFont('Arial','',12);

			$exits = $anteriores;
			$entradas = $extact[0]['entradas'];
			$suman = $exits + $entradas;
			$salidas = $extact[0]['salidas'];
			$existencias = $suman - $salidas;

			$pdf->Cell($margen,6); $pdf->Cell(130,6,"",'LTR',0,'C'); $pdf->Ln(6);	$pdf->SetFont('Arial','B',14);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"PARTE MENSUAL",'LR',0,'C'); $pdf->Ln(6);		$pdf->SetFont('Arial','',12);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"",'LR',0,'C'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    EXISTENCIAS:............ ".number_format($exits,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    ENTRADAS:............... ".number_format($entradas,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    SUMAN:...................... ".number_format($suman,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    SALIDAS:.................... ".number_format($salidas,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"",'LR',0,'C'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    EXISTENCIAS ................ ".number_format($existencias,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"",'LR',0,'C'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    1.1G:....................... ".number_format($clase11g,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    1.3G........................ ".number_format($clase13g,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    1.4G........................ ".number_format($clase14g,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"    1.4S........................ ".number_format($clase14s,3,'.',','),'LR',0,'L'); $pdf->Ln(6);
			$pdf->Cell($margen,6); $pdf->Cell(130,6,"",'LBR',0,'C'); $pdf->Ln(6);

			$pdf->Ln(20);$pdf->SetFont('Arial','',12);
		  $pdf->Cell(5,6);$pdf->MultiCell(160,6,'MADRIDEJOS (TOLEDO)  '.date('d').' de '.$mes.' de '.date('Y'));
/*
			$pdf->AddPage(); $pdf->Ln(10);
			$pdf->SetFont('Arial','',14);$pdf->SetFillColor(255,255,255);
			$pdf->MultiCell(100,5,utf8_decode('PARTE MENSUAL -  '.$mes.' de '.$ano.' '),'',0,'L');

			$pdf->Ln(10);	$pdf->SetFillColor(200,200,200);$pdf->SetTextColor(0);	$pdf->SetDrawColor(0,0,0);	$pdf->SetLineWidth(.2);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(30,10,"Producto",'TLBR',0,'C',1);
			$pdf->Cell(68,10,"Entradas",'TLBR',0,'C',1,true);
			$pdf->Cell(68,10,"Salidas",'TLBR',0,'C',1);
			$pdf->Cell(20,10,"Existencias",'TLBR',0,'C',1);
			$pdf->Ln(10);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(30,6,"",'LR',0,'C',1);
			$pdf->Cell(34,6,"Acumulado mes Actual",'L',0,'C',1);
			$pdf->Cell(34,6,"Mes Actual",'R',0,'C',1);
			$pdf->Cell(34,6,"Acumulado mes Actual",'L',0,'C',1);
			$pdf->Cell(34,6,"Mes Actual",'R',0,'C',1);
			$pdf->Cell(20,6,"",'LR',0,'C',1);
			$pdf->Ln(6);
			$pdf->Cell(30,6,"",'LBR',0,'C',1);
			$pdf->Cell(34,6,"",'LB',0,'C',1);
			$pdf->Cell(34,6,"Total",'BR',0,'C',1);
			$pdf->Cell(34,6,"",'LB',0,'C',1);
			$pdf->Cell(34,6,"Total",'BR',0,'C',1);
			$pdf->Cell(20,6,"",'LRB',0,'C',1);
			$pdf->Ln(6);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(30,6,"Producto",'TLR',0,'C',1);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetFont('Arial','',9);
			  $pdf->Cell(34,6,"0.00",'LT',0,'C',1);
			  $pdf->Cell(34,6,"0.00",'RT',0,'C',1);
			  $pdf->Cell(34,6,"0.00",'LT',0,'C',1);
			  $pdf->Cell(34,6,"0.00",'RT',0,'C',1);
			  $pdf->Cell(20,6,"",'TLR',0,'C',1);
			$pdf->Ln(6);
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(200,200,200);
			$pdf->Cell(30,6,"Intermedio",'LBR',0,'C',1);
			$pdf->SetFillColor(255,255,255);
			  $pdf->Cell(34,6,"",'LB',0,'C',1);
			  $pdf->Cell(34,6,"0.00",'RB',0,'C',1);
			  $pdf->Cell(34,6,"",'LB',0,'C',1);
			  $pdf->Cell(34,6,"0.00",'RB',0,'C',1);
			  $pdf->Cell(20,6,"0.00",'LRB',0,'C',1);
			$pdf->Ln(6);
			$pdf->SetFont('Arial','',10);$pdf->SetFillColor(200,200,200);
			$pdf->Cell(30,6,"Producto",'TLR',0,'C',1);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetFont('Arial','',9);
				$pdf->Cell(34,6,"0.00",'LT',0,'C',1);
				$pdf->Cell(34,6,"0.00",'RT',0,'C',1);
				$pdf->Cell(34,6,"0.00",'LT',0,'C',1);
				$pdf->Cell(34,6,"0.00",'RT',0,'C',1);
			$pdf->Cell(20,6,"",'TLR',0,'C',1);
			$pdf->Ln(6);
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(200,200,200);
			$pdf->Cell(30,6,"Terminado",'LBR',0,'C',1);
			$pdf->SetFillColor(255,255,255);
				$pdf->Cell(34,6,"",'LB',0,'C',1);
				$pdf->Cell(34,6,"0.00",'RB',0,'C',1);
				$pdf->Cell(34,6,"",'LB',0,'C',1);
				$pdf->Cell(34,6,"0.00",'RB',0,'C',1);
				$pdf->Cell(20,6,"0.00",'LRB',0,'C',1);
			$pdf->Ln(6);
*/

		return $pdf;
	}
}
