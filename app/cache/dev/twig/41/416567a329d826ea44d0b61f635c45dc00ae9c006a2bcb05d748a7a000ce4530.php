<?php

/* OGIVEAlertBundle:domain:new.html.twig */
class __TwigTemplate_32643fb9f8aad78d76dddf8674153b27b663e1e21e6936d18d9ce2cb3eb4cce8 extends Twig_Template
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
        $__internal_6bea008222d347b1b44d626f4e32b58b5bfe6294d019b382b326dd17b94ca3d5 = $this->env->getExtension("Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension");
        $__internal_6bea008222d347b1b44d626f4e32b58b5bfe6294d019b382b326dd17b94ca3d5->enter($__internal_6bea008222d347b1b44d626f4e32b58b5bfe6294d019b382b326dd17b94ca3d5_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "OGIVEAlertBundle:domain:new.html.twig"));

        // line 1
        echo "<div id=\"add_domain\" class=\"ui small modal\">
    <i class=\"close icon\"></i>
    <div class=\"header\">
        Ajout d'un domaine
    </div>
    <div class=\"content\">
        ";
        // line 7
        echo         $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->renderBlock(($context["form"] ?? $this->getContext($context, "form")), 'form_start', array("method" => "POST", "action" => "/domains", "attr" => array("class" => "ui form", "id" => "domain_form")));
        echo "


        <div class=\"field\">
            ";
        // line 11
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->searchAndRenderBlock($this->getAttribute(($context["form"] ?? $this->getContext($context, "form")), "name", array()), 'label', array("label" => "Nom"));
        echo "
            ";
        // line 12
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->searchAndRenderBlock($this->getAttribute(($context["form"] ?? $this->getContext($context, "form")), "name", array()), 'errors', array("attr" => array("class" => "ui error message")));
        echo "
            ";
        // line 13
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->searchAndRenderBlock($this->getAttribute(($context["form"] ?? $this->getContext($context, "form")), "name", array()), 'widget', array("attr" => array("placeholder" => "Nom du domaine", "data-validate" => "name")));
        echo "
        </div>

        <div class=\"field\">
            ";
        // line 17
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->searchAndRenderBlock($this->getAttribute(($context["form"] ?? $this->getContext($context, "form")), "description", array()), 'label', array("label" => "Description"));
        echo "
            ";
        // line 18
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->searchAndRenderBlock($this->getAttribute(($context["form"] ?? $this->getContext($context, "form")), "description", array()), 'errors', array("attr" => array("class" => "ui error message")));
        echo "
            ";
        // line 19
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->searchAndRenderBlock($this->getAttribute(($context["form"] ?? $this->getContext($context, "form")), "description", array()), 'widget', array("attr" => array("placeholder" => "Description du domain", "data-validate" => "description")));
        echo "
        </div>

        <div class=\"field\">
            ";
        // line 23
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->searchAndRenderBlock(($context["form"] ?? $this->getContext($context, "form")), 'rest');
        echo "
        </div>

        ";
        // line 26
        echo         $this->env->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')->renderer->renderBlock(($context["form"] ?? $this->getContext($context, "form")), 'form_end');
        echo "
    </div>
    <div class=\"actions\">
        <div id=\"cancel_add_domain\" class=\"ui black deny button\">
            Annuler
        </div>
        <div id='submit_domain' class=\"ui primary icon button\">
            <i class=\"save icon\"></i>
            Sauvegarder
        </div>
    </div>
</div>
<button id=\"add_domain_btn\" class=\"float_button circular ui huge red icon button\" data-tooltip=\"Nouveau domaine\" data-position=\"top center\" data-inverted=\"\">
    <i class=\"icon plus\"></i>
</button>
";
        
        $__internal_6bea008222d347b1b44d626f4e32b58b5bfe6294d019b382b326dd17b94ca3d5->leave($__internal_6bea008222d347b1b44d626f4e32b58b5bfe6294d019b382b326dd17b94ca3d5_prof);

    }

    public function getTemplateName()
    {
        return "OGIVEAlertBundle:domain:new.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 26,  67 => 23,  60 => 19,  56 => 18,  52 => 17,  45 => 13,  41 => 12,  37 => 11,  30 => 7,  22 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div id=\"add_domain\" class=\"ui small modal\">
    <i class=\"close icon\"></i>
    <div class=\"header\">
        Ajout d'un domaine
    </div>
    <div class=\"content\">
        {{ form_start(form, {'method': 'POST', 'action': '/domains', 'attr': {'class': 'ui form', 'id': 'domain_form'} }) }}


        <div class=\"field\">
            {{ form_label(form.name,\"Nom\") }}
            {{ form_errors(form.name, {'attr': {'class': 'ui error message'}})}}
            {{ form_widget(form.name,{'attr':{'placeholder':'Nom du domaine', 'data-validate': 'name'}})}}
        </div>

        <div class=\"field\">
            {{ form_label(form.description,\"Description\") }}
            {{ form_errors(form.description, {'attr': {'class': 'ui error message'}})}}
            {{ form_widget(form.description,{'attr':{'placeholder':'Description du domain', 'data-validate': 'description'}})}}
        </div>

        <div class=\"field\">
            {{form_rest(form)}}
        </div>

        {{ form_end(form) }}
    </div>
    <div class=\"actions\">
        <div id=\"cancel_add_domain\" class=\"ui black deny button\">
            Annuler
        </div>
        <div id='submit_domain' class=\"ui primary icon button\">
            <i class=\"save icon\"></i>
            Sauvegarder
        </div>
    </div>
</div>
<button id=\"add_domain_btn\" class=\"float_button circular ui huge red icon button\" data-tooltip=\"Nouveau domaine\" data-position=\"top center\" data-inverted=\"\">
    <i class=\"icon plus\"></i>
</button>
", "OGIVEAlertBundle:domain:new.html.twig", "C:\\xampp\\htdocs\\alert_ogive\\src\\OGIVE\\AlertBundle/Resources/views/domain/new.html.twig");
    }
}
