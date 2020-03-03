<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
session_start();

class HomeController extends Controller
{
    public function index(){
        $category_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

//        $all_product = DB::table('tbl_product')
//            ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
//            ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
//            ->orderby('tbl_product.product_id','desc')->get();
        $all_product = DB::table('tbl_product')->where('product_status','0')->orderby('product_id','desc')->limit(4)->get();


        return view('pages.home')->with('category',$category_product)->with('brand',$brand_product)->with('all_product',$all_product);
    }
    public function details_home($product_id){
    $category_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderby('category_id', 'desc')->get();

    $brand_product = DB::table('tbl_brand')
        ->where('brand_status','0')
        ->orderby('brand_id','desc')->get();

    $details_home = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.product_id',$product_id)->get();

    foreach ($details_home as $key => $value){
        $category_id = $value->category_id;
    }

    $related_product = DB::table('tbl_product')
        ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_category_product.category_id',$category_id)->whereNotIn('tbl_product.product_id',[$product_id])->get();

    return view('pages.home')
        ->with('category',$category_product)
        ->with('brand',$brand_product)
        ->with('product_home',$details_home)
        ->with('related',$related_product);

}

}
