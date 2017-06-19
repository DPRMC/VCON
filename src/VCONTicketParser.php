<?php
namespace DPRMC\VCON;

class VCONTicketParser{
    /**
     * @var string $text
     */
    protected $text;

    /**
     * VCONTicketParser constructor.
     */
    public function __construct() {

    }


    /**
     * @param string $vconText The raw text from a VCON ticket.
     * @return VCONTicket
     */
    public function parse($vconText){
        $this->text = $vconText; // Not sure I need to save this as a property.
        $vconTicket = new VCONTicket();
        $vconTicket->factor = $this->parseFactor($vconText);


        return $vconTicket;
    }

    protected function parseFactor($text){
        $factor = null;
        $pattern = '/Factor\s*(.*)>/';
        preg_match($pattern,$text,$matches);
        print_r($matches);
        return $factor;
    }
}