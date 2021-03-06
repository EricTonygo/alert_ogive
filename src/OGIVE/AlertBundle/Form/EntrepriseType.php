<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EntrepriseType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, array('required' => false))
//                ->add('file', null, array(
//                    'attr' => array('class' => 'inputfile'),
//                    'required' => false
//                ))
                ->add('domains', 'entity', array(
                    'class' => 'OGIVEAlertBundle:Domain',
                    'property' => 'name',
                    'empty_value' => "Selectionner ces domaines",
                    'multiple' => true,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\DomainRepository $repo) {
                        return $repo->getDomainQueryBuilder();
                    }
                ))
                ->add('subDomains', 'entity', array(
                    'class' => 'OGIVEAlertBundle:SubDomain',
                    'property' => 'name',
                    'empty_value' => "Selectionner ces sous-domaines",
                    'multiple' => true,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\SubDomainRepository $repo) {
                        return $repo->getSubDomainQueryBuilder();
                    }
                ))
                ->add('address', new AddressType())
                ->add('subscribers', CollectionType::class, array(
                    'entry_type' => SubscriberType::class,
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\Entreprise'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'ogive_alertbundle_entreprise';
    }

}
