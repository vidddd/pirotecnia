<?php
namespace PM\PirotecniaBundle\Printer;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class PrintEspectaculoFPDF {
	/**
	 *
	 * @var EntityManager
	 */
	protected $em;

	public function __construct(Doctrine $doctrine)
	{
		$this->em  = $doctrine->getEntityManager();
	}

	public function printFPDF($id)
	{

		$espectaculo = $this->em->getRepository('PMPirotecniaBundle:Espectaculo')->find($id);
		$pdf = new ComunesFPDF('P','mm',array(105,148));
		$numcaja = 1;
		$totalcajas = $espectaculo->getNumeroCajas();			$pdf->setTotalcajas($totalcajas);
		$pdf->setCliente($espectaculo->getCliente());

		$pdf->setDestino($espectaculo->getCliente()->getLocalidad());
		$pdf->setProvincia($espectaculo->getCliente()->getProvinciaCliente());
		$pdf->setFecha($espectaculo->getFecha()->format('d/m/Y'));

		foreach($espectaculo->getCajas() as $caja) {
			$pdf->setNumcaja($numcaja);
			$pdf->setPesobruto($caja->getPesobruto());
			$pdf = PrintCajaFPDF::printFPDF($pdf,$espectaculo, $caja, $numcaja);

			$pdf->setRiesgo($caja->getRiesgoText());
			$pdf->setPesototal($espectaculo->getPesoTotal());
			$numcaja++;
		}
	   return $pdf;
	}

}
