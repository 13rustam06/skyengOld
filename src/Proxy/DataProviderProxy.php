<?php

namespace src\Proxy;

use DateTime;
use Exception;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

/**
 * Class DataProviderProxy
 * @package src\Proxy
 */
class DataProviderProxy extends DataProvider
{
    /**
     * @var CacheInterface
     */
    public $cache;
    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        string $host,
        string $user,
        string $password,
        CacheInterface $cache,
        LoggerInterface $logger)
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input): array
    {
        try {
            // TODO Если оставлять так, то должен быть свой error_handler. В котором логируется такие ошибки.
            $cacheKey = $this->getCacheKey($input);
            $result = $this->cache->get($cacheKey);
            if ($result === null) {
                $result = parent::getResponse($input);
                $this->cache->set($cacheKey, $result, Config::getConfig("ttl"));
            }

            return $result;
        } catch (Exception $e) {
            // TODO Нужна поддержка Exception на стороне логгирование + логировать все об ошибки.
            // $e->getMessage(), $e->getCode() ... Не буду расписывать что нужен logger agregator итд
            $this->logger->critical($e);
        }

        return [];
    }

    /**
     * @param array $input
     * @return string
     */
    private function getCacheKey(array $input): string
    {
        return json_encode($input);
    }
}
