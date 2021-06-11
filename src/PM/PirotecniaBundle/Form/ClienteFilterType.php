<?php

namespace PM\PirotecniaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\Expr;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class ClienteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'filter_number')
            ->add('nombre',  'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('nif', 'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('fechaalta', 'filter_date')
            ->add('direccion', 'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('localidad',  'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('cp','filter_number')
            ->add('pais', 'filter_text')
            ->add('telefono', 'filter_number')
            ->add('fax', 'filter_number')
            ->add('movil','filter_number')
            ->add('web', 'filter_text')
            ->add('email', 'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('provincia_cliente','filter_entity', array('error_bubbling' => true, 'required' => false,
            		'class' => 'PM\InicioBundle\Entity\Provincias',
            		'empty_value' => ''))
        ;

        $listener = function(FormEvent $event)
        {
            foreach ($event->getData() as $data) {
                if(is_array($data)) {
                    foreach ($data as $subData) {
                        if(!empty($subData)) return;
                    }
                }
                else {
                    if(!empty($data)) return;
                }
            }

            $event->getForm()->addError(new FormError('Filtros vacios'));
        };
        $builder->addEventListener(FormEvents::POST_BIND, $listener);
    }

    public function getName()
    {
        return 'clientefilter';
    }
}
