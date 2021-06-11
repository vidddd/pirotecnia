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

class ProductoFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$yaml = new Parser();
    	try {
    		$value = $yaml->parse(file_get_contents(__DIR__ . '/../Resources/config/pirotecnia.yml'));
    	} catch (ParseException $e) {
    		printf("Unable to parse the YAML string: %s", $e->getMessage());
    	}
    	$pagadas = array('0' => 'No','1' => 'Si');
    	$riesgos = $value['divisiones_riesgo'];
        $builder
            ->add('id', 'filter_number')
            ->add('nombre', 'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('numero', 'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('peso', 'filter_number_range')
            ->add('riesgo', 'filter_choice', array( 'choices'   => $riesgos))
            ->add('disabled', 'filter_choice', array( 'choices'   => $pagadas))
        ;

        $listener = function(FormEvent $event)
        {
            // Is data empty?
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
        return 'productofilter';
    }
}