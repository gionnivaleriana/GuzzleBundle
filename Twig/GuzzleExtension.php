<?php

namespace Kopjra\GuzzleBundle\Twig;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Url;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use Twig_SimpleTest;

/**
 * {@inheritdoc}
 */
class GuzzleExtension extends Twig_Extension
{
    /**
     * Guzzle dependency
     *
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * Constructor used only to inject the Guzzle dependency
     *
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('guzzle', function ($value) {
                $value = (string) $value;

                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    return null;
                }

                return $this->client
                            ->get($value)
                            ->json();
            }),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new Twig_SimpleTest('visitable', function ($value) {
                if ($value instanceof Url) {
                    return true;
                }

                if (is_string($value)) {
                    try {
                        Url::fromString($value);

                        return true;
                    } catch (Exception $e) {
                        return false;
                    }
                }

                return false;
            }),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('guzzle', function ($value) {
                $value = (string) $value;

                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    return null;
                }

                return $this->client
                            ->get($value)
                            ->json();
            }),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return [
            'guzzle' => $this->client,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kopjra.guzzle_bundle.twig.guzzle_extension';
    }
}
