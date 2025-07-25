<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Http\Resources\AdResource;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AdCollection;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductCollection;
use Illuminate\Database\Query\JoinClause;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\DiscountCollection;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Resources\ProductDiscountCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'type',
    'element',
    'rank',
  ];


  public function name($lang = null)
  {
    $lang = $lang ?? session('locale', app()->getLocale());

    if ($this->type == 'ads') {
      return match($lang) {
          'ar' => 'اعلانات',
          'fr' => 'Annonces',
          default => 'Ads'
      };
    }

    if ($this->type == 'solo') {
      return match($lang) {
          'ar' => 'فئات منفردة',
          'fr' => 'Catégories',
          default => 'Categories'
      };
    }

    if ($this->type == 'family') {
      $family = Family::find($this->element);
      return $family->name;
    }

    if ($this->type == 'group') {
      $group = Group::find($this->element);
      return $group->name;
    }

    if ($this->type == 'ad') {
      $ad = Ad::find($this->element);
      return $ad->name;
    }

    if ($this->type == 'offer') {
      if ($this->element == 'popular') {
        return match($lang) {
            'ar' => 'الأكثر مبيعا',
            'fr' => 'Les plus populaires',
            default => 'Popular'
        };
      } else {
        $offer = Offer::find($this->element);
        return $offer->name;
      }
    }
  }
  public function data()
  {
    if ($this->type == 'ads') {
      $ads = Ad::inRandomOrder()->limit(5)->get();
      return new AdCollection($ads);
    }

    if ($this->type == 'ad') {
      $ad = Ad::find($this->element);
      return new AdResource($ad);
    }
    if ($this->type == 'solo') {
      $members = Member::distinct('category_id')->pluck('category_id')->toArray();
      $non_members = Category::whereNotIn('id', $members)->inRandomOrder()->limit(8)->get();

      return new CategoryCollection($non_members);
    }

    if ($this->type == 'family') {

      $family = Family::find($this->element);
      $categories = $family->categories()->inRandomOrder()->get();
      return new CategoryCollection($categories);

    }

    if ($this->type == 'group') {

      $group = Group::find($this->element);
      $products = Product::whereNotNull('image')
      ->whereIn('subcategory_id',  $group->subcategories()->pluck('subcategories.id')->toArray())
      ->inRandomOrder()->take(10)->get();
      return new ProductCollection($products);

    }

    if ($this->type == 'offer') {

      if ($this->element == 'popular') {
        $popular_products = Item::select('product_id', DB::raw('COUNT(product_id) as count'))
          ->join('products','products.id','items.product_id')
          ->whereNotNull('products.image')
          ->where('products.deleted_at',null)
          ->where('items.deleted_at',null)
          ->groupBy('product_id')->orderBy('count', 'DESC')
          ->get()->take(10)->pluck('product_id')->toArray();

        //$popular_products = Product::whereIn('products.id', $popular_products)->get();
        $discounts = Discount::WhereRaw('? between start_date and end_date', Carbon::now()->toDateString());

        $popular_products = Product::whereIn('products.id', $popular_products)
        ->leftJoinSub($discounts, 'discounts', function (JoinClause $join) {
          $join->on('products.id', '=', 'discounts.product_id');
        })->select('products.*','discounts.id as discount_id','discounts.amount',
                  'discounts.start_date','discounts.end_date')->OrderBy(DB::raw('FIND_IN_SET(products.id,"'.implode(',',$popular_products).'")'))->get();

        return new ProductDiscountCollection($popular_products);

      } else {
        $offer = Offer::find($this->element);

        $categories = $offer->category_offers()->pluck('category_id')->toArray();
        $subcategories = Subcategory::whereIn('category_id', $categories)->pluck('id')->toArray();
        //$products = Product::whereIn('subcategory_id', $subcategories);

        /* $discounts = Discount::whereIn('product_id', $products)
          ->WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
          ->pluck('product_id')->toArray();
          //->inRandomOrder()->limit(10)->get(); */

        $discounted_products = Product::whereNotNull('image')
        ->whereIn('subcategory_id', $subcategories)
        ->leftjoin('discounts','products.id','discounts.product_id')
        ->WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())->where('discounts.deleted_at',null)
        ->select('products.*','discounts.id as discount_id','discounts.amount','discounts.start_date','discounts.end_date')
        ->inRandomOrder()->limit(10)->get();
        return new ProductDiscountCollection($discounted_products);
        //return new ProductDiscountCollection($discounts);

      }

    }
  }
}
