<?php

namespace PM\PirotecniaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
/**
 * Caja
 */
class Caja
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $peso;

    /**
     * @var \PM\PirotecniaBundle\Entity\Espectaculo
     */
    private $espectaculo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $productos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set peso
     *
     * @param float $peso
     * @return Caja
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
     * Set espectaculo
     *
     * @param \PM\PirotecniaBundle\Entity\Espectaculo $espectaculo
     * @return Caja
     */
    public function setEspectaculo(\PM\PirotecniaBundle\Entity\Espectaculo $espectaculo = null)
    {
        $this->espectaculo = $espectaculo;
    
        return $this;
    }

    /**
     * Get espectaculo
     *
     * @return \PM\PirotecniaBundle\Entity\Espectaculo 
     */
    public function getEspectaculo()
    {
        return $this->espectaculo;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cajas;


    /**
     * Add cajas
     *
     * @param \PM\PirotecniaBundle\Entity\ProductoCaja $cajas
     * @return Caja
     */
    public function addCaja(\PM\PirotecniaBundle\Entity\ProductoCaja $cajas)
    {
        $this->cajas[] = $cajas;
    
        return $this;
    }
    /**
     * Add productos
     *
     * @param \PM\PirotecniaBundle\Entity\Producto $productos
     * @return Caja
     */
    public function addProducto(\PM\PirotecniaBundle\Entity\Producto $productos)
    {
    	
    	 $this->productos[] = $productos;
    	return $this;
    }
    
    /**
     * Remove productos
     *
     * @param \PM\PirotecniaBundle\Entity\Producto $productos
     */
    public function removeProducto(\PM\PirotecniaBundle\Entity\Producto $productos)
    {
    	$this->productos->removeElement($productos);
    }
    
    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductos()
    {
    	return $this->productos;
    }
    /**
     * Remove cajas
     *
     * @param \PM\PirotecniaBundle\Entity\ProductoCaja $cajas
     */
    public function removeCaja(\PM\PirotecniaBundle\Entity\ProductoCaja $cajas)
    {
        $this->cajas->removeElement($cajas);
    }

    /**
     * Get cajas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCajas()
    {
        return $this->cajas;
    }
    public function __toString()
    {
    	return "".$this->getId();
    }
    /**
     * @var integer
     */
    private $riesgo;

    /**
     * @var float
     */
    private $pesobruto;


    /**
     * Set riesgo
     *
     * @param integer $riesgo
     * @return Caja
     */
    public function setRiesgo($riesgo)
    {
        $this->riesgo = $riesgo;
    
        return $this;
    }

    /**
     * Get riesgo
     *
     * @return integer 
     */
    public function getRiesgo()
    {
        return $this->riesgo;
    }

    /**
     * Set pesobruto
     *
     * @param float $pesobruto
     * @return Caja
     */
    public function setPesobruto($pesobruto)
    {
        $this->pesobruto = $pesobruto;
    
        return $this;
    }

    /**
     * Get pesobruto
     *
     * @return float 
     */
    public function getPesobruto()
    {
        return $this->pesobruto;
    }
    public function getRiesgoText(){
    	$yaml = new Parser(); try {	$value = $yaml->parse(file_get_contents(__DIR__ . '/../Resources/config/pirotecnia.yml'));
    	} catch (ParseException $e) {
    		printf("Unable to parse the YAML string: %s", $e->getMessage());
    	}
    	if($this->riesgo != null) return $value['divisiones_riesgo'][$this->riesgo];
    }
}