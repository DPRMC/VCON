<?php
namespace DPRMC\VCON;

use Carbon\Carbon;

class VCONTicket{

    const IDENTIFIER_TYPE_CUSIP = 'CUSIP';
    const IDENTIFIER_TYPE_ISIN = 'ISIN';

    public $rawText;

    /**
     * @var string The CUSIP, ISIN, or some other type of identifier.
     */
    public $identifier;

    /**
     * @var string The type of identifier used for this security. See constants above.
     */
    public $identifierType;

    /**
     * @var bool Set to false if we find a valid CUSIP or ISIN.
     */
    public $identifierMissing = true;

    /**
     * @var string The trader responsible for this ticket.
     */
    public $trader;

    /**
     * @var string The ISIN, if this trade ticket references one. For example: US0378331005
     */
    public $isin;

    /**
     * @var string The CUSIP, if this trade ticket references one. For example: 12669CF96
     */
    public $cusip;

    /**
     * @var string The size or quantity of the security bought or sold.
     * @todo Right now this is a string, and I should convert this to a number.
     */
    public $quantity;

    /**
     * @var string The name of the deal/security. For example: CWALT 2002-8 B3
     */
    public $dealName;

    /**
     * @var string The price of the security for this transaction.
     */
    public $price;

    /**
     * @var Carbon The date this transaction should settle on.
     */
    public $settleDate;

    /**
     * @var
     */
    public $nextPaymentDate;

    /**
     * @var
     */
    public $dayDelay; // zero day delay?
    public $currentFace;
    public $factorDate;

    /**
     * @var float The factor of the security at the time of the trade.
     */
    public $factor;
    public $principalValue;
    //
    public $totalFunds; // Proceeds


    public function __construct() {

    }



}