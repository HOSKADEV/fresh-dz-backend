<?php

namespace App\Http\Controllers;


use Session;
use App\Models\Ad;
use App\Models\Cart;
use App\Models\User;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Driver;
use App\Models\Family;
use App\Models\Notice;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class DatatablesController extends Controller
{
  public function categories(){

    $categories = Category::orderBy('created_at','DESC')->get();

    return datatables()
      ->of($categories)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';


          $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

          $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('subcategories', function ($row) {

          return number_format($row->subcategories()->count());

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function subcategories(Request $request){

    $subcategories = Subcategory::orderBy('created_at','DESC');

    if(!empty($request->category)){
      $subcategories->where('category_id',$request->category);
    }

    $subcategories = $subcategories->get();

    return datatables()
      ->of($subcategories)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

          $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('category', function ($row) {

        return $row->category->name;

    })

      ->addColumn('products', function ($row) {

          return $row->products()->count();

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }


  public function families(){

    $families = Family::orderBy('created_at','DESC')->get();

    return datatables()
      ->of($families)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

          if(is_null($row->section())){

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-success inline-spacing add_to_home" title="'.__('Add to Homepage').'" table_id="'.$row->id.'"><span class="tf-icons bx bxs-plus-square"></span></button>';

          }else{

            $btn .= '<button class="btn btn-icon btn-label-warning inline-spacing remove_from_home" title="'.__('Remove from Homepage').'" table_id="'.$row->section()->id.'"><span class="tf-icons bx bxs-x-square"></span></button>';

          }

          return $btn;
      })

      ->addColumn('categories', function ($row) {

          return $row->categories()->count();

      })

      ->addColumn('is_published', function ($row) {

        if(is_null($row->section())){
         return false ;
        }
        return true;

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function offers(){

    $offers = Offer::orderBy('created_at','DESC')->get();

    return datatables()
      ->of($offers)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

          if(is_null($row->section())){

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-success inline-spacing add_to_home" title="'.__('Add to Homepage').'" table_id="'.$row->id.'"><span class="tf-icons bx bxs-plus-square"></span></button>';

          }else{

            $btn .= '<button class="btn btn-icon btn-label-warning inline-spacing remove_from_home" title="'.__('Remove from Homepage').'" table_id="'.$row->section()->id.'"><span class="tf-icons bx bxs-x-square"></span></button>';

          }

          return $btn;
      })

      ->addColumn('categories', function ($row) {

          return $row->categories()->count();

      })

      ->addColumn('is_published', function ($row) {

        if(is_null($row->section())){
         return false ;
        }
        return true;

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function products(Request $request){

    $products = Product::orderBy('created_at','DESC');

    if(!empty($request->category)){
      $category = Category::findOrFail($request->category);
      $category_subs = $category->subcategories()->pluck('id')->toArray();
      $products = $products->whereIn('subcategory_id',$category_subs);
    }


    if(!empty($request->subcategory)){
      $products = $products->where('subcategory_id',$request->subcategory);
    }

    if(!empty($request->discount)){
      if($request->discount == "1"){
        $discounts = Discount::WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
        ->pluck('product_id')->toArray();
        $products = $products->whereIn('id',$discounts);
      }

      if($request->discount == "2"){
        $discounts = Discount::WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
        ->pluck('product_id')->toArray();
        $products = $products->whereNotIn('id',$discounts);
      }

    }

    if(!empty($request->availability)){

      $products = $products->where('status',$request->availability == 1?'available':'unavailable');


    }

    $products = $products->get();

    return datatables()
      ->of($products)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

          $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          $btn .= '<a class="btn btn-icon btn-label-primary inline-spacing" title="'.__('Images').'" href="'.url('product/'.$row->id.'/images').'"><span class="tf-icons bx bx-image"></span></a>';

          $btn .= '<a class="btn btn-icon btn-label-warning inline-spacing" title="'.__('Videos').'" href="'.url('product/'.$row->id.'/videos').'"><span class="tf-icons bx bx-movie-play"></span></a>';

          $btn .= '<a class="btn btn-icon btn-label-success inline-spacing" title="'.__('Discounts').'" href="'.url('product/'.$row->id.'/discounts').'"><span class="tf-icons bx bxs-discount"></span></a>';

          return $btn;
      })

      ->addColumn('name', function ($row) {

          return $row->unit_name;

      })

      ->addColumn('price', function ($row) {

        return number_format($row->unit_price,2,'.',',');

    })

      ->addColumn('is_discounted', function ($row) {

        if(is_null($row->discount())){
         return false ;
        }
        return true;

      })

      ->addColumn('availability', function ($row) {

        if($row->status == 'unavailable'){
         return false ;
        }
        return true;

      })

      ->addColumn('discount', function ($row) {

        if(is_null($row->discount())){
          return '' ;
         }

        return number_format($row->discount()->amount,2) . '%';

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function sections(Request $request){

    $sections = Section::orderBy('rank','ASC')->get();

    return datatables()
      ->of($sections)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          if($row->deleteable == 1){
            $btn .= '<button class="btn btn-icon btn-label-primary inline-spacing delete" title="'.__('Remove').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-x"></span></button>';
          }

          if($row->moveable == 1){
            $btn .= '<button class="btn btn-icon btn-label-primary inline-spacing switch" title="'.__('Switch').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-refresh"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-primary inline-spacing insert" title="'.__('Insert').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-redo"></span></button>';

          }

          return $btn;
      })

      ->addColumn('type', function ($row) {

        return $row->type;

    })

      ->addColumn('name', function ($row) {

          return $row->name();

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function orders(Request $request){


    $orders = Order::orderBy('created_at','DESC');

    if(!empty($request->status)){
      if($request->status == 'default'){
        $orders = $orders->whereNotIn('status',['delivered','canceled']);
      }else{
        $orders = $orders->where('status',$request->status);
      }

    }

    $orders = $orders->get();

    return datatables()
      ->of($orders)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          $btn .= '<button class="btn btn-icon btn-label-secondary inline-spacing note" title="'.__('Note').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-note"></span></button>';

          $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          $btn .= '<a class="btn btn-icon btn-label-primary inline-spacing" title="'.__('Location').'" href="'.$row->address().'" target="_blank" ><span class="tf-icons bx bx-map"></span></a>';

          $btn .= '<a class="btn btn-icon btn-label-info inline-spacing" title="'.__('Cart').'" href="'.url('order/'.$row->id.'/items').'"><span class="tf-icons bx bx-cart"></span></a>';

          if($row->status == 'pending'){

            $btn .= '<button class="btn btn-icon btn-label-success inline-spacing accept" title="'.__('Approve').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-check"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-warning inline-spacing refuse" title="'.__('Cancel').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-x"></span></button>';

          }

          if($row->status == 'accepted'){

            $btn .= '<button class="btn btn-icon btn-label-primary inline-spacing ship" title="'.__('Ship').'" table_id="'.$row->id.'"><span class="tf-icons bx bxs-truck"></span></button>';

          }


          if(!in_array($row->status,['pending','canceled'])){
            if(!is_null($row->invoice)){

                $btn .= '<button class="btn btn-icon btn-label-dark inline-spacing invoice" title="'.__('Invoice').'" table_id="'.$row->invoice->id.'"><span class="tf-icons bx bx-file"></span></button>';

              if($row->status == 'ongoing' && $row->invoice->is_paid == 'no'){

                $btn .= '<button class="btn btn-icon btn-label-primary inline-spacing payment" title="'.__('Payment').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-money"></span></button>';

              }

            }
          }

          return $btn;
      })

      ->addColumn('user', function ($row) {

          return $row->user->fullname();

      })

      ->addColumn('phone', function ($row) {

        return $row->phone();

      })

      ->addColumn('status', function ($row) {

        return $row->status;

      })

      ->addColumn('driver', function ($row) {

        if(!is_null($row->delivery)){
          return $row->delivery->driver->fullname();
        }

      })

      ->addColumn('purchase_amount', function ($row) {

        if(!is_null($row->invoice)){
          return number_format($row->invoice->purchase_amount,2,'.',',');
        }

      })

      ->addColumn('tax_amount', function ($row) {

        if(!is_null($row->invoice)){
          return number_format($row->invoice->tax_amount,2,'.',',');
        }

      })

      ->addColumn('discount_amount', function ($row) {

        if(!is_null($row->invoice)){
          return number_format($row->invoice->discount_amount,2,'.',',');
        }

      })

      ->addColumn('total_amount', function ($row) {

        if(!is_null($row->invoice)){
          return number_format($row->invoice->total_amount,2,'.',',');
        }

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function items(Request $request){

    $cart = Cart::findOrFail($request->cart_id);
    $items = $cart->items()->orderBy('created_at','DESC')->get();

    return datatables()
      ->of($items)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-primary inline-spacing edit" title="'.__('Edit').'" table_id="'.$row->id.'" quantity="'.$row->quantity.'"><span class="tf-icons bx bx-edit"></span></button>';

          return $btn;
      })

      ->addColumn('product', function ($row) {

          return $row->name();

      })


      ->addColumn('price', function ($row) {

        return number_format($row->price(),2,'.',',');

      })

      ->addColumn('type', function ($row) {

        return $row->type;

      })

      ->addColumn('quantity', function ($row) {

        return $row->quantity;

      })

      ->addColumn('discount', function ($row) {

        return $row->discount.'%';

      })

      ->addColumn('amount', function ($row) {

        return number_format($row->amount,2,'.',',');

      })


      ->make(true);
  }

  public function drivers(){

    $drivers = Driver::orderBy('created_at','DESC')->get();

    return datatables()
      ->of($drivers)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

          $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('name', function ($row) {

        return $row->fullname();

      })

      ->addColumn('phone', function ($row) {

          return $row->phone();

      })

      ->addColumn('status', function ($row) {

          return $row->status();

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function users(){

    $users = User::where('role',1)->whereIn('status',[0,1])->get();

    return datatables()
      ->of($users)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

          if($row->status == 1){
            $btn .= '<button class="btn btn-icon btn-label-warning inline-spacing delete" title="'.__('Block').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-x-circle"></span></button>';
          }else{
            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing restore" title="'.__('Activate').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-check-circle"></span></button>';
          }




          return $btn;
      })

      ->addColumn('name', function ($row) {

        return $row->fullname();

      })

      ->addColumn('phone', function ($row) {

          return $row->phone();

      })

      ->addColumn('email', function ($row) {

        return $row->email;

    })

      ->addColumn('status', function ($row) {

        if($row->status == 1){
            return true;
          }else{
            return false;
          }

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function notices(){

    $notices = Notice::orderBy('created_at','DESC')->get();

    return datatables()
      ->of($notices)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

            $btn .= '<button class="btn btn-icon btn-label-primary inline-spacing view" title="'.__('View').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-show"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('title', function ($row) {

        if(Session::get('locale') == 'en'){
          return $row->title_en;
        }

        return $row->title_ar;
      })

      ->addColumn('type', function ($row) {

          return $row->type;

      })

      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function ads(){

    $ads = Ad::orderBy('created_at','DESC')->get();

    return datatables()
      ->of($ads)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

            $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('name', function ($row) {
        return $row->name;
      })


      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function images(Request $request){

    $images = Product::find($request->product_id)->images()->latest()->get();

    return datatables()
      ->of($images)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('image', function ($row) {
        return $row->path;
      })


      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function videos(Request $request){

    $videos = Product::find($request->product_id)->videos()->latest()->get();

    return datatables()
      ->of($videos)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('video', function ($row) {
        return $row->path;
      })


      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function coupons(){

    $coupons = Coupon::latest()->get();

    return datatables()
      ->of($coupons)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

            $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('name', function ($row) {
        return $row->name;
      })

      ->addColumn('code', function ($row) {
        return $row->code;
      })

      ->addColumn('discount', function ($row) {
        return $row->discount . '%';
      })


      ->addColumn('created_at', function ($row) {

        return date('Y-m-d',strtotime($row->created_at));

      })


      ->make(true);
  }

  public function discounts(Request $request){

    $discounts = Product::find($request->product_id)->discounts()->latest()->get();

    return datatables()
      ->of($discounts)
      ->addIndexColumn()

      ->addColumn('action', function ($row) {
          $btn = '';

            $btn .= '<button class="btn btn-icon btn-label-info inline-spacing update" title="'.__('Edit').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-edit"></span></button>';

            $btn .= '<button class="btn btn-icon btn-label-danger inline-spacing delete" title="'.__('Delete').'" table_id="'.$row->id.'"><span class="tf-icons bx bx-trash"></span></button>';

          return $btn;
      })

      ->addColumn('name', function ($row) {
        return $row->name;
      })

      ->addColumn('amount', function ($row) {
        return $row->amount . '%';
      })


      ->addColumn('start_date', function ($row) {
        return date('Y-m-d',strtotime($row->start_date));
      })


      ->addColumn('end_date', function ($row) {

        return date('Y-m-d',strtotime($row->end_date));

      })


      ->make(true);
  }


}
