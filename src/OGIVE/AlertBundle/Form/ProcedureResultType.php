<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProcedureResultType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('reference', null, array('required' => false))
//                ->add('publicationDate', DateType::class, array(
//                    'widget' => 'single_text',
//                    // this is actually the default format for single_text
//                    'format' => 'yyyy-MM-dd',
//                ))
                ->add('publicationDate', TextType::class, array('required' => false))
//                ->add('sendingDate', DateTimeType::class, array(
//                    'date_widget' => 'single_text',
//                    'time_widget' => 'single_text',
//                    'date_format' => 'yyyy-MM-dd',
//                    'with_seconds' => false,
//                    'required' => false,
//                ))
                ->add('object', null, array('required' => false))
                ->add('uploadedFiles', FileType::class, array(
                    "multiple" => true,
                    'data_class' => null,
                    'required' => false
                ))
                ->add('callOffer', 'entity', array(
                    'class' => 'OGIVEAlertBundle:CallOffer',
                    'property' => 'reference',
                    'empty_value' => "Selectionner l'AAO",
                    'multiple' => false,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\CallOfferRepository $repo) {
                        return $repo->getCallOfferQueryBuilder();
                    }
                ))
                ->add('expressionInterest', 'entity', array(
                    'class' => 'OGIVEAlertBundle:ExpressionInterest',
                    'property' => 'reference',
                    'empty_value' => "Selectionner l'ASMI",
                    'multiple' => false,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\ExpressionInterestRepository $repo) {
                        return $repo->getExpressionInterestQueryBuilder();
                    }
                ))
                ->add('domain', 'entity', array(
                    'class' => 'OGIVEAlertBundle:Domain',
                    'property' => 'name',
                    'empty_value' => "Selectionner le domaine d'activité",
                    'multiple' => false,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\DomainRepository $repo) {
                        return $repo->getDomainQueryBuilder();
                    }))
                ->add('subDomain', 'entity', array(
                    'class' => 'OGIVEAlertBundle:SubDomain',
                    'property' => 'name',
                    'empty_value' => "Selectionner un sous-domaine",
                    'multiple' => false,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\SubDomainRepository $repo) {
                        return $repo->getSubDomainQueryBuilder();
                    }))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\ProcedureResult'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'ogive_alertbundle_procedureResult';
    }

}
