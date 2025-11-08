<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function DisplayProducts(){
        $products = Product::select()->orderBy('id','asc')->get();
            return view('admins.allproducts',compact('products'));

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

    $imagePath = public_path('assets/images');
    if (!file_exists($imagePath)) {
        mkdir($imagePath, 0775, true);
    }

    $imageName = time() . '_' . $request->image->getClientOriginalName();
    $request->image->move($imagePath, $imageName);

    $product = Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'image' => $imageName,
        'description' => $request->description,
        'product_type_id' => $request->product_type_id,
        'quantity' => $request->quantity ?? 0,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Product created successfully!',
        'product' => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'product_type_name' => $product->productType->name ?? 'N/A'
        ]
    ]);
}


    public function DeleteProducts($id){
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


         public function EditProducts($id)
    {
        $product = Product::findOrFail($id);
        return view('admins.edit', compact('product'));
    }

        public function AjaxUpdateProducts(Request $request, $id){
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found']);
            }

            $request->validate([
                'name' => 'required|max:100',
                'price' => 'required|numeric',
                'type' => 'required'
            ]);

            $product->name = $request->name;
            $product->price = $request->price;
            $product->type = $request->type;

            $product->save();

            return response()->json(['success' => true, 'message' => 'Product updated successfully']);
    }
        // Fetch all raw materials with assigned quantity for a product
public function getMaterials($id)
{
    $product = Product::with('rawMaterials')->findOrFail($id);

    $rawMaterials = RawMaterial::all()->map(function ($mat) use ($product) {
        $assigned = $product->rawMaterials->firstWhere('id', $mat->id);
        return [
            'id' => $mat->id,
            'name' => $mat->name,
            'unit' => $mat->unit,
            'quantity' => $mat->quantity,
            'assigned_qty' => $assigned ? $assigned->pivot->quantity_required : 0,
        ];
    });

    return response()->json($rawMaterials);
}

// Fetch only assigned materials
public function getAssignedMaterials($id)
{
    $assigned = DB::table('product_raw_material')
        ->join('raw_materials', 'product_raw_material.raw_material_id', '=', 'raw_materials.id')
        ->where('product_raw_material.product_id', $id)
        ->select('raw_materials.name', 'raw_materials.unit', 'product_raw_material.quantity_required')
        ->get();

    return response()->json($assigned);
}

// Assign / update raw materials for a product
public function addMaterials(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $data = $request->validate([
        'materials' => 'required|array',
        'materials.*' => 'numeric|min:0',
    ]);

    $syncData = [];
    foreach ($data['materials'] as $rawId => $qty) {
        if ($qty > 0) {
            $syncData[$rawId] = ['quantity_required' => $qty];
        }
    }

    $product->rawMaterials()->sync($syncData);

    return response()->json(['success' => true, 'message' => 'Recipe updated successfully!']);
}




}
