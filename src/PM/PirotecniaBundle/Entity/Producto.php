<?php

namespace PM\PirotecniaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Producto
 */
class Producto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $numero;

    /**
     * @var integer
     */
    private $riesgo;

    /**
     * @var float
     */
    private $peso;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cajas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cajas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Producto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Producto
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set riesgo
     *
     * @param integer $riesgo
     * @return Producto
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
     * Set peso
     *
     * @param float $peso
     * @return Producto
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
     * Add cajas
     *
     * @param \PM\PirotecniaBundle\Entity\Caja $cajas
     * @return Producto
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
    	return $this->getNombre();
    }
    public function getRiesgoText(){
    	$yaml = new Parser(); try {	$value = $yaml->parse(file_get_contents(__DIR__ . '/../Resources/config/pirotecnia.yml'));
    	} catch (ParseException $e) {
    		printf("Unable to parse the YAML string: %s", $e->getMessage());
    	}
    	if($this->riesgo != null) return $value['divisiones_riesgo'][$this->riesgo];
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $productos;


    /**
     * Add productos
     *
     * @param \PM\PirotecniaBundle\Entity\ProductoCaja $productos
     * @return Producto
     */
    public function addProducto(\PM\PirotecniaBundle\Entity\ProductoCaja $productos)
    {
        $this->productos[] = $productos;
    
        return $this;
    }

    /**
     * Remove productos
     *
     * @param \PM\PirotecniaBundle\Entity\ProductoCaja $productos
     */
    public function removeProducto(\PM\PirotecniaBundle\Entity\ProductoCaja $productos)
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
     * @var boolean
     */
    private $activo;


    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Producto
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }
    /**
     * @var boolean
     */
    private $disabled;


    /**
     * Set disabled
     *
     * @param boolean $disabled
     * @return Producto
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    
        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean 
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
}