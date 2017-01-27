<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Loader;

use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Type\Parser\TypeParserInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class FileClassMetadataLoader implements ClassMetadataLoaderInterface
{
    const EXTENSION_JSON = 'json';
    const EXTENSION_XML = 'xml';
    const EXTENSION_YAML = 'yml';

    /**
     * @var ClassMetadataLoaderInterface
     */
    private $loader;

    /**
     * @param string                   $file
     * @param TypeParserInterface|null $typeParser
     */
    public function __construct($file, TypeParserInterface $typeParser = null)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not exist.', $file));
        }

        switch (pathinfo($file, PATHINFO_EXTENSION)) {
            case self::EXTENSION_JSON:
                $this->loader = new JsonClassMetadataLoader($file, $typeParser);
                break;

            case self::EXTENSION_XML:
                $this->loader = new XmlClassMetadataLoader($file, $typeParser);
                break;

            case self::EXTENSION_YAML:
                $this->loader = new YamlClassMetadataLoader($file, $typeParser);
                break;
        }

        if ($this->loader === null) {
            throw new \InvalidArgumentException('The file "%s" is not supported.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        return $this->loader->loadClassMetadata($classMetadata);
    }
}
