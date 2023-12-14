<?php

namespace App\Repositories\Frontend\Loose;

use App\Repositories\BaseRepository;
use App\Models\Loose\Loose;
use Illuminate\Http\Request;
use App\Repositories\Frontend\Loose\FilterBindingsRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class LooseFilterRepository.
 */
class LooseFilterRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Loose::class;
    protected $binding;

    
    /**
     * LooseFilterRepository constructor.
     * @param FilterBindingsRepository
     * @return void
     */
    public function __construct(FilterBindingsRepository $binding)
    {
        $this->binding = $binding;
    }
    
    /**
     * get currency
     * @return void
     */
    function currency($amount = null, $from = null, $to = null, $format = true)
    {
        if (is_null($amount)) {
            return app('currency');
        }

        return app('currency')->convert($amount, $from, $to, $format);
    }

    /**
     * get Loose for listing
     * @param Request $r 
     * @param mixed $excluded
     * @return void
     */
    public function getLoose($r, $excluded = false)
    {
       
      
        $result = $this->query();
        
        
        if (!empty($r->Shape)) {
            
            $result->whereIn('shape', explode(',', $r->Shape) );
        }

        $cut_grade = ['Fair' => 'FR', 'Good' => 'GD', 'Very Good'=> 'VG', 'Excellent' => 'EX', 'Ideal' => 'ID'];
                    
        $polish = ['good' => 'GD', 'very good'=> 'VG', 'Excellent' => 'EX', 'Ideal' => 'ID'];
        
        $symmetry = ['good' => 'GD', 'very good'=> 'VG', 'Excellent' => 'EX', 'Ideal' => 'ID'];
        
        $fluorescence = ['None'=>'None', 'Very Slight'=>'VS', 'Slight'=>'S', 'Medium'=>'M', 'Strong'=>'Stg', 'Very Strong'=>'VStg'];
       
        $r->merge( ['Cut_grade' => $r->Cut ] );
       
        $searchKeys = ['cut_grade','polish','symmetry','fluorescence'];

        foreach ( $searchKeys as $key => $k) {

            /*check if filter is not null or absent*/
            if (!empty($r->{ ucfirst($k) })) {
               
                $searchValues = array_intersect( $$k, explode(',', $r->{ ucfirst($k) } ) );
                $result->whereIn( $k , array_keys($searchValues) );
            }
        }

        /*color, clarity*/
        $searchKeys = ['color','clarity','shape'];
        foreach ($searchKeys as $key => $k) {

            if (!empty( $r->{ ucfirst($k) } )) {
                $result->whereIn( $k , explode(',' , $r->{ ucfirst($k)} ) );
            }
        }

        $r->merge( ['WeightFrom' => $r->CaratFrom, 'WeightTo' => $r->CaratTo ] );

        $r->merge( ['Lw_ratioFrom' => $r->RatioFrom, 'Lw_ratioTo' => $r->RatioTo ] );
        
        $r->merge( ['Table_percentFrom' => $r->TableFrom, 'Table_percentTo' => $r->TableTo ] );

        $r->merge( ['Depth_percentFrom' => $r->DepthFrom, 'Depth_percentTo' => $r->DepthTo ] );

        $ranges = ['price', 'depth_percent', 'table_percent', 'weight', 'lw_ratio'];

        foreach ($ranges as $key => $p) {
            if ( !empty( $r->{ ucfirst($p).'From' } ) && !empty( $r->{ ucfirst($p).'To' } ) ) {
                
               $result->whereBetween($p, [ $r->{ ucfirst($p).'From' }, $r->{ ucfirst($p).'To' } ]);
            }
        }

        if (!empty($r->Report)) {
            $result->whereIn( 'lab' , explode(',' , $r->Report ) );
        }

        if (!empty($r->Quickship)) {
            $result->where( 'country' , 'New York' );
        }
        $order = 'asc';
        $sortKey = '';
        if (!empty($r->Sort)) {
            /* sort */
            $order = strpos($r->Sort, 'High') ? 'desc' : 'asc';
            $sortKey = strtolower( str_replace( ['High', 'Low'], '', $r->Sort ) );
        }

        $sortTypes = ['carat', 'color', 'cut', 'clarity', 'price'];

        $sortcolumn = in_array( $sortKey, $sortTypes ) ? $sortKey : 'price';
        
        switch ($sortcolumn) {
            case 'carat':
                $result->orderBy('weight', $order);
                break;
            case 'color':
                $result->orderByRaw(DB::raw("FIELD(color, 'M', 'L', 'K', 'J', 'I', 'H', 'G', 'F', 'E', 'D')" . $order));
                // $result->orderByRaw(DB::raw("FIELD(color, 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M')" . $order));
                break;
            case 'clarity':
                $result->orderByRaw(DB::raw("FIELD(clarity, 'I1','SI2','SI1','VS2','VS1','VVS2','VVS1','IF','FL')" . $order));
            // $result->orderByRaw(DB::raw("FIELD(clarity, 'IF','VVS1','VVS2','VS1','VS2','SI1','SI2','I1')" . $order));
                break;
            case 'cut':
                $result->orderByRaw(DB::raw("FIELD(cut_grade, 'FAIR','GOOD','VERY GOOD','EXCELLENT','IDEAL')" . $order));
                // $result->orderByRaw(DB::raw("FIELD(cut_grade, 'IDEAL', 'EXCELLENT','VERY GOOD','GOOD','FAIR')" . $order));
                break;
            case 'price':
                $result->orderBy('price', $order);
                break;
        }

        $result->select('id','weight','slug','shape','color','clarity','lab','price','cut_grade','diamond_thumb_image','diamond_image');
        // $result->with('assets','isCompared'); //add wishlist later
        //$result->doesnthave('locks');
        // $result->withCount('isCompared'); //add wishlist later
        
        $diamonds = $result->paginate(20);
        
        foreach ($diamonds as $key => $value) {

            // $value['id'] = $value['id'];
            // $value['slug'] = $value['slug'];
            $value->carat = $value->weight;
            // $value['shape'] = $value['shape'];
            // $value['color'] = $value['color'];
            // $value['clarity'] = $value['clarity'];
            $value->report = strtoupper($value->lab);
            $value->clarity = strtoupper($value->clarity);
            $value->price = $value->price;
            $value->cut = $cut_grade[ \Str::title($value->cut_grade)];
            $value->icon = $value->shape;
            $value->thumb_image = empty($value->diamond_thumb_image) ? $value->diamond_image : $value->diamond_thumb_image;
            $value->is_wishlist = false;
            $value->in_cart = false;
            $value->is_compared_count = 0;

        }

        // $finalResult = ['next_page_url'=>false, 'items'=>[]];

        // if (!empty( $diamonds->nextPageUrl() )) {

        //     $finalResult['next_page_url'] = true;
        // }
        
        return $diamonds;
    }

     /**
     * @return mixed
     */
    public function getMinMax()
    {
        return $this->query()->select([
            DB::raw('MIN(weight) AS min_carat'),
            DB::raw('MAX(weight) AS max_carat'),
            DB::raw('MIN(price) AS min_price'),
            DB::raw('MAX(price) AS max_price'),
            DB::raw('MIN(depth_percent) AS min_depth_percent'),
            DB::raw('MAX(depth_percent) AS max_depth_percent'),
            DB::raw('MIN(table_percent) AS min_table_percent'),
            DB::raw('MAX(table_percent) AS max_table_percent'),
            DB::raw('MIN(lw_ratio) AS min_lw'),
            DB::raw('MAX(lw_ratio) AS max_lw'),
        ])->whereIn('color', looseColors())
            ->doesnthave('locks')
            ->first();
    }
}
