<?php
namespace PM\PirotecniaBundle\Printer;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class PrintCajaFPDF {
	/**
	 *
	 * @var EntityManager
	 */
	protected $em;

	public function __construct(Doctrine $doctrine)
	{
		$this->em  = $doctrine->getEntityManager();
	}
	public function getFPDF($id, $numcaja){

		$pdf = $this->iniciar($id, $numcaja);
		$pdf = $this->printFPDF($pdf);
		return $pdf;
	}

	public function getFicheroFPDF($id, $numcaja) {
		$caja = $this->em->getRepository('PMPirotecniaBundle:Caja')->find($id);
		if (!$caja) {throw $pdf->createNotFoundException('No se puede encontrar la Caja.');}
		$espectaculo = $caja->getEspectaculo();
		$pdf = $this->iniciar($id, $numcaja);
		$pdf = $this->printFPDF($pdf, $espectaculo, $caja);
		return $pdf->Output('Caja-'.$id.'.pdf','I');
	}

	public function iniciar($id, $numcaja) {
		$caja = $this->em->getRepository('PMPirotecniaBundle:Caja')->find($id);
		if (!$caja) {throw $pdf->createNotFoundException('No se puede encontrar la Caja.');}
		$espectaculo = $caja->getEspectaculo();
		$pdf = new ComunesFPDF('P','mm',array(105,148));
		$pdf->setNumcaja($numcaja);
		$pdf->setRiesgo($caja->getRiesgoText());
		$pdf->setCliente($espectaculo->getCliente());

		$pdf->setDestino($espectaculo->getCliente()->getLocalidad());
		$pdf->setProvincia($espectaculo->getCliente()->getProvinciaCliente());
		$pdf->setFecha($espectaculo->getFecha()->format('d/m/Y'));
		$pdf->setPesototal($espectaculo->getPesoTotal());
		return $pdf;
	}
	static function printFPDF(&$pdf, &$espectaculo, &$caja)
	{
		$pdf->SetMargins(7,10);
		$pdf->AddPage();
		$pdf->SetY(40);
		$pdf->setY(33);$header = array('Cant.', 'Producto', 'Peso');
		// Colores, ancho de línea y fuente en negrita
		$pdf->SetFillColor(100,100,100);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(100,100,100);
		$pdf->SetLineWidth(.2);
		$pdf->SetFont('','',10);
		// Cabecera
		$w = array(10, 62, 16);
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);

		$pdf->Ln();
		// Restauración de colores y fuentes
		$pdf->SetFillColor(222,222,222);
		//$pdf->SetDrawColor(250,250,250);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Datos
		//$fill = false;
		$pdf->SetWidths(array(10, 62, 16));
		foreach($caja->getCajas() as $productocaja)
		{

   			 $pdf->Row(array($productocaja->getCantidad(),$productocaja->getProducto().'
'.$productocaja->getProducto()->getNumero(),number_format($productocaja->getPeso())));


		}
		// Línea de cierre
		$pdf->Cell(array_sum($w),0,'','T');$pdf->Ln();
		$pdf->Cell($w[0]+$w[1],6,'Peso Materia Reglamentada Caja','LR',0,'R');$pdf->SetFont('','B');
		$pdf->Cell($w[2],6,number_format($caja->getPeso()),'LR',0,'R');$pdf->Ln();
		$pdf->Cell(array_sum($w),0,'','T');

		return $pdf;
	}
}
