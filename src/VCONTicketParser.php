<?php
namespace DPRMC\VCON;
use Exception;
use Carbon\Carbon;
use DPRMC\CUSIP;

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
        $vconTicket->rawText = $vconText;

        $vconTicket->isin = $this->parseIsin($vconText);
        if($vconTicket->isin !== false){
            $vconTicket->identifierType = VCONTicket::IDENTIFIER_TYPE_ISIN;
            $vconTicket->identifier = $vconTicket->isin;
            $vconTicket->identifierMissing = false;
            $cusipWithIsinCheckDigit = substr($vconTicket->isin, 2);
            $cusip = substr($cusipWithIsinCheckDigit,0,-1);
            $vconTicket->cusip = $cusip;
        } else{
            $vconTicket->cusip = $this->parseCusipFromAnywhere($vconText);
            if($vconTicket->cusip !== false){
                $vconTicket->identifierType = VCONTicket::IDENTIFIER_TYPE_CUSIP;
                $vconTicket->identifier = $vconTicket->cusip;
                $vconTicket->identifierMissing = false;
            }
        }



        $vconTicket->factor = $this->parseFactor($vconText);
        $vconTicket->totalFunds = $this->parseTotalFunds($vconText);
        $vconTicket->price = $this->parsePrice($vconText);
        $vconTicket->trader = $this->parseTrader($vconText);

        $vconTicket->quantity = $this->parseQuantity($vconText);
        $vconTicket->principalValue = $this->parsePrincipal($vconText);
        $vconTicket->settleDate = $this->parseSettleDate($vconText);

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
            return false;
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
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $cusip = $matches[1];
        return (string)$cusip;
    }

    protected function parseCusipFromAnywhere($text){
        $tokens = preg_split('/\s+/', $text);
        foreach($tokens as $i => $token):
            if( CUSIP::isCUSIP($token) ):
                return $token;
            endif;
        endforeach;
        return false;
    }

    protected function parseIsin($text){
        $pattern = '/ISIN:\s*([a-zA-Z0-9]{12})\s*/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }

        // http://php.net/manual/en/function.preg-match.php
        $isin = $matches[1];
        return (string)$isin;
    }


    //
    protected function parseQuantity($text){
        $pattern = '/[\bBUYS\b|\bSELLS\b]:\s*(.*) of /';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $quantity = $matches[1];
        // @todo Return the number as a float instead of a string.
        //$numberOfDecimalPlaces = strlen(explode('.',$quantity)[1]);
        return (string)$quantity;
    }

    protected function parsePrincipal($text){
        $pattern = '/PRINCIPAL VAL\s*\$\s*(.*)/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $principal = $matches[1];
        return (string)$principal;
    }

    protected function parseSettleDate($text){
        $pattern = '/SETTLE:\s*([0-9\/]*)\s*\[/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $settleDate = $matches[1];
        return Carbon::parse($settleDate);
    }

    protected function parseTotalFunds($text){
        $pattern1Value = $this->parseTotalFundsPattern1($text);
        if($pattern1Value !== false) return $pattern1Value;
        $pattern2Value = $this->parseTotalFundsPattern2($text);
        if($pattern2Value !== false) return $pattern2Value;
        return false;
    }

    private function parseTotalFundsPattern1($text){
        $pattern = '/TOTAL FUNDS\s*\$\s*([0-9,.]*)\s*\S/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $value = $matches[1];
        return $value;
    }

    private function parseTotalFundsPattern2($text){
        $pattern = '/Total\s*\$\s*([0-9,.]*)\s*/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $value = $matches[1];
        return $value;
    }

    protected function parsePrice($text){
        $pattern1Value = $this->parsePricePattern1($text);
        if($pattern1Value !== false) return $pattern1Value;
        $pattern2Value = $this->parsePricePattern2($text);
        if($pattern2Value !== false) return $pattern2Value;
        return false;
    }

    /**
     * Price: 18-04
     * @param $text
     * @return bool
     */
    private function parsePricePattern1($text){
        $pattern = '/Price:\s*([0-9-]*)/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $value = $matches[1];
        $valueParts = explode('-',$value);
        $dollars = $valueParts[0];
        $cents = isset($valueParts[1]) ? (float)($valueParts[1] / 32) : '00';

        return (float)$dollars . '.' . $cents;
    }

    /**
     * PRICE:  14.5000000
     * @param $text
     * @return bool
     */
    private function parsePricePattern2($text){
        $pattern = '/PRICE:\s*([0-9,.]*)/';
        preg_match($pattern,$text,$matches);
        if(sizeof($matches) != 2 ){
            return false;
        }
        // http://php.net/manual/en/function.preg-match.php
        $value = $matches[1];
        $value = str_replace(',','',$value); // remove comma
        return (float)$value;
    }
}