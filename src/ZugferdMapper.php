<?php

/**
 * This file is a part of horstoeko/zugferd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\zugferd;

use InvalidArgumentException;

/**
 * Class representing the mapper of several codes to own codes and visa versa (e.g. for unit codes)
 *
 * @category Zugferd
 * @package  Zugferd
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/zugferd
 */
class ZugferdMapper
{
    /**
     * Internal list that holds the mappings
     *
     * @var array
     */
    private static $mappings = [];

    /**
     * Identifier for unit code mapping area
     */
    public const MAPPING_AREA_UNITCODE = "unitcode";

    /**
     * Identifier for currency mapping area
     */
    public const MAPPING_AREA_CURRENCY = "currency";

    /**
     * Identifier for incoming mapping
     */
    public const DIRECTION_INCOMING = "incoming";

    /**
     * Identifier for outgoing mapping
     */
    public const DIRECTION_OUTGOING = "outgoing";

    /**
     * Mapping array key identifier for direction
     */
    protected const KEY_DIRECTION = "direction";

    /**
     * Mapping array key identifier for the "from code"
     */
    protected const KEY_FROMCODE = "fromcode";

    /**
     * Mapping array key identifier for the "to code"
     */
    protected const KEY_TOCODE = "tocode";

    /**
     * Load mappings from JSON string
     *
     * @param string $json
     * @return boolean
     */
    public static function loadFromJson(string $json): bool
    {
        $jsonDecoded = json_decode($json, true);

        if (!is_array($jsonDecoded)) {
            return false;
        }
    }

    /**
     * Saves mappings to JSON string
     *
     * @return string
     */
    public static function saveToJson(): string
    {
        return json_encode(static::$mappings, JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    /**
     * Saves mappings to a JSON file
     *
     * @param string $filename
     * @return boolean
     */
    public static function saveToJsonFile(string $filename): bool
    {
        return file_put_contents($filename, static::saveToJson()) !== false;
    }

    /**
     * Add a mapping to the internal mapping list
     *
     * @param string $mappingArea
     * @param string $direction
     * @param string $fromCode
     * @param string $toCode
     * @return void
     */
    public static function addMapping(string $mappingArea, string $direction, string $fromCode, string $toCode): void
    {
        static::testMappingAreaIsValid($mappingArea);
        static::testDirectionIsValid($direction);
        static::ensureMappingArea($mappingArea);

        static::$mappings[$mappingArea][] = [
            static::KEY_DIRECTION => $direction,
            static::KEY_FROMCODE => $fromCode,
            static::KEY_TOCODE => $toCode,
        ];
    }

    /**
     * Adds a unit code mapping
     *
     * @param string $fromCode
     * @param string $toCode
     * @return void
     */
    public static function addUnitCodeMapping(string $direction, string $fromCode, string $toCode): void
    {
        static::addMapping(static::MAPPING_AREA_UNITCODE, $direction, $fromCode, $toCode);
    }

    /**
     * Adds a unit code mapping (for the incoming direction)
     *
     * @param string $fromCode
     * @param string $toCode
     * @return void
     */
    public static function addUnitCodeMappingIncoming(string $fromCode, string $toCode): void
    {
        static::addUnitCodeMapping(static::DIRECTION_INCOMING, $fromCode, $toCode);
    }

    /**
     * Adds a unit code mapping (for the incoming direction)
     *
     * @param string $fromCode
     * @param string $toCode
     * @return void
     */
    public static function addUnitCodeMappingOutgoing(string $fromCode, string $toCode): void
    {
        static::addUnitCodeMapping(static::DIRECTION_OUTGOING, $fromCode, $toCode);
    }

    /**
     * Adds a currency code mapping
     *
     * @param string $fromCode
     * @param string $toCode
     * @return void
     */
    public static function addCurrencyMapping(string $direction, string $fromCode, string $toCode): void
    {
        static::addMapping(static::MAPPING_AREA_CURRENCY, $direction, $fromCode, $toCode);
    }

    /**
     * Adds a unit code mapping (for the incoming direction)
     *
     * @param string $fromCode
     * @param string $toCode
     * @return void
     */
    public static function addCurrencyMappingIncoming(string $fromCode, string $toCode): void
    {
        static::addCurrencyMapping(static::DIRECTION_INCOMING, $fromCode, $toCode);
    }

    /**
     * Adds a unit code mapping (for the incoming direction)
     *
     * @param string $fromCode
     * @param string $toCode
     * @return void
     */
    public static function addCurrencyMappingOutgoing(string $fromCode, string $toCode): void
    {
        static::addCurrencyMapping(static::DIRECTION_OUTGOING, $fromCode, $toCode);
    }

    /**
     * Remove all mappings (all mapping areas)
     *
     * @return void
     */
    public static function clearAllMappings(): void
    {
        foreach (static::getSupportedMappingAreas() as $mappingArea) {
            static::clearMapping($mappingArea);
        }
    }

    /**
     * Removes all mappings for a specific mapping area
     *
     * @param string $mappingArea
     * The mapping area for which all mappings should be removed
     * @return void
     */
    public static function clearMapping(string $mappingArea): void
    {
        static::testMappingAreaIsValid($mappingArea);
        static::ensureMappingArea($mappingArea);
        static::$mappings[$mappingArea] = [];
    }

    /**
     * General function to retrieve a mapping for a direction and a mepping area by $fromCode.
     * When no mapping was found the original $fromCode is returned
     *
     * @param string $direction
     * @param string $mappingArea
     * @param string $fromCode
     * @return string
     */
    public static function getMapping(string $direction, string $mappingArea, string $fromCode): string
    {
        static::testMappingAreaIsValid($mappingArea);
        static::testDirectionIsValid($direction);
        static::ensureMappingArea($mappingArea);

        $foundElements = array_filter(array_filter(static::$mappings[$mappingArea], function ($item) use ($direction) {
            return strcasecmp($item[static::KEY_DIRECTION], $direction) === 0;
        }), function ($item) use ($fromCode) {
            return strcasecmp($item[static::KEY_FROMCODE], $fromCode) === 0;
        });

        if (($firstElement = reset($foundElements)) === false) {
            return $fromCode;
        }

        return $firstElement[static::KEY_TOCODE];
    }

    /**
     * Gets a mapping for the incoming direction and a specific mapping area
     *
     * @param string $mappingArea
     * The mapping area for which a mapping should be retrieved
     * @param string $fromCode
     * Your own code
     * @return string
     * The zugferd code (from codelist)
     */
    public static function getIncomingMapping(string $mappingArea, string $fromCode): string
    {
        return static::getMapping(static::DIRECTION_INCOMING, $mappingArea, $fromCode);
    }

    /**
     * Gets a unit code mapping for the incoming direction
     *
     * @param string $fromCode
     * @return string
     */
    public static function getIncomingUnitCodeMapping(string $fromCode): string
    {
        return static::getIncomingMapping(static::MAPPING_AREA_UNITCODE, $fromCode);
    }

    /**
     * Gets a currency mapping for the incoming direction
     *
     * @param string $fromCode
     * @return string
     */
    public static function getIncomingCurrencyMapping(string $fromCode): string
    {
        return static::getIncomingMapping(static::MAPPING_AREA_CURRENCY, $fromCode);
    }

    /**
     * Gets a mapping for the outgoing direction and a specific mapping area
     *
     * @param string $mappingArea
     * The mapping area for which a mapping should be retrieved
     * @param string $fromCode
     * Your own code
     * @return string
     * The zugferd code (from codelist)
     */
    public static function getOutgoingMapping(string $mappingArea, string $fromCode): string
    {
        return static::getMapping(static::DIRECTION_OUTGOING, $mappingArea, $fromCode);
    }

    /**
     * Gets a unit code mapping for the outgoing direction
     *
     * @param string $fromCode
     * @return string
     */
    public static function getOutgoingUnitCodeMapping(string $fromCode): string
    {
        return static::getOutgoingMapping(static::MAPPING_AREA_UNITCODE, $fromCode);
    }

    /**
     * Gets a currency mapping for the outgoing direction
     *
     * @param string $fromCode
     * @return string
     */
    public static function getOutgoingCurrencyMapping(string $fromCode): string
    {
        return static::getOutgoingMapping(static::MAPPING_AREA_CURRENCY, $fromCode);
    }

    /**
     * Ensures that a mapping area exists in the internal mapping list
     *
     * @param string $mappingArea
     * @return void
     */
    private static function ensureMappingArea(string $mappingArea): void
    {
        if (isset(static::$mappings[$mappingArea])) {
            return;
        }

        static::$mappings[$mappingArea] = [];
    }

    /**
     * Returns a list of all supported mapping areas
     *
     * @return array
     */
    private static function getSupportedMappingAreas(): array
    {
        return [
            static::MAPPING_AREA_UNITCODE,
            static::MAPPING_AREA_CURRENCY,
        ];
    }

    /**
     * Returns true if the $mappingArea is supported by the mapper
     *
     * @param string $mappingArea
     * @return boolean
     */
    private static function checkMappingAreaIsValid(string $mappingArea): bool
    {
        return in_array($mappingArea, static::getSupportedMappingAreas());
    }

    /**
     * Tests that the mapping area is valid. If not an InvalidArgumentException is raised
     *
     * @param string $mappingArea
     * @return void
     */
    private static function testMappingAreaIsValid(string $mappingArea): void
    {
        if (static::checkMappingAreaIsValid($mappingArea)) {
            return;
        }

        throw new InvalidArgumentException(sprintf("The mapping area %s is not supported", $mappingArea));
    }

    /**
     * Get supported directions
     *
     * @return array
     */
    private static function getSupportedDirections(): array
    {
        return [
            static::DIRECTION_INCOMING,
            static::DIRECTION_OUTGOING,
        ];
    }

    /**
     * Checks if a given $direction is valid
     *
     * @param string $direction
     * @return boolean
     */
    private static function checkDirectionIsValid(string $direction): bool
    {
        return in_array($direction, static::getSupportedDirections());
    }

    /**
     * @param string $direction
     * @return void
     * @throws InvalidArgumentException
     */
    private static function testDirectionIsValid(string $direction): void
    {
        if (static::checkDirectionIsValid($direction)) {
            return;
        }

        throw new InvalidArgumentException(sprintf("The direction %s is not supported", $direction));
    }
}
