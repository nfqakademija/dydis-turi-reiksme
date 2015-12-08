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
            ->add('name', null, array('label' => 'Pavadinimas'))
            ->add('guestLimit', null, array('label' => 'Max svečiai'))
            ->add('fundsLimit', null, array('label' => 'Max išlaidos'))
            ->add('date', null, array('label' => 'Data'))
//            ->add('shops', 'collection', [
//                'type' => 'entity',
//                'options' => [
//                    'class' => 'DTRBundle:Shop',
//                    'choice_label' => 'name',
//                    'label' => false
//                ],
//                'label' => 'Parduotuvės',
//                'allow_add' => true,
//                'by_reference' => false,
//                'allow_delete' => true
//            ])
                ;
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
