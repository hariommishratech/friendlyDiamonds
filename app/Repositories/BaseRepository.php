<?php

namespace App\Repositories;

use Illuminate\Http\Request;

/**
 * Class BaseRepository.
 */
class BaseRepository
{
    /**
     * @return mixed
     */
    public function query()
    {
        return call_user_func(static::MODEL.'::query');
    }

    /**
     * get default metal values 
     * @return array
     */
    public function getShopVariation()
    {
        return [
                    [ 'kt' => '14kt',   'col' => 'white',  'display_name' => '14kt white gold', 'name'  => '14ktWhiteGold', ],
                    [ 'kt' => '14kt',   'col' => 'yellow', 'display_name' => '14kt yellow gold','name'  => '14ktYellowGold',],
                    [ 'kt' => '14kt',   'col' => 'rose',   'display_name' => '14kt rose gold',  'name'  => '14ktRoseGold',  ],
                    [ 'kt' => 'platinum','col' => 'white', 'display_name' => 'platinum',        'name'  => 'Platinum',      ]
                ];
    }

    /**
     * get default metal values 
     * @return array
     */
    public function defaultMetalValues()
    {
        return ['10KtWhiteGold','10KtYellowGold','10KtRoseGold','14KtWhiteGold','14KtYellowGold','14KtRoseGold','18KtWhiteGold','18KtYellowGold','18KtRoseGold','Platinum'];
    }

    
    /**
     * get default shape values 
     * @return array
     */
    Public function defaultShapeValues()
    {
        return ['round','princess','cushion','emerald','heart','marquise','radiant','pear','oval','asscher'];
    }


    /**
     * get default setting options(type) 
     * @param $type
     * @return array
     */
    public function settingOptions($type)
    {
        $subType = [
            'ring' => ['solitaire','vintage','three-stone','side-stone','halo'],
            'pendant' => ['solitaire','circle','cross','fashion','cluster','heart'],
            'earring' => ['solitaire','circle','hoop','fashion','cluster','dangle','halo'],
        ];

        return $subType[$type];
    }

    /**
     * @param 
     * @return
     */
    public function getMetalAttribute($string)
    {
        switch ($string) {
            case '14KtWhiteGold':
                $metal = 4; 
                $kt = 2;
                $color = 1; 
                $colVarients = [4,5,6];       
                break;
            case '14KtYellowGold':
                $metal = 5; 
                $kt = 2;
                $color = 2;     
                $colVarients = [4,5,6];    
                break;
            case '14KtRoseGold':
                $metal = 6; 
                $kt = 2;
                $color = 3;   
                $colVarients = [4,5,6];      
                break;
            case '18KtWhiteGold':
                $metal = 7; 
                $kt = 3;
                $color = 1;  
                $colVarients = [7,8,9];       
                break;
            case '18KtYellowGold':
                $metal = 8; 
                $kt = 3;
                $color = 2;   
                $colVarients = [7,8,9];     
                break;
            case '18KtRoseGold':
                $metal = 9; 
                $kt = 3;
                $color = 3; 
                $colVarients = [7,8,9];   
                break;
            case '10KtWhiteGold':
                $metal = 1; 
                $kt = 1;
                $color = 1; 
                $colVarients = [1,2,3];        
                break;
            case '10KtYellowGold':
                $metal = 2; 
                $kt = 1;
                $color = 2;      
                $colVarients = [1,2,3];    
                break;
            case '10KtRoseGold':
                $metal = 3; 
                $kt = 1;
                $color = 3; 
                $colVarients = [1,2,3];         
                break;
            case 'Platinum':
                $metal = 10; 
                $kt = 4;
                $color = 1; 
                $colVarients = [10];    
                break;
            default:
                $metal = 4; 
                $kt = 2;
                $color = 1; 
                $colVarients = [4,5,6];
                break;
        }

        return (object)['metal'=>$metal, 'kt'=>$kt, 'color'=>$color, 'colVarients'=>$colVarients];
    }

    
    /**
     * @param 
     * @return
     */
    public function getAvailableShapes($item)
    {
        $shapes = $this->defaultShapeValues();
        $array = [];

        foreach ($shapes as $key => $shape) {
            
            if ($item->{$shape} === 1) {
                
                array_push($array, $shape);
            }  
            
        }

        return $array;
    }
    
    /**
     * generate slug
     * @param mixed $currentCol 
     * @param mixed $currentKt 
     * @param mixed $collection 
     * @return array|json
     */
    public function generateSlug($currentCol, $currentKt, $collection)
    {
        $make = [

            'yellow'        => ($currentCol === 'yellow') ? 1 : 0,
            'white'         => ($currentCol === 'white') ? 1 : 0,
            'rose'          => ($currentCol === 'rose') ? 1 : 0,
            'kt_18'         => ($currentKt === '18kt') ? 1 : 0,
            'kt_14'         => ($currentKt === '14kt') ? 1 : 0,
            'kt_10'         => ($currentKt === '10kt') ? 1 : 0,
            'platinum'      => ($currentKt === 'platinum') ? 1 : 0,
            'main_sku'      => ($collection['main_sku']) ? $collection['main_sku'] : 0,
            'slug_material' => 0,
            'product_name'  => (!empty($collection['product_name']) ? $collection['product_name'] : 0)
        ];

        $slugs = array_merge(...buildSettingSlug($make)) ;
        return $slugs;
    }

    /**
     * generate hint data
     * @param Request $request 
     * @return array|json
     */
    public function getHintData($request)
    {
        $q = $this->query();
        $tableType = $q->getModel()->getTable();

        $data =[];

        if ($tableType === 'loose_lots') {
            
            $item = $q->withTrashed()->where('id', $request->loose_id)->first(); 

            $data['name'] =  $item->weight.' Carat '. $item->shape;
            $data['slug']  = config('app.frontend_url') . '/loose-diamonds/' . $item->slug;
            $data['image'] = $item->diamond_image;

            return $data;
        }

        $item = $q->where('id', $request->setting_id)->first();        
        
        $data['name']  = getMetalForExport($item->metal)['value'] .' '.$item->product_name;

        if ( $tableType === 'ring_settings' ) {  
            
            $data['image'] = $item->images->round;
            $data['slug']  = config('app.frontend_url') . '/ring-settings/' . $item->slug;
        }

        if ( $tableType === 'earrings_settings' ) {  
            
            $data['image'] = $item->images->round;
            $data['slug']  = config('app.frontend_url') . '/earring-settings/' . $item->slug;
        }

        if ( $tableType === 'pendants_settings' ) {  
            
            $data['image'] = $item->images->round;
            $data['slug']  = config('app.frontend_url') . '/pendant-settings/' . $item->slug;
        }

        if ($tableType === 'shop_settings') {    

            $data['slug']  = config('app.frontend_url') . $item->full_slug;
            $data['image'] = $item->images->front;
        }
        
        return $data;
    }
    
}

