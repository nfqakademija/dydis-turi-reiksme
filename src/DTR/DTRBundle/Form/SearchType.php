<?php

namespace DTR\DTRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('searchInput', 'search', array(
                'attr' => array(
                'placeholder' => 'Ieškoti parduotuvių'),
                'label' => false))
            ->add('searchButton', 'submit', array('label' => 'Ieškoti'))
        ;
    }

    public function getName()
    {
        return 'dtr_dtrbundle_search';
    }
}