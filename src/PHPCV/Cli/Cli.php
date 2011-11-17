<?php
/**
 * PHPCV
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   PHPCV
 * @package    PHPCV_Cli
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace PHPCV\Cli;

use PHPCV\Version,
    PHPCV\File\Tokenizer,
    PHPCV\Rule\Manager,
    RegexIterator,
    RecursiveDirectoryIterator,
    RecursiveIteratorIterator;

/**
 * CLI handler.
 * 
 * @category   PHPCV
 * @package    PHPCV_Cli
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class Cli
{
    /**
     * Working directory.
     * 
     * @var string
     */
    protected $workingDirectory;
    
    /**
     * Standard to use.
     * 
     * @var string
     */
    protected $standard = 'ZF2';
    
    /**
     * Path to scan.
     * 
     * @var string
     */
    protected $path;
    
    /**
     * Create a new CLI object.
     * 
     * @param  string $workingDirectory
     * @return void
     */
    public function __construct($workingDirectory)
    {
        $this->workingDirectory = rtrim($workingDirectory, '/\\');
    }
    
    /**
     * Run ZFCS.
     * 
     * @param  array $arguments
     * @return void
     */
    public function run(array $arguments)
    {
        echo "PHPCV " . Version::getVersion() . " by Ben Scholzen.\n\n";
        
        $method = $this->parseCommandLineArguments($arguments);
        
        $this->{$method}();
    }
    
    /**
     * Analyze files for coding standard violations.
     * 
     * @return void
     */
    protected function analyzeFiles()
    {
        if ($this->path === null) {
            $this->printHelp();
            return;
        }
                
        if (!file_exists($this->path) || !is_readable($this->path)) {
            echo "Cannot open " . $this->path . "\n";
        }
        
        $iterator = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->path)
            ),
            '(\.php$)i'
        );
               
        $manager   = new Manager(__DIR__ . '/../../../standards', '~/.phpcv/standards', $this->standard);
        $tokenizer = new Tokenizer();
        
        foreach ($iterator as $fileInfo) {
            $file = $tokenizer->tokenize($fileInfo->getFilename(), file_get_contents($fileInfo->getPathname()));
            
            $manager->check($file);
            
            echo '.';
        }
        
        echo "\n";
    }
    
    /**
     * Print help.
     * 
     * @return void
     */
    protected function printHelp()
    {
        echo "Usage: phpcv [switches] <directory>\n"
           . "       phpcv [switches] <file>\n\n"
           . "  -s, --standard=STANDARD  Use specified coding standard\n"
           . "  -h, --help               Prints this usage information\n";
    }
    
    /**
     * Parse command line arguments.
     * 
     * @param  array $arguments
     * @return string 
     */
    protected function parseCommandLineArguments(array $arguments)
    {
        $method = 'analyzeFiles';
        
        array_shift($arguments);
        
        while (count($arguments) > 0) {
            $argument = array_shift($arguments);
            
            if (substr($argument, 0, 2) === '--') {
                $argument = explode('=', substr(ltrim($argument), 2));
                $flag     = $argument[0];
                $value    = (isset($argument[1]) ? $argument[1] : null);
                
                switch ($flag) {
                    case 'help':
                        $method = 'printHelp';
                        break;
                    
                    case 'standard':
                        $this->standard = $value;
                        break;
                    
                    default:
                        $method = 'printHelp';
                        break;
                }
            } elseif (substr($argument, 0, 1) === '-') {
                $flag = substr($argument, 1);
                
                switch ($flag) {
                    case 'h':
                        $method = 'printHelp';
                        break;
                    
                    case 's':
                        if (count($arguments) > 0) {
                            $this->standard = array_unshift($arguments);
                        } else {
                            $method = 'printHelp';
                        }
                        break;
                    
                    default:
                        $method = 'printHelp';
                        break;
                }
            } else {
                $this->path = rtrim($argument, '/\\');
                break;
            }
        }
        
        return $method;
    }
    
    /**
     * Check if a given path is absolute.
     * 
     * @param  string $path
     * @return boolean
     */
    protected function isAbsolute($path)
    {
        if (preg_match('((?:/|\\\\)\.\.(?=/|$))', $path)) {
            return false;
        } elseif (!strncasecmp(PHP_OS, 'win', 3)) {
            return ($path[0] === '/' || preg_match('(^[a-zA-Z]:(\\\\|/))', $path));
        } else {
            return ($path[0] === '/' || $path[0] === '~');
        }
    }
}
