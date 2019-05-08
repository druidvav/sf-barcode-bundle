<?php
namespace DvBarcodeBundle\Twig;

use DvBarcodeBundle\Barcode\BarcodeGeneratorSVG;
use DvBarcodeBundle\Barcode\Exceptions\BarcodeException;
use DvBarcodeBundle\Barcode\Exceptions\InvalidCharacterException;
use DvBarcodeBundle\Barcode\Exceptions\InvalidCheckDigitException;
use DvBarcodeBundle\Barcode\Exceptions\InvalidLengthException;
use DvBarcodeBundle\Barcode\Exceptions\UnknownTypeException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SvgBarcodeExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('code39', [ $this, 'code39' ], [ 'is_safe' => [ 'html' ] ]),
            new TwigFunction('code128', [ $this, 'code128' ], [ 'is_safe' => [ 'html' ] ]),
            new TwigFunction('codeITF', [ $this, 'codeITF' ], [ 'is_safe' => [ 'html' ] ]),
            new TwigFunction('codeEAN13', [ $this, 'codeEAN13' ], [ 'is_safe' => [ 'html' ] ]),
            new TwigFunction('codeEAN13text', [ $this, 'codeEAN13text' ], [ 'is_safe' => [ 'html' ] ]),
        ];
    }

    /**
     * @param $code
     * @param $opts
     * @return string
     * @throws UnknownTypeException
     */
    public function code39($code, $opts)
    {
        $generator = new BarcodeGeneratorSVG();
        return '<g fill="black" stroke="none">' . $generator->getBarcodeFragment($code, BarcodeGeneratorSVG::TYPE_CODE_39, $opts) . '</g>';
    }

    /**
     * @param $code
     * @param $opts
     * @return string
     * @throws UnknownTypeException
     */
    public function code128($code, $opts)
    {
        $generator = new BarcodeGeneratorSVG();
        return '<g fill="black" stroke="none">' . $generator->getBarcodeFragment($code, BarcodeGeneratorSVG::TYPE_CODE_128, $opts) . '</g>';
    }

    /**
     * @param $code
     * @param $opts
     * @return string
     * @throws UnknownTypeException
     */
    public function codeEAN13($code, $opts)
    {
        $generator = new BarcodeGeneratorSVG();
        return '<g fill="black" stroke="none">' . $generator->getBarcodeFragment($code, BarcodeGeneratorSVG::TYPE_EAN_13, $opts) . '</g>';
    }

    /**
     * @param $code
     * @return string
     * @throws UnknownTypeException
     * @throws BarcodeException
     * @throws InvalidCharacterException
     * @throws InvalidCheckDigitException
     * @throws InvalidLengthException
     */
    public function codeEAN13text($code)
    {
        $generator = new BarcodeGeneratorSVG();
        return $generator->getBarcodeCode($code, BarcodeGeneratorSVG::TYPE_EAN_13);
    }

    /**
     * @param $code
     * @param $opts
     * @return string
     * @throws UnknownTypeException
     */
    public function codeITF($code, $opts)
    {
        $generator = new BarcodeGeneratorSVG();
        return '<g fill="black" stroke="none">' . $generator->getBarcodeFragment($code, BarcodeGeneratorSVG::TYPE_INTERLEAVED_2_5, $opts) . '</g>';
    }
}
