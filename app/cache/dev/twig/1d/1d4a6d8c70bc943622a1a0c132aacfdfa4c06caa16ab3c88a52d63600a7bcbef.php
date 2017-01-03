<?php

/* OGIVEAlertBundle::header.html.twig */
class __TwigTemplate_537ba7d6c65899449e704d9f4a7bcca63f2084a8a0ba683136687b575d6578e4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_08baac63fd6503762060e143623a4c6740426f6aeea6c5d6c0c418a2ae6e9e82 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_08baac63fd6503762060e143623a4c6740426f6aeea6c5d6c0c418a2ae6e9e82->enter($__internal_08baac63fd6503762060e143623a4c6740426f6aeea6c5d6c0c418a2ae6e9e82_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "OGIVEAlertBundle::header.html.twig"));

        // line 1
        echo "<header> 
    <div class=\"ui mobile_top_nav top inverted fixed main menu\" style=\"background-color: #4285f4; display: none\">
        <div class=\"ui container\">
            <div class=\"ui left inverted secondary menu\">
                <a class=\"mobile_menu item\"><i class=\"large sidebar icon\"></i></a>
                <a id=\"logo_computer_menu\" class=\"item\">
                    ALERT M.P
                </a>
            </div>
        </div>
    </div>
    <div id=\"first_computer_top_nav\" class=\"ui computer_top_nav top inverted fixed main menu\" style=\"background-color: #4285f4;\">
        <div class=\"ui left inverted secondary menu\">
            <a class=\"mobile_menu item\"><i class=\"large sidebar icon\"></i></a>
            <a id=\"logo_computer_menu\" class=\"item\">
                ALERT M.P
            </a>
            <div class=\"ui search item\">
                <div class=\"ui icon input\">
                    <input class=\"prompt\" type=\"text\" placeholder=\"Recherche...\">
                    <i class=\"search icon\"></i>
                </div>
                <div class=\"results\"></div>
            </div>
        </div>
        <div class=\"ui right inverted secondary menu\">
            <div class=\"ui dropdown top right pointing item\">
                <i class=\"large alarm icon\"></i><div class=\"floating ui circular red label\">2</div>
                <div class=\"menu\">
                    <a href='#' class=\"ui item\">
                        First Notification                         
                    </a>
                    <div class=\"divider\"></div>
                    <a href=\"#\" class=\"ui item\">
                        Second notification 
                    </a>
                </div>
            </div>
            ";
        // line 39
        if ($this->getAttribute(($context["app"] ?? $this->getContext($context, "app")), "user", array())) {
            // line 40
            echo "            <div class=\"ui dropdown top right pointing item\"> 
                    ";
            // line 41
            if ($this->getAttribute($this->getAttribute(($context["app"] ?? $this->getContext($context, "app")), "user", array()), "photo", array())) {
                // line 42
                echo "                        <img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl(("uploads/profils/" . $this->getAttribute($this->getAttribute(($context["app"] ?? $this->getContext($context, "app")), "user", array()), "photo", array()))), "html", null, true);
                echo "\" alt=\"...\" class=\"ui avatar image\">
                    ";
            } else {
                // line 44
                echo "                        <img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("bundles/ogivealert/images/avatar_1.png"), "html", null, true);
                echo "\" alt=\"...\" class=\"ui avatar image\">
                    ";
            }
            // line 46
            echo "                
                <div class=\"menu\">
                    <h2 class=\"header\">Authentifié en tant que ";
            // line 48
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["app"] ?? $this->getContext($context, "app")), "user", array()), "username", array()), "html", null, true);
            echo "</h2>
                    <a href='#' class=\"ui item\">
                        Profil                         
                    </a>
                    ";
            // line 52
            if ($this->env->getExtension('Symfony\Bridge\Twig\Extension\SecurityExtension')->isGranted("ROLE_ADMIN")) {
                // line 53
                echo "                    <a href=\"";
                echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("fos_user_registration_register");
                echo "\" class=\"ui item\">
                        Créer un compte                         
                    </a>
                    ";
            }
            // line 57
            echo "                    <div class=\"divider\"></div>
                    <a href=\"";
            // line 58
            echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("fos_user_security_logout");
            echo "\" class=\"ui item\">
                        Deconnexion
                    </a>
                </div>
            </div>
            ";
        }
        // line 64
        echo "        </div>
    </div>  
</header>";
        
        $__internal_08baac63fd6503762060e143623a4c6740426f6aeea6c5d6c0c418a2ae6e9e82->leave($__internal_08baac63fd6503762060e143623a4c6740426f6aeea6c5d6c0c418a2ae6e9e82_prof);

    }

    public function getTemplateName()
    {
        return "OGIVEAlertBundle::header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 64,  105 => 58,  102 => 57,  94 => 53,  92 => 52,  85 => 48,  81 => 46,  75 => 44,  69 => 42,  67 => 41,  64 => 40,  62 => 39,  22 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<header> 
    <div class=\"ui mobile_top_nav top inverted fixed main menu\" style=\"background-color: #4285f4; display: none\">
        <div class=\"ui container\">
            <div class=\"ui left inverted secondary menu\">
                <a class=\"mobile_menu item\"><i class=\"large sidebar icon\"></i></a>
                <a id=\"logo_computer_menu\" class=\"item\">
                    ALERT M.P
                </a>
            </div>
        </div>
    </div>
    <div id=\"first_computer_top_nav\" class=\"ui computer_top_nav top inverted fixed main menu\" style=\"background-color: #4285f4;\">
        <div class=\"ui left inverted secondary menu\">
            <a class=\"mobile_menu item\"><i class=\"large sidebar icon\"></i></a>
            <a id=\"logo_computer_menu\" class=\"item\">
                ALERT M.P
            </a>
            <div class=\"ui search item\">
                <div class=\"ui icon input\">
                    <input class=\"prompt\" type=\"text\" placeholder=\"Recherche...\">
                    <i class=\"search icon\"></i>
                </div>
                <div class=\"results\"></div>
            </div>
        </div>
        <div class=\"ui right inverted secondary menu\">
            <div class=\"ui dropdown top right pointing item\">
                <i class=\"large alarm icon\"></i><div class=\"floating ui circular red label\">2</div>
                <div class=\"menu\">
                    <a href='#' class=\"ui item\">
                        First Notification                         
                    </a>
                    <div class=\"divider\"></div>
                    <a href=\"#\" class=\"ui item\">
                        Second notification 
                    </a>
                </div>
            </div>
            {%if app.user %}
            <div class=\"ui dropdown top right pointing item\"> 
                    {%if app.user.photo %}
                        <img src=\"{{asset('uploads/profils/'~app.user.photo)}}\" alt=\"...\" class=\"ui avatar image\">
                    {%else %}
                        <img src=\"{{asset('bundles/ogivealert/images/avatar_1.png')}}\" alt=\"...\" class=\"ui avatar image\">
                    {%endif %}
                
                <div class=\"menu\">
                    <h2 class=\"header\">Authentifié en tant que {{app.user.username}}</h2>
                    <a href='#' class=\"ui item\">
                        Profil                         
                    </a>
                    {% if is_granted('ROLE_ADMIN') %}
                    <a href=\"{{path(\"fos_user_registration_register\")}}\" class=\"ui item\">
                        Créer un compte                         
                    </a>
                    {%endif %}
                    <div class=\"divider\"></div>
                    <a href=\"{{ path('fos_user_security_logout') }}\" class=\"ui item\">
                        Deconnexion
                    </a>
                </div>
            </div>
            {%endif %}
        </div>
    </div>  
</header>", "OGIVEAlertBundle::header.html.twig", "C:\\xampp\\htdocs\\alert_ogive\\src\\OGIVE\\AlertBundle/Resources/views/header.html.twig");
    }
}
