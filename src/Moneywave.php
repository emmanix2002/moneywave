<?php

namespace Emmanix2002\Moneywave;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Emmanix2002\Moneywave\Enum\Environment;
use Emmanix2002\Moneywave\Exception\UnknownServiceException;
use Emmanix2002\Moneywave\Service\AccountNumberValidation;
use Emmanix2002\Moneywave\Service\Banks;
use Emmanix2002\Moneywave\Service\CardToBankAccount;
use Emmanix2002\Moneywave\Service\CardToWallet;
use Emmanix2002\Moneywave\Service\Disburse;
use Emmanix2002\Moneywave\Service\DisburseBulk;
use Emmanix2002\Moneywave\Service\QueryCardToAccountTransfer;
use Emmanix2002\Moneywave\Service\QueryDisbursement;
use Emmanix2002\Moneywave\Service\RetryFailedTransfer;
use Emmanix2002\Moneywave\Service\TotalChargeToCard;
use Emmanix2002\Moneywave\Service\VerifyMerchant;
use Emmanix2002\Moneywave\Service\WalletBalance;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * The entry point for accessing the SDK.
 *
 * An instance of this class needs to be created first; from the created instance, it is then possible to create
 * instances of the various services.
 *
 * Each functionality/endpoint of the Moneywave API is exposed as a service. To gain access to the service, an instance
 * of the service needs to be created, then the <code>send()</code> method called on it to make a call to the API.
 *
 * An example follows:
 * $moneywave = new Moneywave(); # this automatically creates the VerifyMerchant service to get an authorization token
 * $walletBalance = $moneywave->createWalletBalanceService(); # creates the service
 * $response = $walletBalance->send();  # sends the request
 *
 * You should wrap most services in a try-catch block, since there's always the possibility of it throwing a
 * ValidationException if some of the required data has not been set.
 * For more examples, check out the contents of the example directory.
 *
 *
 * @package Emmanix2002\Moneywave
 *
 * @method AccountNumberValidation      createAccountNumberValidationService()
 * @method Banks                        createBanksService()
 * @method CardToBankAccount            createCardToBankAccountService()
 * @method CardToWallet                 createCardToWalletService()
 * @method Disburse                     createDisburseService()
 * @method DisburseBulk                 createDisburseBulkService()
 * @method QueryCardToAccountTransfer   createQueryCardToAccountTransferService()
 * @method QueryDisbursement            createQueryDisbursementService()
 * @method RetryFailedTransfer          createRetryFailedTransferService()
 * @method TotalChargeToCard            createTotalChargeToCardService()
 * @method VerifyMerchant               createVerifyMerchantService()
 * @method WalletBalance                createWalletBalanceService()
 *
 * @link https://moneywave.flutterwave.com/api
 */
class Moneywave
{
    /** @var array  */
    private $envUrls = [
        Environment::PRODUCTION => 'https://live.moneywaveapi.co/',
        Environment::STAGING => 'https://moneywave.herokuapp.com/'
    ];
    
    /** @var string  */
    private $environment = Environment::STAGING;
    
    /** @var null|string  */
    private $apiKey = null;
    
    /** @var null|string  */
    private $secretKey = null;
    
    /** @var Client  */
    private $httpClient;
    
    /** @var null|string  */
    private $accessToken = null;
    
    /** @var null|LoggerInterface  */
    private $logger = null;
    
    /**
     * Moneywave constructor.
     *
     * @param string|null          $accessToken an unexpired access token, if available
     * @param string|null          $apiKey
     * @param string|null          $secretKey
     * @param string|null          $environment one of the Environment::* constants
     * @param LoggerInterface|null $logger
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $accessToken = null,
        string $apiKey = null,
        string $secretKey = null,
        string $environment = null,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?: $this->setupLogger();
        $this->accessToken = $accessToken ?: null;
        $this->loadSettings();
        $env = $environment ?: getenv('MONEYWAVE_ENV');
        $this->apiKey = $apiKey ?: getenv('MONEYWAVE_API_KEY');
        $this->secretKey = $secretKey ?: getenv('MONEYWAVE_SECRET_KEY');
        $this->setEnvironment($env);
        if (empty($this->accessToken)) {
            $this->verifyMerchant();
        }
    }
    
    /**
     * This method is responsible for creating wrappers around the services
     *
     * @param string $name
     * @param string $arguments
     *
     * @return $this
     *
     * @throws UnknownServiceException
     */
    public function __call($name, $arguments)
    {
        $action = null;
        if (strpos($name, 'create') === 0 && strtolower(substr($name, -7)) === 'service') {
            $action = 'createService';
        }
        switch ($action) {
            case 'createService':
                $serviceName = substr($name, 6, -7);
                $className = __NAMESPACE__.'\\Service\\'.$serviceName;
                $this->logger->info('Create service', ['service' => $serviceName, 'class' => $className]);
                if (!class_exists($className)) {
                    throw new UnknownServiceException('Unknown service '.$serviceName);
                }
                return new $className($this);
                break;
        }
        return $this;
    }
    
    /**
     * It sets the operating environment for all calls.
     * It sets the environment, as well as adjusts the base_uri used for making requests in the Http Client.
     *
     * @param string $environment   One of the Environment::* values
     *
     * @return Moneywave
     * @throws \InvalidArgumentException
     */
    public function setEnvironment(string $environment): Moneywave
    {
        $options = [Environment::STAGING, Environment::PRODUCTION];
        if (!in_array($environment, $options, true)) {
            throw new \InvalidArgumentException('The environment must be one of: '.implode(', ', $options));
        }
        $this->environment = $environment;
        try {
            $this->httpClient = new Client([
                'base_uri' => $this->envUrls[$this->environment],
                RequestOptions::TIMEOUT => 60.0,
                RequestOptions::CONNECT_TIMEOUT => 60.0
            ]);
        } catch (ConnectException $e) {
            $this->logger->error($e->getMessage());
        }
        return $this;
    }
    
    /**
     * Loads the environment variables.
     * If this library was not installed using composer, it loads settings from the library's root directory, else
     * it tries to load settings from the directory which is the parent to the composer vendor directory.
     *
     * @return Moneywave
     */
    private function loadSettings(): Moneywave
    {
        try {
            $vendorDir = dirname(__DIR__, 3);
            $loadDir = strpos($vendorDir, PATH_SEPARATOR.'vendor') === false ? dirname(__DIR__) : dirname($vendorDir);
            # if the /vendor path doesn't exist in the variable, use the current directory, else use the project dir
            $dotEnv = new Dotenv($loadDir);
            $dotEnv->load();
        } catch (InvalidPathException $e) {
            $this->logger->error($e->getMessage());
        }
        return $this;
    }
    
    /**
     * Returns the API key as a string
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return (string) $this->apiKey;
    }
    
    /**
     * Returns the secret key as a string
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return (string) $this->secretKey;
    }
    
    /**
     * Returns the access token to be used for authorising requests
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return (string) $this->accessToken;
    }
    
    /**
     * Returns the Guzzle Http client instantiated for the class
     *
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }
    
    /**
     * Returns the logger
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
    
    /**
     * Sets up a logger for the package
     *
     * @return LoggerInterface
     */
    private function setupLogger(): LoggerInterface
    {
        $logger = new Logger(__CLASS__);
        $logger->pushHandler(new ChromePHPHandler());
        $logger->pushHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::WARNING));
        return $logger;
    }
    
    /**
     * Verifies the merchant against the API for a session.
     * This verifies the merchant and gets an access token for authorizing other requests in a response.
     */
    public function verifyMerchant()
    {
        $verifyService = $this->createVerifyMerchantService();
        try {
            $mvVerify = $verifyService->send();
            $this->accessToken = $mvVerify->isSuccessful() ? $mvVerify->token : '';
        } catch (BadResponseException $e) {
            $this->logger->error($e->getMessage(), [
                'status code' => $e->getResponse()->getStatusCode(),
                'response' => $e->getResponse()->getBody()->getContents()
            ]);
        }
    }
}