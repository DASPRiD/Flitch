<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Cli;

use Flitch\File\Tokenizer;
use Flitch\Report;
use Flitch\Rule\Manager;
use Flitch\Version;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * CLI handler.
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
    protected $standard = 'PSR2';

    /**
     * Paths to scan.
     *
     * @var array
     */
    protected $paths = array();

    /**
     * Path to checkstyle report output file
     *
     * @var string
     */
    protected $checkstyleReportFilename;

    /**
     * Run silently w/o any console output
     *
     * @var bool
     */
    protected $quiet = false;

    /**
     * Reports
     *
     * @var array
     */
    protected $reports = array();

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
     * Run Flitch.
     *
     * @param  array $arguments
     * @return void
     */
    public function run(array $arguments)
    {
        echo "Flitch " . Version::getVersion() . " by Ben Scholzen 'DASPRiD'.\n\n";

        $parser = new ArgumentParser($arguments, array(
            array(
                'code'    => 's',
                'name'    => 'standard',
                'has_arg' => true
            ),
            array(
                'code'    => 'c',
                'name'    => 'checkstyle',
                'has_arg' => true
            ),
            array(
                'code'    => 'q',
                'name'    => 'quiet',
                'has_arg' => false
            ),
            array(
                'code'    => 'h',
                'name'    => 'help',
                'has_arg' => false
            ),
            array(
                'code'    => 'v',
                'name'    => 'version',
                'has_arg' => false
            ),
        ));

        if ($parser->getError() !== null) {
            echo $parser->getError() . "\n";
            return;
        }

        $method  = 'analyzeFiles';

        foreach ($parser->getOptions() as $option) {
            switch ($option['code']) {
                case 's':
                    $this->standard = $option['argument'];
                    break;

                case 'c':
                    $this->checkstyleReportFilename = $option['argument'];
                    break;

                case 'q':
                    $this->quiet = true;
                    break;

                case 'h':
                    $method = 'printHelp';
                    break;

                case 'v':
                    return;
            }
        }

        foreach ($parser->getNonOptions() as $nonOption) {
            $this->paths[] = $nonOption;
        }

        $this->{$method}();
    }

    /**
     * Analyze files for coding standard violations.
     *
     * @return void
     */
    protected function analyzeFiles()
    {
        if (!$this->paths) {
            $this->printHelp();
            return;
        }

        $paths = array();

        foreach ($this->paths as $path) {
            if (!file_exists($path) || !is_readable($path)) {
                echo "Cannot open " . $path . "\n";
            }

            if (is_dir($path)) {
                $paths[] = new RegexIterator(
                    new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($path)
                    ),
                    '(\.php$)i'
                );
            } else {
                $paths[] = $path;
            }
        }

        $manager   = new Manager(__DIR__ . '/../../../standards', '~/.flitch/standards', $this->standard);
        $tokenizer = new Tokenizer();

        if (false === $this->quiet) {
            $this->reports['cli'] = new Report\Cli();
        }

        if (!empty($this->checkstyleReportFilename)) {
            $this->reports['checkstyle'] = new Report\Checkstyle($this->checkstyleReportFilename);
        }

        foreach ($paths as $path) {
            if (is_string($path)) {
                $file = $this->analyzeFile($path, $tokenizer, $manager);
            } else {
                foreach ($path as $fileInfo) {
                    $file = $this->analyzeFile($fileInfo->getPathname(), $tokenizer, $manager);
                }
            }
        }
    }

    /**
     * Analyze single file for coding standard violations.
     *
     * @param  string       $path
     * @param  Tokenizer    $tokenizer
     * @param  Manager      $manager
     * @return File
     */
    protected function analyzeFile($path, Tokenizer $tokenizer, Manager $manager)
    {
        $file = $tokenizer->tokenize($path, file_get_contents($path));

        $manager->check($file);

        foreach ($this->reports as $report) {
            $report->addFile($file);
        }

        return $file;
    }

    /**
     * Print help.
     *
     * @return void
     */
    protected function printHelp()
    {
        echo "Usage: flitch [switches] <directory>\n"
           . "       flitch [switches] <file>\n\n"
           . "  -s, --standard=STANDARD   Use specified coding standard\n"
           . "  -c, --checkstyle=FILENAME Generate CheckStyle report\n"
           . "  -q, --quiet               Run silently\n"
           . "  -h, --help                Prints this usage information\n"
           . "  -v, --version             Print version information\n";
    }
}
