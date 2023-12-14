<?php

namespace App\Models\Loose;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ImportLists;
use App\Models\Loose\LockedLooseLots;
use App\Models\Merchant\MerchantData;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Wishlist\Modules\WishListLoose;
use Laravel\Scout\Searchable;

class Loose extends BaseModel
{
    use HasFactory, Searchable, SoftDeletes;

    /**
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'lot',
        'weight',
        'country',
        'shape',
        'cut_grade',
        'color',
        'clarity',
        'over_tone',
        'lab',
        'polish',
        'symmetry',
        'fluorescence',
        'certificate_no',
        'price',
        'is_supplier_stone',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'lot',
        'weight',
        'country',
        'shape',
        'cut_grade',
        'color',
        'clarity',
        'over_tone',
        'lab',
        'polish',
        'symmetry',
        'fluorescence',
        'certificate_no',
        'price',
        'is_supplier_stone',
        'deleted_at',
    ];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loose_lots';
    protected static $enableLog = false;


    /**
     * get all columns from table
     */
    public static function getTableColumns()
    {
        return \Illuminate\Support\Facades\Schema::getColumnListing((new self())->getTable());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function merchantData()
    {
        return $this->belongsTo(MerchantData::class, 'id', 'loose_id')->where('g_availability', 'in_stock');
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
    public function isCompared()
    {
        return $this->hasOne(LooseCompare::class, 'loose_id', 'id')->where('cart_id', request()->header('Cart'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasMarkup()
    {
        return $this->hasOne(ImportLists::class, 'id', 'import_list_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function looseInCart()
    {     
        return $this->hasOne(CreateLoose::class, 'loose_id', 'id')->where('cart_id', request()->header('Cart'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wishlist()
    {
        $result =  $this->hasOne(WishListLoose::class,'wishlistable_id','id');

        $result->join('wishlist','wishlist.id','=','wishlist_loose.line_id');

        $result->where([['wishlist.session','=',session('session_fd')]]);

        $result->select('wishlist_loose.*');

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getDiamondTitle()
    {
        return $this->weight.' Ct. '.ucfirst($this->shape).' Lab Grown Diamond';
    }

    public function getDiamondSearchkeyword()
    {
        return intval($this->weight).'ct  '.$this->weight.' ' .str_replace('0','',$this->weight).' '.ucfirst($this->shape).' Lab Grown Diamond'. ' ' . $this->certificate_no.' '.  $this->cut_grade . ' Cut, '. $this->color_shade .' '.$this->color . ' Color, ' . $this->clarity .' Clarity, '. $this->growth_process . ' Lot No. '.$this->lot;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getDiamondInfo()
    {
        return ucfirst($this->cut_grade).' Cut, '.ucfirst($this->color).' Color, '. strtoupper($this->clarity) .' Clarity, Lot No. '.$this->lot;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getSearchDiamondInfo()
    {
        return ucfirst($this->cut_grade).' Cut, '.ucfirst($this->color).' Color, '. strtoupper($this->clarity) .' Clarity';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locks()
    {
        return $this->hasMany(LockedLooseLots::class, 'item_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function locked()
    {
        return $this->hasOne(LockedLooseLots::class, 'item_id', 'id');
    }
}
