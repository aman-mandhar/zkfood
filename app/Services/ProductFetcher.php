<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProductFetcher
{
    public static function getProducts()
{
    // First: Load all items with category, subcategory, variation
    $items = DB::table('items')
        ->join('product_categories', 'items.category_id', '=', 'product_categories.id')
        ->join('product_subcategories', 'items.subcategory_id', '=', 'product_subcategories.id')
        ->join('product_variations', 'items.variation_id', '=', 'product_variations.id')
        ->join('tokens', 'items.token_id', '=', 'tokens.id')
        ->select(
            'items.id as item_id',
            'items.name as item_name',
            'items.gst as gst_rate',
            'items.description as description',
            'items.type as item_type',
            'items.prod_pic as product_picture',
            'product_categories.id as category_id',
            'product_categories.name as category_name',
            'product_subcategories.id as subcategory_id',
            'product_subcategories.name as subcategory_name',
            'product_variations.id as variation_id',
            'product_variations.color as color',
            'product_variations.size as size',
            'product_variations.weight  as weight',
            'product_variations.length as length',
            'product_variations.liquid_volume as liquid_volume',
            'tokens.id as token_id',
            'tokens.title as token_name',
        )
        ->get()
        ->keyBy('item_id');  // ✅ so it matches the key you're later using

    // Then: Load stocks with purchase + user (owner) + employee info
    $stocks = DB::table('stocks')
        ->leftjoin('purchases', 'stocks.purchase_id', '=', 'purchases.id')
        ->leftjoin('users as owners', 'stocks.owner_id', '=', 'owners.id')
        ->select(
            'stocks.id as stock_id',
            'stocks.owner_id as owner_id',
            'stocks.pur_value  as purchase_price',
            'stocks.mrp as mrp',
            'stocks.sale_price as sale_price',
            'stocks.discount as discount',
            'stocks.gst as gst_amount',
            'stocks.warehouse as wh_commission',
            'stocks.warehouse_ref as wh_ref_commission',
            'stocks.sub_warehouse as sub_wh_commission',
            'stocks.sub_warehouse_ref as sub_wh_ref_commission',
            'stocks.store as store_commission',
            'stocks.store_ref as store_ref_commission',
            'stocks.shop as shop_commission',
            'stocks.shop_ref as shop_ref_commission',
            'stocks.customer as activity_points',
            'stocks.direct_ref as direct_ref',
            'stocks.ref_1 as ref_1',
            'stocks.ref_2 as ref_2',
            'stocks.ref_3 as ref_3',
            'stocks.admin as admin_commission',
            'stocks.super_admin_ref as super_admin_ref',
            'stocks.barcode as barcode',
            'stocks.batch_no as batch_no',
            'stocks.mfg_date as mfg_date',
            'stocks.exp_date as exp_date',
            'stocks.status_for_sale as status_for_sale',
            'stocks.employee_id as stock_submitter_id',
            'purchases.bill_number as purchase_bill_number',
            'purchases.bill_date as purchase_bill_date',
            'purchases.bill_image as purchase_bill_image',
            'purchases.merchant_id as purchase_merchant_id',
            'purchases.super_admin_id as purchase_super_admin_id',
            'owners.name as owner_name',
        )
        ->get()
		->keyBy('stock_id');

    // Merge item info into each stock entry
    $products = $stocks->map(function ($stock) use ($items) {
        $item = $items->get($stock->item_id);
        return (object) array_merge((array) $stock, (array) $item);
    });

    return $products;
}
}
