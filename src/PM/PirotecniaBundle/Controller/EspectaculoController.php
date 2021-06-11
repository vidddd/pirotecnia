<?php

namespace PM\PirotecniaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use PM\PirotecniaBundle\Entity\Espectaculo;
use PM\PirotecniaBundle\Form\EspectaculoType;
use PM\PirotecniaBundle\Form\EspectaculoFilterType;
use PM\PirotecniaBundle\Form\CajaType;
use PM\PirotecniaBundle\Entity\Caja;
use PM\PirotecniaBundle\Entity\ProductoCaja;

class EspectaculoController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $entity = new Espectaculo();
        $form = $this->createForm(new EspectaculoType(), $entity);

        if ($this->getRequest()->get('cuantos')) {
            $cuantos = $this->getRequest()->get('cuantos');
        } else if ($session->get('EspectaculoCuantos')) {
            $cuantos = $session->get('EspectaculoCuantos');
        } else {
            $cuantos = 20;
        }

        $cuantosarr = array('10' => '10', '25' => '25', '50' => '50', '100' => '100');
        if ($cuantos) $session->set('EspectaculoCuantos', $cuantos);
        ($cuantos) ? $entradas = $cuantos : $entradas = 20;

        list($filterForm, $queryBuilder) = $this->filter();
        list($entities, $pagerHtml) = $this->paginator($queryBuilder, $cuantos);

        return $this->render('PMPirotecniaBundle:Espectaculo:index.html.twig', array(
            'entities' => $entities, 'form'   => $form->createView(),
            'pagerHtml' => $pagerHtml,    'cuantos' => $cuantosarr,    'entradas' => $entradas,
            'filterForm' => $filterForm->createView(),
        ));
    }

    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new EspectaculoFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PMPirotecniaBundle:Espectaculo')->createQueryBuilder('e')->orderBy('e.id', 'DESC');

        if ($request->get('filter_action') == 'reset') {
            $session->remove('EspectaculoControllerFilter');
        }

        if ($request->get('filter_action') == 'filter') {
            $filterForm->bind($request);
            if ($filterForm->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);

                $filterData = $filterForm->getData();
                $session->set('EspectaculoControllerFilter', $filterData);
            }
        } else {
            if ($session->has('EspectaculoControllerFilter')) {
                $filters =  $session->get('EspectaculoControllerFilter');
                foreach ($filters as $key => $filter) {
                    if (is_object($filter)) {
                        $filters[$key] = $em->merge($filter);
                    }
                }
                $filterForm = $this->createForm(new EspectaculoFilterType(), $filters);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }
        // var_dump($queryBuilder->getDql());
        return array($filterForm, $queryBuilder);
    }

    protected function paginator($queryBuilder, $cuantos)
    {
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $pagerfanta->setMaxPerPage($cuantos);
        $entities = $pagerfanta->getCurrentPageResults();
        $me = $this;
        $routeGenerator = function ($page) use ($me) {
            return $me->generateUrl('espectaculo', array('page' => $page));
        };
        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => '← Anteriores',
            'next_message' => 'Siguientes →',
        ));

        return array($entities, $pagerHtml);
    }

    public function createAction(Request $request)
    {
        $entity  = new Espectaculo();
        $form = $this->createForm(new EspectaculoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('espectaculo'));
        }

        return $this->render('PMPirotecniaBundle:Espectaculo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function newAction()
    {
        $entity = new Espectaculo();
        $form   = $this->createForm(new EspectaculoType(), $entity);

        return $this->render('PMPirotecniaBundle:Espectaculo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Espectaculo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Espectaculo entity.');
        }

        return $this->render('PMPirotecniaBundle:Espectaculo:show.html.twig', array(
            'espectaculo'      => $entity,
        ));
    }

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $espectaculo = $em->getRepository('PMPirotecniaBundle:Espectaculo')->find($id);
        if (!$espectaculo) {
            throw $this->createNotFoundException('Unable to find Espectaculo entity.');
        }

        $caja = new Caja();
        $caja->setEspectaculo($espectaculo);

        $editForm = $this->createForm(new EspectaculoType(), $espectaculo);

        $cajaform = $this->createForm(new CajaType(), $caja);

        return $this->render('PMPirotecniaBundle:Espectaculo:edit.html.twig', array(
            'espectaculo'      => $espectaculo,
            'form'   => $editForm->createView(), 'cajaform' => $cajaform->createView(),
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Espectaculo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Espectaculo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EspectaculoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('espectaculo'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        return $this->render('PMPirotecniaBundle:Espectaculo:edit.html.twig', array(
            'espectaculo'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function anadirCajaAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $espectaculo = $em->getRepository('PMPirotecniaBundle:Espectaculo')->find($id);

        if (!$espectaculo) {
            throw $this->createNotFoundException('Unable to find Espectaculo entity.');
        }
        $editForm = $this->createForm(new EspectaculoType(), $espectaculo);

        $caja = new Caja;
        $espectaculo->addCaja($caja);
        $caja->setEspectaculo($espectaculo);
        $cajaForm = $this->createForm(new CajaType(), $caja);

        if ($request->getMethod() == 'POST') {
            $cajaForm->handleRequest($request);
            if ($cajaForm->isValid()) {

                foreach ($caja->getCajas() as $productocaja) {
                    $productocaja->setCaja($caja);
                    $em->persist($productocaja);
                }
                $em->persist($caja);
                $em->flush();
                return $this->redirect($this->generateUrl('espectaculo_edit', array('id' => $id)));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
                die;
            }
        }
        return $this->render('PMPirotecniaBundle:Espectaculo:anadir_caja.html.twig', array(
            'espectaculo'      => $espectaculo, 'label' => 'Añadir', 'caja' => $caja,
            'form'   => $editForm->createView(), 'cajaform'   => $cajaForm->createView(),
        ));
    }

    public function editCajaAction(Request $request, $id, $espectaculoid)
    {
        $em = $this->getDoctrine()->getManager();
        $espectaculo = $em->getRepository('PMPirotecniaBundle:Espectaculo')->find($espectaculoid);
        if (!$espectaculo) {
            throw $this->createNotFoundException('Unable to find Espectaculo entity.');
        }
        $caja = $em->getRepository('PMPirotecniaBundle:Caja')->find($id);
        if (!$caja) {
            throw $this->createNotFoundException('Unable to find Caja entity.');
        }
        $espectaculo->addCaja($caja);

        $originalProductos = new ArrayCollection();
        foreach ($caja->getCajas() as $producto) {
            $originalProductos->add($producto);
        }

        $cajaForm = $this->createForm(new CajaType(), $caja);
        if ($request->getMethod() == 'POST') {
            $cajaForm->handleRequest($request);
            if ($cajaForm->isValid()) {
                foreach ($caja->getCajas() as $productocaja) {
                    $productocaja->setCaja($caja);
                    $em->persist($productocaja);
                }
                foreach ($originalProductos as $producto) {
                    if (false === $caja->getCajas()->contains($producto)) {
                        //$caja->getCajas()->removeElement($producto);
                        //$caja->removeCaja($producto);
                        $em->remove($producto);
                        //  $em->persist($caja);
                    }
                }
                $em->persist($caja);
                $em->flush();
                return $this->redirect($this->generateUrl('espectaculo_edit', array('id' => $espectaculoid)));
            }
        }
        return $this->render('PMPirotecniaBundle:Espectaculo:anadir_caja.html.twig', array(
            'espectaculo'      => $espectaculo, 'id' => $id, 'label' => 'Editar', 'caja' => $caja,
            'cajaform' => $cajaForm->createView(),
        ));
    }

    public function deleteAction($id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PMPirotecniaBundle:Espectaculo')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Espectaculo entity.');
            }
            $em->remove($entity);
            $em->flush();
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }
        return $this->redirect($this->generateUrl('espectaculo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }


    public function deleteCajaAction($id, $espectaculoid)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PMPirotecniaBundle:Caja')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Espectaculo entity.');
            }
            $em->remove($entity);
            $em->flush();
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('espectaculo_edit', array('id' => $espectaculoid)));
    }

    public function printAction($id)
    {
        $printer = $this->container->get('pirotecnia.print_espectaculo');
        $pdf = $printer->printFPDF($id);
        $response = new Response();
        $response->setContent($pdf->Output('Espectaculo-' . $id . '.pdf', 'I'));
        //$response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Type', 'application/pdf');
        //$response->headers->set('Content-Disposition', 'inline; filename="Espectaculo.pdf"');
        //$response->headers->set('Content-Disposition: attachment; filename=Albaran.pdf');*/
        return $response;
    }
    public function printCajaAction($id, $espectaculoid, $numcaja)
    {
        $printer = $this->container->get('pirotecnia.print_caja');
        $pdf = $printer->getFicheroFPDF($id, $numcaja);

        $response = new Response();
        //$response->clearHttpHeaders();
        $response->setContent($pdf);
        //$response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Type', 'application/pdf');
        //$response->headers->set('Content-Disposition', 'inline; filename="Espectaculo.pdf"');
        //$response->headers->set('Content-Disposition: attachment; filename=Albaran.pdf');*/
        return $response;
    }
}
