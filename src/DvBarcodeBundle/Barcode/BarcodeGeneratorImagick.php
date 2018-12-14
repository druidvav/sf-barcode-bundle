<?php

namespace DvBarcodeBundle\Barcode;

class BarcodeGeneratorImagick extends BarcodeGenerator
{
    /**
     * Return a PNG image representation of barcode (requires GD or Imagick library).
     *
     * @param string $code code to print
     * @param string $type type of barcode:
     * @param int $widthFactor Width of a single bar element in pixels.
     * @param int $totalHeight Height of a single bar element in pixels.
     * @param array $color RGB (0-255) foreground color for bar elements (background is transparent).
     * @return \Imagick imagick object with barcode
     * @throws Exceptions\UnknownTypeException
     * @throws \ImagickException
     * @public
     */
    public function getBarcode($code, $type, $widthFactor = 2, $totalHeight = 30, $color = array(0, 0, 0))
    {
        $barcodeData = $this->getBarcodeData($code, $type);

        // calculate image size
        $width = ($barcodeData['maxWidth'] * $widthFactor);
        $height = $totalHeight;

        $colorForeground = new \imagickpixel('rgb(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ')');
        $png = new \Imagick();
        $png->newImage($width, $height, 'none', 'png');
        $imageMagickObject = new \imagickdraw();
        $imageMagickObject->setFillColor($colorForeground);
        $imageMagickObject->setStrokeAntialias(false);
        $imageMagickObject->setStrokeColor('rgb(255,255,255)');

        // print bars
        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            $bw = round(($bar['width'] * $widthFactor), 3);
            $bh = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);
            if ($bar['drawBar']) {
                $y = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $imageMagickObject->rectangle($positionHorizontal, $y, ($positionHorizontal + $bw), ($y + $bh));
            }
            $positionHorizontal += $bw;
        }
        $png->drawImage($imageMagickObject);
        return $png;
    }
}
