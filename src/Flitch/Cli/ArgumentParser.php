<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Cli;

/**
 * Argument parser.
 */
class ArgumentParser
{
    /**
     * Parsed options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Parsed non-options.
     *
     * @var array
     */
    protected $nonOptions = array();

    /**
     * Error during argument parsing.
     *
     * @var string
     */
    protected $error;

    /**
     * Parse command line arguments.
     *
     * @param  array $arguments
     * @param  array $options
     * @return void
     */
    public function __construct(array $arguments, array $options)
    {
        $argumentsLength = count($arguments);
        $index           = 1;

        if ($argumentsLength < 2 || !$options) {
            return;
        }

        while ($index < $argumentsLength) {
            $char1 = (isset($arguments[$index][0]) ? $arguments[$index][0] : null);
            $char2 = (isset($arguments[$index][1]) ? $arguments[$index][1] : null);

            if ($char1 === '-' && $char2) {
                $option   = $arguments[$index];
                $argument = ($index + 1 < $argumentsLength ? $arguments[$index + 1] : null);

                if ($char2 === '-') {
                    if (!isset($arguments[$index][2])) {
                        $index++;
                        break;
                    } elseif (!$this->parseLongOption($option, $argument, $options, $index)) {
                        break;
                    }
                } elseif (!$this->parseShortOption($option, $argument, $options, $index)) {
                    break;
                }
            } else {
                $this->nonOptions[] = $arguments[$index++];
            }
        }
    }

    /**
     * Get all parsed options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get all parsed non-options.
     *
     * @return array
     */
    public function getNonOptions()
    {
        return $this->nonOptions;
    }

    /**
     * Get parser error.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Parse a long option.
     *
     * @param  string  $option
     * @param  string  $argument
     * @param  array   $options
     * @param  integer $argumentIndex
     * @return boolean
     */
    protected function parseLongOption($option, $argument, array $options, &$argumentIndex)
    {
        $index      = null;
        $exact      = false;
        $ambiguous  = false;
        $optionName = substr($option, 2);

        for ($length = 0; isset($option[$length + 2]) && $option[$length + 2] !== '='; $length++);

        foreach ($options as $i => $optionData) {
            if (!strncmp($optionData['name'], $optionName, $length)) {
                if (strlen($optionName) === strlen($optionData['name'])) {
                    $index = $i;
                    $exact = true;
                    break;
                } elseif ($index === null) {
                    $index = $i;
                } elseif ($options[$index]['code'] !== $optionData['code'] || $options[$index]['has_arg'] !== $optionData['has_arg']) {
                    $ambiguous = true;
                }
            }
        }

        if ($ambiguous && !$exact) {
            $this->error = sprintf('Option "%s" is ambigious', $option);
            return false;
        } elseif ($index === null) {
            $this->error = sprintf('Unrecognized option "%s"', $option);
            return false;
        }

        $argumentIndex++;

        if (isset($option[$length + 2])) {
            if ($options[$index]['has_arg'] === false) {
                $this->error = sprintf('Option "%s" doesn\'t allow an argument', $option);
                return false;
            } elseif ($options[$index]['has_arg'] === true && !isset($option[$length + 3])) {
                $this->error = sprintf('Option "%s" requires an argument', $option);
                return false;
            }

            $this->options[] = array(
                'code'     => $options[$index]['code'],
                'argument' => substr($option, $length + 3)
            );

            return true;
        }

        if ($options[$index]['has_arg'] === true) {
            if (!$argument || !isset($argument[0])) {
                $this->error = sprintf('Option "%s" requires an argument', $option);
                return false;
            }

            $argumentIndex++;
            $this->options[] = array(
                'code'     => $options[$index]['code'],
                'argument' => $argument
            );

            return true;
        }

        $this->options[] = array(
            'code'     => $options[$index]['code'],
            'argument' => null
        );

        return true;
    }

    /**
     * Parse a short option.
     *
     * @param  string  $option
     * @param  string  $argument
     * @param  array   $options
     * @param  integer $argumentIndex
     * @return boolean
     */
    protected function parseShortOption($option, $argument, array $options, &$argumentIndex)
    {
        $charIndex = 1;

        while ($charIndex !== null) {
            $index = null;
            $char  = (isset($option[$charIndex]) ? $option[$charIndex] : null);

            if ($char !== null) {
                foreach ($options as $i => $optionData) {
                    if ($char === $optionData['code']) {
                        $index = $i;
                        break;
                    }
                }
            }

            if ($index === null) {
                $this->errors[] = sprintf('Invalid option -- %s', $char);
                return false;
            }

            if (!isset($option[++$charIndex])) {
                $argumentIndex++;
                $charIndex = null;
            }

            if ($options[$index]['has_arg'] !== false && $charIndex > 0 && isset($option[$charIndex])) {
                $this->options[] = array(
                    'code'     => $char,
                    'argument' => substr($option, $charIndex)
                );

                $charIndex = null;
            } elseif ($options[$index]['has_arg'] === true) {
                if (!$argument || !isset($argument[0])) {
                    $this->error = sprintf('Option "%s" requires an argument', $char);
                    return false;
                }

                $this->options[] = array(
                    'code'     => $char,
                    'argument' => $argument
                );

                $charIndex = null;
            }
        }

        return true;
    }
}
