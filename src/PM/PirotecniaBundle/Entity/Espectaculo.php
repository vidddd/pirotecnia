<?php

namespace PM\PirotecniaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Espectaculo
 */
class Espectaculo
{
    /**
     * @var integer
     */
    protected $id;


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
     * @var \DateTime
     */
    protected $fecha;

    /**
     * @var \DateTime
     */
    protected $hora;

    /**
     * @var float
     */
    protected $peso;

    /**
     * @var integer
     */
    protected $cajas;


    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Espectaculo
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     * @return Espectaculo
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    
        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set peso
     *
     * @param float $peso
     * @return Espectaculo
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
    
        return $this;
    }

    /**
     * Get peso
     *
     * @return float 
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set cajas
     *
     * @param integer $cajas
     * @return Espectaculo
     */
    public function setCajas($cajas)
    {
        $this->cajas = $cajas;
    
        return $this;
    }

    /**
     * Get cajas
     *
     * @return integer 
     */
    public function getCajas()
    {
        return $this->cajas;
    }
    /**
     * @var integer
     */
    protected $numcajas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cajas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set numcajas
     *
     * @param integer $numcajas
     * @return Espectaculo
     */
    public function setNumcajas($numcajas)
    {
        $this->numcajas = $numcajas;
    
        return $this;
    }

    /**
     * Get numcajas
     *
     * @return integer 
     */
    public function getNumcajas()
    {
        return $this->numcajas;
    }

    /**
     * Add cajas
     *
     * @param \PM\PirotecniaBundle\Entity\Caja $cajas
     * @return Espectaculo
     */
    public function addCaja(\PM\PirotecniaBundle\Entity\Caja $cajas)
    {
        $this->cajas[] = $cajas;
    
        return $this;
    }

    /**
     * Remove cajas
     *
     * @param \PM\PirotecniaBundle\Entity\Caja $cajas
     */
    public function removeCaja(\PM\PirotecniaBundle\Entity\Caja $cajas)
    {
        $this->cajas->removeElement($cajas);
    }
    /**
     * @var \PM\PirotecniaBundle\Entity\Cliente
     */
    protected $cliente;


    /**
     * Set cliente
     *
     * @param \PM\PirotecniaBundle\Entity\Cliente $cliente
     * @return Espectaculo
     */
    public function setCliente(\PM\PirotecniaBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
        return $this;
    }

    /**
     * Get cliente
     *
     * @return \PM\PirotecniaBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }
    public function __toString()
    {
    	return "".$this->getId();
    }
    public function __sleep(){
    		   return array('id', 'cliente');
   	}
    /**
     * @var string
     */
    private $descripcion;


    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Espectaculo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getNumeroCajas(){
    	return count($this->cajas);
    }
    public function getpesoTotal(){
    	$total = 0;
    	foreach ($this->cajas as $caja){
    		$total += $caja->getPeso();
    	}
    	return $total;
    }
}