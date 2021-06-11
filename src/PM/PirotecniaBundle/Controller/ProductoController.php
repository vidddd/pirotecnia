<?php

namespace PM\PirotecniaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Symfony\Component\HttpFoundation\Response;
use PM\PirotecniaBundle\Entity\Producto;
use PM\PirotecniaBundle\Form\ProductoType;
use PM\PirotecniaBundle\Form\ProductoFilterType;

class ProductoController extends Controller
{

    public function indexAction()
    {
        
    	$em = $this->getDoctrine()->getManager();$request = $this->getRequest();$session = $request->getSession();
        $entity = new Producto();
        $form = $this->createCreateForm($entity);
        
        if($this->getRequest()->get('cuantos')) { $cuantos = $this->getRequest()->get('cuantos');
       
        } else if ($session->get('ProductoCuantos')){
        	$cuantos = $session->get('ProductoCuantos');
        } else {  $cuantos = 10; }
        list($filterForm, $queryBuilder) = $this->filter();
        list($entities, $pagerHtml) = $this->paginator($queryBuilder, $cuantos);
        
        $cuantosarr = array('10' => '10','25' => '25','50' => '50','100' => '100');
        if($cuantos) $session->set('ProductoCuantos', $cuantos);
        ($cuantos)? $entradas = $cuantos : $entradas = 20;
        
        return $this->render('PMPirotecniaBundle:Producto:index.html.twig', array(
            'entities' => $entities,   'form'   => $form->createView(),
        		'pagerHtml' => $pagerHtml,
        		'cuantos' => $cuantosarr,
        		'entradas' => $entradas,
        		'filterForm' => $filterForm->createView(),
        ));
    }

    protected function filter()
    {
    	$request = $this->getRequest();
    	$session = $request->getSession();
    	$filterForm = $this->createForm(new ProductoFilterType());
    	$em = $this->getDoctrine()->getManager();
    	$queryBuilder = $em->getRepository('PMPirotecniaBundle:Producto')->createQueryBuilder('e')->orderBy('e.id', 'DESC');
    
    	// Reset filter
    	if ($request->get('filter_action') == 'reset') {
    		$session->remove('ProductoControllerFilter');
    	}
    
    	// Filter action
    	if ($request->get('filter_action') == 'filter') {
    		// Bind values from the request
    		$filterForm->bind($request);
    
    		if ($filterForm->isValid()) {
    			// Build the query from the given form object
    			$this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
    			// Save filter to session
    			$filterData = $filterForm->getData();
    			$session->set('ProductoControllerFilter', $filterData);
    		}
    	} else {
    		// Get filter from session
    		if ($session->has('ProductoControllerFilter')) {
    			$filterData = $session->get('ProductoControllerFilter');
    			$filterForm = $this->createForm(new ProductoFilterType(), $filterData);
    			$this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
    		}
    	}
    
    	return array($filterForm, $queryBuilder);
    }
    
    /**
     * Get results from paginator and get paginator view.
     *
     */
    protected function paginator($queryBuilder, $cuantos)
    {
    	// Paginator
    	$adapter = new DoctrineORMAdapter($queryBuilder);
    	$pagerfanta = new Pagerfanta($adapter);
    	$currentPage = $this->getRequest()->get('page', 1);
    	$pagerfanta->setCurrentPage($currentPage);
    	$pagerfanta->setMaxPerPage($cuantos);
    	$entities = $pagerfanta->getCurrentPageResults();
    
    	// Paginator - route generator
    	$me = $this;
    	$routeGenerator = function($page) use ($me)
    	{
    		return $me->generateUrl('producto', array('page' => $page));
    	};
    
    	// Paginator - view
    	$translator = $this->get('translator');
    	$view = new TwitterBootstrapView();
    	$pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
    			'proximity' => 3,
    			'prev_message' => '← Anteriores',
    			'next_message' => 'Siguientes →',
    	));
    
    	return array($entities, $pagerHtml);
    }
    /**
     * Creates a new Producto entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Producto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('producto'));
        }
        return $this->redirect($this->generateUrl('producto'));
		/*
        return $this->render('PMPirotecniaBundle:Producto:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));*/
    }

    /**
    * Creates a form to create a Producto entity.
    *
    * @param Producto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Producto $entity)
    {
        $form = $this->createForm(new ProductoType(), $entity, array(
            'action' => $this->generateUrl('producto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Producto entity.
     *
     */
    public function newAction()
    {
        $entity = new Producto();
        $form   = $this->createCreateForm($entity);

        return $this->render('PMPirotecniaBundle:Producto:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Producto entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Producto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Producto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PMPirotecniaBundle:Producto:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Producto entity.
     *
     */
    public function editAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Producto')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Producto entity.');
        }    	

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        //echo "222"; die;
        return $this->render('PMPirotecniaBundle:Producto:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Producto entity.
    *
    * @param Producto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Producto $entity)
    {
        $form = $this->createForm(new ProductoType(), $entity, array(
            'action' => $this->generateUrl('producto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Producto entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Producto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Producto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        //$editForm = $this->createEditForm($entity);
        $editForm = $this->createForm(new ProductoType(), $entity);
        $editForm->handleRequest($request);
		
        //if ($editForm->isValid()) {
         $em->persist($entity);  $em->flush();
            return $this->redirect($this->generateUrl('producto'));
        //}

        return $this->render('PMPirotecniaBundle:Producto:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Producto entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PMPirotecniaBundle:Producto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Producto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('producto'));
    }

    /**
     * Creates a form to delete a Producto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('producto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    public function getPesoAction(){
    	$request = $this->get('request');
    	$productoid=$request->request->get('productoid');
    	$em = $this->get('doctrine')->getEntityManager();
    	$producto = $em->getRepository('PMPirotecniaBundle:Producto')->find($productoid);
    	 
    	if (!$producto) {
    		throw $this->createNotFoundException('Unable to find Orden entity.');
    	}
    	if($producto) {
    		return new Response(json_encode(array('peso' => $producto->getPeso())));
    	} else {
    		return new Response(json_encode(array('error' => '<span class="error-nombre">Error Añadiendo la materia prima</span>')));
    	}
    
    }
}
