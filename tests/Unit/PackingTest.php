<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\PackingService;

class PackingTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_boxa_pack_items_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 60, 'height' => 50, 'weight' => 50, 'quantity' => 1]
            ]
        ];

        // $this->assertIsArray($products);
        $expectedProducts = [
            'hasBox' =>[
                'box' => ['name' => "BOXC", 'length' => 60, 'width' => 55, 'height' => 50, 'weight_limit'=> 50, 'volume_limit' => 165000],
                'products' => ['height'=>50,'length'=>60,'name'=>'product 1','quantity'=>1,'volume'=>165000,'weight'=>50,'width'=>50]
            ],
            'noBox'=>[],
        ];
        $expectedProducts = [1,2,3,4,5];

        $prods = $packingServices->findSmallestBoxWithItemsRemoval($products);
        
        $this->assertIsArray($prods);
        $this->assertIsArray($products);
        $this->assertEquals($expectedProducts, $prods);


        // // Replace dd() with assertions
        // $this->assertIsArray($products);
        // $this->assertArrayHasKey('product', $products);
        // $this->assertIsArray($products['product']);
        // $this->assertCount(1, $products['product']);

        // // Optionally, verify the content of the product
        // $product = $products['product'][0];
        // $this->assertArrayHasKey('name', $product);
        // $this->assertArrayHasKey('width', $product);
        // $this->assertArrayHasKey('height', $product);
        // $this->assertArrayHasKey('weight', $product);
        // $this->assertArrayHasKey('quantity', $product);
        // $this->assertEquals('product 1', $product['name']);
        // $this->assertEquals(60, $product['width']);
        // $this->assertEquals(50, $product['height']);
        // $this->assertEquals(50, $product['weight']);
        // $this->assertEquals(1, $product['quantity']);
    }
}
