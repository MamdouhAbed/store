<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Category extends Model
{
    //
    /*protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';*/

    protected $fillable = [
        'name', 'parent_id', 'status',
    ];

    public $timestamps = true;

    protected $perPage = 10;

    public function products()
    {
        return $this->hasMany(
            Product::class, // Related Model Class name (Product)
            'category_id' , // Forign key for categories in realated table (products)
            'id'            // Primary Key for categories (id)
        );
    }

    public function children()
    {
        return $this->hasMany( Category::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')
            ->withDefault([
                'name' => 'No Parent'
            ]);
    }

    public static function getValidator($data, $except = 0)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:categories,name,' . $except,
            ],
            'parent_id' => [
                'nullable',
                'int',
                'exists:categories,id'
            ],
            'status' => [
                'required',
                'string',
                'in:published,draft'
            ],
        ];
        $validator = Validator::make($data, $rules, [
            'required' => ':attribute Required data!!'
        ]);
        return $validator;
    }
}
