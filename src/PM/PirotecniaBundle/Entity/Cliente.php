<?php

namespace PM\PirotecniaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
/**
 * Cliente
 */
class Cliente
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
    private $nif;

    /**
     * @var \DateTime
     */
    private $fechaalta;

    /**
     * @var string
     */
    private $direccion;

    /**
     * @var string
     */
    private $localidad;

    /**
     * @var string
     */
    private $cp;

    /**
     * @var string
     */
    private $pais;

    /**
     * @var string
     */
    private $telefono;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $movil;

    /**
     * @var string
     */
    private $web;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $cuenta;

    /**
     * @var integer
     */
    private $descuento;

    /**
     * @var boolean
     */
    private $recargo;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var \PM\InicioBundle\Entity\Provincias
     */
    private $provincia_cliente;


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
     * @return Cliente
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
     * Set nif
     *
     * @param string $nif
     * @return Cliente
     */
    public function setNif($nif)
    {
        $this->nif = $nif;
    
        return $this;
    }

    /**
     * Get nif
     *
     * @return string 
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * Set fechaalta
     *
     * @param \DateTime $fechaalta
     * @return Cliente
     */
    public function setFechaalta($fechaalta)
    {
        $this->fechaalta = $fechaalta;
    
        return $this;
    }

    /**
     * Get fechaalta
     *
     * @return \DateTime 
     */
    public function getFechaalta()
    {
        return $this->fechaalta;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Cliente
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Cliente
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;
    
        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set cp
     *
     * @param string $cp
     * @return Cliente
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    
        return $this;
    }

    /**
     * Get cp
     *
     * @return string 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set pais
     *
     * @param string $pais
     * @return Cliente
     */
    public function setPais($pais)
    {
        $this->pais = $pais;
    
        return $this;
    }

    /**
     * Get pais
     *
     * @return string 
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Cliente
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Cliente
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set movil
     *
     * @param string $movil
     * @return Cliente
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;
    
        return $this;
    }

    /**
     * Get movil
     *
     * @return string 
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set web
     *
     * @param string $web
     * @return Cliente
     */
    public function setWeb($web)
    {
        $this->web = $web;
    
        return $this;
    }

    /**
     * Get web
     *
     * @return string 
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     * @return Cliente
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;
    
        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string 
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set descuento
     *
     * @param integer $descuento
     * @return Cliente
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    
        return $this;
    }

    /**
     * Get descuento
     *
     * @return integer 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set recargo
     *
     * @param boolean $recargo
     * @return Cliente
     */
    public function setRecargo($recargo)
    {
        $this->recargo = $recargo;
    
        return $this;
    }

    /**
     * Get recargo
     *
     * @return boolean 
     */
    public function getRecargo()
    {
        return $this->recargo;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Cliente
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set provincia_cliente
     *
     * @param \PM\InicioBundle\Entity\Provincias $provinciaCliente
     * @return Cliente
     */
    public function setProvinciaCliente(\PM\InicioBundle\Entity\Provincias $provinciaCliente = null)
    {
        $this->provincia_cliente = $provinciaCliente;
    
        return $this;
    }

    /**
     * Get provincia_cliente
     *
     * @return \PM\InicioBundle\Entity\Provincias 
     */
    public function getProvinciaCliente()
    {
        return $this->provincia_cliente;
    }
    /**
     * @ORM\PrePersist
     */
    public function setFechaAltaPre()
    {
    	$hoy = new \DateTime();
    	$this->fechaalta = $hoy;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $espectaculo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->espectaculo = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add espectaculo
     *
     * @param \PM\PirotecniaBundle\Entity\Espectaculo $espectaculo
     * @return Cliente
     */
    public function addEspectaculo(\PM\PirotecniaBundle\Entity\Espectaculo $espectaculo)
    {
        $this->espectaculo[] = $espectaculo;
    
        return $this;
    }

    /**
     * Remove espectaculo
     *
     * @param \PM\PirotecniaBundle\Entity\Espectaculo $espectaculo
     */
    public function removeEspectaculo(\PM\PirotecniaBundle\Entity\Espectaculo $espectaculo)
    {
        $this->espectaculo->removeElement($espectaculo);
    }

    /**
     * Get espectaculo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEspectaculo()
    {
        return $this->espectaculo;
    }
    public function __toString()
    {
    	return $this->getNombre();
    }
    public function __sleep(){
    	return array('id', 'nombre', 'nif');
    }
}