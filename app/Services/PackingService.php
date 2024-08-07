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

    public function findSmallestBoxWithProductsRemoval($products){
        for ($i = 0; $i < count($products); $i++) { 
            $products[$i]['volume'] = $products[$i]['length'] * $products[$i]['width'] * $products[$i]['height'];
            //calculate the total volume of the products
		}

        array_multisort(array_column($products, 'volume'), SORT_DESC, $products);

        $products = collect($products);
        $box = array();
        while ($products->isNotEmpty()) {
            $smallestBox = $this->findSmallestBox($products);

            if ($smallestBox) {
                array_push($box,[$smallestBox['name'],...$products]);
                break;
            }else{
                $largestItem = $products->shift();
                $findBox = $this->findSmallestBox(collect([$largestItem]));
                if ($findBox) {
                    //find largest box
                    array_push($box,array($findBox['name'],$largestItem));
                }
                else{
                    //no box fits
                    array_push($box,array('NO BOX',$largestItem));
                }
            }
        }
        return $box;

    }

    public function findSmallestBox($products){
        //get package list
        $boxes = $this->boxList();

        //calculate volume_limit of the boxes
        for ($i = 0; $i < count($boxes); $i++) {
			$boxes[$i]['volume_limit'] = $boxes[$i]['length'] * $boxes[$i]['width'] * $boxes[$i]['height'];
		} 

        //sort the boxes to ascending
        array_multisort(array_column($boxes, 'volume_limit'), SORT_ASC, $boxes);

        //loop the boxes
        foreach($boxes as $box){

            //find fitted box
            if($this->ProductsFitInBox($products,$box)){
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

    public function ProductsFitInBox($products,$box){

        $totalVolume = 0;
        $totalWeight = 0;
        foreach ($products as $product) {
            // calculate the total volume of the product
            $totalVolume += ($product['length'] * $product['width'] * $product['height']); 

            // calculate the total weight of the product
            $totalWeight += $product['weight'];

            //dimension check 
            // return false if the product does not fit.
            if(!$this->itemFitsInBox($product, $box)) {
                return false;
            }
        }
        
        $boxVolume = $box['volume_limit'];
        $boxWeight = $box['weight_limit'];
        
        //return if product total volume is less than equal to package volume limit and product total weight is less than equal to package weight limit
        //weight and volume checker
        return ($totalVolume <= $boxVolume && $totalWeight <= $boxWeight);
    }

}
