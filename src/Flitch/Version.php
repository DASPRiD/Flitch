<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch;

use Flitch\Exception;

/**
 * Version information.
 */
class Version
{
    /**
     * Flitch version, should be in the format <major>.<minor>.<mini>[<suffix>]
     */
    const VERSION = '0.2.0dev';

    /**
     * Constants of version parts
     */
    const PART_MAJOR  = 'major';
    const PART_MINOR  = 'minor';
    const PART_MINI   = 'mini';
    const PART_SUFFIX = 'suffix';

    /**
     * Reverse map of version parts
     *
     * @var array
     */
    private static $reverseMap = array(
        'major'  => 1,
        'minor'  => 2,
        'mini'   => 3,
        'suffix' => 4
    );

    /**
     * Get the version
     *
     * If $part is null, the entire version is returned. If it is a string,
     * the version part is returned. If a part is given and $fromBeginning is
     * set to true, the version is returned from the beginning to the named
     * part.
     *
     * @param  string  $part
     * @param  boolean $fromBeginning
     * @return string
     */
    public static function getVersion($part = null, $fromBeginning = false)
    {
        if ($part === null) {
            return self::VERSION;
        } elseif (!preg_match('(^(?P<major>\d+)\.(?P<minor>\d+)\.(?P<mini>\d+)(?P<suffix>.*)?$)', self::VERSION, $matches)) {
            throw new Exception\RuntimeException('Unable to parse application version');
        } elseif (!isset($matches[$part])) {
            throw new Exception\RuntimeException('Named part "' . $part . '" does not exist in version');
        } elseif ($fromBeginning === false) {
            return $matches[$part];
        } else {
            $version = '';

            for ($i = 1; $i <= self::$reverseMap[$part]; $i++) {
                $version .= (($i > 1 && $i < 4) ? '.' : '') . $matches[$i];
            }

            return $version;
        }
    }
}

