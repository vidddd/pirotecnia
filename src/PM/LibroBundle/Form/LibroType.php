<?php

namespace PM\LibroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PM\PirotecniaBundle\Form\DataTransformer\FechaTransformer;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class LibroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          	$transformer = new FechaTransformer();

            $yaml = new Parser();$hoy = new \DateTime();
          	try {
          		$value = $yaml->parse(file_get_contents(__DIR__ . '/../../PirotecniaBundle/Resources/config/pirotecnia.yml'));
          	} catch (ParseException $e) {printf("Unable to parse the YAML string: %s", $e->getMessage());}
          	$riesgos = $value['divisiones_riesgo']; $productos = $value['tipos_producto'];
            $hoy = date('d/m/Y');
        $builder

            ->add($builder->create('fecha','text', array(
                       'error_bubbling' => true,
                       'data' => date('d/m/Y'),
                ))->addModelTransformer($transformer))
            ->add('cartaporte')
            ->add('clase' , 'choice', array(
            				'choices'   => $riesgos,
            				'empty_value' => '-- Seleccione --'
            		))
            ->add('producto' , 'choice', array(
                        'choices'   => $productos,
                        'data' => 1
                    ))
            ->add('procedenciad','text',array('required' => true))
            ->add('folio')
            ->add('entrada')
            ->add('salida')
            ->add('existencias')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\LibroBundle\Entity\Libro'
        ));
    }

    public function getName()
    {
        return 'libro';
    }
}
