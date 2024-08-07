<?php

namespace App\Services;

class PackingService
{
    public function __construct()
    {
        //
    }

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

    public function findSmallestBoxWithItemsRemoval($products){
        $packages = $this->packageList();
        for ($i = 0; $i < count($products); $i++) { 
            $products[$i]['volume'] = $products[$i]['length'] * $products[$i]['width'] * $products[$i]['height'];
            //calculate the total volume of the products
		}

        for ($i = 0; $i < count($packages); $i++) {
			$packages[$i]['volume_limit'] = $packages[$i]['length'] * $packages[$i]['width'] * $packages[$i]['height'];
            //calculate the total volume of the packages
		}

        array_multisort(array_column($products, 'volume'), SORT_DESC, $products);
        array_multisort(array_column($packages, 'volume_limit'), SORT_DESC, $packages);

        $products = collect($products);
        $box = [];
        while ($products->isNotEmpty()) {
            $smallestBox = $this->findSmallestBox($products);

            if ($smallestBox) {
                // $box[] = array($smallestBox,$products);
                array_push($box,array("box" => $smallestBox, "products" => $products));
                return $box;
                break;
            }else{
                $largestItem = $products->shift();
                $findBox = $this->findSmallestBox(collect([$largestItem]));
                if ($findBox) {
                    array_push($box,array("box" => $findBox, "products" => $largestItem));
                }
                else{
                    //no box fitted
                    array_push($box,array("box" => "NO BOX", "products" => $largestItem));
                }
            }
        }

        return $box; // No box fits any items

    }

    public function findSmallestBox($products){
        //get package list
        $packages = $this->packageList();

        //calculate volume_limit
        for ($i = 0; $i < count($packages); $i++) {
			$packages[$i]['volume_limit'] = $packages[$i]['length'] * $packages[$i]['width'] * $packages[$i]['height'];
		} 

        array_multisort(array_column($packages, 'volume_limit'), SORT_DESC, $packages);

        //loop the packages
        foreach($packages as $package){

            //find fitted box
            if($this->itemsFitInBox($products,$package)){
                // echo $key;
                return $package;
            }
        }

        return null;
    }

    public function itemFitsInBox($product, $package)
    {
        return $product['length'] <= $package['length'] &&
               $product['width']<= $package['width'] &&
               $product['height'] <= $package['height'];
    }

    public function itemsFitInBox($products,$package){

        $totalVolume = 0;
        $totalWeight = 0;
        $dimensionChecker = true; //if false product is not fitted
        foreach ($products as $product) {
            // calculate the total volume of the product
            $totalVolume += ($product['length'] * $product['width'] * $product['height']); 

            // calculate the total weight of the product
            $totalWeight += $product['weight'];


            if(!$this->itemFitsInBox($product, $package)) {
                return false;
            }
            //if product length is greater than prackage length,width,height

            // if(
            //     ($product['length'] > $package['length'] || $product['length'] > $package['width'] 
            //     || $product['length'] > $package['height']) 
            //     && 
            //     ($product['height'] > $package['length'] || $product['height'] > $package['width'] || $product['height'] > $package['height']) 
            //     &&
            //     ($product['width'] > $package['length'] || $product['width'] > $package['width'] || $product['width'] > $package['height'])){
            //         $dimensionChecker = false;
            //     }


            
            // if(
            // ($product['length'] > $package['length'] || $product['length'] > $package['width'] 
            // || $product['length'] > $package['height']) 
            // && 
            // ($product['height'] > $package['length'] || $product['height'] > $package['width'] || $product['height'] > $package['height']) 
            // &&
            // ($product['width'] > $package['length'] || $product['width'] > $package['width'] || $product['width'] > $package['height'])){
            //     $dimensionChecker = false;
            // }
        }

        $packageVolume = $package['volume_limit'];
        $packageWeight = $package['weight_limit'];
        
        //return if product total volume is less than equal to package volume limit and product total weight is less than equal to package weight limit
        return ($totalVolume <= $packageVolume && $totalWeight <= $packageWeight) && $dimensionChecker;
    }

}
