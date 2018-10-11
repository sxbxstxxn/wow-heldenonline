<?php

/* base.twig */
class __TwigTemplate_d31cc7b19c67fac628b0ed2fb8bb8891c3eb5fc58f1f176c8184c099357f0910 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>

<html>
<head>
    <meta http-equiv=\"Content-type\" content=\"text/html; charset=utf-8\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/base.css\">
    <title>Twig test</title>
</head>
<body>
    
    <h1>Hello world</h1>
    
    <p>And hello ";
        // line 13
        echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
        echo "</p>
    
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 13,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "base.twig", "/var/www/vhosts/helden.online/wow.helden.online/templates/base.twig");
    }
}
