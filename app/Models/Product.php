<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory,softDeletes;
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'category_id', 'store_id',
        'price', 'compare_price', 'status',
    ];

    protected $hidden =[
        'image',
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $appends = [
        'image_url',
    ];
    protected static function booted()
    {
        static::addGlobalScope('store',new StoreScope());
        static::created(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    //relation
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id','id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,           // Related Model
            'product_tag',          // Pivot table name
            'product_id',   // FK in pivot table for the current model
            'tag_id',       // FK in pivot table for the related model
            'id',               // PK current model
            'id'               // PK related model
        );
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status','=','active');
    }

    //Accessors
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getSalePercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }
        return round(100 - (100 * $this->price / $this->compare_price), 1);
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'store_id'=>null,
            'category_id'=>null,
            'tags'=>[],
            'status'=>'active',
        ], $filters);

        $builder->when($options['store_id'],function (Builder $builder,$value){
            $builder->where('store_id',$value);
        });

        $builder->when($options['category_id'],function (Builder $builder,$value){
            $builder->where('category_id',$value);
        });

        $builder->when($options['status'],function ($query,$statues){
            return $query->where('status',$statues);
        });

        $builder->when($options['tag_id']??null,function (Builder $builder,$value){
            $builder->whereExists(function ($query)use($value){
                $query->select(1)
                    ->from('product_tag')
                    ->where('product_id = products.id')
                    ->where('tag_id',$value);
            }) ;

//            $builder->whereHas('tags',function (Builder $builder) use ($value){
//                $builder->where('tag_id',$value);
//            });
        });
    }

}
