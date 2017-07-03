# Moneywave

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP library for consuming the Moneywave API services.    
You can check out the documentation to see all that is available: [https://moneywave.flutterwave.com/api](https://moneywave.flutterwave.com/api)     

* [Quickstart](#quickstart)
* [Introduction](#introduction)
* [Configuration](#configuration)
* [Usage](#usage)
* [Services](#services)
* [Handling Responses](#handling-responses)


<a name="quickstart">Quickstart</a>
--------
To get started, you simply need to install it via `composer`:

    $ composer require emmanix2002/moneywave
    
This will add it to your `composer.json` and install it as a project dependency.

<a name="introduction">Introduction</a>
--------
All the _Features_ and _Resources_ available on the **Moneywave** service are exposed as 
_**Services**_. Hence, to use any of the services, it first needs to be created.   

> All Features and Resources on the Moneywave API are exposed as services in this library.

The entry point to this library is the `Moneywave` class.

    $moneywave = new Moneywave();
    
We'll discuss more about this later.

<a name="configuration">Configuration</a>
--------
To use the library, you need to get your credentials from your Moneywave account. They provide you two keys:   

- API Key
- Secret Key

Your account can be in one of two states: `Test` or `Production`. For each of these _states_, you'll use different 
**keys**.    
These **keys** are required by the `Moneywave` class (and must be protected -- they are used to authenticate 
the merchant account); to use them with this library, you can use one of two possible methods.

#### Environment Variables
Using this method stores the key in a specific file on your server, meaning the values are not **hardcoded** into your 
code. The library expects to find a file called `.env` at the same level as your _**composer**_ `vendor` directory.   

    .env
    vendor/
    composer.json
    composer.lock
    
As you can see above, the setting file should be at the level described. The content of the file should be the same as 
you can find in the `.env.example` like so:

    # your account Moneywave API key
    MONEYWAVE_API_KEY="your API key goes here"
    # your account Moneywave Secret key
    MONEYWAVE_SECRET_KEY="your secret key goes here"
    # the environment - staging | production
    MONEYWAVE_ENV="staging"
    
Those values must be set to use the library; with this done, you can simply call:  

    $moneywave = new Moneywave();

#### Pass into the Constructor
The second way to configure the Moneywave client is to pass all the settings into the constructor.   
Unlike **method one**, you'll need to store the keys somewhere and provide them to the client when you instantiate it.    

    $moneywave = new Moneywave(null, $apiKey, $secretKey); # this defaults to the STAGING environment
    $moneywave = new Moneywave(null, $apiKey, $secretKey, Environment::STAGING);
    

<a name="usage">Usage</a>
--------
When the client is instantiated (**see** [configuration](#configuration)), it automatically starts up the 
`VerifyMerchant` service. This service gets an `access token` from the Moneywave service that will be used 
to **authorize** every other request you make against the API.    
Every `access token` has a lifespan of `2 hours`. In your application, you have one of 2 options:    

- Save the retrieved token to your `Session` to use it across multiple requests
- Allow the library request one for every call made to the API  

For the first option, take a look at the sample files in the `examples` directory. You'll see something like 
this:

    use Emmanix2002\Moneywave\Exception\ValidationException;
    use Emmanix2002\Moneywave\Moneywave;
    
    require(dirname(__DIR__).'/vendor/autoload.php');
    session_start();
    
    try {
        $accessToken = !empty($_SESSION['accessToken']) ? $_SESSION['accessToken'] : null;
        $mw = new Moneywave($accessToken);
        $_SESSION['accessToken'] = $mw->getAccessToken();
        $query = $mw->createWalletBalanceService();
        $response = $query->send();
        var_dump($response->getData());
        var_dump($response->getMessage());
    } catch (ValidationException $e) {
        var_dump($e->getMessage());
    }

This makes it possible to use the same `access token` for another request from the same machine.  

<a name="services">Services</a>
--------
After instantiating the `Moneywave` object, you follow these steps to use a service:

* create an instance of the required service from it by calling one of the `create*Service()` methods
* set the properties on the **service object**
* call the `send()` method on the created **service object**.     

Each feature and resource maps to a service; the mappings can be easily inferred from the **class name**.        
The table below describes all the services:   


| Class Name                | Service Call                          |      
|---------------------------|---------------------------------------|      
| AccountNumberValidation   | createAccountNumberValidationService  |      
| AccountToAccount          | createAccountToAccountService         |      
| AccountToWallet           | createAccountToWalletService          |      
| AccountTransfer           | createAccountTransferService          |      
| Banks                     | createBanksService                    |      
| CardToBankAccount         | createCardToBankAccountService        |      
| CardTokenization          | createCardTokenizationService         |      
| CardToWallet              | createCardToWalletService             |      
| CardTransfer              | createCardTransferService             |      
| Disburse                  | createDisburseService                 |      
| DisburseBulk              | createDisburseBulkService             |      
| QueryCardToAccountTransfer| createQueryCardToAccountTransfer      |      
| QueryDisbursement         | createQueryDisbursementService        |      
| RetryFailedTransfer       | createRetryFailedTransferService      |      
| TotalChargeToCard         | createTotalChargeToCardService        |      
| ValidateCardTransfer      | createValidateCardTransferService     |      
| ValidateTransfer          | createValidateTransferService         |      
| VerifyMerchant            | createVerifyMerchantService           |      
| WalletBalance             | createWalletBalanceService            |      


Each service has a list of properties that must be set on it before it can be sent to the API; if one or more of 
these properties are not set, calling `send()` throws a `ValidationException`.   
Let's use the `Account Number validation API` _resource_ as an example:    
From the documentation, the following properties are required:    

| Field Name    | Description                               |
| ------------- | ----------------------------------------- |
| account_number| the account number of the sender          |
| bank_code     | the bank code of the account to resolve   |

To use the library, we'll do something like this:  

    $moneywave = new Moneywave();
    $accountValidation = $moneywave->createAccountNumberValidationService();
    $accountValidation->account_number = '0690000004';
    $accountValidation->bank_code = Banks::ACCESS_BANK;
    $response = $accountValidation->send();
    
If one of those fields was not set on the _service object_, a `ValidationException` exception would have been thrown.
    
> Every field defined within the Moneywave documentation (for a service) can be set as a property on the 
> created service object.

#### Special Fields
There are certain fields which are special, and do not need to be set by you (although you can choose to set them); 
they'll be automatically given their required value by the library. Find them listed below:

| Field     | Description                                                                   |
| --------- | ----------------------------------------------------------------------------- |
| apiKey    | this will be set to the API key used to instantiate the `Moneywave` object    |
| secret    | this will be set to the secret key used for the `Moneywave` object            |
| fee       | set by **default** to `0`                                                     |
| recipient | for the `createCardToWalletService`, this is set to `wallet` by default       |
| currency  | automatically set to Naira `Currency::NAIRA`                                  |

#### Special Services
Just as there're special fields, there're also some special service objects, that present more than the regular: 
`send()` method.    

#### Transfers (`createCardToBankAccountService()`, `createCardToWalletService()`)
These transfer services are special because, most times, they're _2-legged_. They involve the following steps:    

1 The transfer leg
2 The validation leg

These steps work one after the other; and both steps must be completed to _*complete*_ the transfer.     
There're 2 services that take care of the `validation` leg; they are:

- `createValidateCardTransferService()`: allows you to validate a _*card to wallet*_ or _*card to account*_ transfer 
made with a _Verve_ debit card
- `createValidateTransferService()`: allows you to validate a _*card to wallet*_ or _*card to account*_ transfer made 
with an account. That is, the __charge_with__ field was set to `ChargeMethod::ACCOUNT`.    

So, when you receive a success response from the API after the _first leg_; you start the validate leg.

_*NOTE*_: For Mastercard and Visa card transfers, you'll receive a `authurl` key in the successful API response; you 
 are to _*redirect*_ to this URL for the payer to validate the transfer. After success or failure, the payer will be
 redirected back to the URL set in the `redirecturl` field of the transfer request.

##### createDisburseBulkService()
This service is for disbursing cash from your `Moneywave` wallet to multiple bank accounts. It has a special method on 
it for adding the individual `beneficiary` accounts. 

    addRecipient(string $bankCode, string $accountNumber, float $amount, string $reference = null);
    
This method allows you to add each beneficiary account in turn:

    $bulkDisbursement = $moneywave->createDisburseBulkService();
    $bulkDisbursement->lock = 'wallet password';
    $bulkDisbursement->ref = 'unique reference';    # suggestion: you could use a UUID; check out ramsey/uuid package
    $bulkDisbursement->senderName = 'MoneywaveSDK';
    $bulkDisbursement->addRecipient(Banks::ACCESS_BANK, '0690000004', 1)
                     ->addRecipient(Banks::ACCESS_BANK, '0690000005', 2);
                         
Look at the `examples/disburse_bulk.php` file for the full example.

<a name="handling-responses">Handling Responses</a>
----------
If all the required fields on the **service object** (see [services](#services)) have been set, the call to `send()` 
will return a _response object_ which is an instance of the `MoneywaveResponse` class.    
Continuing with the _Account Validation_ example above:    

    $response = $accountValidation->send();
    
A successful response `JSON` will be of this form:   

    {
        status: "success",
        data: {
            name: "MICHAEL JACKSON"
        }
    }
    
While a failure response `JSON` will be of the form:    

    {
        status: "error",
        message: "error message description",
        code: "error code string; e.g. INVALID_ID",
        data: "data string -- this is absent most of the time"
    }
    
The `$response` variable presents a few functions that can be used to work with this data:

    # was a request successful?
    if ($response->isSuccessful()) {
        # do something with the returned data
        $name = $response->getData()['name'];
    } else {
        # this was a failure
        $message = $response->getMessage();
        $code = $response->getCode();
        $data = $response->getData();
    }
    
_**NOTE**_: All keys within the response `JSON` are also accessible from the **response object** as properties:

    if ($response->isSuccessful()) {
        $name = $response->getData()['name'];
    } else {
        $message = $response->message;
        $code = $response->code;
    }
    
As another example, the response from the `VerifyMerchant` service looks like this:

    {
        status: "success",
        token: "" // a valid merchant token
    }
    
Using the response, you'll have to do this:

    $response = $verifyService->send();
    if ($response->isSuccessful()) {
        $token = $response->token;
    } else {
        # process the error response as you normally would
    }
    
The table below describes the methods defined on the `MoneywaveResponse` object:

| Method            | Return Type   | Description                               |
| ----------------- | ------------- | ----------------------------------------- |
| getRawResponse()  | string        | the JSON response from the API            |
| isSuccessful()    | bool          | checks if the `status` key `=== "success"`|
| getCode()         | string        | returns the `code` key                    |
| getMessage()      | string        | returns the `message` attribute           |
| getData()         | array         | returns the `data` key                    |

**NOTE**: For responses where `data` is a string; it returns this array `[data: string]`


[ico-version]: https://img.shields.io/packagist/v/emmanix2002/moneywave.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/emmanix2002/moneywave/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/emmanix2002/moneywave.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/emmanix2002/moneywave.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/emmanix2002/moneywave
[link-travis]: https://travis-ci.org/emmanix2002/moneywave
[link-code-quality]: https://scrutinizer-ci.com/g/emmanix2002/moneywave
[link-downloads]: https://packagist.org/packages/emmanix2002/moneywave
[link-author]: https://github.com/emmanix2002