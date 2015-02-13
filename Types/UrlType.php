<?php

namespace Kopjra\GuzzleBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Exception;
use GuzzleHttp\Url;

/**
 * ...
 */
class UrlType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value) || !trim($value)) {
            return;
        }

        try {
            return Url::fromString($value);
        } catch (Exception $e) {
            return $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value) || !trim($value)) {
            return;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'url';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return '\\GuzzleHttp\\Url';
    }
}
