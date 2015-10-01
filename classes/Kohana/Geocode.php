<?php defined('SYSPATH') OR die('No direct script access.');

use Geocoder\Geocoder;
use Geocoder\Adapter;
use Geocoder\Provider\AbstractProvider;

class Kohana_Geocode
{
	/** @var HttpAdapterInterface */
	protected $_adapter;
	/** @var Geocoder */
	protected $_geocoder;

	/** @var array */
	protected static $_config;

	/** @var AbstractProvider[] */
	protected $_providers;

//	/**
//	 * @param array|null  $providersConfig
//	 * @param int|null    $providersMaxResults
//	 * @param string|null $adapter
//	 */
//	function __construct(array $providersConfig = NULL, $providersMaxResults = NULL, $adapter = NULL)
//	{
//		// Initializing adapter
//		NULL !== $adapter or $adapter = $this->config('adapter');
//		$adapter = "\\Geocoder\\HttpAdapter\\{$adapter}HttpAdapter";
//		$this->_adapter = new $adapter();
//
//		// Initializing Providers
//		$this->_providers = [];
//		NULL !== $providersConfig or $providersConfig = $this->config('providers');
//		foreach ($providersConfig as $providerName => $providerConfig) {
//			$instance = new ReflectionClass("\\Geocoder\\Provider\\{$providerName}");
//			/** @var ProviderInterface $providerObject */
//			$providerObject = $instance->newInstanceArgs(Arr::merge(['adapter' => $this->_adapter], $providerConfig));
//			NULL === $providersMaxResults or $providerObject->setMaxResults($providersMaxResults);
//
//			$this->_providers[$providerObject->getName()] = $providerObject;
//		}
//	}

	static function factory($providerName)
	{
        $adapter = NULL;

        // Select adapter
        switch ($providerName) {
            case 'google_maps':
            case 'yandex':
                $adapter = new \Ivory\HttpAdapter\CurlHttpAdapter();
                break;

            case 'geoip2':
                throw new Kohana_Exception('TODO GeoIP2 provider adapter');
                break;

            default:
                throw new Kohana_Exception('Can not find adapter for provider :name', array(':name' => $providerName));
        }

        $providerConfig = self::config('providers.'.$providerName);

        $provider = NULL;

        // Select adapter
        switch ($providerName) {
            case 'google_maps':
                $provider = new \Geocoder\Provider\GoogleMaps(
                    $adapter,
                    $providerConfig['locale'],
                    $providerConfig['region'],
                    $providerConfig['useSsl'],
                    $providerConfig['apiKey']
                );
                break;

            case 'yandex':
                $provider = new \Geocoder\Provider\Yandex(
                    $adapter,
                    $providerConfig['locale'],
                    $providerConfig['toponym']
                );
                break;

            case 'geoip2':
                throw new Kohana_Exception('TODO GeoIP2 provider');
                break;

            default:
                throw new Kohana_Exception('Can not find provider :name', array(':name' => $providerName));
        }

        return $provider;
//        return new Geocode($providersConfig, $providersMaxResults, $adapter);
	}

	/**
	 * @param null|mixed  $path
	 * @param null|mixed  $default
	 * @param null|string $delimeter
	 *
	 * @return array|mixed
	 */
	public static function config($path = NULL, $default = NULL, $delimeter = NULL)
	{
		if (NULL === self::$_config) {
			self::$_config = Kohana::$config->load('geocode')->as_array();
		}

		return NULL === $path
			? self::$_config
			: Arr::path(self::$_config, $path, $default, $delimeter);
	}

//	/**
//	 * @param string      $value
//	 * @param null        $limit
//	 * @param null|string $using
//	 * @param bool|null   $useChain
//	 *
//	 * @return \Geocoder\Result\ResultInterface
//	 */
//	public function geocode($value, $limit = NULL, $using = NULL, $useChain = NULL)
//	{
//		$instance = $this->instance($useChain);
//
//		NULL === $limit or $instance->limit($limit);
//		NULL === $using or $instance->using($using);
//
//		return $instance->geocode($value);
//	}

//	/**
//	 * @param           $latitude
//	 * @param           $longitude
//	 * @param null      $limit
//	 * @param null      $using
//	 * @param bool|null $useChain
//	 *
//	 * @return \Geocoder\
//	 */
//	public function reverse($latitude, $longitude, $limit = NULL, $using = NULL, $useChain = NULL)
//	{
//		$instance = $this->instance($useChain);
//
//		NULL === $limit or $instance->limit($limit);
//		NULL === $using or $instance->using($using);
//
//		return $instance->reverse($latitude, $longitude);
//	}
//
//	/**
//	 * @param null $useChain
//	 *
//	 * @return Geocoder
//	 */
//	protected function instance($useChain = NULL)
//	{
//		if (!isset($this->_geocoder) or NULL !== $useChain) {
//			// Initializing Geocoder
//			$this->_geocoder = new \Geocoder\Geocoder();
//
//			if ($useChain && count($this->_providers) > 1) {
//				$this->_geocoder->registerProvider(
//					new \Geocoder\Provider\ChainProvider($this->_providers)
//				);
//			} else {
//				$this->_geocoder->registerProviders($this->_providers);
//			}
//		}
//
//		return $this->_geocoder;
//	}

}
