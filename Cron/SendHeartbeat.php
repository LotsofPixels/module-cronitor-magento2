<?php

namespace Lotsofpixels\Cronitor\Cron;


use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Psr\Log\LoggerInterface;
use Magento\Framework\Validator\Url;

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
        Url                                 $url
    )
    {
        $this->storeConfig = $storeConfig;
        $this->_curl = $curl;
        $this->logger = $logger;
        $this->url = $url;

    }


    /**
     * @return void
     */
    public function execute(): void
    {
        if ($this->storeConfig->getValue('cronitor/general/enabled')) {
            $heartbeatState = '?state=run';
            $heartbeatUrl = $this->storeConfig->getValue('cronitor/general/ping_url');
            if ($this->url->isValid($heartbeatUrl)) {
                try{
                $MONITOR_URL = $heartbeatUrl . $heartbeatState;
                $this->_curl->get($MONITOR_URL);
                } catch (Exception $ex) {
                    $this->logger->critical($ex->getMessage());
                }
            }}
        }

}