<?php
namespace DPRMC\VCON;

use Carbon\Carbon;

class VCONTicket{

    const IDENTIFIER_TYPE_CUSIP = 'CUSIP';
    const IDENTIFIER_TYPE_ISIN = 'ISIN';

    public $rawText;

    public $identifier;
    public $identifierType;

    /**
     * @var bool Set to false if we find a valid CUSIP or ISIN.
     */
    public $identifierMissing = true;

    public $trader;
    public $isin;
    public $cusip;
    public $quantity;
    public $dealName;
    //
    //
    public $price;
    public $settleDate;
    public $nextPaymentDate;
    public $dayDelay; // zero day delay?
    public $currentFace;
    public $factorDate;
    public $factor;
    public $principalValue;
    //
    public $totalFunds; // Proceeds


    public function __construct() {

    }



}