<?php

/* OGIVEAlertBundle:domain:index.html.twig */
class __TwigTemplate_433b2e3290a601bf84204236d688c65564b90e922662427fc08e257ae418a245 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("OGIVEAlertBundle::layout.html.twig", "OGIVEAlertBundle:domain:index.html.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'sub_header' => array($this, 'block_sub_header'),
            'content' => array($this, 'block_content'),
            'block_add_new' => array($this, 'block_block_add_new'),
            'block_edit' => array($this, 'block_block_edit'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "OGIVEAlertBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_33ac1b26f01e9d7c290bbacd1a49d0e512b0ab58e3854ce785b796a8fbda5031 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_33ac1b26f01e9d7c290bbacd1a49d0e512b0ab58e3854ce785b796a8fbda5031->enter($__internal_33ac1b26f01e9d7c290bbacd1a49d0e512b0ab58e3854ce785b796a8fbda5031_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "OGIVEAlertBundle:domain:index.html.twig"));

        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_33ac1b26f01e9d7c290bbacd1a49d0e512b0ab58e3854ce785b796a8fbda5031->leave($__internal_33ac1b26f01e9d7c290bbacd1a49d0e512b0ab58e3854ce785b796a8fbda5031_prof);

    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        $__internal_c1f6ff4edf38a4a159ef53ced0aaaf8a2c70922880cb0966bf5f97523173401c = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_c1f6ff4edf38a4a159ef53ced0aaaf8a2c70922880cb0966bf5f97523173401c->enter($__internal_c1f6ff4edf38a4a159ef53ced0aaaf8a2c70922880cb0966bf5f97523173401c_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "title"));

        echo "La liste";
        
        $__internal_c1f6ff4edf38a4a159ef53ced0aaaf8a2c70922880cb0966bf5f97523173401c->leave($__internal_c1f6ff4edf38a4a159ef53ced0aaaf8a2c70922880cb0966bf5f97523173401c_prof);

    }

    // line 4
    public function block_sub_header($context, array $blocks = array())
    {
        $__internal_241016a35fd0360567e3f12db282e710505afc9ae0c77007ba768132e69f79a7 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_241016a35fd0360567e3f12db282e710505afc9ae0c77007ba768132e69f79a7->enter($__internal_241016a35fd0360567e3f12db282e710505afc9ae0c77007ba768132e69f79a7_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "sub_header"));

        // line 5
        echo "    <div id=\"second_computer_top_nav\" class=\"ui computer_top_nav inverted main menu\" style=\"background-color: #eeeeee; position: fixed;
         top: 70px; left: 0; right: 0; height:4em;\">
        <div class=\"ui container\">
            <div class=\"ui left inverted secondary menu\">
                <a id=\"document_title\" class=\"item\">
                    <h3>Liste des domaines</h3>
                </a>
            </div>

            <div class=\"ui right inverted secondary menu\">
                <div id='show_list_table' class=\"ui circular icon button item\" data-tooltip=\"Afficher sous forme de liste\" data-position=\"bottom center\" data-inverted=\"\">
                    <i class=\"large list layout icon\"></i>
                </div>
                <a class=\"ui circular icon button item\">
                    <i class=\"large trash icon\"></i>
                </a>
                <a class=\"ui circular icon button item\">
                    <i class=\"large folder icon\"></i>
                </a>
            </div>
        </div>
    </div>
";
        
        $__internal_241016a35fd0360567e3f12db282e710505afc9ae0c77007ba768132e69f79a7->leave($__internal_241016a35fd0360567e3f12db282e710505afc9ae0c77007ba768132e69f79a7_prof);

    }

    // line 28
    public function block_content($context, array $blocks = array())
    {
        $__internal_d2cbe1e884bb80905e5ec02bc6d963750e1a1a4fa2a5f527ab8ede9e1c01644c = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_d2cbe1e884bb80905e5ec02bc6d963750e1a1a4fa2a5f527ab8ede9e1c01644c->enter($__internal_d2cbe1e884bb80905e5ec02bc6d963750e1a1a4fa2a5f527ab8ede9e1c01644c_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "content"));

        // line 29
        echo "
    ";
        // line 30
        $this->loadTemplate("OGIVEAlertBundle:domain:grid.html.twig", "OGIVEAlertBundle:domain:index.html.twig", 30)->display($context);
        echo " 
    ";
        // line 31
        $this->loadTemplate("OGIVEAlertBundle:domain:list.html.twig", "OGIVEAlertBundle:domain:index.html.twig", 31)->display($context);
        
        $__internal_d2cbe1e884bb80905e5ec02bc6d963750e1a1a4fa2a5f527ab8ede9e1c01644c->leave($__internal_d2cbe1e884bb80905e5ec02bc6d963750e1a1a4fa2a5f527ab8ede9e1c01644c_prof);

    }

    // line 33
    public function block_block_add_new($context, array $blocks = array())
    {
        $__internal_b4d496be531ea296173e95a3dc430fe3af347a6546b405407bdd3cc8f5c2a03d = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_b4d496be531ea296173e95a3dc430fe3af347a6546b405407bdd3cc8f5c2a03d->enter($__internal_b4d496be531ea296173e95a3dc430fe3af347a6546b405407bdd3cc8f5c2a03d_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "block_add_new"));

        // line 34
        echo "    ";
        $this->loadTemplate("OGIVEAlertBundle:domain:new.html.twig", "OGIVEAlertBundle:domain:index.html.twig", 34)->display($context);
        
        $__internal_b4d496be531ea296173e95a3dc430fe3af347a6546b405407bdd3cc8f5c2a03d->leave($__internal_b4d496be531ea296173e95a3dc430fe3af347a6546b405407bdd3cc8f5c2a03d_prof);

    }

    // line 37
    public function block_block_edit($context, array $blocks = array())
    {
        $__internal_0bfe5698ab62c3b9645451691c2b210a3c1a2675d1544a35a61cca12509caf9d = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_0bfe5698ab62c3b9645451691c2b210a3c1a2675d1544a35a61cca12509caf9d->enter($__internal_0bfe5698ab62c3b9645451691c2b210a3c1a2675d1544a35a61cca12509caf9d_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "block_edit"));

        // line 38
        echo "    <div id=\"edit_domain_content\">

    </div>
";
        
        $__internal_0bfe5698ab62c3b9645451691c2b210a3c1a2675d1544a35a61cca12509caf9d->leave($__internal_0bfe5698ab62c3b9645451691c2b210a3c1a2675d1544a35a61cca12509caf9d_prof);

    }

    public function getTemplateName()
    {
        return "OGIVEAlertBundle:domain:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  125 => 38,  119 => 37,  111 => 34,  105 => 33,  98 => 31,  94 => 30,  91 => 29,  85 => 28,  56 => 5,  50 => 4,  38 => 2,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"OGIVEAlertBundle::layout.html.twig\" %}
{% block title %}La liste{% endblock %}

{% block sub_header %}
    <div id=\"second_computer_top_nav\" class=\"ui computer_top_nav inverted main menu\" style=\"background-color: #eeeeee; position: fixed;
         top: 70px; left: 0; right: 0; height:4em;\">
        <div class=\"ui container\">
            <div class=\"ui left inverted secondary menu\">
                <a id=\"document_title\" class=\"item\">
                    <h3>Liste des domaines</h3>
                </a>
            </div>

            <div class=\"ui right inverted secondary menu\">
                <div id='show_list_table' class=\"ui circular icon button item\" data-tooltip=\"Afficher sous forme de liste\" data-position=\"bottom center\" data-inverted=\"\">
                    <i class=\"large list layout icon\"></i>
                </div>
                <a class=\"ui circular icon button item\">
                    <i class=\"large trash icon\"></i>
                </a>
                <a class=\"ui circular icon button item\">
                    <i class=\"large folder icon\"></i>
                </a>
            </div>
        </div>
    </div>
{% endblock %}
{% block content %}

    {% include('OGIVEAlertBundle:domain:grid.html.twig') %} 
    {% include('OGIVEAlertBundle:domain:list.html.twig') %}
{% endblock %}
{% block block_add_new %}
    {% include('OGIVEAlertBundle:domain:new.html.twig') %}
{% endblock %}

{% block block_edit %}
    <div id=\"edit_domain_content\">

    </div>
{% endblock %}
", "OGIVEAlertBundle:domain:index.html.twig", "C:\\xampp\\htdocs\\alert_ogive\\src\\OGIVE\\AlertBundle/Resources/views/domain/index.html.twig");
    }
}
