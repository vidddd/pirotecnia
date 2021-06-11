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

class ProductoCajaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
        	->add('producto','entity',array('class' => 'PMPirotecniaBundle:Producto',
        									'empty_value'=> '--   Seleccione un Producto   --', 'required' => true,
        			'query_builder' => function(EntityRepository $er) {
        				return $er->createQueryBuilder('p')->where('p.disabled = 0')
        				->orderBy('p.nombre', 'ASC');
        			},
        	))
        	->add('caja','entity',array('class' => 'PMPirotecniaBundle:Caja',
        							'empty_value'=> '--   Seleccione una Caja   --', 'required' => true))
            ->add('peso')
            ->add('cantidad')
   ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\PirotecniaBundle\Entity\ProductoCaja'
        ));
    }

    public function getName()
    {
        return 'productocaja';
    }
}
