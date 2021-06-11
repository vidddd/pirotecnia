<?php
namespace PM\PirotecniaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class HoraTransformer implements DataTransformerInterface
{
	private $om;

	public function __construct()
	{
	}

	/**
	 * Transforms an object (issue) to a string (number).
	 *
	 * @param  Issue|null $issue
	 * @return string
	 */
	public function transform($dateTime)
	{
	   if (null === $dateTime) {
	        return null;
	    }
    	return $dateTime->format('H:i');
	}

	/**
	 * Transforma DD/MM/YYYY en  YYYY-MM-DD
	 */
	public function reverseTransform($hora)
	{
	    if ( (null === $hora) || empty($hora) ) {
	        return null; 
	    }
	    $hora = str_replace(" PM", "", $hora);
	    $hora = str_replace(" AM", "", $hora);
	    
	    $horas = preg_split('/:/',$hora);
		$ho = $horas[0];
		$min = $horas[1];
		$dat = new \DateTime($ho.':'.$min.':00');  
		return $dat; 
	}
}