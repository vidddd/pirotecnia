<?php
namespace PM\PirotecniaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('nif')
            ->add('fechaalta')
            ->add('direccion')
            ->add('localidad')
            ->add('cp')
            ->add('pais')
            ->add('telefono')
            ->add('fax')
            ->add('movil')
            ->add('web')
            ->add('email')
            ->add('cuenta')
            ->add('descuento')
            ->add('recargo')
            ->add('observaciones')
            ->add('provincia_cliente', 'entity', array('class' => 'PMInicioBundle:Provincias',
            								   'property' => 'denprovincia', 
            		 						   'empty_value' => '-- ',))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\PirotecniaBundle\Entity\Cliente'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cliente';
    }
}
