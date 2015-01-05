<?php

namespace Kopjra\GuzzleBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

/**
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @date 27/12/14
 *
 * Class Services
 * @package Kopjra\GuzzleBundle\Services
 */
class Services
{
    /**
     * Single GuzzleHttp\Command\Guzzle\Description
     * returned by getWebService() in case only one
     * webservice is provided
     *
     * @var Description The WebService
     */
    public $webService;

    /**
     * Object of GuzzleHttp\Command\Guzzle\Description
     * returned by getWebService() in case multple
     * web services are provided
     *
     * @var \stdClass Object of WebServices
     */
    public $webServices;

    /**
     * @var string Default path if not setted
     *             app/Resources/webservices
     */
    private $path;

    public function __construct()
    {
        $this->webServices = new \stdClass();
    }

    /**
     * Optional configuration, such as custom path for the remote
     * web service file location
     *
     * @param array $config
     */
    public function config(array $config)
    {
        /** @var \AppKernel $kernel */
        $kernel = $GLOBALS['kernel'];
        $path = isset($config["path"]) ? $config["path"] : "Resources/webservices";
        $this->path = $kernel->getRootDir()."/".$path;
    }

    /**
     * Sets the web service to use
     *
     * @param  string|array     $webServices The web service or array of
     *                                       web services
     * @param $type string              Type|Extension of the file to load
     * @param $path string              Optional custom path
     * @return string|\stdClass The string to be then converted in a
     *                                      GuzzleHttp\Command\Guzzle\Description obj
     *                                      Or an object with the strings
     */
    public function setWebServices($webServices, $type = "json", $path = null)
    {
        $path = isset($path) ? $path : $this->path;
        if (is_array($webServices)) {
            foreach ($webServices as $webService) {
                $this->webServices->{$webService} = $this->getWebService($webService, $type, $path);
            }

            return $this->webServices;
        }
        $this->webService = $this->getWebService($webServices, $type, $path);

        return $this->webService;
    }

    /**
     * Attach the selected web service to the Client
     *
     * @param  Client       $client     Http Client
     * @param  array        $webservice The web service as array
     * @return GuzzleClient Guzzle web service
     *                                 client implementation
     */
    public function attachWebService(Client $client, Array $webservice)
    {
        return new GuzzleClient($client, new Description($webservice));
    }

    /**
     * Get the web service from a remote file in xml|json format
     *
     * @param $webServices string           Name of the Web Service to load
     * @param $type string                  Type|Extension of the file to load
     * @param $path string                  Optional custom path
     * @return string                    The string to be then converted in a
     *                                   GuzzleHttp\Command\Guzzle\Description obj
     * @throws \InvalidArgumentException The $type must be implemented in the switch
     */
    private function getWebService($webServices, $type, $path)
    {
        switch ($type) {
            // TODO: Implement yaml?
            case "xml":
                return json_decode(
                    json_encode((array) simplexml_load_string(
                            file_get_contents($path."/".$webServices.".xml")
                        )
                    ),
                    true
                );
            case "json":
                return json_decode(
                    file_get_contents($path."/".$webServices.".json"),
                    true
                );
                break;
            default: throw new \InvalidArgumentException("Type ['$type'] not recognized or not implemented yet.");
        }
    }
}
