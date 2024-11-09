<?php

namespace Lotsofpixels\Cronitor\Cron;


use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Psr\Log\LoggerInterface;
use Magento\Framework\Validator\Url;
use Magento\Store\Model\StoreManagerInterface;

/**
 *
 */
class SendHeartbeat
{
    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;

    /**
     * @var ScopeConfigInterface
     */
    protected $storeConfig;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected $storeManager;


    /**
     * Data constructor.
     *
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param ScopeConfigInterface $storeConfig
     */
    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $curl,
        ScopeConfigInterface                $storeConfig,
        LoggerInterface                     $logger,
        Url                                 $url,
        StoreManagerInterface $storeManager
    )
    {
        $this->storeConfig = $storeConfig;
        $this->_curl = $curl;
        $this->logger = $logger;
        $this->url = $url;
        $this->storeManager = $storeManager;

    }


    /**
     * @return void
     */
    public function execute(): void
    {
        $cronitorHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            "User-Agent" => 'cronitor-magento',
            "Cronitor-Version" => '1.0.1',
        ];

        $heartbeatState = '?state=run';

        $env = '&env=' .$this->storeConfig->getValue('cronitor/general/environment');

        $host = '&host=' . $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);

        $count = '&metric=count:' . date('i');

        if ($this->storeConfig->getValue('cronitor/general/enabled')) {
            $heartbeatUrl = $this->storeConfig->getValue('cronitor/general/ping_url');
            if ($this->url->isValid($heartbeatUrl)) {
                    $MONITOR_URL = $heartbeatUrl . $heartbeatState . $host . $count;
                    $this->_curl->setHeaders($cronitorHeaders);
                    $this->_curl->get($MONITOR_URL);
                    $this->logger->info($MONITOR_URL);
            }
        }
    }

}