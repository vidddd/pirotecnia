<?php

namespace PM\PirotecniaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;


class ProductoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$yaml = new Parser();
    	try {
    		$value = $yaml->parse(file_get_contents(__DIR__ . '/../Resources/config/pirotecnia.yml'));
    	} catch (ParseException $e) {printf("Unable to parse the YAML string: %s", $e->getMessage());}
    	$riesgos = $value['divisiones_riesgo'];
        $builder
            ->add('nombre')
            ->add('riesgo' , 'choice', array(
					    'choices'   => $riesgos,
            			'empty_value' => '-- Seleccione --'
					))
			->add('numero')
            ->add('peso')
            ->add('disabled')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\PirotecniaBundle\Entity\Producto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'producto';
    }
}
