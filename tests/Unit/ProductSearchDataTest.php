<?php

namespace Tests\Unit;

use App\Data\Param\ProductSearchData;
use App\Data\Param\RangeData; // Assuming RangeData exists and is a simple DTO
use PHPUnit\Framework\TestCase;

class ProductSearchDataTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_null_values()
    {
        $data = new ProductSearchData(
            searchTerm: null,
            filterProductType: null,
            filterManufacturer: null,
            filterConnectorType: null,
            filterPrice: null,
            filterPowerOutput: null,
            filterCapacity: null,
        );

        $this->assertNull($data->searchTerm);
        $this->assertNull($data->filterProductType);
        $this->assertNull($data->filterManufacturer);
        $this->assertNull($data->filterConnectorType);
        $this->assertNull($data->filterPrice);
        $this->assertNull($data->filterPowerOutput);
        $this->assertNull($data->filterCapacity);
    }

    /** @test */
    public function it_can_be_instantiated_with_all_values()
    {
        // Assuming RangeData is a simple DTO with min and max properties
        $rangePrice = new RangeData(min: 10.0, max: 100.0);
        $rangePower = new RangeData(min: 50.0, max: 500.0);
        $rangeCapacity = new RangeData(min: 1000.0, max: 5000.0);

        $data = new ProductSearchData(
            searchTerm: 'solar panel',
            filterProductType: 'Solar Panel',
            filterManufacturer: ['ExampleCorp'],
            filterConnectorType: ['MC4'],
            filterPrice: $rangePrice,
            filterPowerOutput: $rangePower,
            filterCapacity: $rangeCapacity,
        );

        $this->assertEquals('solar panel', $data->searchTerm);
        $this->assertEquals('Solar Panel', $data->filterProductType);
        $this->assertEquals(['ExampleCorp'], $data->filterManufacturer);
        $this->assertEquals(['MC4'], $data->filterConnectorType);
        $this->assertEquals($rangePrice, $data->filterPrice);
        $this->assertEquals($rangePower, $data->filterPowerOutput);
        $this->assertEquals($rangeCapacity, $data->filterCapacity);
    }

    /** @test */
    public function it_can_be_instantiated_with_some_values()
    {
        $rangePrice = new RangeData(min: 10.0, max: null);

        $data = new ProductSearchData(
            searchTerm: 'battery',
            filterProductType: 'Battery',
            filterManufacturer: null,
            filterConnectorType: null,
            filterPrice: $rangePrice,
            filterPowerOutput: null,
            filterCapacity: null,
        );

        $this->assertEquals('battery', $data->searchTerm);
        $this->assertEquals('Battery', $data->filterProductType);
        $this->assertNull($data->filterManufacturer);
        $this->assertNull($data->filterConnectorType);
        $this->assertEquals($rangePrice, $data->filterPrice);
        $this->assertNull($data->filterPowerOutput);
        $this->assertNull($data->filterCapacity);
    }
}
