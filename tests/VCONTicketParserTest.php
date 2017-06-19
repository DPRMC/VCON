<?php
namespace DPRMC\VCON;

use PHPUnit\Framework\TestCase;
use DPRMC\FileMunger\Engines\GS360JournalEntryToSentryFile;




class VCONTicketParserTest extends TestCase {

    protected $tickets = [];

    public function setUp() {
        

    }

    public function testParseFactor() {

    }

    public function testGetSource() {
        $engine = new GS360JournalEntryToSentryFile();
        $munger = new Munger($engine);
        $munger->setSource();
        $source = '/not/a/real/path/file.csv';
        $returnedSource = $munger->getSource();
        $this->assertEquals($returnedSource,
                            $source);
    }


}