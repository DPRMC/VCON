<?php
namespace DPRMC\VCON;
use Exception;

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
        try{
            $vconTicket->factor = $this->parseFactor($vconText);
        } catch(Exception $e) {

        }

        return $vconTicket;
    }

    /**
     * @param $text
     * @return float
     * @throws Exception
     */
    protected function parseFactor($text){
        $pattern = '/Factor\s*(.*)>/';
        preg_match($pattern,$text,$matches);

        if(sizeof($matches) != 2 ){
            throw new Exception("parseFactor() failed because it didn't find a factor. (Or found too many...)");
        }

        // http://php.net/manual/en/function.preg-match.php
        $factor = $matches[1];

        return (float)$factor;
    }
}