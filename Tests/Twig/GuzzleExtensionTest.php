<?php

namespace Kopjra\GuzzleBundle\Tests\Twig;

use GuzzleHttp\Client;
use Kopjra\GuzzleBundle\Twig\GuzzleExtension;
use Twig_Test_IntegrationTestCase;

/**
 * {@inheritdoc}
 */
class GuzzleExtensionTest extends Twig_Test_IntegrationTestCase
{
    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return [
            new GuzzleExtension(new Client()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }
}
