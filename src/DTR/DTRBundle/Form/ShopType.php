<?php

namespace DTR\DTRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTR\DTRBundle\Entity\Item',
            'attr' => [ 'novalidate' => 'novalidate' ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dtr_dtrbundle_item';
    }
}
