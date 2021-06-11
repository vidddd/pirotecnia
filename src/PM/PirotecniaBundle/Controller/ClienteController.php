<?php

namespace PM\PirotecniaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use PM\PirotecniaBundle\Entity\Cliente;
use PM\PirotecniaBundle\Form\ClienteType;
use PM\PirotecniaBundle\Form\ClienteFilterType;

class ClienteController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $request->getSession();
        $entity = new Cliente();
        $form   = $this->createCreateForm($entity);

        if ($this->getRequest()->get('cuantos')) {
            $cuantos = $this->getRequest()->get('cuantos');
        } else if ($session->get('ClienteCuantos')) {
            $cuantos = $session->get('ClienteCuantos');
        } else {
            $cuantos = 10;
        }

        $cuantosarr = array('10' => '10', '25' => '25', '50' => '50', '100' => '100');
        if ($cuantos) $session->set('ClienteCuantos', $cuantos);
        ($cuantos) ? $entradas = $cuantos : $entradas = 20;

        list($filterForm, $queryBuilder) = $this->filter();
        list($entities, $pagerHtml) = $this->paginator($queryBuilder, $cuantos);

        return $this->render('PMPirotecniaBundle:Cliente:index.html.twig', array(
            'entities' => $entities, 'form'   => $form->createView(),
            'pagerHtml' => $pagerHtml,    'cuantos' => $cuantosarr,    'entradas' => $entradas,
            'filterForm' => $filterForm->createView(),
        ));
    }

    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new ClienteFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('PMPirotecniaBundle:Cliente')->createQueryBuilder('e')->orderBy('e.id', 'DESC');

        if ($request->get('filter_action') == 'reset') {
            $session->remove('ClienteControllerFilter');
        }

        if ($request->get('filter_action') == 'filter') {
            $filterForm->bind($request);
            if ($filterForm->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                $filterData = $filterForm->getData();
                $session->set('ClienteControllerFilter', $filterData);
            }
        } else {
            /*if ($session->has('ClienteControllerFilter')) {
    			$filterData = $session->get('ClienteControllerFilter');
    			$filterForm = $this->createForm(new ClienteFilterType(), $filterData);
    			$this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
    		}*/
            if ($session->has('ClienteControllerFilter')) {
                $filters =  $session->get('ClienteControllerFilter');
                foreach ($filters as $key => $filter) {
                    if (is_object($filter)) {
                        $filters[$key] = $em->merge($filter);
                    }
                }
                $filterForm = $this->createForm(new ClienteFilterType(), $filters);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }
        //var_dump($queryBuilder->getDql()); die;
        return array($filterForm, $queryBuilder);
    }

    /**
     * Get results from paginator and get paginator view.
     *
     */
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
            return $me->generateUrl('cliente', array('page' => $page));
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
        $entity = new Cliente();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cliente'));
        }

        return $this->redirect($this->generateUrl('cliente'));
    }

    /**
     * Creates a form to create a Cliente entity.
     *
     * @param Cliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Cliente $entity)
    {
        $form = $this->createForm(new ClienteType(), $entity, array(
            'action' => $this->generateUrl('cliente_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Cliente entity.
     *
     */
    public function newAction()
    {
        $entity = new Cliente();
        $form   = $this->createCreateForm($entity);

        return $this->render('PMPirotecniaBundle:Cliente:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Cliente entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PMPirotecniaBundle:Cliente:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Cliente entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PMPirotecniaBundle:Cliente:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Cliente entity.
     *
     * @param Cliente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Cliente $entity)
    {
        $form = $this->createForm(new ClienteType(), $entity, array(
            'action' => $this->generateUrl('cliente_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Cliente entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PMPirotecniaBundle:Cliente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cliente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        //$editForm = $this->createEditForm($entity);
        $editForm = $this->createForm(new ClienteType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cliente'));
        }

        return $this->render('PMPirotecniaBundle:Cliente:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Cliente entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PMPirotecniaBundle:Cliente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Cliente entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cliente'));
    }

    /**
     * Creates a form to delete a Cliente entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cliente_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
