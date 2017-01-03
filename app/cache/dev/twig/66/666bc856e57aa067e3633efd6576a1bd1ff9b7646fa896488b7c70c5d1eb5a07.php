<?php

/* OGIVEAlertBundle::layout.html.twig */
class __TwigTemplate_9d6feaa8c87ddf6203db7775f16f277e2010e640a60582e95bd205a866236151 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("::base.html.twig", "OGIVEAlertBundle::layout.html.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
            'sub_header' => array($this, 'block_sub_header'),
            'content' => array($this, 'block_content'),
            'block_add_new' => array($this, 'block_block_add_new'),
            'block_edit' => array($this, 'block_block_edit'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_eccd2915347003ff6f06c4c6042bb5a4ace90250140f37615552b0b1b845b500 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_eccd2915347003ff6f06c4c6042bb5a4ace90250140f37615552b0b1b845b500->enter($__internal_eccd2915347003ff6f06c4c6042bb5a4ace90250140f37615552b0b1b845b500_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "OGIVEAlertBundle::layout.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_eccd2915347003ff6f06c4c6042bb5a4ace90250140f37615552b0b1b845b500->leave($__internal_eccd2915347003ff6f06c4c6042bb5a4ace90250140f37615552b0b1b845b500_prof);

    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        $__internal_932e26419b04c335f6b1cfa612200d87498726341934f80e4f9fa72a51af8713 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_932e26419b04c335f6b1cfa612200d87498726341934f80e4f9fa72a51af8713->enter($__internal_932e26419b04c335f6b1cfa612200d87498726341934f80e4f9fa72a51af8713_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        
        $__internal_932e26419b04c335f6b1cfa612200d87498726341934f80e4f9fa72a51af8713->leave($__internal_932e26419b04c335f6b1cfa612200d87498726341934f80e4f9fa72a51af8713_prof);

    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        $__internal_d08b004729b1d5181c498077a6d19a2179c1dcaa2da0d28faa7c390fdbceee57 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_d08b004729b1d5181c498077a6d19a2179c1dcaa2da0d28faa7c390fdbceee57->enter($__internal_d08b004729b1d5181c498077a6d19a2179c1dcaa2da0d28faa7c390fdbceee57_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "body"));

        // line 6
        echo "    ";
        $this->loadTemplate("OGIVEAlertBundle::header.html.twig", "OGIVEAlertBundle::layout.html.twig", 6)->display($context);
        // line 7
        echo "    ";
        $this->displayBlock('sub_header', $context, $blocks);
        // line 9
        echo "    <div class=\"ui bottom attached segment pushable\">
        <div class=\"pusher\">
            ";
        // line 11
        $this->loadTemplate("OGIVEAlertBundle::sidebar.html.twig", "OGIVEAlertBundle::layout.html.twig", 11)->display($context);
        // line 12
        echo "            ";
        $this->displayBlock('content', $context, $blocks);
        // line 14
        echo "        </div>
    </div>
    ";
        // line 16
        $this->displayBlock('block_add_new', $context, $blocks);
        // line 17
        echo " 
    ";
        // line 18
        $this->displayBlock('block_edit', $context, $blocks);
        // line 19
        echo " 
";
        
        $__internal_d08b004729b1d5181c498077a6d19a2179c1dcaa2da0d28faa7c390fdbceee57->leave($__internal_d08b004729b1d5181c498077a6d19a2179c1dcaa2da0d28faa7c390fdbceee57_prof);

    }

    // line 7
    public function block_sub_header($context, array $blocks = array())
    {
        $__internal_c024ad6bb7b87d7630c4c148367e33360023397b4dd8a8dc38b7c4dd9fadf819 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_c024ad6bb7b87d7630c4c148367e33360023397b4dd8a8dc38b7c4dd9fadf819->enter($__internal_c024ad6bb7b87d7630c4c148367e33360023397b4dd8a8dc38b7c4dd9fadf819_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "sub_header"));

        // line 8
        echo "    ";
        
        $__internal_c024ad6bb7b87d7630c4c148367e33360023397b4dd8a8dc38b7c4dd9fadf819->leave($__internal_c024ad6bb7b87d7630c4c148367e33360023397b4dd8a8dc38b7c4dd9fadf819_prof);

    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        $__internal_5cd383f79953535cee545ace7aafb163d5731aeeb3fc8327e443825b525cc278 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_5cd383f79953535cee545ace7aafb163d5731aeeb3fc8327e443825b525cc278->enter($__internal_5cd383f79953535cee545ace7aafb163d5731aeeb3fc8327e443825b525cc278_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "content"));

        // line 13
        echo "            ";
        
        $__internal_5cd383f79953535cee545ace7aafb163d5731aeeb3fc8327e443825b525cc278->leave($__internal_5cd383f79953535cee545ace7aafb163d5731aeeb3fc8327e443825b525cc278_prof);

    }

    // line 16
    public function block_block_add_new($context, array $blocks = array())
    {
        $__internal_100ae93290201cefa54df2ae896861fbefb8460cc1a04ff7c0a56c195d05fdee = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_100ae93290201cefa54df2ae896861fbefb8460cc1a04ff7c0a56c195d05fdee->enter($__internal_100ae93290201cefa54df2ae896861fbefb8460cc1a04ff7c0a56c195d05fdee_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "block_add_new"));

        // line 17
        echo "    ";
        
        $__internal_100ae93290201cefa54df2ae896861fbefb8460cc1a04ff7c0a56c195d05fdee->leave($__internal_100ae93290201cefa54df2ae896861fbefb8460cc1a04ff7c0a56c195d05fdee_prof);

    }

    // line 18
    public function block_block_edit($context, array $blocks = array())
    {
        $__internal_9a25ffcdbbbac95bca82ca3969a8afe341b49718e27237eb6ffc6461903b3916 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_9a25ffcdbbbac95bca82ca3969a8afe341b49718e27237eb6ffc6461903b3916->enter($__internal_9a25ffcdbbbac95bca82ca3969a8afe341b49718e27237eb6ffc6461903b3916_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "block_edit"));

        // line 19
        echo "    ";
        
        $__internal_9a25ffcdbbbac95bca82ca3969a8afe341b49718e27237eb6ffc6461903b3916->leave($__internal_9a25ffcdbbbac95bca82ca3969a8afe341b49718e27237eb6ffc6461903b3916_prof);

    }

    public function getTemplateName()
    {
        return "OGIVEAlertBundle::layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 19,  129 => 18,  122 => 17,  116 => 16,  109 => 13,  103 => 12,  96 => 8,  90 => 7,  82 => 19,  80 => 18,  77 => 17,  75 => 16,  71 => 14,  68 => 12,  66 => 11,  62 => 9,  59 => 7,  56 => 6,  50 => 5,  39 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"::base.html.twig\" %}

{% block title %}{% endblock %}

{% block body %}
    {% include('OGIVEAlertBundle::header.html.twig') %}
    {% block sub_header %}
    {% endblock sub_header %}
    <div class=\"ui bottom attached segment pushable\">
        <div class=\"pusher\">
            {% include('OGIVEAlertBundle::sidebar.html.twig') %}
            {% block content %}
            {% endblock content %}
        </div>
    </div>
    {% block block_add_new %}
    {% endblock block_add_new %} 
    {% block block_edit %}
    {% endblock block_edit %} 
{% endblock %}", "OGIVEAlertBundle::layout.html.twig", "C:\\xampp\\htdocs\\alert_ogive\\src\\OGIVE\\AlertBundle/Resources/views/layout.html.twig");
    }
}
