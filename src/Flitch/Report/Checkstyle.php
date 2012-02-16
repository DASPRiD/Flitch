<?php
/**
 * Flitch
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   Flitch
 * @package    Flitch_Report
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Report;

use Flitch\File\File
    \XMLWriter;

/**
 * Checkstyle report.
 * 
 * @category   Flitch
 * @package    Flitch_Report
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class Checkstyle implements Report
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
                        
            if ($error->getColumn() > 0) {
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
