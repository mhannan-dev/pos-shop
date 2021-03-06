<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductsController extends Controller
{

//    private $unit;
//    private $supplier;
//
//    public function __construct(Unit $unit, Supplier $supplier)
//    {
//        $this->middleware('auth');
//        $this->unit = $unit;
//        $this->supplier = $unit;
//    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $data['title'] = "Product";
        $data['products'] = Product::all();
        //dd($data['products']);
        return view('backend.pages.product.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        $data['title'] = "Create New Product";
        $data['suppliers'] = Supplier::all();
        $data['categories'] = Category::all();
        $data['sub_categories'] = SubCategory::all();
        $data['brands'] = Brand::all();
        $data['units'] = Unit::all();
        //$units = $this->unit->getSelectFromData();
        //dd($data);
        //return view('backend.pages.product.create', $data)->withUnits($units);;
        return view('backend.pages.product.create', $data);;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function postStore(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'category_id' => 'required',
            'supplier_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required|string|max:255',
            'quantity' => 'required',
            'description' => 'required',

        ],
            [
                'category_id.required' => 'Please select category',
                'supplier_id.required' => 'Please select supplier',
                'brand_id.required' => 'Please select brand',
                'unit_id.required' => 'Please select unit',
                'sub_category_id.required' => 'Please select sub_category',
                'name.required' => 'Please enter a name',
                'quantity' => 'Please enter quantity',
                'description' => 'Enter description',
            ]
        );

        $product = new  Product();
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->supplier_id = $request->supplier_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->name = $request->name;
        #$product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->created_by = Auth::user()->id;
        $product->save();
        toast('Data added successfully !!', 'success');
        return redirect()->route('admin.products.view');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)

    {
        $data['title'] = "Update Product";
        $data['suppliers'] = Supplier::all();
        $data['categories'] = Category::all();
        $data['sub_categories'] = SubCategory::all();
        $data['brands'] = Brand::all();
        $data['units'] = Unit::all();
        $data['product'] = Product::find($id);
        //dd($data['sub_categories']);
        if (!is_null($data)) {
            return view('backend.pages.product.edit', $data);
        } else {
            return redirect()->route('backend.pages.product.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request, $id)
    {
       // dd($request->all());
        $request->validate([
            'category_id' => 'required',
            'supplier_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'sub_category_id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required',

        ]
        );

        $product = Product::find($id);
        $product->category_id = $request->category_id;
        $product->supplier_id = $request->supplier_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->updated_by = Auth::user()->id;
        $product->save();
        toast('Data has been updated successfully !!', 'success');
        return redirect()->route('admin.products.view');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete($id)
    {

        $delete_row = Product::find($id);
       // dd($delete_row);
        if (!is_null($delete_row)) {
            $delete_row->delete();
        }
        toast('Data deleted successfully !!', 'success');
        return back();
    }
}
