<?php
// src/PB/PirotecniaBundle/EventListener/FechasListener.php
namespace PM\PirotecniaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use PM\PirotecniaBundle\Entity\Espectaculo;

class FechasListener
{
	
	public function prePersist(LifecycleEventArgs $args)
	{
/*
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();

		if ($entity instanceof Espectaculo) {
			//echo "Espectaculo"; die;
		}*/
	}
}