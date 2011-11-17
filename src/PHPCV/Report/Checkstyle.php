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
 * @package    PHPCV_Report
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace PHPCV\Report;

use PHPCV\File\File
    \XMLWriter;

/**
 * Checkstyle report.
 * 
 * @category   PHPCV
 * @package    PHPCV_Checkstyle
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
        
        foreach ($file->getErrors() as $error) {
            $this->writer->startElement('error');
        
            $this->writer->writeAttribute('line', $error->getLine());
                        
            if ($error->getColumn() > 0) {
                $this->writer->writeAttribute('column', $error->getColumn());
            }
            
            $this->writer->writeAttribute('severity', $error->getSeverity());
            $this->writer->writeAttribute('message', $error->getMessage());
            $this->writer->writeAttribute('source', $error->getSource());
            
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
