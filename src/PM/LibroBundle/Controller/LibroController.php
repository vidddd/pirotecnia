<?php
namespace PM\LibroBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use PM\LibroBundle\Entity\Libro;
use PM\LibroBundle\Form\LibroType;
use PM\LibroBundle\Form\LibroFilterType;

class LibroController extends Controller
{
    public function indexAction()
    {
      $request = $this->getRequest();$session = $request->getSession();
      $entity = new Libro();
      $form = $this->createForm(new LibroType(), $entity);$hoy = new \DateTime(); $entity->setFecha($hoy);
      if($this->getRequest()->get('cuantos')) { $cuantos = $this->getRequest()->get('cuantos');
      } else if ($session->get('LibroCuantos')){
        $cuantos = $session->get('LibroCuantos');
      } else {  $cuantos = 20; }

      $cuantosarr = array('10' => '10','25' => '25','50' => '50','100' => '100');
      if($cuantos) $session->set('LibroCuantos', $cuantos);
      ($cuantos)? $entradas = $cuantos : $entradas = 20;

      list($filterForm, $queryBuilder) = $this->filter();
      list($entities, $pagerHtml) = $this->paginator($queryBuilder, $cuantos);

        return $this->render('PMLibroBundle:Libro:index.html.twig', array(
            'entities' => $entities,'form'   => $form->createView(),
            'pagerHtml' => $pagerHtml,	'cuantos' => $cuantosarr,	'entradas' => $entradas,
            'filterForm' => $filterForm->createView(),
        ));
    }

    protected function filter()
    {
        $request = $this->getRequest(); $session = $request->getSession();$filterForm = $this->createForm(new LibroFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PMLibroBundle:Libro')->createQueryBuilder('e')->orderBy('e.id', 'DESC');
        if ($request->get('filter_action') == 'reset') {
            $session->remove('LibroControllerFilter');
        }

        if ($request->get('filter_action') == 'filter') {
            $filterForm->bind($request);
            if ($filterForm->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('LibroControllerFilter', $filterData);
            }
        } else {
            if ($session->has('LibroControllerFilter')) {
                $filterData = $session->get('LibroControllerFilter');
                $filterForm = $this->createForm(new LibroFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }
        return array($filterForm, $queryBuilder);
    }

    protected function paginator($queryBuilder)
    {
        $adapter = new DoctrineORMAdapter($queryBuilder);$pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);$pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('libro', array('page' => $page));
        };

        $translator = $this->get('translator');$view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));
        return array($entities, $pagerHtml);
    }

    public function createAction(Request $request)
    {
        $entity  = new Libro();
        $form = $this->createForm(new LibroType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setFecha(new \DateTime());
            $fec = $entity->getFecha();
            $mesres = $fec->format('m');
            $mesactual = date('m'); $anoactual = date('Y');
            if($mesres != $mesactual) {
              $this->get('session')->getFlashBag()->add('error', 'No se pueden introducir registros que no sean del mes actual');
                  return $this->redirect($this->generateUrl('libro'));
            }

            if($entity->getFecha() < new \DateTime()) {
              $this->get('session')->getFlashBag()->add('error', 'No se pueden introducir registros con Fecha menor a la de hoy');
                  return $this->redirect($this->generateUrl('libro'));
            }
            $entity->setAno($anoactual);$entity->setMes($mesactual);
            $em->persist($entity);$em->flush();
            return $this->redirect($this->generateUrl('libro'));
        }

        return $this->render('PMLibroBundle:Libro:new.html.twig', array(
            'entity' => $entity,'form'   => $form->createView(),
        ));
    }

    public function newAction()
    {
        $entity = new Libro();
        $form   = $this->createForm(new LibroType(), $entity);
        return $this->render('PMLibroBundle:Libro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PMLibroBundle:Libro')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Libro entity.');
        }
        return $this->render('PMLibroBundle:Libro:show.html.twig', array( 'entity'      => $entity   ));
    }

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PMLibroBundle:Libro')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Libro entity.');
        }
        $editForm = $this->createForm(new LibroType(), $entity);
        return $this->render('PMLibroBundle:Libro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PMLibroBundle:Libro')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Libro entity.');
        }
        $editForm = $this->createForm(new LibroType(), $entity);
        $editForm->bind($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');
            return $this->redirect($this->generateUrl('libro_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }
        return $this->render('PMLibroBundle:Libro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    public function deleteAction($id)
    {
      if ($id) {
          $em = $this->getDoctrine()->getManager();
          $entity = $em->getRepository('PMLibroBundle:Libro')->find($id);
          if (!$entity) { throw $this->createNotFoundException('Unable to find Libro entity.'); }
          $fec = $entity->getFecha();
          $mesres = $fec->format('m');
          $mesactual = date('m');
          if($mesres != $mesactual) {
            $this->get('session')->getFlashBag()->add('error', 'No se pueden eliminar Registros que no sean del mes actual');
          }

          else {
          $em->remove($entity);
          $em->flush();
          }
      } else {
          $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
      }
      return $this->redirect($this->generateUrl('libro'));
    }
}
