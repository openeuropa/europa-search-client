<?php

/**
 * @file
 * Contains EC\EuropaSearch\Index\Communication\Providers\ParserProvider
 */

namespace EC\EuropaSearch\Index\Communication\Providers;

use EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Index\Communication\Parsers\BooleanMetadataParser;
use EC\EuropaSearch\Index\Communication\Parsers\DateMetadataParser;
use EC\EuropaSearch\Index\Communication\Parsers\DefaultMetadataParser;
use Pimple\Container;

/**
 * Class ParserProvider.
 *
 * Provides the different parser classes for the transformation process of
 * the communication layer.
 *
 * @package EC\EuropaSearch\Index\Communication\Providers
 */
class ParserProvider extends Container
{

    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->container = new Container();

        $parserMapping = $this->getParserMapping();
        foreach ($parserMapping as $key => $class) {
            $this->container[$key] = function (Container $container) use ($class) {
                return new $class();
            };
        }
    }

    /**
     * Return service object or parameter value.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->container[$name];
    }

    /**
     * Gets the mapping between the metadata objects and their parser.
     *
     * @return array
     *   The mapping list where:
     *   - The key is the parser identifier (name) as defined in the metadata
     *     object;
     *   - The corresponding parser.
     */
    private function getParserMapping()
    {

        $defaultClass = DefaultMetadataParser::class;

        return [
            'parser.metadata.boolean' => BooleanMetadataParser::class,
            'parser.metadata.date' => DateMetadataParser::class,
            'parser.metadata.float' => $defaultClass,
            'parser.metadata.fulltext' => $defaultClass,
            'parser.metadata.integer' => $defaultClass,
            'parser.metadata.not_indexed' => $defaultClass,
            'parser.metadata.string' => $defaultClass,
            'parser.metadata.uri' => $defaultClass,
        ];
    }
}
