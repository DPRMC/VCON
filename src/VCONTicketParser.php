<?php
namespace DPRMC\VCON;
use Exception;
use Carbon\Carbon;

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
     * @param $vconText The raw text from a VCON ticket.
     * @return VCONTicket
     * @throws Exception
     */
    public function parse($vconText){
        $this->text = $vconText; // Not sure I need to save this as a property.
        $vconTicket = new VCONTicket();
        try{
            $vconTicket->factor = $this->parseFactor($vconText);
            $vconTicket->trader = $this->parseTrader($vconText);
            $vconTicket->cusip = $this->parseCusip($vconText);
            $vconTicket->quantity = $this->parseQuantity($vconText);
            $vconTicket->principalValue = $this->parsePrincipal($vconText);
            $vconTicket->settleDate = $this->parseSettleDate($vconText);
        } catch(Exception $e) {
            throw $e;
        }

        return $vconTicket;
    }

    /**
     * @param $text
     * @return bool|float
     */
    protected function parseFactor($text){
        $pattern = '/Factor\s*(.*)>/';
        preg_match($pattern,$text,$matches);

        if(sizeof($matches) != 2 ){
            return false;
            //throw new Exception("parseFactor() failed because it didn't find a factor. (Or found too many...) " . print_r($matches, true) . "\nPATTERN" . $pattern . "\n\n" . $text);
        }

        // http://php.net/manual/en/function.preg-match.php
        $factor = $matches[1];

        return (float)$factor;
    }

    /**
     * @param $text
     * @return string
     * @throws Exception
     */
    protected function parseTrader($text){
        $pattern = '/TRADER: (.*)CUSIP/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            throw new Exception("parseCusip() failed because it didn't find a CUSIP. (Or found too many...)");
        }
        // http://php.net/manual/en/function.preg-match.php
        $cusip = trim($matches[1]); // I can lose the trim if I tweak the REGEX. This feels simpler.
        return (string)$cusip;
    }


    /**
     * @param $text
     * @return string
     * @throws Exception
     */
    protected function parseCusip($text){
        $pattern = '/CUSIP:\s*(.*)\s*\n/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            throw new Exception("parseCusip() failed because it didn't find a CUSIP. (Or found too many...)");
        }
        // http://php.net/manual/en/function.preg-match.php
        $cusip = $matches[1];
        return (string)$cusip;
    }

    //
    protected function parseQuantity($text){
        $pattern = '/[\bBUYS\b|\bSELLS\b]:\s*(.*) of /';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            throw new Exception("parseQuantity() failed because it didn't find a quantity. (Or found too many...)");
        }
        // http://php.net/manual/en/function.preg-match.php
        $quantity = $matches[1];
        return (string)$quantity;
    }

    protected function parsePrincipal($text){
        $pattern = '/PRINCIPAL VAL\s*\$\s*(.*)/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            throw new Exception("parsePrincipal() failed because it didn't find a principal. (Or found too many...)");
        }
        // http://php.net/manual/en/function.preg-match.php
        $principal = $matches[1];
        return (string)$principal;
    }

    protected function parseSettleDate($text){
        $pattern = '/SETTLE:\s*([0-9\/]*)\s*\[/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            throw new Exception("parseSettleDate() failed because it didn't find a settle date. (Or found too many...)");
        }
        // http://php.net/manual/en/function.preg-match.php
        $settleDate = $matches[1];
        return Carbon::parse($settleDate);
    }
}