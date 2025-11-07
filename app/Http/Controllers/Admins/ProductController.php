<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\ProductType;
use App\Models\SubType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;


    class ProductController extends Controller
    {
        public function index() {
            $products = Product::all();
            return view('admins.allproducts', compact('products'));
        }
public function DisplayProducts(){
    $products = Product::with('productType', 'rawMaterials')->orderBy('id','asc')->get();
    return view('admins.allproducts', compact('products'));
}



    public function CreateProducts()
    {
        $types = ProductType::all(); // fetch all product types
        return view('admins.createproducts', compact('types'));
    }

public function StoreProducts(Request $request)
{
    $request->validate([
        'name' => 'required|unique:products,name|max:100',
        'price' => 'required|numeric',
        'product_type_id' => 'required|exists:product_types,id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        'description' => 'nullable',
    ]);

    $imageName = $request->image->getClientOriginalName();
    $request->image->move(public_path('assets/images/'), $imageName);

    Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'product_type_id' => $request->product_type_id,
        'description' => $request->description,
        'image' => $imageName,
        'quantity' => 0,
    ]);

    return redirect()->route('all.products')->with('success', 'Product created successfully!');
}


    public function DeleteProducts($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        if (File::exists(public_path('assets/images/' . $product->image))) {
            File::delete(public_path('assets/images/' . $product->image));
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }



public function AjaxUpdateProducts(Request $request, $id)
{
    $product = Product::find($id);
    if(!$product){
        return response()->json(['success'=>false, 'message'=>'Product not found']);
    }

    $request->validate([
        'name' => 'required|max:100',
        'price' => 'required|numeric',
    ]);

    $product->name = $request->name;
    $product->price = $request->price;
    $product->save();

    return response()->json(['success'=>true, 'message'=>'Product updated successfully']);
}
public function getAllMaterialsForAssign($id)
{
    $product = Product::findOrFail($id);

    $materials = \App\Models\RawMaterial::all()->map(function($mat) use ($product) {
        $assignedQty = $product->rawMaterials()->where('raw_material_id', $mat->id)->first()?->pivot->quantity_required ?? 0;

        return [
            'id' => $mat->id,
            'name' => $mat->name,
            'unit' => $mat->unit,
            'stock_quantity' => $mat->quantity,
            'quantity_required' => $assignedQty, // already assigned qty
        ];
    });

    return response()->json($materials);
}





public function addMaterials(Request $request, Product $product)
{
    $materials = $request->input('materials', []);
    foreach ($materials as $id => $qty) {
        $product->rawMaterials()->syncWithoutDetaching([$id => ['quantity_required' => $qty]]);
    }
    return response()->json(['success' => true, 'message' => 'Materials assigned successfully']);
}


    }
