<?php
namespace PM\LibroBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Lexik\Bundle\FormFilterBundle\Filter\Expr;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;

class LibroFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $yaml = new Parser();
      try {
        $value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
      } catch (ParseException $e) {
        printf("Unable to parse the YAML string: %s", $e->getMessage());
      }
      $riesgos = $value['divisiones_riesgo']; $tipos = $value['tipos_producto'];
        $builder
            ->add('id', 'filter_number_range')
            ->add('fecha', 'filter_date_range', array('left_date' => array('widget' => 'single_text',
                                         'label' => '',
                                            'attr' =>array('class' => 'input-small datepicker'
                              )),
                               'right_date' => array('widget' => 'single_text')
                           ))
            ->add('cartaporte', 'filter_text')
            ->add('clase', 'filter_choice', array( 'choices'   => $riesgos))
            ->add('procedenciad',  'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('producto', 'filter_choice', array( 'choices'   => $tipos))
            ->add('entrada', 'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            ->add('salida', 'filter_text', array( 'condition_pattern' =>  FilterOperands::STRING_BOTH ))
            //->add('existencias', 'filter_number_range')
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

            $event->getForm()->addError(new FormError('Debes introducir algun Filtro'));
        };
        $builder->addEventListener(FormEvents::POST_BIND, $listener);
    }

    public function getName()
    {
        return 'librofilter';
    }
}
