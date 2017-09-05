<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;
use Emmanix2002\Moneywave\Moneywave;

/**
 * You can tokenize a card and use the token for initiate a card to account transfer request.
 *
 *
 * @property string $card_no        the card number of the debit card
 * @property string $cvv            the CVV of the debit card
 * @property int    $expiry_month   the expiry month of the debit card
 * @property int    $expiry_year    the expiry year of the debit card
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#card-tokenization
 */
class CardTokenization extends AbstractService
{
    /**
     * CardTokenization constructor.
     *
     * @param Moneywave $moneyWave
     */
    public function __construct(Moneywave $moneyWave)
    {
        parent::__construct($moneyWave);
        $this->setRequiredFields('card_no', 'cvv', 'expiry_month', 'expiry_year');
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
        return Endpoints::TOKENIZE_CARD;
    }
}
