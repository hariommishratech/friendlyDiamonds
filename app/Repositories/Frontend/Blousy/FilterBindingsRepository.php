<?php

namespace App\Repositories\Frontend\Loose;

use App\Repositories\BaseRepository;
use App\Models\Filters\FiltersBindings;
use Illuminate\Http\Request;

/**
 * Class FilterBindingsRepository.
 */
class FilterBindingsRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = FiltersBindings::class;

    /**
     * store a completed ring setting 
     * @param Request $request
     * @param mixed $cart_id 
     * @return mixed
     */
    public function filterAdd($column = false, $from = false, $to = false)
    {
        if ($to >= 0 && $from >= 0 && !empty($column)) {
            $getValues = $this->query()->where([['column', '=', $column]])
                ->whereBetween('level', [$from, $to])
                ->select('value')
                ->pluck('value')->toArray();
            if (!empty($getValues)) {
                return $getValues;
            }
            if (empty($getValues)) {
                return false;
            }
        }
    }

    /**
     * get shape values 
     * @param mixed $shape 
     * @return mixed
     */
    public function getShapes($shape = false)
    {
        $level = array();
        /* pear */
        if (!empty($shape['pear_shape'])) {
            $level[] = 0; // level 0
        }
        /* heart */
        if (!empty($shape['ht_shape'])) {
            $level[] = 1;
        }
        /* marquise */
        if (!empty($shape['mq_shape'])) {
            $level[] = 2; //level 2
        }
        /* asscher */
        if (!empty($shape['asscher_shape'])) {
            $level[] = 3; //level 3
        }
        /* radiant */
        if (!empty($shape['radiant_shape'])) {
            $level[] = 4; //level 4
        }
        /* oval  */
        if (!empty($shape['oval_shape'])) {
            $level[] = 5; //level 5
        }
        /* emerald */
        if (!empty($shape['emerald_shape'])) {
            $level[] = 6;//level 6
        }
        /* princess */
        if (!empty($shape['ps_shape'])) {
            $level[] = 7;//level 7
        }
        /* cushion */
        if (!empty($shape['cu_shape'])) {
            $level[] = 8;//level 8
        }
        /* round */
        if (!empty($shape['rd_shape'])) {
            $level[] = 9;//level 9
        }

        if (!empty($level)) {
            $getValues = $this->query()->where([['column', '=', 'shape']])
                ->whereIn('level', $level)
                ->select('value')
                ->pluck('value')->toArray();
            if (!empty($getValues)) {

                return $getValues;
            }
            if (empty($getValues)) {
                return false;
            }
        }
        if (empty($level)) {
            return false;
        }

    }
}
