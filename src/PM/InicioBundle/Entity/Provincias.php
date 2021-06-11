<?php

namespace PM\InicioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provincias
 */
class Provincias
{
    /**
     * @var integer
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var string
     */
    private $codprovincia;

    /**
     * @var string
     */
    private $denprovincia;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $provincia2;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->provincia2 = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set codprovincia
     *
     * @param string $codprovincia
     * @return Provincias
     */
    public function setCodprovincia($codprovincia)
    {
        $this->codprovincia = $codprovincia;
    
        return $this;
    }

    /**
     * Get codprovincia
     *
     * @return string 
     */
    public function getCodprovincia()
    {
        return $this->codprovincia;
    }

    /**
     * Set denprovincia
     *
     * @param string $denprovincia
     * @return Provincias
     */
    public function setDenprovincia($denprovincia)
    {
        $this->denprovincia = $denprovincia;
    
        return $this;
    }

    /**
     * Get denprovincia
     *
     * @return string 
     */
    public function getDenprovincia()
    {
        return $this->denprovincia;
    }

    public function __toString()
    {
    	return $this->getDenprovincia();
    }
    

    /**
     * Add provincia2
     *
     * @param \PM\PirotecniaBundle\Entity\Cliente $provincia2
     * @return Provincias
     */
    public function addProvincia2(\PM\PirotecniaBundle\Entity\Cliente $provincia2)
    {
        $this->provincia2[] = $provincia2;
    
        return $this;
    }

    /**
     * Remove provincia2
     *
     * @param \PM\PirotecniaBundle\Entity\Cliente $provincia2
     */
    public function removeProvincia2(\PM\PirotecniaBundle\Entity\Cliente $provincia2)
    {
        $this->provincia2->removeElement($provincia2);
    }

    /**
     * Get provincia2
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProvincia2()
    {
        return $this->provincia2;
    }
}