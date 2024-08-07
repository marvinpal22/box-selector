<?php

namespace App\Services;

class PackingService
{
    public function __construct()
    {
        //
    }

    public function boxList(){

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

    public function findSmallestBoxWithItemsRemoval($products){
        for ($i = 0; $i < count($products); $i++) { 
            $products[$i]['volume'] = $products[$i]['length'] * $products[$i]['width'] * $products[$i]['height'];
            //calculate the total volume of the products
		}

        array_multisort(array_column($products, 'volume'), SORT_DESC, $products);

        $products = collect($products);
        $box = [];
        $noBox = [];
        while ($products->isNotEmpty()) {
            $smallestBox = $this->findSmallestBox($products);

            if ($smallestBox) {
                // $box[] = array($smallestBox,$products);
                array_push($box,array("box" => $smallestBox, "products" => $products));
                break;
            }else{
                $largestItem = $products->shift();
                $findBox = $this->findSmallestBox(collect([$largestItem]));
                if ($findBox) {
                    array_push($box,array("box" => $findBox, "products" => $largestItem));
                }
                else{
                    //no box fits
                    array_push($noBox,$largestItem);
                }
            }
        }
        // return array_merge($box,$noBox);
        return response()->json(['hasBox' => $box,'noBox'=>$noBox]);

    }

    public function findSmallestBox($products){
        //get package list
        $boxes = $this->boxList();

        //calculate volume_limit
        for ($i = 0; $i < count($boxes); $i++) {
			$boxes[$i]['volume_limit'] = $boxes[$i]['length'] * $boxes[$i]['width'] * $boxes[$i]['height'];
		} 

        array_multisort(array_column($boxes, 'volume_limit'), SORT_ASC, $boxes);

        //loop the packages
        foreach($boxes as $box){

            //find fitted box
            if($this->itemsFitInBox($products,$box)){
                // echo $key;
                return $box;
            }
        }

        return null;
    }

    public function itemFitsInBox($product, $box)
    {   
        //dimension checker
        return $product['length'] <= $box['length'] &&
               $product['width']<= $box['width'] &&
               $product['height'] <= $box['height'];
    }

    public function itemsFitInBox($products,$box){

        $totalVolume = 0;
        $totalWeight = 0;
        $dimensionChecker = true; //if false product is not fitted
        foreach ($products as $product) {
            // calculate the total volume of the product
            $totalVolume += ($product['length'] * $product['width'] * $product['height']); 

            // calculate the total weight of the product
            $totalWeight += $product['weight'];


            if(!$this->itemFitsInBox($product, $box)) {
                return false;
            }
            //if product length is greater than prackage length,width,height

            // if(
            //     ($product['length'] > $box['length'] || $product['length'] > $box['width'] 
            //     || $product['length'] > $box['height']) 
            //     && 
            //     ($product['height'] > $box['length'] || $product['height'] > $box['width'] || $product['height'] > $box['height']) 
            //     &&
            //     ($product['width'] > $box['length'] || $product['width'] > $box['width'] || $product['width'] > $box['height'])){
            //         $dimensionChecker = false;
            //     }


            
            // if(
            // ($product['length'] > $box['length'] || $product['length'] > $box['width'] 
            // || $product['length'] > $box['height']) 
            // && 
            // ($product['height'] > $box['length'] || $product['height'] > $box['width'] || $product['height'] > $box['height']) 
            // &&
            // ($product['width'] > $box['length'] || $product['width'] > $box['width'] || $product['width'] > $box['height'])){
            //     $dimensionChecker = false;
            // }
        }

        $boxVolume = $box['volume_limit'];
        $boxWeight = $box['weight_limit'];
        
        //return if product total volume is less than equal to package volume limit and product total weight is less than equal to package weight limit

        //weight and volume checker
        return ($totalVolume <= $boxVolume && $totalWeight <= $boxWeight);
    }

}
