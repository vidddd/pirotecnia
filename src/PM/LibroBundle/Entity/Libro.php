<?php

namespace PM\LibroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
/**
 * Libro
 */
class Libro
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $cartaporte;

    /**
     * @var integer
     */
    private $clase;

    /**
     * @var string
     */
    private $procedenciad;

    /**
     * @var string
     */
    private $folio;

    /**
     * @var integer
     */
    private $asiento;

    /**
     * @var float
     */
    private $entrada;

    /**
     * @var float
     */
    private $salida;

    /**
     * @var float
     */
    private $existencias;

    /**
     * @var float
     */
    private $existencias2;

    /**
     * @var float
     */
    private $existencias11g;

    /**
     * @var float
     */
    private $existencias13g;

    /**
     * @var float
     */
    private $existencias14g;

    /**
     * @var float
     */
    private $existencias14s;

    /**
     * @var integer
     */
    private $ano;

    /**
     * @var integer
     */
    private $producto;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Libro
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
     * Set cartaporte
     *
     * @param string $cartaporte
     * @return Libro
     */
    public function setCartaporte($cartaporte)
    {
        $this->cartaporte = $cartaporte;

        return $this;
    }

    /**
     * Get cartaporte
     *
     * @return string
     */
    public function getCartaporte()
    {
        return $this->cartaporte;
    }

    /**
     * Set clase
     *
     * @param integer $clase
     * @return Libro
     */
    public function setClase($clase)
    {
        $this->clase = $clase;

        return $this;
    }

    /**
     * Get clase
     *
     * @return integer
     */
    public function getClase()
    {
        return $this->clase;
    }

    /**
     * Set procedenciad
     *
     * @param string $procedenciad
     * @return Libro
     */
    public function setProcedenciad($procedenciad)
    {
        $this->procedenciad = $procedenciad;

        return $this;
    }

    /**
     * Get procedenciad
     *
     * @return string
     */
    public function getProcedenciad()
    {
        return $this->procedenciad;
    }

    /**
     * Set folio
     *
     * @param string $folio
     * @return Libro
     */
    public function setFolio($folio)
    {
        $this->folio = $folio;

        return $this;
    }

    /**
     * Get folio
     *
     * @return string
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * Set asiento
     *
     * @param integer $asiento
     * @return Libro
     */
    public function setAsiento($asiento)
    {
        $this->asiento = $asiento;

        return $this;
    }

    /**
     * Get asiento
     *
     * @return integer
     */
    public function getAsiento()
    {
        return $this->asiento;
    }

    /**
     * Set entrada
     *
     * @param float $entrada
     * @return Libro
     */
    public function setEntrada($entrada)
    {
        $this->entrada = $entrada;

        return $this;
    }

    /**
     * Get entrada
     *
     * @return float
     */
    public function getEntrada()
    {
        return $this->entrada;
    }

    /**
     * Set salida
     *
     * @param float $salida
     * @return Libro
     */
    public function setSalida($salida)
    {
        $this->salida = $salida;

        return $this;
    }

    /**
     * Get salida
     *
     * @return float
     */
    public function getSalida()
    {
        return $this->salida;
    }

    /**
     * Set existencias
     *
     * @param float $existencias
     * @return Libro
     */
    public function setExistencias($existencias)
    {
        $this->existencias = $existencias;

        return $this;
    }

    /**
     * Get existencias
     *
     * @return float
     */
    public function getExistencias()
    {
        return $this->existencias;
    }

    /**
     * Set existencias2
     *
     * @param float $existencias2
     * @return Libro
     */
    public function setExistencias2($existencias2)
    {
        $this->existencias2 = $existencias2;

        return $this;
    }

    /**
     * Get existencias2
     *
     * @return float
     */
    public function getExistencias2()
    {
        return $this->existencias2;
    }

    /**
     * Set existencias11g
     *
     * @param float $existencias11g
     * @return Libro
     */
    public function setExistencias11g($existencias11g)
    {
        $this->existencias11g = $existencias11g;

        return $this;
    }

    /**
     * Get existencias11g
     *
     * @return float
     */
    public function getExistencias11g()
    {
        return $this->existencias11g;
    }

    /**
     * Set existencias13g
     *
     * @param float $existencias13g
     * @return Libro
     */
    public function setExistencias13g($existencias13g)
    {
        $this->existencias13g = $existencias13g;

        return $this;
    }

    /**
     * Get existencias13g
     *
     * @return float
     */
    public function getExistencias13g()
    {
        return $this->existencias13g;
    }

    /**
     * Set existencias14g
     *
     * @param float $existencias14g
     * @return Libro
     */
    public function setExistencias14g($existencias14g)
    {
        $this->existencias14g = $existencias14g;

        return $this;
    }

    /**
     * Get existencias14g
     *
     * @return float
     */
    public function getExistencias14g()
    {
        return $this->existencias14g;
    }

    /**
     * Set existencias14s
     *
     * @param float $existencias14s
     * @return Libro
     */
    public function setExistencias14s($existencias14s)
    {
        $this->existencias14s = $existencias14s;

        return $this;
    }

    /**
     * Get existencias14s
     *
     * @return float
     */
    public function getExistencias14s()
    {
        return $this->existencias14s;
    }

    /**
     * Set ano
     *
     * @param integer $ano
     * @return Libro
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }

    /**
     * Get ano
     *
     * @return integer
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Set producto
     *
     * @param integer $producto
     * @return Libro
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return integer
     */
    public function getProducto()
    {
        return $this->producto;
    }
    public function getClaseText() {
      $yaml = new Parser();
      try {
                    $value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
                  } catch (ParseException $e) {printf("Unable to parse the YAML string: %s", $e->getMessage());}
      if($this->clase != null) return $value['divisiones_riesgo'][$this->clase];
    }
    public function getProductoText() {
      $yaml = new Parser();
      try {
                    $value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
                  } catch (ParseException $e) {printf("Unable to parse the YAML string: %s", $e->getMessage());}
      if($this->producto != null) return $value['tipos_producto'][$this->producto];
    }
    /**
     * @var integer
     */
    private $mes;


    /**
     * Set mes
     *
     * @param integer $mes
     * @return Libro
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    
        return $this;
    }

    /**
     * Get mes
     *
     * @return integer 
     */
    public function getMes()
    {
        return $this->mes;
    }
}