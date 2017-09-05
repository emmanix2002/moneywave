<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * Get total amount that will be charged to a card based on the amount, and additional fee (if any).
 *
 * Every transfer from a card attracts certain fees for the service. As a merchant, you can also choose to add your
 * own fee you want to charge your customer for the service. To get the total amount we’ll charge
 * (all fees+transfer amount), you need to call the /v1/get-charge endpoint.
 *
 *
 * @property float  $amount the amount to charge the card for
 * @property float  $fee    the service fee to charge on behalf of the merchant (default: 0)
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#get-total-charge-to-card
 */
class TotalChargeToCard extends AbstractService
{
    /**
     * TotalChargeToCard constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->fee = 0;
        $this->setRequiredFields('amount', 'fee');
    }

    /**
     * Returns the HTTP request method for the service.
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return 'POST';
    }

    /**
     * Returns the API request path for the service.
     *
     * @return string
     */
    public function getRequestPath(): string
    {
        return Endpoints::GET_CHARGE;
    }
}
