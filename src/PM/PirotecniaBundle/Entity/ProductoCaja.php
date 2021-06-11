<?php

namespace PM\PirotecniaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;


class ProductoCaja
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $cantidad;

    /**
     * @var float
     */
    private $peso;


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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return ProductoCaja
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set peso
     *
     * @param float $peso
     * @return ProductoCaja
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
     * @var \PM\PirotecniaBundle\Entity\Producto
     */
    private $producto;


    /**
     * Set producto
     *
     * @param \PM\PirotecniaBundle\Entity\Producto $producto
     * @return ProductoCaja
     */
    public function setProducto(\PM\PirotecniaBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return \PM\PirotecniaBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto;
    }
    /**
     * @var \PM\PirotecniaBundle\Entity\Caja
     */
    private $caja;


    /**
     * Set caja
     *
     * @param \PM\PirotecniaBundle\Entity\Caja $caja
     * @return ProductoCaja
     */
    public function setCaja(\PM\PirotecniaBundle\Entity\Caja $caja = null)
    {
        $this->caja = $caja;
    
        return $this;
    }

    /**
     * Get caja
     *
     * @return \PM\PirotecniaBundle\Entity\Caja 
     */
    public function getCaja()
    {
        return $this->caja;
    }
    public function getRiesgoText(){
    	$yaml = new Parser(); try {	$value = $yaml->parse(file_get_contents(__DIR__ . '/../Resources/config/pirotecnia.yml'));
    	} catch (ParseException $e) {
    		printf("Unable to parse the YAML string: %s", $e->getMessage());
    	}
    	if($this->riesgo != null) return $value['divisiones_riesgo'][$this->riesgo];
    }
}