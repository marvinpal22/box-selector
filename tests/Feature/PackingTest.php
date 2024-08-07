<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PackingService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PackingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_it_validates_pack_items_request()
    {
        $response = $this->postJson('/pack-items', [
            'product' => [
                ['name' => 'product 1','length' => '', 'width' => '', 'height' => '', 'weight' => '','quantity' => '']
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product.0.length','product.0.width','product.0.height','product.0.weight','product.0.quantity']);
    }

    // public function test_boxa_pack_items_request(){
    //     $products = [
    //         'product' => 
    //             [
    //                 ['name' => 'product 1', 'width' => '60', 'height' => '50', 'weight' => '50','quantity' => '1']
    //             ]
    //     ];
    //     $prods = $this->packingServices->findSmallestBoxWithItemsRemoval($products);

    //     dd($prods);
    //     $expectedProducts = [
    //         'hasBox' =>[
    //             ['id' => 1, 'name' => 'Product A', 'price' => 100]
    //         ],
    //         'noBox'=>[],
    //     ];

    //     $test = [
    //         'hasBox' =>[
    //             ['id' => 1, 'name' => 'Product A', 'price' => 100]
    //         ],
    //         'noBox'=>[],
    //     ];

    //     // $this->assertIsArray($products);

        
    //     $this->assertEquals($expectedProducts, $test);
    // }
    
}
