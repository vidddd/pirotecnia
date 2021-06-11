<?php

namespace PM\PirotecniaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use PM\PirotecniaBundle\Form\DataTransformer\FechaTransformer;
use PM\PirotecniaBundle\Form\DataTransformer\HoraTransformer;
use Doctrine\ORM\EntityRepository;

class EspectaculoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$transformer = new FechaTransformer();$transformerh = new HoraTransformer();

        $builder
        	->add('cliente','entity',array('class' => 'PMPirotecniaBundle:Cliente',
        								   'empty_value'=> '--   Seleccione un Cliente   --', 
                                           'required' => true,
        			'query_builder' => function(EntityRepository $er) {
        				return $er->createQueryBuilder('e')
                               //->where('1')
        				       //->orderBy('p.nombre', 'ASC')
                        ;
        			},
        	))
            ->add($builder->create('fecha','text', array(
							//    'widget' => 'single_text',
							 //	'data' => $hoy,
            					'error_bubbling' => true
							))->addModelTransformer($transformer)            	)
            ->add($builder->create('hora','text', array(
							//    'widget' => 'single_text',
							 //	'data' => $hoy,
            					'error_bubbling' => true
							))->addModelTransformer($transformerh))
            ->add('peso')
            ->add('numcajas')
            ->add('descripcion')
            ->add('cajas', 'collection', array('label' => 'Cajas',
            		'type' => new CajaType(),
            		'allow_add' => true,
            		'allow_delete' => true,
            		'prototype' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\PirotecniaBundle\Entity\Espectaculo'
        ));
    }

    public function getName()
    {
        return 'espectaculo';
    }
}
