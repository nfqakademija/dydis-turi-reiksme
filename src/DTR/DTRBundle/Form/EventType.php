<?php

namespace DTR\DTRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('guestLimit')
            ->add('fundsLimit')
            ->add('date')
            ->add('save', 'submit', [ 'label' => 'Sukurti' ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DTR\DTRBundle\Entity\Event',
            'attr' => [ 'novalidate' => 'novalidate' ]
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dtr_dtrbundle_event';
    }
}
