<?php
namespace DvBarcodeBundle\Twig;

use DvBarcodeBundle\SVGFont;
use Twig_Extension;
use Twig_SimpleFunction;

class SvgExtension extends Twig_Extension
{
    protected $font;

    public function setFont($font)
    {
        $this->font = $font;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('svg_text', [ $this, 'text' ], [ 'is_safe' => [ 'html' ] ]),
            new Twig_SimpleFunction('svg_data_url', [ $this, 'dataUrl' ], [ 'is_safe' => [ 'html' ] ]),
        ];
    }

    public function text($text, $config = [ ])
    {
        $config['size'] = intval(!empty($config['size']) ? intval($config['size']) : 10);
        $config['left'] = intval(!empty($config['left']) ? intval($config['left']) : 0);
        $config['top'] = intval(!empty($config['top']) ? intval($config['top']) : 0);

        $svgFont = new SVGFont($config);
        $svgFont->loadSvgFont($this->font);
        return '<g transform="translate(' . $config['left'] . ', ' . $config['top'] . ')">' . $svgFont->textToPaths($text) . '</g>';
    }

    public function dataUrl($svgBody)
    {
        return 'data:image/svg+xml;base64,' . base64_encode(strval($svgBody));
    }
}
