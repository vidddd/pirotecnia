<?php

namespace PM\PirotecniaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class EspectaculoFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'filter_number_range')
            ->add('fecha', 'filter_date_range', array('left_date' => array('widget' => 'single_text',
		    															   'label' => '',
            																'attr' =>array('class' => 'input-small datepicker'
		    											)),
		    										   'right_date' => array('widget' => 'single_text')
		    									 ))
            ->add('hora', 'filter_text')
            ->add('peso', 'filter_number_range')
            ->add('numcajas', 'filter_number_range')
            ->add('cliente','filter_entity', array(	'class' => 'PM\PirotecniaBundle\Entity\Cliente',
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
        return 'espectaculofilter';
    }
}
