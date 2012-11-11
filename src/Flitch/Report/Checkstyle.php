<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Report;

use Flitch\File\File;
use XMLWriter;

/**
 * Checkstyle report.
 */
class Checkstyle implements ReportInterface
{
    /**
     * XML writer.
     *
     * @var XMLWriter
     */
    protected $writer;

    /**
     * Create a new checkstyle report.
     *
     * @param  string $filename
     * @return void
     */
    public function __construct($filename)
    {
        $this->writer = new XMLWriter();
        $this->writer->openUri($filename);
        $this->writer->setIndent(true);
        $this->writer->setIndentString('    ');

        $this->writer->startDocument('1.0', 'UTF-8');
        $this->writer->startElement('checkstyle');
        $this->writer->writeAttribute('version', '5.5');
    }

    /**
     * addFile(): defined by Report interface.
     *
     * @see    Report::addFile()
     * @param  File $file
     * @return void
     */
    public function addFile(File $file)
    {
        $this->writer->startElement('file');
        $this->writer->writeAttribute('name', $file->getFilename());

        foreach ($file->getViolations() as $violation) {
            $this->writer->startElement('error');

            $this->writer->writeAttribute('line', $violation->getLine());

            if ($violation->getColumn() > 0) {
                $this->writer->writeAttribute('column', $violation->getColumn());
            }

            $this->writer->writeAttribute('severity', $violation->getSeverity());
            $this->writer->writeAttribute('message', $violation->getMessage());
            $this->writer->writeAttribute('source', $violation->getSource());

            $this->writer->endElement();
        }

        $this->writer->endElement();
    }

    /**
     * Close the file handle on destruct.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->writer->endElement();
        $this->writer->endDocument();
    }
}
