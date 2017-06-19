<?php
namespace DPRMC\VCON;

class VCONTicketParser{
    public function __construct() {

    }


    /**
     * @param string $vconText The raw text from a VCON ticket.
     * @return VCONTicket
     */
    public function parse($vconText){

        $vconTicket = new VCONTicket();
        return $vconTicket;
    }
}