<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoxController extends Controller
{
    //
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
    public function packItem(Request $request){

        $packages = $this->packageList();
        for ($i = 0; $i < count($packages); $i++) {
			$packages[$i]['volume_limit'] = $packages[$i]['length'] * $packages[$i]['width'] * $packages[$i]['height'];

            //set the total volume_limit of the packages
		}

        $prods = $this->volumeChecker($request->product,$packages);

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

    public function volumeChecker($products,$packages){

        $prods = array();
        $product_total_volume = 0;
        $product_total_weight = 0;
        for ($i = 0; $i < count($products); $i++) { 

            $products[$i]['volume'] = $products[$i]['length'] * $products[$i]['width'] * $products[$i]['height'];
            $products[$i]['total_volume'] = $products[$i]['volume'] * $products[$i]['quantity'];
            $products[$i]['total_weight'] = $products[$i]['weight'] * $products[$i]['quantity'];

            $product_total_volume+=$products[$i]['total_volume'];
            $product_total_weight+=$products[$i]['total_weight'];
		}

        $prods[0]['product'] = $products;
        $prods[0]['total_weight'] = $product_total_weight;
        $prods[0]['total_volume'] = $product_total_volume;

        // return count($prods);
        $box = array();
        $let_found_box = 0;
        $index = 0;

        $i = 0;
        while (count($prods)){
        // for($i =0; $i < count($prods); $i++){
            for($b = 0 ; $b < count($prods[$i]['product']); $b++){
                array_multisort(array_column($prods[$i]['product'], 'total_volume'), SORT_DESC, $prods[$i]['product']);
                // array_push($prods, array("TEST"));
                // array_push($prods, array(
                //     "product"=>array($prods[$i]['product'][0]),
                //     "total_weight"=>"100",
                //     "total_volume"=> "200"
                // ));

                for ($x = 0; $x < count($packages); $x++) {
                    // if(isset($prods[$i]['product'][$b])){
                        if(($packages[$x]['volume_limit'] >= $prods[$i]['total_volume'] && $prods[$i]['total_volume'] <= $packages[$x]['volume_limit'] ) && ($packages[$x]['weight_limit'] >= $prods[$i]['total_weight'] && $prods[$i]['total_weight'] <= $packages[$x]['weight_limit']))
                        {

                            // return 'test';
                            array_push($box, array(
                                $packages[$x],
                                $prods[$i]['product']
                            ));
                            // echo "found box - ".$prods[$i];
                            $let_found_box++;
                            unset($prods[$i]);
                            break;
                            // return $packages[$b];
                        }
                        // break;

                    // }

                     else{

                        // return $prods[$i]['product'][0];
                        if(isset($prods[$i]['product'][0])){
                            array_push($prods, array(
                                "product"=>[$prods[$i]['product'][0]],
                                "total_weight"=>"100",
                                "total_volume"=> "200"
                            ));
                            unset($prods[$i]['product'][0]);
                        }

                        // return $prods[$i]['product'][0];
                        // return $prods[$i]['product'][0];
                        // $prods[] = $prods[$i]['product'][0];
                        // array_push($prods,$prods[$i]['product'][0]);
                        // break;

                        // array_push($prods,
                        // array(
                        //     array($prods[$i]['product'][0]),
                        //     "total_weight"=>"100",
                        //     "total_volume"=> "200"
                        // ));
                        // break;
                    }
                }

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
            $i++;
        }   

        // }
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
