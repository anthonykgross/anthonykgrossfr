<?php 

namespace AppBundle\Twig;

class HtmlEncodeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'htmlencode' => new \Twig_Filter_Method($this, 'htmlEncodeFilter', array(
                'is_safe' => array('html')
            )),
        );
    }

    public function htmlEncodeFilter($value)
    {   
        return htmlentities($value);
    }

    public function getName()
    {
        return 'htmlencode_extension';
    }
}

?>