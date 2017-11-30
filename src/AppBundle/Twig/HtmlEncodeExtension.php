<?php
namespace AppBundle\Twig;

use Twig\TwigFilter;

class HtmlEncodeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new TwigFilter(
                'htmlencode',
                array(
                    $this,
                    'htmlEncodeFilter',
                    array(
                        'is_safe' => array('html')
                    )
                )
            ),
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