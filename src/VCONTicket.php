<?php
namespace DPRMC\VCON;

use Carbon\Carbon;

class VCONTicket{

    const IDENTIFIER_TYPE_CUSIP = 'CUSIP';
    const IDENTIFIER_TYPE_ISIN = 'ISIN';

    public $identifier;
    public $identifierType;

    public $trader;
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
    public $totalFunds;


    public function __construct() {

    }



}