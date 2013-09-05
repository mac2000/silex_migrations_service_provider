<?php
namespace Acme\Migrations;

use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;

class OutputFormatterStyle implements OutputFormatterStyleInterface
{
    private static $availableOptions = array(
        'bold' => 'font-weight:bold',
        'underscore' => 'text-decoration:underline',
        'italic' => 'font-style:italic'
    );

    private $foreground;
    private $background;
    private $options = array();

    public function __construct($foreground = null, $background = null, array $options = array())
    {
        if (null !== $foreground) {
            $this->setForeground($foreground);
        }
        if (null !== $background) {
            $this->setBackground($background);
        }
        if (count($options)) {
            $this->setOptions($options);
        }
    }


    /**
     * Sets style foreground color.
     *
     * @param string $color The color name
     *
     * @api
     */
    public function setForeground($color = null)
    {
        $this->foreground = $color;
    }

    /**
     * Sets style background color.
     *
     * @param string $color The color name
     *
     * @api
     */
    public function setBackground($color = null)
    {
        $this->background = $color;
    }

    /**
     * Sets some specific style option.
     *
     * @param string $option The option name
     *
     * @api
     */
    public function setOption($option)
    {
        if (!isset(static::$availableOptions[$option])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid option specified: "%s". Expected one of (%s)',
                $option,
                implode(', ', array_keys(static::$availableOptions))
            ));
        }

        if (false === array_search(static::$availableOptions[$option], $this->options)) {
            $this->options[] = static::$availableOptions[$option];
        }
    }

    /**
     * Unsets some specific style option.
     *
     * @param string $option The option name
     */
    public function unsetOption($option)
    {
        if (!isset(static::$availableOptions[$option])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid option specified: "%s". Expected one of (%s)',
                $option,
                implode(', ', array_keys(static::$availableOptions))
            ));
        }

        $pos = array_search(static::$availableOptions[$option], $this->options);
        if (false !== $pos) {
            unset($this->options[$pos]);
        }
    }

    /**
     * Sets multiple style options at once.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = array();

        foreach ($options as $option) {
            $this->setOption($option);
        }
    }

    /**
     * Applies the style to a given text.
     *
     * @param string $text The text to style
     *
     * @return string
     */
    public function apply($text)
    {
        $styles = array();
        if ($this->foreground) {
            $styles[] = 'color:' . $this->foreground;
        }
        if ($this->background) {
            $styles[] = 'background:' . $this->foreground;
        }
        $styles = array_merge($styles, $this->options);
        $style = count($this->options) > 0 ? ' style="' . implode(';', $this->options) . '"' : '';

        return '<span style="' . implode(';', $styles) . '">' . $text . '</span>';
    }
}