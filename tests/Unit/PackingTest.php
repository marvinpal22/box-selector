<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\PackingService;
use Illuminate\Support\Collection;

class PackingTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }

    public function test_boxc_pack_products_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 55, 'height' => 50,'length' =>60, 'weight' => 50, 'quantity' => 1],
            ]
        ];

        $expectedProducts = 
            [
              'BOXC',
              [
                  "name"=>"product 1",
                  "width"=>55,
                  "height"=>50,
                  "length"=>60,
                  "weight"=>50,
                  "quantity"=>1,
                  "volume"=>165000,
              ],
            
        ];

        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        // dd($prods);

        $this->assertEquals($expectedProducts,$prods[0]);
    }

    public function test_boxa_pack_products_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 15, 'height' => 10,'length' =>20, 'weight' => 5, 'quantity' => 1],
            ]
        ];

        $expectedProducts = 
            [
              'BOXA',
              [
                  "name"=>"product 1",
                  "length"=>20,
                  "height"=>10,
                  "width"=>15,
                  "weight"=>5,
                  "quantity"=>1,
                  "volume"=>3000,
              ],
            
        ];

        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        $this->assertEquals($expectedProducts,$prods[0]);
    }

    public function test_boxb_pack_products_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 25, 'height' => 20,'length' =>30, 'weight' => 10, 'quantity' => 1],
            ]
        ];

        $expectedProducts = 
            [
              'BOXB',
              [
                  "name"=>"product 1",
                  "length"=>30,
                  "height"=>20,
                  "width"=>25,
                  "weight"=>10,
                  "quantity"=>1,
                  "volume"=>15000,
              ],
            
        ];

        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        $this->assertEquals($expectedProducts,$prods[0]);
    }

    public function test_boxd_pack_products_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 45, 'height' => 40,'length' =>50, 'weight' => 30, 'quantity' => 1],
            ]
        ];

        $expectedProducts = 
            [
              'BOXD',
              [
                  "name"=>"product 1",
                  "length"=>50,
                  "width"=>45,
                  "height"=>40,
                  "weight"=>30,
                  "quantity"=>1,
                  "volume"=>90000,
              ],
            
        ];

        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        $this->assertEquals($expectedProducts,$prods[0]);
    }

    public function test_boxe_pack_products_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 35, 'height' => 30,'length' =>40, 'weight' => 20, 'quantity' => 1],
            ]
        ];

        $expectedProducts = 
            [
              'BOXE',
              [
                  "name"=>"product 1",
                  "length"=>40,
                  "width"=>35,
                  "height"=>30,
                  "weight"=>20,
                  "quantity"=>1,
                  "volume"=>42000,
              ],
            
        ];

        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        $this->assertEquals($expectedProducts,$prods[0]);
    }
    
    public function test_multiple_pack_products_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'height' => 50, 'length' => 60,'width' =>55, 'weight' => 50, 'quantity' => 1],
                ['name' => 'product 2', 'height' => 20, 'length' => 30,'width' =>25, 'weight' => 10, 'quantity' => 1],
                ['name' => 'product 3', 'height' => 10, 'length' => 20,'width' =>15, 'weight' => 5, 'quantity' => 1],
                ['name' => 'product 4', 'height' => 40, 'length' => 50,'width' =>45, 'weight' => 30, 'quantity' => 1],
                ['name' => 'product 5', 'height' => 30, 'length' => 40,'width' =>35, 'weight' => 20, 'quantity' => 1],
            ]
        ];

        $expectedProducts = [
            [
                'BOXC',
                [
                    "name"=>"product 1",
                    "length"=>60,
                    "width"=>55,
                    "height"=>50,
                    "weight"=>50,
                    "quantity"=>1,
                    "volume"=>165000,
                ],
                
            ],
            [
                'BOXD',
                [
                    "name"=>"product 4",
                    "height"=>40,
                    "length"=>50,
                    "width"=>45,
                    "weight"=>30,
                    "quantity"=>1,
                    "volume"=>90000,
                ],
                
            ],
            [
                'BOXC',
                [
                    "name"=>"product 5",
                    "height"=>30,
                    "length"=>40,
                    "width"=>35,
                    "weight"=>20,
                    "quantity"=>1,
                    "volume"=>42000,
                ],
                [
                    "name"=>"product 2",
                    "height"=>20,
                    "length"=>30,
                    "width"=>25,
                    "weight"=>10,
                    "quantity"=>1,
                    "volume"=>15000,
                ],
                [
                    "name"=>"product 3",
                    "height"=>10,
                    "length"=>20,
                    "width"=>15,
                    "weight"=>5,
                    "quantity"=>1,
                    "volume"=>3000,
                ],
            ]
        ];
        // dd($expectedProducts);
        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        $this->assertEquals($expectedProducts,$prods);
    }

    public function test_product_dimension_too_big_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 35, 'height' => 30,'length' =>70, 'weight' => 20, 'quantity' => 1],
            ]
        ];

        $expectedProducts = 
            [
              'NO BOX',
              [
                  "name"=>"product 1",
                  "length"=>70,
                  "width"=>35,
                  "height"=>30,
                  "weight"=>20,
                  "quantity"=>1,
                  "volume"=>73500,
              ],
            
        ];

        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        $this->assertEquals($expectedProducts,$prods[0]);
    }

    public function test_product_weight_too_big_request()
    {
        $packingServices = new PackingService();
        $products = [
            'product' => [
                ['name' => 'product 1', 'width' => 55, 'height' => 50,'length' =>60, 'weight' => 100, 'quantity' => 1],
            ]
        ];

        $expectedProducts = 
            [
              'NO BOX',
              [
                  "name"=>"product 1",
                  "length"=>60,
                  "width"=>55,
                  "height"=>50,
                  "weight"=>100,
                  "quantity"=>1,
                  "volume"=>165000,
              ],
            
        ];

        // dd($expectedProducts);
        $prods = $packingServices->findSmallestBoxWithproductsRemoval($products['product']);
        $this->assertEquals($expectedProducts,$prods[0]);
    }
}
