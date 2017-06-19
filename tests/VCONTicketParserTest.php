<?php

namespace DPRMC\VCON;

use PHPUnit\Framework\TestCase;
use DPRMC\VCON\VCONTicketParser;


class VCONTicketParserTest extends TestCase {

    /**
     * @var array
     */
    protected $ticketUrls = [ 0 => 'https://raw.githubusercontent.com/DPRMC/VCON/master/tests/testTickets/ticket_1.txt',
                              1 => 'https://raw.githubusercontent.com/DPRMC/VCON/master/tests/testTickets/ticket_2.txt',
                              2 => 'https://raw.githubusercontent.com/DPRMC/VCON/master/tests/testTickets/ticket_3.txt',
                              3 => 'https://raw.githubusercontent.com/DPRMC/VCON/master/tests/testTickets/ticket_4.txt', ];

    /**
     * @var array
     */
    protected $ticketTexts = [];


    /**
     *
     */
    public function setUp() {
        foreach ( $this->ticketUrls as $i => $ticketUrl ):
            $this->ticketTexts[ $i ] = file_get_contents( $ticketUrl );
        endforeach;
    }

    public function testParseFactor() {
        $index = 0;
        $factorForIndex = 0.9996246086;
        $text = $this->ticketTexts[ $index ];

        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );

        $this->assertEquals( $factorForIndex, $ticket->factor );

    }

    public function testMissingFactor() {
        $index = 2;
        $factorForIndex = NULL;
        $text = $this->ticketTexts[ $index ];

        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );

        $this->assertEquals( $factorForIndex, $ticket->factor );

    }


}