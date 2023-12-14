<?php

namespace App\Models\Loose;

use App\Models\BaseModel;
use App\Models\Create\CreateRing;
use App\Models\Locked\LockedLooseLots;
//use App\Traits\CyoModelTrait;
use Illuminate\Support\Facades\Cookie;

class CreateLoose extends BaseModel
{
    //use CyoModelTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    
    //protected $table = 'create_loose_item';
    protected $cyoType = 'loose';
    protected static $enableLog = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function locked()
    {
        return $this->hasOne(LockedLooseLots::class, 'item_id', 'loose_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locks()
    {
        return $this->hasMany(LockedLooseLots::class, 'item_id', 'loose_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loose()
    {
        return $this->hasOne(Loose::class, 'id', 'loose_id')->withTrashed();
    }

    /**
	* @return \Illuminate\Database\Eloquent\Relations\HasOne
	*/
	public function gtag()
	{
	  return $this->hasOne(CreateLoose::class, 'id','id');
	}


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function diamond()
    {
        return $this->hasOne(Loose::class, 'id', 'loose_id')->withTrashed()->withCount('locks');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function assets()
    {
        return $this->hasOne(LooseMedia::class, 'loose_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function looseCart()
    {
        return $this->hasOne(CreateRing::class, 'loose_id', 'id')->where([['cart_id', '=', Cookie::get('cart')]]);
    }

    
   	/**
	* @return void
	*/
    public function setType()
    {	
        $this->type = $this->cyoType;
    }

    /**
    * @return void
    */
    public function setGtagValues()
    {
        // gtag
        $a = [
            'id'              => $this->id,
            'brand'           => 'friendly diamonds',
            'name'            => $this->diamond->getDiamondTitle(),
            'variant'         => 'Lab Grown Diamonds',
            'category'        => $this->cyoType,
            'quantity'        => 1,
        ];

        $this->gtag = $a;
    }

   	/**
	* @return void
	*/
    public function setCartDisplayValues()
    {      
        if ( empty($this->diamond) ) {
            
            \Log::info(['invalid create loose entry']);
            return null;
        }

        $this->setCyoBaseValues();

        $this->setType();

        $this->setGtagValues();

        // need to improve below array setup
        $this->setVisible([ 'id','diamond','cart_id','loose_id','appraisal','module_type','type','totalPrice','eta','appraisalPrice', 'gtag' ]);
    }

    /**
     * @param $session
     * @param $bool
     * @return mixed
     */
    public function getFrontendCartItem()
    { 
        $set =[];
        
        $set['name']                = $this->diamond->weight . ' ' . $this->diamond->shape . ' Lab Grown Diamond';
        $set['diamond_thumb_image'] = $this->diamond->diamond_thumb_image;
        
        return $set;
    }
}
