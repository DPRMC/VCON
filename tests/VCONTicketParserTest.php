<?php

namespace DPRMC\VCON;

use PHPUnit\Framework\TestCase;
use DPRMC\VCON\VCONTicketParser;
use Carbon\Carbon;


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
        $ticketIndex = 0;
        $testValue = (float)0.9996246086;
        $text = $this->ticketTexts[ $ticketIndex ];
        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );
        $this->assertEquals( $testValue, $ticket->factor );
    }

    public function testMissingFactor() {
        $ticketIndex = 2;
        $factorForIndex = false;
        $text = $this->ticketTexts[ $ticketIndex ];
        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );
        $this->assertEquals( $factorForIndex, $ticket->factor );
    }

    /**
     *
     */
    public function testParseTrader() {
        $ticketIndex = 0;
        $traderForIndex = 'JOE S';
        $text = $this->ticketTexts[ $ticketIndex ];
        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );
        $this->assertEquals( $traderForIndex, $ticket->trader );
    }

    /**
     *
     */
    public function testParseCusip() {
        $ticketIndex = 0;
        $cusipForIndex = '00430BCA1';
        $text = $this->ticketTexts[ $ticketIndex ];
        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );
        $this->assertEquals( $cusipForIndex, $ticket->cusip );
    }

    public function testParseQuantity() {
        $ticketIndex = 0;
        $testValue = '1,234,000';
        $text = $this->ticketTexts[ $ticketIndex ];
        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );
        $this->assertEquals( $testValue, $ticket->quantity );
    }

    public function testParsePrincipal() {
        $ticketIndex = 0;
        $testValue = '12,345.67';
        $text = $this->ticketTexts[ $ticketIndex ];
        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );
        $this->assertEquals( $testValue, $ticket->principalValue );
    }

    public function testParseSettleDate() {
        $ticketIndex = 0;
        $testValue = Carbon::parse('01/23/15');
        $text = $this->ticketTexts[ $ticketIndex ];
        $ticketParser = new VCONTicketParser();
        $ticket = $ticketParser->parse( $text );
        $this->assertEquals( $testValue, $ticket->settleDate );
    }


}