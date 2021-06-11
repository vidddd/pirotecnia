<?php
namespace PM\PirotecniaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PM\PirotecniaBundle\Entity\Producto;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;


class CajaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$yaml = new Parser();
    	try {
    		$value = $yaml->parse(file_get_contents(__DIR__ . '/../Resources/config/pirotecnia.yml'));
    	} catch (ParseException $e) {printf("Unable to parse the YAML string: %s", $e->getMessage());}
    	$riesgos = $value['divisiones_riesgo'];
        $builder
            ->add('peso')
            ->add('espectaculo','entity',array('class' => 'PMPirotecniaBundle:Espectaculo',
            		'empty_value'=> '--   Seleccione un Espectáculo   --', 'required' => true))
            ->add('cajas', 'collection', array('label' => 'Lineas',
            				'type' => new ProductoCajaType(),
            				'allow_add' => true,
            				'allow_delete' => true,
            				'prototype' => true,
            		))
          	->add('riesgo' , 'choice', array(
            				'choices'   => $riesgos,
            				'empty_value' => '-- Seleccione --'
            		))
            ->add('pesobruto')
            
         ;   	
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\PirotecniaBundle\Entity\Caja',
        		//  'csrf_protection'   => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'caja';
    }
}
