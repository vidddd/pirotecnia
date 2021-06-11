<?php

namespace PM\LibroBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LibroRepository extends EntityRepository
{
  public function getTotalexis()
	{
		$result = $this->getEntityManager()
		->createQuery('SELECT l FROM PMLibroBundle:Libro l ORDER BY l.id DESC')->setMaxResults(1)
		->getResult();
     if($result) {
        $li = $result[0];
        return $li->getExistencias();
      } else { return 0; }
	}
  public function getTotalexis11g()
  {
    $result = $this->getEntityManager()
    ->createQuery('SELECT l FROM PMLibroBundle:Libro l ORDER BY l.id DESC')->setMaxResults(1)
    ->getResult();
     if($result) {
        $li = $result[0];
        return $li->getExistencias11g();
      } else { return 0; }
  }
  public function getTotalexis13g()
  {
    $result = $this->getEntityManager()
    ->createQuery('SELECT l FROM PMLibroBundle:Libro l ORDER BY l.id DESC')->setMaxResults(1)
    ->getResult();
     if($result) {
        $li = $result[0];
        return $li->getExistencias13g();
      } else { return 0; }
  }
  public function getTotalexis14g()
  {
    $result = $this->getEntityManager()
    ->createQuery('SELECT l FROM PMLibroBundle:Libro l ORDER BY l.id DESC')->setMaxResults(1)
    ->getResult();
     if($result) {
        $li = $result[0];
        return $li->getExistencias14g();
      } else { return 0; }
  }
  public function getTotalexis14s()
  {
    $result = $this->getEntityManager()
    ->createQuery('SELECT l FROM PMLibroBundle:Libro l ORDER BY l.id DESC')->setMaxResults(1)
    ->getResult();
     if($result) {
        $li = $result[0];
        return $li->getExistencias14s();
      } else { return 0; }
  }
  public function getTotalexisIntermedio()
	{
		$result = $this->getEntityManager()
		->createQuery('SELECT l FROM PMLibroBundle:Libro l ORDER BY l.id DESC')->setMaxResults(1)
		->getResult();
     if($result) {
        $li = $result[0];
        return $li->getExistencias2();
      } else { return 0; }
	}

  public function getMensual($mes, $ano)
  {
    ($mes == '')? $wheremes = '': $wheremes = 'AND MONTH(e.fecha) = :mes ';
    ($ano == '')? $whereano = '': $whereano = 'AND YEAR(e.fecha) = :ano ';
    $st = 'SELECT DISTINCT YEAR(e.fecha) as Ano, MONTHNAME(e.fecha) AS Mes, SUM(e.entrada) as entradas, SUM(e.salida) as salidas FROM Libro e WHERE 1 '.$whereano.$wheremes.' GROUP BY MONTH(e.fecha)';
    $stmt = $this->getEntityManager()->getConnection()
        ->prepare($st);
    if($ano != '') $stmt->bindValue('ano', $ano);
    if($mes != '') $stmt->bindValue('mes', $mes);
    $stmt->execute();
    return $stmt->fetchAll();
    return $result;
  }

  public function getMensualClase($mes, $ano,$clase)
  {
    ($mes == '')? $wheremes = '': $wheremes = 'AND MONTH(e.fecha) = :mes ';
    ($ano == '')? $whereano = '': $whereano = 'AND YEAR(e.fecha) = :ano ';
    ($clase == '')? $whereclase = '': $whereclase = 'AND e.clase = :clase ';

    $st = 'SELECT DISTINCT YEAR(e.fecha) as Ano, MONTHNAME(e.fecha) AS Mes, SUM(e.entrada) as entradas, SUM(e.salida) as salidas FROM Libro e WHERE 1 '.$whereano.$wheremes.$whereclase.'';

     $stmt = $this->getEntityManager()->getConnection()
        ->prepare($st);
    if($ano != '') $stmt->bindValue('ano', $ano);
    if($ano != '') $stmt->bindValue('mes', $mes);
    if($clase != '') $stmt->bindValue('clase', $clase);
    $stmt->execute();
    return $stmt->fetchAll();

    return $result;
  }

  public function getTotalClase($clase)
  {
    ($clase == '')? $whereclase = '': $whereclase = 'AND e.clase = :clase ';

  //  $st = 'SELECT DISTINCT YEAR(e.fecha) as Ano, MONTHNAME(e.fecha) AS Mes, SUM(e.entrada) as entradas, SUM(e.salida) as salidas FROM Libro e WHERE 1 '.$whereclase.'';
    $st = 'SELECT DISTINCT SUM(e.entrada) as entradas, SUM(e.salida) as salidas FROM Libro e WHERE 1 '.$whereclase.'';

     $stmt = $this->getEntityManager()->getConnection()
        ->prepare($st);

    if($clase != '') $stmt->bindValue('clase', $clase);
    $stmt->execute();
    $res = $stmt->fetchAll();
  //  print_r($res);
    return $res[0]['entradas'] - $res[0]['salidas'];
  }
   // Obtienes las ultimas exsitencias del mes anterior
  public function getExistenciasAnteriores($mes,$ano)
  {
  /*  if($mes == 1) {
      $mes = 12;
    } else { $mes = $mes - 1; }*/

    ($mes == '')? $wheremes = '': $wheremes = 'AND e.mes < :mes ';
    ($ano == '')? $whereano = '': $whereano = 'AND e.ano <= :ano ';
    $st = 'SELECT DISTINCT SUM(e.entrada) as entradas, SUM(e.salida) as salidas FROM Libro e WHERE 1 '.$whereano.$wheremes.'';

    $stmt = $this->getEntityManager()->getConnection()->prepare($st);
    if($ano != '') $stmt->bindValue('ano', $ano);
    if($ano != '') $stmt->bindValue('mes', (integer)$mes);
    $stmt->execute();
    $res = $stmt->fetchAll();

    return $res[0]['entradas'] - $res[0]['salidas'];

  }

  public function getExistenciasActuales($mes,$ano)
  {
    ($mes == '')? $wheremes = '': $wheremes = 'AND MONTH(e.fecha) = :mes ';
    ($ano == '')? $whereano = '': $whereano = 'AND YEAR(e.fecha) = :ano ';
    $st = 'SELECT DISTINCT YEAR(e.fecha) as Ano, MONTHNAME(e.fecha) AS Mes, SUM(e.entrada) as entradas, SUM(e.salida) as salidas FROM Libro e WHERE 1 '.$whereano.$wheremes.'';
    $stmt = $this->getEntityManager()->getConnection()->prepare($st);
    if($ano != '') $stmt->bindValue('ano', $ano);
    if($ano != '') $stmt->bindValue('mes', $mes);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
