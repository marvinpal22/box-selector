<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PackingService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreBoxRequest;

class BoxController extends Controller
{
    //
    public function __construct(
        private PackingService $packingServices,
    ) {}
    public function packageList(){

        return array(
            [
                "name"=> "BOXA",
                "length"=> 20,
                "width" => 15,
                "height" => 10,
                "weight_limit" => 5
            ],
            [
                "name"=> "BOXB",
                "length"=> 30,
                "width" => 25,
                "height" => 20,
                "weight_limit"=>10
            ],
            [
                "name"=> "BOXC",
                "length"=> 60,
                "width" => 55,
                "height" => 50,
                "weight_limit"=>50
            ],
            [
                "name"=> "BOXD",
                "length"=> 50,
                "width" => 45,
                "height" => 40,
                "weight_limit"=>30
            ],
            [
                "name"=> "BOXE",
                "length"=> 40,
                "width" => 35,
                "height" => 30,
                "weight_limit" => 20 
            ],
        );
    }
    public function packItem(StoreBoxRequest $request){
        $products = $this->packingServices->findSmallestBoxWithProductsRemoval($request->product);
        return $products;




        $prods = $this->volumeChecker1($request->product,$packages);

        return $prods;



        $no_box_fit = array();
        $products = $request->product;
        $product_total_weight = 0;
        $product_total_volume = 0;
        for ($i = 0; $i < count($products); $i++) {
			$products[$i]['volume'] = $products[$i]['length'] * $products[$i]['width'] * $products[$i]['height'];
            $products[$i]['total_volume'] = $products[$i]['volume'] * $products[$i]['quantity'];
            $products[$i]['total_weight'] = $products[$i]['weight'] * $products[$i]['quantity'];

            // $product_total_volume+=$products[$i]['total_volume'];
            // $product_total_weight+=$products[$i]['total_weight'];
            //set the total volume of the products
		}

        // $products['total_weight'] = $product_total_weight;
        // $products['total_volume'] = $product_total_volume;
        // return $products;
		for ($i = 0; $i < count($packages); $i++) {
			$packages[$i]['volume_limit'] = $packages[$i]['length'] * $packages[$i]['width'] * $packages[$i]['height'];

            //set the total volume_limit of the packages
		}

        //sort the volume_limit by asceding;
        array_multisort(array_column($packages, 'volume_limit'), SORT_ASC, $packages);

        //find the box that will fit all products
        //else remove the biggest and then repeat the process

        //check the length,width,heigt and weight if the product is fitted in the box

        $packedBoxes = array();
        $noBoxes = array();
        $index = 0;
        while (count($products)){
            for ($b = 0; $b < count($packages); $b++) {
                //volume and weight checker
                if(($packages[$b]['volume_limit'] >= $products[$index]['total_volume'] && $products[$index]['total_volume'] <= $packages[$b]['volume_limit'] ) && ($packages[$b]['weight_limit'] >= $products[$index]['total_weight'] && $products[$index]['total_weight'] <= $packages[$b]['weight_limit']))
                {
                    $packedBoxes[] = array(
                        "box" => $packages[$b]['name'],
                        "name" => $products[$index]['name'],
                        "length" => $products[$index]['length'],
                        "width" => $products[$index]['width'],
                        "height" => $products[$index]['height'],
                        "weight" => $products[$index]['weight'],
                        "quantity" => $products[$index]['quantity'],
                    );
                    unset($products[$index]);
                    break;
                }
            }
            $index++;
        }



        return response()->json([$packedBoxes,$noBoxes]);


        $box = array();
        // return $products;
        //check the product length,height,width and weight if its fit in the packages
        //product loop
        foreach ($products as $key => $product) {
            //packages loop
            for ($b = 0; $b < count($packages); $b++) {
                //volume and weight checker
                if(($packages[$b]['volume_limit'] >= $products['total_volume'] && $products['total_volume'] <= $packages[$b]['volume_limit'] ) && ($packages[$b]['weight_limit'] >= $products['total_weight'] && $products['total_weight'] <= $packages[$b]['weight_limit']))
                {
                    $box[$packages[$b]['name']] = array(
                        "name" => $product['name'],
                        "length" => $product['length'],
                        "width" => $product['width'],
                        "height" => $product['height'],
                        "weight" => $product['weight'],
                        "quantity" => $product['quantity'],
                    );
                }else{
                    // return 'TEST';
                    // foreach ($products as $key => $product) {
                    //     if(($packages[$b]['volume_limit'] >= $product['total_volume'] && $product['total_volume'] <= $packages[$b]['volume_limit'] ) && ($packages[$b]['weight_limit'] >= $product['total_weight'] && $product['total_weight'] <= $packages[$b]['weight_limit']))
                    //     {
                    //         return $box;
                    //         // $box[$packages[$b]['name']] = array(
                    //         //     "name" => $product['name'],
                    //         //     "length" => $product['length'],
                    //         //     "width" => $product['width'],
                    //         //     "height" => $product['height'],
                    //         //     "weight" => $product['weight'],
                    //         //     "quantity" => $product['quantity'],
                    //         // );
                    //     }
                    // }
                }

                // if($packages[$b]['volume_limit'] > $products[$key]['volume'] && $packages[$b]['weight_limit'] < $products[$key]['weight'])
                // if ($package[$key]['volume'] < $product['volume']) {
                // 	continue;
                // }
            }

            return $box;

            // if ($package[$key]['volume'] < $product['volume']) {
            // 	continue;
            // }

                
            return "NO BOX SELECTED";
		}

        return $packages;
    }


    public function findSmallestBoxWithItemsRemoval($products,$packages){
        for ($i = 0; $i < count($products); $i++) { 
            $products[$i]['volume'] = $products[$i]['length'] * $products[$i]['width'] * $products[$i]['height'];
		}

        array_multisort(array_column($products, 'volume'), SORT_DESC, $products);
        array_multisort(array_column($packages, 'volume_limit'), SORT_DESC, $packages);

        $removedProduct = [];
        $box = [];
        while($products){
            $smallestBox = $this->findSmallestBox($products);
            if($smallestBox){
                // dd($smallestBox);
                $box[] = array($smallestBox,$products);
                return $box;
                // break;
            }

            // dd('TEST');
            // Log::info($products);
            //remove the largest item
            // array_push($removedProduct,$products);
            array_shift($products);

            // $products->shift(); 
            // echo "TEST";
        }

        return null;
        return response()->json($box,$products);
        return $box;
        // while($removeProduct){
        //     $smallestBox = $this->findSmallestBox($products);
        //     if($smallestBox){
        //         return $smallestBox;
        //     }
        // }

    }

    public function findSmallestBox($products){
        $packages = $this->packageList();
        for ($i = 0; $i < count($packages); $i++) {
			$packages[$i]['volume_limit'] = $packages[$i]['length'] * $packages[$i]['width'] * $packages[$i]['height'];
		} //calculate volume_limit

        foreach($packages as $package){
            if($this->itemsFitInBox($products,$package)){
                return $package;
            }
        }

        return null;
    }

    public function itemsFitInBox($products,$package){
        $totalVolume = 0;
        foreach ($products as $product) {
            $totalVolume += $product['length'] * $product['width'] * $product['height'];
        }
        $packageVolume = $package['volume_limit'];
        return $totalVolume <= $packageVolume;
    }

    public function volumeChecker($products,$packages){


        // npm install
        // composer install
        // php artisan key:generate

        // to run 
        // npm run dev && php artisan serve




        $bins = [];
        $usedBoxes = [];
        foreach($products as $product){
            $places = false;

            foreach($packages  as $package){
                // return $bins[$package['volume_limit']];
                if (isset($bins[$package['volume_limit']]) && $bins[$package['volume_limit']]['remaining'] >= $product['volume']) {
                    $bins[$package['volume_limit']]['contents'][] = $product;
                    $bins[$package['volume_limit']]['remaining'] -= $product['volume'];
                    $placed = true;
                    break;
                }

                // Check if this box size can accommodate the item
                if (!isset($bins[$package['volume_limit']]) && $package['volume_limit'] >= $product['volume']) {
                    $bins[$package['volume_limit']] = [
                        'capacity' => $package['volume_limit'],
                        'remaining' => $package['volume_limit'] - $product['volume'],
                        'contents' => $product,
                    ];

                    $placed = true;
                    $usedBoxes[] = $package;
                    break;
                }
            }

            if(!$placed){
                $largestItem = max(array_column($products, 'volume'));
                return "TEST";
            }
        }
        return $bins;













        return $prods;
        // return count($prods);
        $box = array();
        $let_found_box = 0;
        $index = 0;

        // $i = 0;
        // $b = 0;
        // return $prods;
        while (count($prods)){
        // for($i =0; $i < count($prods); $i++){
            // echo count($prods);
            // if(!array_key_exists($i, $prods)) return;
            // if(!isset($prods[$i]['product'])) return;
            for($b = 0 ; $b < count($prods[$i]['product']); $b++){
                $let_found = 0;
                // echo $prods[$i]['product'][$b]['name'];

                array_multisort(array_column($prods[$i]['product'], 'total_volume'), SORT_DESC, $prods[$i]['product']);
                // echo $prods[$i]['product'][$b]['name'];
                // dd( $prods[$i]['product'][0]['name']);
                // array_push($prods, array("TEST"));
                // array_push($prods, array(
                //     "product"=>array($prods[$i]['product'][0]),
                //     "total_weight"=>"100",
                //     "total_volume"=> "200"
                // ));

                // unset($prods[$i]['product'][0]);
                for ($x = 0; $x < count($packages); $x++) {
                    // if(isset($prods[$i]['product'][$b])){
                        if(($packages[$x]['volume_limit'] >= $prods[$i]['total_volume'] && $prods[$i]['total_volume'] <= $packages[$x]['volume_limit'] ) && ($packages[$x]['weight_limit'] >= $prods[$i]['total_weight'] && $prods[$i]['total_weight'] <= $packages[$x]['weight_limit']))
                        {

                            array_push($box, array(
                                $packages[$x],
                                $prods[$i]
                            ));

                            // echo "found box - ".$prods[$i];
                            // $let_found_box++;
                            
                            unset($prods[$i]);
                            $let_found++;
                            // return;
                            // echo count($prods);
                            // echo $let_found;
                            // array_splice($prods[$i], 0, 3);
                            // return $prods;
                            // array_shift($prods[$i]);
                            // break;

                            // return $packages[$b];
                        }
                        // echo $let_found."2";
                        // if($let_found > 0){
                        //     // break ;
                        // }
                        // // break;
                        // echo "TEST";
                            // echo $prods[$i]['product'][0]['name'];
                            // array_push($prods, array(
                            //     "product"=>array($prods[$i]['product'][0]),
                            //     "total_weight"=>"100",
                            //     "total_volume"=> "200"
                            // ));
                           
                            // array_splice($prods[$i]['product'], 0, 1);
                            // break;



                            // return $prods[$i]['product'][0];
                            // return $prods[$i]['product'][0];
                            // unset($prods[$i]['product'][0]);
                            // array_shift($prods[$i]['product'][0]);

                            // break;
                        
                        // break;
                }


                if($let_found > 0){
                    break;
                }else{
                    echo "REASSIGN";
                    array_push($prods, array(
                        "product"=>array($prods[$i]['product'][0]),
                        "total_weight"=>"100",
                        "total_volume"=> "200"
                    ));
                    
                    // array_splice($prods[$i]['product'][0], 0, 1);
                    unset($prods[$i]['product'][0]);
                    array_values($prods[$i]['product']);
                    return;
                }

                //      else{

                //         // return $prods[$i]['product'][0];
                //         if(isset($prods[$i]['product'][0])){
                //             array_push($prods, array(
                //                 "product"=>[$prods[$i]['product'][0]],
                //                 "total_weight"=>"100",
                //                 "total_volume"=> "200"
                //             ));
                //             unset($prods[$i]['product'][0]);
                //         }

                //         // return $prods[$i]['product'][0];
                //         // return $prods[$i]['product'][0];
                //         // $prods[] = $prods[$i]['product'][0];
                //         // array_push($prods,$prods[$i]['product'][0]);
                //         // break;

                //         // array_push($prods,
                //         // array(
                //         //     array($prods[$i]['product'][0]),
                //         //     "total_weight"=>"100",
                //         //     "total_volume"=> "200"
                //         // ));
                //         // break;
                //     }
                // }

                // return count($prods);

                // if($let_found_box <= 0){
                //     // $prods[] = $prods[$i];
                //     // array_push($prods, array(
                //     //     "product"=>array($prods[$i]['product'][0]),
                //     //     "total_weight"=>"100",
                //     //     "total_volume"=> "200"
                //     // ));
                //     unset($prods[$i]['product'][0]);
                // }
                // break;
            }
            // break;
            // $i++;
            // $i++;
            // echo "TEST";
            // echo $let_found;
        }   

        // }
        return $box;
        return $i;
        return $prods;

        // return 'TESt';

        // $products['total_volume'] = $product_total_volume;
        return $prods;

        $product_total_weight = 0;
        $product_total_volume = 0;
        $box = array();
        for ($i = 0; $i < count($products); $i++) { 
            $product_total_volume+=$products[$i]['total_volume'];
            $product_total_weight+=$products[$i]['total_weight'];
            //set the total volume of the products
		}

        $products['total_weight'] = $product_total_weight;
        $products['total_volume'] = $product_total_volume;

        $maxValue = null;
        $keyOfMaxValue = null;
        return $products;
        return count($products);
        foreach ($products as $key => $value) {
            if ($maxValue === null || $value > $maxValue) {
                $maxValue = $value;
                $keyOfMaxValue = $key;
            }
        }

        // return array(
        //     array('test'),
        //     array('test1')
        // );
        $box = array();
        for ($b = 0; $b < count($packages); $b++) {
            if(($packages[$b]['volume_limit'] >= $products['total_volume'] && $products['total_volume'] <= $packages[$b]['volume_limit'] ) && ($packages[$b]['weight_limit'] >= $products['total_weight'] && $products['total_weight'] <= $packages[$b]['weight_limit']))
            {
                array_push($box, array(
                    $packages[$b],
                    $products
                ));
                // return $packages[$b];
            }else{
 
            }
        }

        foreach($products as $product){

        }

        // foreach ($products as $key => $product) {
        //     //packages loop
        //     for ($b = 0; $b < count($packages); $b++) {
        //         //volume and weight checker
        //         if(($packages[$b]['volume_limit'] >= $products['total_volume'] && $products['total_volume'] <= $packages[$b]['volume_limit'] ) && ($packages[$b]['weight_limit'] >= $products['total_weight'] && $products['total_weight'] <= $packages[$b]['weight_limit']))
        //         {
        //             $box[$packages[$b]['name']] = array(
        //                 "name" => $product['name'],
        //                 "length" => $product['length'],
        //                 "width" => $product['width'],
        //                 "height" => $product['height'],
        //                 "weight" => $product['weight'],
        //                 "quantity" => $product['quantity'],
        //             );
        //         }else{
        //             return "NO BOX SELECTED";

        //             // return 'TEST';
        //             // foreach ($products as $key => $product) {
        //             //     if(($packages[$b]['volume_limit'] >= $product['total_volume'] && $product['total_volume'] <= $packages[$b]['volume_limit'] ) && ($packages[$b]['weight_limit'] >= $product['total_weight'] && $product['total_weight'] <= $packages[$b]['weight_limit']))
        //             //     {
        //             //         return $box;
        //             //         // $box[$packages[$b]['name']] = array(
        //             //         //     "name" => $product['name'],
        //             //         //     "length" => $product['length'],
        //             //         //     "width" => $product['width'],
        //             //         //     "height" => $product['height'],
        //             //         //     "weight" => $product['weight'],
        //             //         //     "quantity" => $product['quantity'],
        //             //         // );
        //             //     }
        //             // }
        //         }

        //         // if($packages[$b]['volume_limit'] > $products[$key]['volume'] && $packages[$b]['weight_limit'] < $products[$key]['weight'])
        //         // if ($package[$key]['volume'] < $product['volume']) {
        //         // 	continue;
        //         // }
        //     }


        //     // if ($package[$key]['volume'] < $product['volume']) {
        //     // 	continue;
        //     // }

                
		// }

        return $box;
        return $packages;
        return $products;
    }
}
