<?php
namespace PM\LibroBundle\Printer;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class PrintInformeDiarioFPDF {

	protected $em;
	protected $producto;
	protected $clase;

	public function __construct(Doctrine $doctrine)
	{
	//	$this->em  = $doctrine->getEntityManager();
		//	$this->producto = '';
	}

	public function getFicheroFPDF($query,$filterData) {

		$yaml = new Parser();
		try {$value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
		} catch (ParseException $e) {printf("Unable to parse the YAML string: %s", $e->getMessage());	}
		$riesgos = $value['divisiones_riesgo']; $productos = $value['tipos_producto'];

		$data = $query->getQuery()->getArrayResult();

		$pdf = $this->printFPDF($pdf, $data, $riesgos, $productos, $filterData);
		return $pdf->Output('Libro-Diario.pdf','I');
	}

	static function printFPDF(&$pdf, &$data, $riesgos, $productos, $filterData)
	{
    $clase = '';
		$yaml = new Parser();
		try {$value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
		} catch (ParseException $e) {printf("Unable to parse the YAML string: %s", $e->getMessage());	}
		$riesgos = $value['divisiones_riesgo']; $productos = $value['tipos_producto'];
		$producto = '';
		$pdf = new ComunesFPDF();
		$pdf->SetMargins(15,10);
		$pdf->AddPage();
		$producto = $filterData['producto']; $clase = $filterData['clase']; $exist = 'existencias';

		  $pdf->SetFont('Arial','',10);
		  $pdf->Cell(80,12,$productos[$producto],0,0,'C');$pdf->Cell(80,12,'Clase: '.$riesgos[$clase],0,0,'C');
			$pdf->Ln(12);

			$pdf->Ln(10);	$pdf->SetFillColor(200,200,200);$pdf->SetTextColor(0);	$pdf->SetDrawColor(0,0,0);	$pdf->SetLineWidth(.2);	$pdf->SetFont('Arial','B',10);
			$pdf->Cell(20,6,"Fecha",'TLR',0,'C',1);
			$pdf->Cell(65,6,"Procedenica o Destino",'TLR',0,'C',1);
	    $pdf->Cell(13,6,"Clase",'TLR',0,'C',1);
			$pdf->Cell(17,6,"Carta",'TLR',0,'C',1);
			$pdf->Cell(20,6,"Entradas",'TLR',0,'C',1,true);
			$pdf->Cell(20,6,"Salidas",'TLR',0,'C',1);
			//$pdf->Cell(25,6,"Existencias",'TLR',0,'C',1);
			$pdf->Ln(6);
			$pdf->Cell(20,6,"",'LBR',0,'C',1);
			$pdf->Cell(65,6,"",'LRB',0,'C',1);
	    $pdf->Cell(13,6,"",'LRB',0,'C',1);
			$pdf->Cell(17,6,"Porte",'LRB',0,'C',1);
			$pdf->Cell(20,6,"(kg)",'LRB',0,'C',1);
			$pdf->Cell(20,6,"(kg)",'LRB',0,'C',1);
			//$pdf->Cell(25,6,"(kg)",'LRB',0,'C',1);
			$pdf->Ln(6);
      // LAs Existencias que imprimimos depende de la busqueda que hemos hecho

			/*
			if($producto == 1) { // Producto Terminado
					if($clase == 5) {
						$exist = 'existencias11g';
					}
					if($clase == 7) {
						$exist = 'existencias13g';
					}
					if($clase == 8) {
						$exist = 'existencias14g';
					}
					if($clase == 9) {
						$exist = 'existencias14s';
					}
			} else if($producto == 2){ // Producto Intermedio
				$exist = 'existencias2';
			}*/
			$pdf->SetFillColor(224,235,255);$pdf->SetTextColor(0);	$pdf->SetDrawColor(0,0,0);	$pdf->SetLineWidth(.2);	$pdf->SetFont('Arial','',9);
			$sumentradas = 0; $sumsalidas = 0;
			foreach ($data as $linea){
				$pdf->Cell(20,5,$linea['fecha']->format('d/m/Y'),'LR',0,'C');
				$pdf->Cell(65,5,substr($linea['procedenciad'],0,44),'LR',0,'L');
				$pdf->Cell(13,5,$riesgos[$linea['clase']],'LR',0,'C');
				$pdf->Cell(17,5,$linea['cartaporte'],'LR',0,'C');
				$pdf->Cell(20,5,number_format($linea['entrada'],3,'.',','),'LR',0,'R');
				$pdf->Cell(20,5,number_format($linea['salida'],3,'.',','),'LR',0,'R');
				$sumentradas += $linea['entrada']; $sumsalidas += $linea['salida'];

				$pdf->Ln(5);
			}
				$pdf->Cell(20,2,"",'LBR',0,'C');
				$pdf->Cell(65,2,"",'LRB',0,'C');
		    $pdf->Cell(13,2,"",'LRB',0,'C');
				$pdf->Cell(17,2,"",'LRB',0,'C');
				$pdf->Cell(20,2,"",'LRB',0,'C');
				$pdf->Cell(20,2,"",'LRB',0,'C');
			$pdf->Ln(2);
				$pdf->Cell(20,10,"",'LBR',0,'C');
				$pdf->Cell(65,10,"",'LRB',0,'C');
				$pdf->Cell(13,10,"",'LRB',0,'C');
				$pdf->Cell(17,10,"",'LRB',0,'C'); $pdf->SetFont('Arial','BI',10);
				$pdf->Cell(20,10,number_format($sumentradas,3,'.',','),'LRB',0,'R');
				$pdf->Cell(20,10,number_format($sumsalidas,3,'.',','),'LRB',0,'R');
				$pdf->Cell(25,10,number_format($sumentradas - $sumsalidas,3,'.',','),'LRTB',0,'R');
			$pdf->Ln(10);
    $pdf->AliasNbPages();
		return $pdf;
	}
}
