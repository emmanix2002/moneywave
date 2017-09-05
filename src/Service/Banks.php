<?php

namespace Emmanix2002\Moneywave\Service;

use Emmanix2002\Moneywave\Enum\Endpoints;

/**
 * Get the list of supported banks and their Moneywave code.
 *
 * A lot of the time, when using the moneywave API, you’ll be required to enter a 3-character bank code used to
 * identify a bank. You can get this list of banks by calling the /banks endpoint. We’ll really recommend that you
 * send a post request to this end point and save the response in a variable before you call any other endpoint when
 * using Moneywave.
 *
 * You can also get the Bank codes from the Emmanix2002\Moneywave\Enum\Banks class
 *
 * @link https://moneywave-doc.herokuapp.com/index.html#get-list-of-banks
 */
class Banks extends AbstractService
{
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
        return Endpoints::BANKS;
    }
}
