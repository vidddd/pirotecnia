<?php
namespace PM\PirotecniaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use PM\PirotecniaBundle\Entity\Espectaculo;

class FechaTransformer implements DataTransformerInterface
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
			if (is_object($dateTime)) {
					return $dateTime->format('d/m/Y');
			} else return $dateTime;
			

	}

	/**
	 * Transforma DD/MM/YYYY en  YYYY-MM-DD
	 */
	public function reverseTransform($date)
	{
	    if ( (null === $date) || empty($date) ) {
	        return null;
	    }
	    $dates = preg_split('/\//',$date);
		$day = $dates[0];
		$month = $dates[1];
		$year = $dates[2];
		$dat = new \DateTime($year.'-'.$month.'-'.$day);
		//return $year.'-'.$month.'-'.$day;
		return $dat;
	}
}
