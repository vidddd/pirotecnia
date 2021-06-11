<?php
namespace PM\LibroBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use PM\LibroBundle\Form\InformeDiarioFilterType;
use PM\LibroBundle\Form\InformeMensualFilterType;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class InformeController extends Controller
{
    public function indexAction()
    {
        return $this->render('PMLibroBundle:Default:index.html.twig');
    }
    public function diarioAction()
    {
        $request = $this->getRequest();$session = $request->getSession();

        if($this->getRequest()->get('cuantos')) { $cuantos = $this->getRequest()->get('cuantos');
        } else if ($session->get('InformeDiarioCuantos')){
          $cuantos = $session->get('InformeDiarioCuantos');
        } else {  $cuantos = 20; }

        $cuantosarr = array('10' => '10','25' => '25','50' => '50','100' => '100');
        if($cuantos) $session->set('InformeDiarioCuantos', $cuantos);
        ($cuantos)? $entradas = $cuantos : $entradas = 20;

        list($filterForm, $queryBuilder) = $this->filterDiario();

        if($filterForm->getErrors() || !$filterForm->getData()){
          $entities = array(); $pagerHtml = array(); $filterData = array();
        } else {
          // Segun ha pulsado la busqueda las existencias a mostrar son distintas
          $filterData = $filterForm->getData();
          list($entities, $pagerHtml) = $this->paginatorDiario($queryBuilder, $cuantos);
        }
        return $this->render('PMLibroBundle:Informe:diario.html.twig', array(
          'filterForm' => $filterForm->createView(), 'entities' => $entities, 'pagerHtml' => $pagerHtml,	'cuantos' => $cuantosarr,	'entradas' => $entradas,'filterData' => $filterData)
        );
    }

    protected function filterDiario()
    {
        $request = $this->getRequest();  $session = $request->getSession();
        $filterForm = $this->createForm(new InformeDiarioFilterType());
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('PMLibroBundle:Libro')->createQueryBuilder('e')->orderBy('e.id', 'DESC');

        if ($request->get('filter_action') == 'reset') {
            $session->remove('InformeDiarioFilter');
        }

        if ($request->get('filter_action') == 'filter') {
            $filterForm->bind($request);

            if ($filterForm->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                $filterData = $filterForm->getData();
                $session->set('InformeDiarioFilter', $filterData);
            }
        } else {
            if ($session->has('InformeDiarioFilter')) {
                $filterData = $session->get('InformeDiarioFilter');
                $filterForm = $this->createForm(new InformeDiarioFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }
        return array($filterForm, $queryBuilder);
    }

    public function mensualAction()
    {
      list($form,$iniciales,$entradas, $salidas,
            $mes,$mesn,$ano,$clase11g,$clase13g,$clase14g,$clase14s) = $this->filterMensual();

      return $this->render('PMLibroBundle:Informe:mensual.html.twig', array(
        'iniciales' => $iniciales, 'entradas' => $entradas, 'salidas' => $salidas, 'mes' => $mes,'mesn'=> $mesn, 'ano' => $ano, 'clase11g' => $clase11g, 'clase13g' => $clase13g,
        'clase14g' => $clase14g, 'clase14s' => $clase14s,
        'filterForm' => $form->createView(),
      ));
    }

    private function filterMensual()
    {

      $request = $this->getRequest();	$session = $request->getSession(); $em = $this->getDoctrine()->getManager();$defaultData = array();
      $mes = ''; $ano = ''; $mesn = ''; $iniciales = 0; $entradas = 0; $salidas = 0; $mespalabra = ''; $totales = array();$totales3 = array(); $clase11g = 0; $clase13g = 0; $clase14g = 0; $clase14s=0;
      $yaml = new Parser();
      try {
        $value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
      } catch (ParseException $e) {
        printf("Unable to parse the YAML string: %s", $e->getMessage());
      }
      $anos = $value['anos'];
      $meses = array("" => "", "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");

      $form = $this->createFormBuilder($defaultData)
        ->add('meses', 'choice', array( 'choices' => $meses, 'multiple'=> false, 'expanded' => false, 'error_bubbling' => true, 'empty_value' => '', 'required' => true, 'data' => date('m')))
        ->add('anos', 'choice', array( 'choices' => $anos,'data' => 'column', 'multiple'=> false,'expanded' => false, 'error_bubbling' => true, 'empty_value' => '', 'required' => true, 'data' => date('Y')))
      ->getForm();

      // Reset filter
      if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'reset') {
          $session->remove('MensualControllerFilter');
      }

      // Filter action
      if ($request->getMethod() == 'POST' && $request->get('filter_action') == 'filter') {
         $form->bind($request); $filtra = true;

        if ($form->isValid()) {
          $filterData = $form->getData();
          $session->set('MensualControllerFilter', $filterData);
          $filtra= true;$mes = $form["meses"]->getData();$ano = $form["anos"]->getData();
          $mespalabra = $meses[$mes];
          if(!$anos) {
            $this->get('session')->getFlashBag()->add('error', 'Debe Seleccionar algún año'); $filtra = false;
          } else if ($filtra) {
             $iniciales = $em->getRepository('PMLibroBundle:Libro')->getExistenciasAnteriores($mes, $ano );

             $act = $em->getRepository('PMLibroBundle:Libro')->getExistenciasActuales($mes, $ano );

             if($act) {
               $entradas = $act[0]['entradas'];$salidas = $act[0]['salidas'];
             }

             $clase11g = $em->getRepository('PMLibroBundle:Libro')->getTotalClase(5);
             $clase13g = $em->getRepository('PMLibroBundle:Libro')->getTotalClase(7);

             $clase14g = $em->getRepository('PMLibroBundle:Libro')->getTotalClase(8);
             $clase14s = $em->getRepository('PMLibroBundle:Libro')->getTotalClase(9);
        
          }
          if(!$act && $filtra) {
            $this->get('session')->getFlashBag()->add('error', 'Ningun resultado, modifique los parámetros de consulta');
          }
        }
      }
      return array($form,$iniciales,$entradas, $salidas, $mespalabra, $mes, $ano,$clase11g,
                  $clase13g,
                  $clase14g,
                  $clase14s);
    }


    protected function paginatorDiario($queryBuilder)
    {
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1); $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();  $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('informe_diario', array('page' => $page));
        };

        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));
        return array($entities, $pagerHtml);
    }

    public function printDiarioAction()
    {
      list($filterForm, $query) = $this->filterDiario();
    	$printer = $this->container->get('libro.print_diario');
    	$pdf = $printer->getFicheroFPDF($query, $filterForm->getData());
    	$response = new Response();
    	//$response->clearHttpHeaders();
    	$response->setContent($pdf);
    	 //$response->headers->set('Content-Type', 'application/force-download');
    	$response->headers->set('Content-Type', 'application/pdf');
    	return $response;
    }

    public function printMensualAction($mes,$mesn,$ano)
    {
      $printer = $this->container->get('libro.print_mensual');
      $pdf = $printer->getFicheroFPDF($mes,$mesn,$ano);
      $response = new Response();
      $response->setContent($pdf);
      $response->headers->set('Content-Type', 'application/pdf');
      return $response;
    }
}
