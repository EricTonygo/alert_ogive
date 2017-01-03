<?php

/* @WebProfiler/Collector/router.html.twig */
class __TwigTemplate_7d4a562353669601815e4579d02236c332bceabdde9911153a7c6f0597e3f795 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@WebProfiler/Profiler/layout.html.twig", "@WebProfiler/Collector/router.html.twig", 1);
        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@WebProfiler/Profiler/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_f60bac43ac1edc50b1f4143e2970ceea91e93ac74d444afa1c58c27b0e21febf = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_f60bac43ac1edc50b1f4143e2970ceea91e93ac74d444afa1c58c27b0e21febf->enter($__internal_f60bac43ac1edc50b1f4143e2970ceea91e93ac74d444afa1c58c27b0e21febf_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "@WebProfiler/Collector/router.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_f60bac43ac1edc50b1f4143e2970ceea91e93ac74d444afa1c58c27b0e21febf->leave($__internal_f60bac43ac1edc50b1f4143e2970ceea91e93ac74d444afa1c58c27b0e21febf_prof);

    }

    // line 3
    public function block_toolbar($context, array $blocks = array())
    {
        $__internal_11223bc27d26a2bcd3a01bc249ffc54ed15b10cb1f69c504b5ada65b440f067c = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_11223bc27d26a2bcd3a01bc249ffc54ed15b10cb1f69c504b5ada65b440f067c->enter($__internal_11223bc27d26a2bcd3a01bc249ffc54ed15b10cb1f69c504b5ada65b440f067c_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "toolbar"));

        
        $__internal_11223bc27d26a2bcd3a01bc249ffc54ed15b10cb1f69c504b5ada65b440f067c->leave($__internal_11223bc27d26a2bcd3a01bc249ffc54ed15b10cb1f69c504b5ada65b440f067c_prof);

    }

    // line 5
    public function block_menu($context, array $blocks = array())
    {
        $__internal_a71d90c839d50827b59f9ac52ade536760f6e85f74735f4bee0ccfaa51149484 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_a71d90c839d50827b59f9ac52ade536760f6e85f74735f4bee0ccfaa51149484->enter($__internal_a71d90c839d50827b59f9ac52ade536760f6e85f74735f4bee0ccfaa51149484_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "menu"));

        // line 6
        echo "<span class=\"label\">
    <span class=\"icon\">";
        // line 7
        echo twig_include($this->env, $context, "@WebProfiler/Icon/router.svg");
        echo "</span>
    <strong>Routing</strong>
</span>
";
        
        $__internal_a71d90c839d50827b59f9ac52ade536760f6e85f74735f4bee0ccfaa51149484->leave($__internal_a71d90c839d50827b59f9ac52ade536760f6e85f74735f4bee0ccfaa51149484_prof);

    }

    // line 12
    public function block_panel($context, array $blocks = array())
    {
        $__internal_9375a9f877228c63816d2c53db60212297818486d617c3c5b6ed3cf2a48e9739 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_9375a9f877228c63816d2c53db60212297818486d617c3c5b6ed3cf2a48e9739->enter($__internal_9375a9f877228c63816d2c53db60212297818486d617c3c5b6ed3cf2a48e9739_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "panel"));

        // line 13
        echo "    ";
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\HttpKernelExtension')->renderFragment($this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("_profiler_router", array("token" => ($context["token"] ?? $this->getContext($context, "token")))));
        echo "
";
        
        $__internal_9375a9f877228c63816d2c53db60212297818486d617c3c5b6ed3cf2a48e9739->leave($__internal_9375a9f877228c63816d2c53db60212297818486d617c3c5b6ed3cf2a48e9739_prof);

    }

    public function getTemplateName()
    {
        return "@WebProfiler/Collector/router.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 13,  67 => 12,  56 => 7,  53 => 6,  47 => 5,  36 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends '@WebProfiler/Profiler/layout.html.twig' %}

{% block toolbar %}{% endblock %}

{% block menu %}
<span class=\"label\">
    <span class=\"icon\">{{ include('@WebProfiler/Icon/router.svg') }}</span>
    <strong>Routing</strong>
</span>
{% endblock %}

{% block panel %}
    {{ render(path('_profiler_router', { token: token })) }}
{% endblock %}
", "@WebProfiler/Collector/router.html.twig", "C:\\xampp\\htdocs\\alert_ogive\\vendor\\symfony\\symfony\\src\\Symfony\\Bundle\\WebProfilerBundle\\Resources\\views\\Collector\\router.html.twig");
    }
}
