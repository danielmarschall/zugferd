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
    private $mappings = [];

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
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Add a mapping to the internal mapping list
     *
     * @param string $mappingArea
     * Gives the mapping area
     * @param string $direction
     * The mapping direction
     * @param string $ownCode
     * Your own code
     * @param string $zugferdCode
     * A code from the official code lists
     * @return void
     */
    public function addMapping(string $mappingArea, string $direction, string $ownCode, string $zugferdCode): void
    {
        $this->testMappingAreaIsValid($mappingArea);
        $this->ensureMappingArea($mappingArea);
        $this->mappings[$mappingArea][] = ["direction" => $direction, "owncode" => $ownCode, "zugferdcode" => $zugferdCode];
    }

    /**
     * Adds a unit code mapping
     *
     * @param string $ownCode
     * Your own code
     * @param string $zugferdCode
     * A code from the official code lists
     * @return void
     */
    public function addUnitCodeMapping(string $direction, string $ownCode, string $zugferdCode): void
    {
        $this->addMapping(static::MAPPING_AREA_UNITCODE, $direction, $ownCode, $zugferdCode);
    }

    /**
     * Adds a currency code mapping
     *
     * @param string $ownCode
     * Your own code
     * @param string $zugferdCode
     * A code from the official code lists
     * @return void
     */
    public function addCurrencyMapping(string $direction, string $ownCode, string $zugferdCode): void
    {
        $this->addMapping(static::MAPPING_AREA_CURRENCY, $direction, $ownCode, $zugferdCode);
    }

    /**
     * Removes all mappings for a specific mapping area
     *
     * @param string $mappingArea
     * The mapping area for which all mappings should be removed
     * @return void
     */
    public function clearMapping(string $mappingArea): void
    {
        $this->testMappingAreaIsValid($mappingArea);
        $this->ensureMappingArea($mappingArea);
        $this->mappings[$mappingArea] = [];
    }

    /**
     * Gets the zugferd code for a specific mapping area by own code
     *
     * @param string $mappingArea
     * The mapping area for which a mapping should be retrieved
     * @param string $ownCode
     * Your own code
     * @return string
     * The zugferd code (from codelist)
     */
    public function getMappingByOwnCode(string $mappingArea, string $ownCode): string
    {
        $this->testMappingAreaIsValid($mappingArea);
        $this->ensureMappingArea($mappingArea);

        foreach ($this->mappings[$mappingArea] as $mapping) {
            if (strcasecmp($mapping["owncode"], $ownCode) !== 0) {
                continue;
            }

            return $mapping["zugferdcode"];
        }

        return $ownCode;
    }

    /**
     * Gets your own code for a specific mapping area by a code from official code lists
     *
     * @param string $mappingArea
     * The mapping area for which a mapping should be retrieved
     * @param string $zugferdCode
     * A code from the official code lists
     * @return string
     * Your own code
     */
    public function getMappingByZugferdCode(string $mappingArea, string $zugferdCode): string
    {
        $this->testMappingAreaIsValid($mappingArea);
        $this->ensureMappingArea($mappingArea);

        foreach ($this->mappings[$mappingArea] as $mapping) {
            if (strcasecmp($mapping["zugferdcode"], $zugferdCode) !== 0) {
                continue;
            }

            return $mapping["owncode"];
        }

        return $zugferdCode;
    }

    /**
     * Ensures that a mapping area exists in the internal mapping list
     *
     * @param string $mappingArea
     * @return void
     */
    private function ensureMappingArea(string $mappingArea): void
    {
        if (isset($this->mappings[$mappingArea])) {
            return;
        }

        $this->mappings[$mappingArea] = [];
    }

    /**
     * Returns a list of all supported mapping areas
     *
     * @return array
     */
    private function getSupportedMappingAreas(): array
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
    private function checkMappingAreaIsValid(string $mappingArea): bool
    {
        return in_array($mappingArea, $this->getSupportedMappingAreas());
    }

    /**
     * Tests that the mapping area is valid. If not an InvalidArgumentException is raised
     *
     * @param string $mappingArea
     * @return void
     */
    private function testMappingAreaIsValid(string $mappingArea): void
    {
        if ($this->checkMappingAreaIsValid($mappingArea)) {
            return;
        }

        throw new InvalidArgumentException(sprintf("The mapping area %s is not supported", $mappingArea));
    }

    /**
     * Get supported directions
     *
     * @return array
     */
    private function getSupportedDirections(): array
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
    private function checkDirectionIsValid(string $direction): bool
    {
        return in_array($direction, $this->getSupportedDirections());
    }

    /**
     * @param string $direction
     * @return void
     * @throws InvalidArgumentException
     */
    private function testDirectionIsValid(string $direction): void
    {
        if ($this->checkDirectionIsValid($direction)) {
            return;
        }

        throw new InvalidArgumentException(sprintf("The direction %s is not supported", $direction));
    }
}
