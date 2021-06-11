<?php
namespace PM\PirotecniaBundle\Printer;

class ComunesFPDF extends \FPDF_FPDF {
	protected $numcaja;
	protected $pesobruto;
	protected $riesgo;
	protected $totalcajas;
	protected $pestotal;

	function Header()
	{
		$this->SetY(2);
		$this->SetFont('Arial','BI',14);
		$this->Cell(71,10,'Pirotecnia Manchega, S.L.');
		$this->SetFont('Arial','',10);
		$this->Cell(8,10,'Caja');
		$this->SetFont('Arial','BI',13);
		$this->Cell(10,10,$this->numcaja." / ".$this->totalcajas);
		$this->Ln(6);
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'Paraje de los Llanos S/N');
		$this->Ln(5);
		$this->SetFont('Arial','',10);
		$this->Cell(20,10,'45710 - Madridejos (TOLEDO)');
		$this->Ln(5);
		$this->SetFont('Arial','',10);
		$this->Cell(30,10,'Tlfns: 925-460355 // 608-913684 // 630-702897');		$this->Ln(9);
	}

	function Footer()
	{
		$this->SetY(-18);
		$this->SetFont('helvetica','',10);
		$this->Cell(16,6,utf8_decode('D. Riesgo'));
		$this->SetFont('helvetica','B',11);
		$this->Cell(16,6,$this->riesgo);
		$this->SetFont('helvetica','',10);
		$this->Cell(38,6,utf8_decode('Peso Total Espectáculo'));
		$this->SetFont('helvetica','B',11);
		$this->Cell(12,6,number_format($this->pesototal).' Gr');
		$this->Ln();

		$this->SetFont('Courier','',9);
		$this->Cell(0,10,utf8_decode('www.pirotecniamanchega.com'),0,0,'C');
	}

	public function setNumcaja($numcaja){
		$this->numcaja = $numcaja;
	}
	public function setPesobruto($value){
		$this->pesobruto = $value;
	}
	public function setRiesgo($value){
		$this->riesgo = $value;
	}
	public function setTotalcajas($value){
		$this->totalcajas = $value;

	}
	public function setPesototal($value){
		$this->pesototal = $value;
	}
	var $widths;
	var $aligns;

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			$h=5*$nb;
			//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
		$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
		}

		function CheckPageBreak($h)
		{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
		}

		function NbLines($w,$txt)
		{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
				$sep=-1;
				$i=0;
				$j=0;
			$l=0;
			$nl=1;
					while($i<$nb)
					{
					$c=$s[$i];
					if($c=="\n")
					{
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
		}
		if($c==' ')
			$sep=$i;
			$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
			if($i==$j)
				$i++;
		}
		else
			$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
		}
		return $nl;
		}
}
