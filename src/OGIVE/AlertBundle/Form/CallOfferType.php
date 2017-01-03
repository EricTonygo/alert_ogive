<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallOfferType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('reference')
                ->add('publicationDate', 'date')
                ->add('deadline', 'datetime')
                ->add('openingDate', 'datetime')
                ->add('object')
                ->add('owner')
                ->add('abstract')
                ->add('status')
                ->add('state')
                ->add('sendingDate', 'datetime')
                ->add('uploadedFiles', 'file', array(
                    'multiple' => true, 
                    'data_class' => null,
                ))
                ->add('domain','entity', array(
                    'class' => 'NNGenieInfosMatBundle:Type',
                    'property' => 'name',
                    'empty_value' => "",
                    'multiple'=>false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\DomainRepository $repo) {
                        return $repo->getDomainQueryBuilder();
                    }
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\CallOffer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ogive_alertbundle_calloffer';
    }


}
