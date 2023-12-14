<?php

namespace App\Http\Controllers\Api\Frontend\Loose;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Loose\LooseFilterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Frontend\BaseController as BaseController;
use Validator;

class LooseFilterController extends BaseController
{
    protected $filter;
   
    /**
     * LooseFilterController constructor.
     * @param LooseFilterRepository $filter
     */
    public function __construct(LooseFilterRepository $filter)
    {

        $this->filter  = $filter;
    }


    /**
     * Display a Filter of the resource.
     * @param bool $id
     * @return \Illuminate\Http\Response
     */

    /*
    SHIRISH TODO -
        create request file to validate request
    */

    public function getLooseFilter(Request $request)
    {        
       //dd('test');
        // TODO -- validate request
        return $this->handleResponse( $this->filter->getLoose($request) , 'success');
    }


    /**
     * get minimum and maximum value for filter value.
     * @param null
     * @return json
     */
    public function getMinMax()
    {
        Auth::user();
        $minMax = $this->filter->getMinMax();

        $data = [];

        $data['shape'] = [
            ["label"=> "round", "value"=> "round", "icon"=> "RD",'url'=>'Round', 'active'=>0], 
            ["label"=> "oval", "value"=> "oval", "icon"=> "OV",'url'=>'Oval', 'active'=>0], 
            ["label"=> "pear", "value"=> "pear", "icon"=> "PS",'url'=>'Pear', 'active'=>0], 
            ["label"=> "cushion", "value"=> "cushion", "icon"=> "CU",'url'=>'Cushion', 'active'=>0], 
            ["label"=> "princess", "value"=> "princess", "icon"=> "PR",'url'=>'Princess', 'active'=>0], 
            ["label"=> "radiant", "value"=> "radiant", "icon"=> "RAD",'url'=>'Radiant', 'active'=>0], 
            ["label"=> "emerald", "value"=> "emerald", "icon"=> "EM",'url'=>'Emerald', 'active'=>0], 
            ["label"=> "heart", "value"=> "heart", "icon"=> "HT",'url'=>'Heart', 'active'=>0], 
            ["label"=> "asscher", "value"=> "asscher", "icon"=> "AC",'url'=>'Asscher', 'active'=>0], 
            ["label"=> "marquise", "value"=> "marquise", "icon"=> "MQ",'url'=>'Marquise', 'active'=>0], 
        ];


        $data['price'] = [
            "min" => (float)$minMax->min_price,
            "max" => (float)$minMax->max_price,
            "from" => (float)$minMax->min_price,
            "to" => (float)$minMax->max_price
        ];

        $data['carat'] = [
            "min" => (float)$minMax->min_carat,
            "max" => (float)$minMax->max_carat,
            "from" => (float)$minMax->min_carat,
            "to" => (float)$minMax->max_carat
        ];
        
        $data['color'] = [
            "min" => 0,
            "max" => 10,
            "from" => 0,
            "to" => 10,
            "values"=>['M','L','K','J','I','H','G','F','E','D']
        ];

        $data['cut'] = [
            "min" => 0,
            "max" => 5,
            "from" => 0,
            "to" => 5,
            "values"=>['FR','GD','VG','EX','ID']
        ];
        $data['clarity'] = [
            "min" => 0,
            "max" => 9,
            "from" => 0,
            "to" => 9,
            "values"=> ['I1','SI2','SI1','VS2','VS1','VVS2','VVS1','IF','FL'] 
        ];
        $data['depth'] = [
            "min" => (float)$minMax->min_depth_percent,
            "max" => (float)$minMax->max_depth_percent,
            "from" => (float)$minMax->min_depth_percent,
            "to" => (float)$minMax->max_depth_percent,
        ];
        $data['polish'] = [
            "min" => 0,
            "max" => 4,
            "from" => 0,
            "to" => 4,
            "values"=>['FR','GD','VG','EX']
        ];
        $data['ratio'] = [
            "min" => (float)$minMax->min_lw,
            "max" => (float)$minMax->max_lw,
            "from" => (float)$minMax->min_lw,
            "to" => (float)$minMax->max_lw,
        ];
        $data['fluorescence'] = [
            "min" => 0,
            "max" => 6,
            "from" => 0,
            "to" => 6,
            "values"=>[ 'VStg', 'Stg', 'M', 'S', 'VS', 'None']
        ];
        $data['table'] = [
            "min" => (float)$minMax->min_table_percent,
            "max" => (float)$minMax->max_table_percent,
            "from" => (float)$minMax->min_table_percent,
            "to" => (float)$minMax->max_table_percent,
        ];
        $data['symmetry'] = [
            "min" => 0,
            "max" => 5,
            "from" => 0,
            "to" => 5,
            "values"=>['FR','GD','VG','EX','ID']
        ];

        $data['sortV2'] = [
            ["value"=> "carat_desc","url"=> "CaratHigh", "label"=> "CaratHigh", "active"=> 0, "label"=> "Carat - high to low" ],
            ["value"=> "carat_asc","url"=> "CaratLow", "label"=> "CaratLow", "active"=> 0, "label"=> "Carat - low to high" ],
            
            ["value"=> "color_desc","url"=> "ColorHigh",  "label"=> "ColorHigh", "active"=> 0, "label"=> "Color - high to low" ],
            ["value"=> "color_asc","url"=> "ColorLow",  "label"=> "ColorLow", "active"=> 0, "label"=> "Color - low to High" ],
            
            ["value"=> "clarity_desc","url"=> "ClarityHigh",  "label"=> "ClarityHigh", "active"=> 0, "label"=> "Clarity - high to low" ],
            ["value"=> "clarity_asc","url"=> "ClarityLow",  "label"=> "ClarityLow", "active"=> 0, "label"=> "Clarity - low to high" ],
            
            ["value"=> "cut_desc","url"=> "CutHigh",  "label"=> "CutHigh", "active"=> 0, "label"=> "Cut - high to low" ],
            ["value"=> "cut_asc","url"=> "CutLow",  "label"=> "CutLow", "active"=> 0, "label"=> "Cut - low to high" ],
            
            ["value"=> "price_desc","url"=> "PriceHigh",  "label"=> "PriceHigh", "active"=> 0, "label"=> "Price - high to low" ],
            ["value"=> "price_asc","url"=> "PriceLow",  "label"=> "PriceLow", "active"=> 1, "label"=> "Price - low to high" ]
        ];

        $data['sort'][] = [
            'label'  => 'Carat', 
            'options'=> [
                        ["value"=> "carat_desc","url"=> "CaratHigh", "label"=> "CaratHigh", "active"=> 0, "label"=> "high to low" ],
                        ["value"=> "carat_asc","url"=> "CaratLow", "label"=> "CaratLow", "active"=> 0, "label"=> "low to high" ],
                    ],
            ];
        $data['sort'][] = [
            'label'  => 'Color', 
            'options'=> [
                        ["value"=> "color_desc","url"=> "ColorHigh",  "label"=> "ColorHigh", "active"=> 0, "label"=> "high to low" ],
                        ["value"=> "color_asc","url"=> "ColorLow",  "label"=> "ColorLow", "active"=> 0, "label"=> "low to High" ],
                    ],
            ];
        $data['sort'][] = [
            'label'  => 'Clarity', 
            'options'=> [
                        ["value"=> "clarity_desc","url"=> "ClarityHigh",  "label"=> "ClarityHigh", "active"=> 0, "label"=> "high to low" ],
                        ["value"=> "clarity_asc","url"=> "ClarityLow",  "label"=> "ClarityLow", "active"=> 0, "label"=> "low to high" ],
                    ],
            ];
        // $data['sort'][] = [
        //     'label'  => 'Cut', 
        //     'options'=> [
        //                 ["value"=> "cut_desc","url"=> "CutHigh",  "label"=> "CutHigh", "active"=> 0, "label"=> "high to low" ],
        //                 ["value"=> "cut_asc","url"=> "CutLow",  "label"=> "CutLow", "active"=> 0, "label"=> "low to high" ],
        //             ],
        //     ];
        $data['sort'][] = [
            'label'  => 'Price', 
            'options'=> [
                        ["value"=> "price_desc","url"=> "PriceHigh",  "label"=> "PriceHigh", "active"=> 0, "label"=> "high to low" ],
                        ["value"=> "price_asc","url"=> "PriceLow",  "label"=> "PriceLow", "active"=> 1, "label"=> "low to high" ]
                    ],
            ];

        $data['report'] = [
            ["value"=> "igi", "label"=> "IGI", 'url'=> 'IGI', 'active'=>0], 
            ["value"=> "gia", "label"=> "GIA", 'url'=> 'GIA', 'active'=>0], 
            ["value"=> "gcal", "label"=> "GCAL", 'url'=> 'GCAL', 'active'=>0]
        ];

        $data['quickship'] = [
            ["value"=> "yes", "label"=> "Quick Ship",'url'=> 'Yes', 'active'=>0]
        ];
        $data['blockchain'] = [
            ["value"=> "yes", "label"=> "Blockchain enabled diamonds",'url'=> 'Yes',  'active'=>0]
        ];

        // $data['quickship'][] = [ "value"=> "Yes", "active"=> 0, "label"=> "Quick Ship"];
        // $data['blockchain'][] = [ "value"=> "Yes", "active"=> 0, "label"=> "Blockchain Enabled"];

        return $this->handleResponse( $data, 'Success');
    }
}