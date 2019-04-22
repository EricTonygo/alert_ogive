<?php

namespace AppBundle\Twig;

/**
 * Description of AppExtension
 *
 * @author Eric TONYE
 */
class AppExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('country_name', function($value) {
                        return \Symfony\Component\Intl\Intl::getRegionBundle()->getCountryName($value);
                    }),
            new \Twig_SimpleFilter('periodicity', function($value) {
                        switch ($value) {
                            case 1:
                                return "Annuelle";
                            case 2:
                                return "Semestrielle";
                            case 3:
                                return "Trimestrielle";
                            case 4:
                                return "Mensuelle";
                            case 5:
<<<<<<< HEAD
                                return "Hebdomadaire";
=======
                                return "Free";
>>>>>>> master
                        }
                    }),
            new \Twig_SimpleFilter('notificationType', function($value) {
                        switch ($value) {
                            case 1:
                                return "Email seulement";
                            case 2:
                                return "SMS seulement";
                            default:
                                return "SMS et Email";
                        }
                    }),
        );
    }

    public function getName() {
        return 'app_extension';
    }

}
