<?php

namespace DvBarcodeBundle;

use stdClass;
use XMLReader;

class SVGFont
{
    protected $id = '';
    protected $horizAdvX = 0;
    protected $unitsPerEm = 0;
    protected $ascent = 0;
    protected $descent = 0;
    protected $glyphs = array();

    protected $config = [ ];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function textToPaths($text)
    {
        $lines = explode("\n", $text);
        $result = "";
        $yShift = 0;
        $linesCount = 1;
        foreach ($lines as $text) {
            $text = $this->utf8ToUnicode($text);
            $size = $this->config['size'] / $this->unitsPerEm;
            $result .= "<g transform=\"scale({$size}) translate(0, {$yShift})\">";
            $xShift = 0;
            for ($i = 0; $i < count($text); $i++) {
                $letter = $text[$i];
                if (empty($this->glyphs[$letter])) continue;
                if (($xShift + $this->glyphs[$letter]->horizAdvX) >= ($this->config['width'] / $size)) {
                    $result .= "</g>";
                    $xShift = 0;
                    $yShift += $this->ascent + $this->descent;
                    $linesCount++;
                    if ($linesCount > $this->config['line_limit']) {
                        return $result;
                    }
                    $result .= "<g transform=\"scale({$size}) translate(0, {$yShift})\">";
                }
                $result .= "<path transform=\"translate({$xShift},{$yShift}) rotate(180) scale(-1, 1)\" d=\"{$this->glyphs[$letter]->d}\" />";
                $xShift += $this->glyphs[$letter]->horizAdvX;
            }
            $result .= "</g>";
            $yShift += $this->ascent + $this->descent;
            $linesCount++;
            if ($linesCount > $this->config['line_limit']) {
                return $result;
            }
        }

        return $result;
    }

    public function loadSvgFont($file)
    {
        $this->glyphs = array();
        $z = new XMLReader;
        $z->open($file);

        // move to the first <product /> node
        while ($z->read()) {
            $name = $z->name;

            if ($z->nodeType == XMLReader::ELEMENT) {
                if ($name == 'font') {
                    $this->id = $z->getAttribute('id');
                    $this->horizAdvX = $z->getAttribute('horiz-adv-x');
                }

                if ($name == 'font-face') {
                    $this->unitsPerEm = $z->getAttribute('units-per-em');
                    $this->ascent = $z->getAttribute('ascent');
                    $this->descent = $z->getAttribute('descent');
                }

                if ($name == 'glyph') {
                    $unicode = $z->getAttribute('unicode');
                    $unicode = $this->utf8ToUnicode($unicode);
                    $unicode = $unicode ? $unicode[0] : null;

                    $this->glyphs[$unicode] = new stdClass();
                    $this->glyphs[$unicode]->horizAdvX = $z->getAttribute('horiz-adv-x');
                    if (empty($this->glyphs[$unicode]->horizAdvX)) {
                        $this->glyphs[$unicode]->horizAdvX = $this->horizAdvX;
                    }
                    $this->glyphs[$unicode]->d = $z->getAttribute('d');
                }
            }
        }
    }

    /**
     * Function takes UTF-8 encoded string and returns unicode number for every character.
     * Copied somewhere from internet, thanks.
     * @param $str
     * @return array
     */
    protected function utf8ToUnicode($str)
    {
        $unicode = array();
        $values = array();
        $lookingFor = 1;

        for ($i = 0; $i < strlen($str); $i++) {
            $thisValue = ord($str[$i]);
            if ($thisValue < 128) $unicode[] = $thisValue;
            else {
                if (count($values) == 0) $lookingFor = ($thisValue < 224) ? 2 : 3;
                $values[] = $thisValue;
                if (count($values) == $lookingFor) {
                    $number = ($lookingFor == 3) ?
                        (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64) :
                        (($values[0] % 32) * 64) + ($values[1] % 64);

                    $unicode[] = $number;
                    $values = array();
                    $lookingFor = 1;
                }
            }
        }

        return $unicode;
    }
}