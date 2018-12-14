<?php
namespace ZappstoreBundle\Twig;

use Picqer\Barcode\BarcodeGeneratorSVG;
use Twig_Extension;
use Twig_SimpleFunction;

class SvgBarcodeExtension extends Twig_Extension
{
    protected $font;

    public function setFont($font)
    {
        $this->font = $font;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('code128', [ $this, 'code128' ], [ 'is_safe' => [ 'html' ] ]),
            new Twig_SimpleFunction('codeITF', [ $this, 'codeITF' ], [ 'is_safe' => [ 'html' ] ]),
            new Twig_SimpleFunction('codeEAN13', [ $this, 'codeEAN13' ], [ 'is_safe' => [ 'html' ] ]),
            new Twig_SimpleFunction('codeEAN13text', [ $this, 'codeEAN13text' ], [ 'is_safe' => [ 'html' ] ]),
        ];
    }

    public function code128($code, $opts)
    {
        $generator = new BarcodeGeneratorSVG();
        return '<g fill="black" stroke="none">' . $generator->getBarcodeFragment($code, BarcodeGeneratorSVG::TYPE_CODE_128, $opts) . '</g>';
    }

    public function codeEAN13($code, $opts)
    {
        $generator = new BarcodeGeneratorSVG();
        return '<g fill="black" stroke="none">' . $generator->getBarcodeFragment($code, BarcodeGeneratorSVG::TYPE_EAN_13, $opts) . '</g>';
    }

    public function codeEAN13text($code)
    {
        $generator = new BarcodeGeneratorSVG();
        return $generator->getBarcodeCode($code, BarcodeGeneratorSVG::TYPE_EAN_13);
    }

    public function codeITF($code, $opts)
    {
        $generator = new BarcodeGeneratorSVG();
        return '<g fill="black" stroke="none">' . $generator->getBarcodeFragment($code, BarcodeGeneratorSVG::TYPE_INTERLEAVED_2_5, $opts) . '</g>';
    }
}
