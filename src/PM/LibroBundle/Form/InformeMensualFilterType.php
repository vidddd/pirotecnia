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

class InformeMensualFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $yaml = new Parser();
      try {
        $value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
      } catch (ParseException $e) {
        printf("Unable to parse the YAML string: %s", $e->getMessage());
      }
      $anos = $value['anos'];
      $meses = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre");
         $builder
            ->add('mes', 'filter_choice', array( 'choices' => $meses, 'mapped' => false,
                  'required' => true, 'empty_value' => '-- Seleccione un mes --'))
            ->add('ano', 'filter_choice', array( 'choices' => $anos, 'mapped' => false ,
                  'required' => true, 'empty_value' => '-- Seleccione un Año --'))
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

            $event->getForm()->addError(new FormError('No hay Datos'));
        };
        $builder->addEventListener(FormEvents::POST_BIND, $listener);
    }

    public function getName()
    {
        return 'Informefilter';
    }
}
