<?php

/* ::base.html.twig */
class __TwigTemplate_57e8f9d28fb644bc474ffcd3388ebf99b385c6a6ee919c15cc52fcc78de0a63d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'body' => array($this, 'block_body'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_5179b1a93bba0952d277e0d61d750435ec3371ffb596ebd3336a4bc80e093c59 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_5179b1a93bba0952d277e0d61d750435ec3371ffb596ebd3336a4bc80e093c59->enter($__internal_5179b1a93bba0952d277e0d61d750435ec3371ffb596ebd3336a4bc80e093c59_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "::base.html.twig"));

        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\" />
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\"/>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0\">
        <title>Alert M.P - ";
        // line 7
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 8
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 12
        echo "        <link rel=\"icon\" type=\"image/x-icon\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    </head>
    <body>
        ";
        // line 15
        $this->displayBlock('body', $context, $blocks);
        // line 16
        echo "        ";
        $this->displayBlock('javascripts', $context, $blocks);
        // line 23
        echo "    </body>
</html>
";
        
        $__internal_5179b1a93bba0952d277e0d61d750435ec3371ffb596ebd3336a4bc80e093c59->leave($__internal_5179b1a93bba0952d277e0d61d750435ec3371ffb596ebd3336a4bc80e093c59_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_543aadc301607b2842f1f468a7134d007a77e94b86288ad6469eee2c42edc421 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_543aadc301607b2842f1f468a7134d007a77e94b86288ad6469eee2c42edc421->enter($__internal_543aadc301607b2842f1f468a7134d007a77e94b86288ad6469eee2c42edc421_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        
        $__internal_543aadc301607b2842f1f468a7134d007a77e94b86288ad6469eee2c42edc421->leave($__internal_543aadc301607b2842f1f468a7134d007a77e94b86288ad6469eee2c42edc421_prof);

    }

    // line 8
    public function block_stylesheets($context, array $blocks = array())
    {
        $__internal_38099369ae3cb5e5b83fc9349b511777cc2e44de151d84aad76994328f385b35 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_38099369ae3cb5e5b83fc9349b511777cc2e44de151d84aad76994328f385b35->enter($__internal_38099369ae3cb5e5b83fc9349b511777cc2e44de151d84aad76994328f385b35_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 9
        echo "            <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.css\">
            <link rel=\"stylesheet\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("bundles/ogivealert/css/reset.css"), "html", null, true);
        echo "\">
        ";
        
        $__internal_38099369ae3cb5e5b83fc9349b511777cc2e44de151d84aad76994328f385b35->leave($__internal_38099369ae3cb5e5b83fc9349b511777cc2e44de151d84aad76994328f385b35_prof);

    }

    // line 15
    public function block_body($context, array $blocks = array())
    {
        $__internal_9ba1f9efc472f3a1ad5a36c98031576c1281c9792c2dce00e76ca9dcda203565 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_9ba1f9efc472f3a1ad5a36c98031576c1281c9792c2dce00e76ca9dcda203565->enter($__internal_9ba1f9efc472f3a1ad5a36c98031576c1281c9792c2dce00e76ca9dcda203565_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        
        $__internal_9ba1f9efc472f3a1ad5a36c98031576c1281c9792c2dce00e76ca9dcda203565->leave($__internal_9ba1f9efc472f3a1ad5a36c98031576c1281c9792c2dce00e76ca9dcda203565_prof);

    }

    // line 16
    public function block_javascripts($context, array $blocks = array())
    {
        $__internal_94a5211a68d2b21ee2153343647c0db2f1ea82e4a60fe043d564e729688b4fbb = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_94a5211a68d2b21ee2153343647c0db2f1ea82e4a60fe043d564e729688b4fbb->enter($__internal_94a5211a68d2b21ee2153343647c0db2f1ea82e4a60fe043d564e729688b4fbb_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "javascripts"));

        // line 17
        echo "            <script src=\"https://code.jquery.com/jquery-3.1.1.min.js\"></script>
            <script src=\"https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.js\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("bundles/ogivealert/js/main.js"), "html", null, true);
        echo "\"></script>
            <script src=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("bundles/fosjsrouting/js/router.js"), "html", null, true);
        echo "\"></script>
            <script src=\"";
        // line 21
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("fos_js_routing_js", array("callback" => "fos.Router.setData"));
        echo "\"></script>
        ";
        
        $__internal_94a5211a68d2b21ee2153343647c0db2f1ea82e4a60fe043d564e729688b4fbb->leave($__internal_94a5211a68d2b21ee2153343647c0db2f1ea82e4a60fe043d564e729688b4fbb_prof);

    }

    public function getTemplateName()
    {
        return "::base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 21,  115 => 20,  111 => 19,  107 => 17,  101 => 16,  90 => 15,  81 => 10,  78 => 9,  72 => 8,  61 => 7,  52 => 23,  49 => 16,  47 => 15,  40 => 12,  38 => 8,  34 => 7,  26 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\" />
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\"/>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0\">
        <title>Alert M.P - {% block title %}{% endblock %}</title>
        {% block stylesheets %}
            <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.css\">
            <link rel=\"stylesheet\" href=\"{{ asset('bundles/ogivealert/css/reset.css') }}\">
        {% endblock %}
        <link rel=\"icon\" type=\"image/x-icon\" href=\"{{ asset('favicon.ico') }}\" />
    </head>
    <body>
        {% block body %}{% endblock %}
        {% block javascripts %}
            <script src=\"https://code.jquery.com/jquery-3.1.1.min.js\"></script>
            <script src=\"https://cdn.jsdelivr.net/semantic-ui/2.2.6/semantic.min.js\"></script>
            <script type=\"text/javascript\" src=\"{{ asset('bundles/ogivealert/js/main.js')}}\"></script>
            <script src=\"{{ asset('bundles/fosjsrouting/js/router.js') }}\"></script>
            <script src=\"{{ path('fos_js_routing_js', { callback: 'fos.Router.setData' }) }}\"></script>
        {% endblock %}
    </body>
</html>
", "::base.html.twig", "C:\\xampp\\htdocs\\alert_ogive\\app/Resources\\views/base.html.twig");
    }
}
