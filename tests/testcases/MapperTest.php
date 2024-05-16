<?php

namespace horstoeko\zugferd\tests\testcases;

use \horstoeko\zugferd\tests\TestCase;
use horstoeko\zugferd\ZugferdMapper;

class MapperTest extends TestCase
{
    /**
     * @var ZugferdDocumentReader
     */
    protected static $document;

    public function testGetMapping(): void
    {
        ZugferdMapper::addCurrencyMappingIncoming('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingIncoming('DEM', 'DM');

        $this->assertEquals('EUR', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_INCOMING, ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DM', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_INCOMING, ZugferdMapper::MAPPING_AREA_CURRENCY, 'DEM'));
        $this->assertEquals('GBP', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_INCOMING, ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));

        ZugferdMapper::addCurrencyMappingOutgoing('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingOutgoing('DM', 'DEM');

        $this->assertEquals('EUR', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_OUTGOING, ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DEM', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_OUTGOING, ZugferdMapper::MAPPING_AREA_CURRENCY, 'DM'));
        $this->assertEquals('GBP', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_OUTGOING, ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));

        ZugferdMapper::addUnitCodeMappingIncoming('C62', 'STK');

        $this->assertEquals('STK', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_INCOMING, ZugferdMapper::MAPPING_AREA_UNITCODE, 'C62'));
        $this->assertEquals('XPP', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_INCOMING, ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));

        ZugferdMapper::addUnitCodeMappingOutgoing('STK', 'C62');

        $this->assertEquals('C62', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_OUTGOING, ZugferdMapper::MAPPING_AREA_UNITCODE, 'STK'));
        $this->assertEquals('XPP', ZugferdMapper::getMapping(ZugferdMapper::DIRECTION_OUTGOING, ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));
    }

    public function testGetIncomingMapping(): void
    {
        ZugferdMapper::addCurrencyMappingIncoming('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingIncoming('DEM', 'DM');

        $this->assertEquals('EUR', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DM', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'DEM'));
        $this->assertEquals('GBP', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));

        ZugferdMapper::addUnitCodeMappingIncoming('C62', 'STK');

        $this->assertEquals('STK', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'C62'));
        $this->assertEquals('XPP', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));
    }

    public function testGetOutgoingMapping(): void
    {
        ZugferdMapper::addCurrencyMappingOutgoing('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingOutgoing('DM', 'DEM');

        $this->assertEquals('EUR', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DEM', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'DM'));
        $this->assertEquals('GBP', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));

        ZugferdMapper::addUnitCodeMappingOutgoing('STK', 'C62');

        $this->assertEquals('C62', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'STK'));
        $this->assertEquals('XPP', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));
    }

    public function testGetIncomingUnitCodeMapping(): void
    {
        ZugferdMapper::addUnitCodeMappingIncoming('C62', 'STK');

        $this->assertEquals('STK', ZugferdMapper::getIncomingUnitCodeMapping('C62'));
        $this->assertEquals('XPP', ZugferdMapper::getIncomingUnitCodeMapping('XPP'));
    }

    public function testGetIncomingCurrencyMapping(): void
    {
        ZugferdMapper::addCurrencyMappingIncoming('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingIncoming('DEM', 'DM');

        $this->assertEquals('EUR', ZugferdMapper::getIncomingCurrencyMapping('EUR'));
        $this->assertEquals('DM', ZugferdMapper::getIncomingCurrencyMapping('DEM'));
        $this->assertEquals('GBP', ZugferdMapper::getIncomingCurrencyMapping('GBP'));
    }

    public function testGetOutgoingUnitCodeMapping(): void
    {
        ZugferdMapper::addUnitCodeMappingOutgoing('STK', 'C62');

        $this->assertEquals('C62', ZugferdMapper::getOutgoingUnitCodeMapping('STK'));
        $this->assertEquals('XPP', ZugferdMapper::getOutgoingUnitCodeMapping('XPP'));
    }

    public function testGetOutgoingCurrencyMapping(): void
    {
        ZugferdMapper::addCurrencyMappingOutgoing('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingOutgoing('DM', 'DEM');

        $this->assertEquals('EUR', ZugferdMapper::getOutgoingCurrencyMapping('EUR'));
        $this->assertEquals('DEM', ZugferdMapper::getOutgoingCurrencyMapping('DM'));
        $this->assertEquals('GBP', ZugferdMapper::getOutgoingCurrencyMapping('GBP'));
    }

    /**
     * @covers \horstoeko\zugferd\ZugferdMapper::clearAllMappings
     * @covers \horstoeko\zugferd\ZugferdMapper::clearMapping
     */
    public function testClearAllMappings(): void
    {
        ZugferdMapper::addCurrencyMappingIncoming('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingIncoming('DEM', 'DM');
        ZugferdMapper::addCurrencyMappingOutgoing('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingOutgoing('DM', 'DEM');

        ZugferdMapper::addUnitCodeMappingIncoming('C62', 'STK');
        ZugferdMapper::addUnitCodeMappingOutgoing('STK', 'C62');

        $this->assertEquals('EUR', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DM', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'DEM'));
        $this->assertEquals('GBP', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));
        $this->assertEquals('EUR', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DEM', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'DM'));
        $this->assertEquals('GBP', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));
        $this->assertEquals('STK', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'C62'));
        $this->assertEquals('XPP', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));
        $this->assertEquals('C62', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'STK'));
        $this->assertEquals('XPP', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));

        ZugferdMapper::clearAllMappings();

        $this->assertEquals('EUR', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DM', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'DM'));
        $this->assertEquals('GBP', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));
        $this->assertEquals('EUR', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'EUR'));
        $this->assertEquals('DEM', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'DEM'));
        $this->assertEquals('GBP', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'GBP'));
        $this->assertEquals('C62', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'C62'));
        $this->assertEquals('XPP', ZugferdMapper::getIncomingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));
        $this->assertEquals('STK', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'STK'));
        $this->assertEquals('XPP', ZugferdMapper::getOutgoingMapping(ZugferdMapper::MAPPING_AREA_UNITCODE, 'XPP'));
    }

    public function testInvalidMappingAreaFromAddMapping(): void
    {
        $this->expectNoticeOrWarningExt(
            function () {
                ZugferdMapper::addMapping('INVALIDMAPPINGAREA', ZugferdMapper::DIRECTION_INCOMING, "EUR", "EUR");
            }
        );
    }

    public function testInvalidMappingAreaFromGetMapping(): void
    {
        $this->expectNoticeOrWarningExt(
            function () {
                ZugferdMapper::getMapping('INVALIDMAPPINGAREA', ZugferdMapper::DIRECTION_INCOMING, "EUR");
            }
        );
    }

    public function testInvalidDirectionAddMapping(): void
    {
        $this->expectNoticeOrWarningExt(
            function () {
                ZugferdMapper::addMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'INVALIDDIRECTION', "EUR", "EUR");
            }
        );
    }

    public function testInvalidDirectionGetMapping(): void
    {
        $this->expectNoticeOrWarningExt(
            function () {
                ZugferdMapper::getMapping(ZugferdMapper::MAPPING_AREA_CURRENCY, 'INVALIDDIRECTION', "EUR");
            }
        );
    }

    public function testSaveToJsonFile(): void
    {
        $filename = getcwd() . "/mappings.json";

        ZugferdMapper::addCurrencyMappingIncoming('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingIncoming('DEM', 'DM');
        ZugferdMapper::addCurrencyMappingOutgoing('EUR', 'EUR');
        ZugferdMapper::addCurrencyMappingOutgoing('DM', 'DEM');
        ZugferdMapper::addUnitCodeMappingIncoming('C62', 'STK');
        ZugferdMapper::addUnitCodeMappingOutgoing('STK', 'C62');

        ZugferdMapper::saveToJsonFile($filename);

        $this->assertTrue(file_exists($filename));
    }
}
