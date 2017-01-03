<?php

/* @Twig/Exception/exception_full.html.twig */
class __TwigTemplate_eed8b1e610e719d03456b7b459d185e7742dafe2c40407224e6c176fee035138 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@Twig/layout.html.twig", "@Twig/Exception/exception_full.html.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@Twig/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_7a1862f07af0eefd1bcca9d011ab843a8526a089e8bb58f2d31abfbec8417ee7 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_7a1862f07af0eefd1bcca9d011ab843a8526a089e8bb58f2d31abfbec8417ee7->enter($__internal_7a1862f07af0eefd1bcca9d011ab843a8526a089e8bb58f2d31abfbec8417ee7_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "@Twig/Exception/exception_full.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_7a1862f07af0eefd1bcca9d011ab843a8526a089e8bb58f2d31abfbec8417ee7->leave($__internal_7a1862f07af0eefd1bcca9d011ab843a8526a089e8bb58f2d31abfbec8417ee7_prof);

    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        $__internal_0a6e1a490b9af9327b09ced87b28be57fed3864ae27ca5769631605dee3316cb = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_0a6e1a490b9af9327b09ced87b28be57fed3864ae27ca5769631605dee3316cb->enter($__internal_0a6e1a490b9af9327b09ced87b28be57fed3864ae27ca5769631605dee3316cb_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "head"));

        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\HttpFoundationExtension')->generateAbsoluteUrl($this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("bundles/framework/css/exception.css")), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
";
        
        $__internal_0a6e1a490b9af9327b09ced87b28be57fed3864ae27ca5769631605dee3316cb->leave($__internal_0a6e1a490b9af9327b09ced87b28be57fed3864ae27ca5769631605dee3316cb_prof);

    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        $__internal_be1a4525d5cfa3ad2f6393267de4ddf056a575997e6fc9f8dc5b2574246ab216 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_be1a4525d5cfa3ad2f6393267de4ddf056a575997e6fc9f8dc5b2574246ab216->enter($__internal_be1a4525d5cfa3ad2f6393267de4ddf056a575997e6fc9f8dc5b2574246ab216_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        // line 8
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["exception"] ?? $this->getContext($context, "exception")), "message", array()), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, ($context["status_code"] ?? $this->getContext($context, "status_code")), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, ($context["status_text"] ?? $this->getContext($context, "status_text")), "html", null, true);
        echo ")
";
        
        $__internal_be1a4525d5cfa3ad2f6393267de4ddf056a575997e6fc9f8dc5b2574246ab216->leave($__internal_be1a4525d5cfa3ad2f6393267de4ddf056a575997e6fc9f8dc5b2574246ab216_prof);

    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        $__internal_4d8afc05d5dcea4b266a5fbcea0d5e018f274d8aaa3cee4ac80efead3f510566 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_4d8afc05d5dcea4b266a5fbcea0d5e018f274d8aaa3cee4ac80efead3f510566->enter($__internal_4d8afc05d5dcea4b266a5fbcea0d5e018f274d8aaa3cee4ac80efead3f510566_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 12
        echo "    ";
        $this->loadTemplate("@Twig/Exception/exception.html.twig", "@Twig/Exception/exception_full.html.twig", 12)->display($context);
        
        $__internal_4d8afc05d5dcea4b266a5fbcea0d5e018f274d8aaa3cee4ac80efead3f510566->leave($__internal_4d8afc05d5dcea4b266a5fbcea0d5e018f274d8aaa3cee4ac80efead3f510566_prof);

    }

    public function getTemplateName()
    {
        return "@Twig/Exception/exception_full.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 12,  72 => 11,  58 => 8,  52 => 7,  42 => 4,  36 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends '@Twig/layout.html.twig' %}

{% block head %}
    <link href=\"{{ absolute_url(asset('bundles/framework/css/exception.css')) }}\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
{% endblock %}

{% block title %}
    {{ exception.message }} ({{ status_code }} {{ status_text }})
{% endblock %}

{% block body %}
    {% include '@Twig/Exception/exception.html.twig' %}
{% endblock %}
", "@Twig/Exception/exception_full.html.twig", "C:\\xampp\\htdocs\\alert_ogive\\vendor\\symfony\\symfony\\src\\Symfony\\Bundle\\TwigBundle\\Resources\\views\\Exception\\exception_full.html.twig");
    }
}
