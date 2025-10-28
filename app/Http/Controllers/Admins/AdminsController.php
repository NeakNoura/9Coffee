<?php
namespace App\Http\Controllers\Admins;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\Product\Booking;
use App\Models\Product\Product;
use App\Models\Product\Order;
use App\Models\Product\Receipt;
use App\Models\Admin;
use App\Models\RawMaterial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class AdminsController extends Controller
{



 public function home()
    {
        return view('home');
    }

public function showReceipt($id)
{
    $order = Order::findOrFail($id);
    return view('products.receipt', compact('order'));
}

    public function viewLogin(){
        return view('admins.login');
    }

   public function checkLogin(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
        return redirect()->route('admins.dashboard');
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
}

public function logout(Request $request)
{
    // Logout admin
    Auth::guard('admin')->logout();

    // Invalidate session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Forget remember me cookie if it exists
    Cookie::queue(Cookie::forget('remember_admin_' . sha1('admin')));

    // Redirect to admin login
    return redirect()->route('view.login');
}




 public function index(){
    $productsCount = Product::count();
    $ordersCount = Order::count();
    $bookingsCount = Booking::count();
    $adminsCount = Admin::count();
    $usersCount = User::count();
    $earning = Order::sum('price');
    $recentOrders = Order::latest()->take(8)->get();
    return view('admins.index', compact(
        'productsCount',
        'ordersCount',
        'bookingsCount',
        'adminsCount',
        'usersCount',
        'earning',
        'recentOrders'  // now defined
    ));
}

    public function DisplayAllAdmins(){
        $allAdmins = Admin::select()->orderBy('id','asc',)->get();
        return view('admins.alladmins',compact('allAdmins'));
    }
public function product() {
    return $this->belongsTo(Product::class, 'product_id', 'id');
}




    public function createAdmins(){

        return view('admins.createadmins');
    }

    public function storeAdmins(Request $request)
{
    $request->validate([
        "name" => "required|max:40",
        "email" => "required|email|max:40|unique:admins,email",
        "password" => "required|min:6",
    ]);

    $admin = Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('all.admins')->with('success', 'Admin created successfully!');
}

public function editAdmin($id)
{
    $admin = Admin::findOrFail($id);
    return view('admins.editadmins', compact('admin')); // singular matches the variable
}

public function updateAdmin(Request $request, $id)
{
    $request->validate([
        "name" => "required|max:40",
        "email" => "required|email|max:40|unique:admins,email,".$id,
        "password" => "nullable|min:6",
    ]);

    $admin = Admin::findOrFail($id);
    $admin->name = $request->name;
    $admin->email = $request->email;

    // Only update password if a new one is provided
    if (!empty($request->password)) {
        $admin->password = Hash::make($request->password);
    }

    $admin->save();

    return redirect()->route('all.admins')->with('success', 'Admin updated successfully!');
}

public function deleteAdmin($id)
{
    $admin = Admin::findOrFail($id);
    $admin->delete();

    return redirect()->route('all.admins')->with('success', 'Admin deleted successfully!');
}
public function DisplayAllUsers()
{
    $users = User::orderBy('id', 'asc')->get();
    return view('admins.allusers', compact('users'));
}


    public function DisplayAllOrders(){
      $allOrders = Order::select()->orderBy('created_at','desc')->get();

        return view('admins.allorders',compact('allOrders'));
    }
    public function EditOrders($id){
        $order = Order::find($id);

          return view('admins.editorders',compact('order'));
      }

    public function UpdateOrders(Request $request, $id){
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found']);
    }

    $request->validate([
        'status' => 'required|in:Pending,Delivered,Cancelled'
    ]);

    $order->status = $request->status;
    $order->save();

    return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
}



     public function DeleteOrders($id){
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found']);
    }

    $order->delete();
    return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
}

        public function DeleteAllOrders()
        {
            \App\Models\Product\Order::query()->delete();

            return Redirect::route('all.orders')->with(['delete' => "All orders deleted successfully"]);
        }

      public function DisplayProducts(){
        $products = Product::select()->orderBy('id','asc')->get();


            return view('admins.allproducts',compact('products'));



      }
      public function CreateProducts(){

            return view('admins.createproducts');

      }

      public function StoreProducts(Request $request)
{
    $request->validate([
        'name' => 'required|unique:products,name|max:100',
        'price' => 'required|numeric',
        'type' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        'description' => 'nullable',
    ]);

    $descriptionPath = 'assets/images/';
    $myimage = $request->image->getClientOriginalName();
    $request->image->move(public_path($descriptionPath), $myimage);

    Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'image' => $myimage,
        'description' => $request->description,
        'type' => $request->type,
        'quantity' => $request->quantity ?? 0,
    ]);

    return Redirect::route('all.products')
        ->with(['success' => "Product created successfully!"]);
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


         public function EditProducts($id)
    {
        $product = Product::findOrFail($id);
        return view('admins.edit', compact('product'));
    }

  public function AjaxUpdateProducts(Request $request, $id)
{
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




         public function DisplayBookings(){
            $bookings = Booking::select()->orderBy('id','asc')->get();


                return view('admins.allbookings',compact('bookings'));

          }
          public function EditBookings($id){
            $booking = Booking::find($id);

              return view('admins.editbooking',compact('booking'));
          }

 public function DeleteBookings($id)
{
    $booking = Booking::find($id);
    if(!$booking){
        return response()->json(['success' => false, 'message' => 'Booking not found']);
    }
    $booking->delete();

    // Reset auto-increment if table empty
    if (Booking::count() === 0) {
        DB::statement('ALTER TABLE bookings AUTO_INCREMENT = 1');
    }

    return response()->json(['success' => true, 'message' => 'Booking deleted successfully']);
}
    public function UpdateBookings(Request $request, $id)
{
    $booking = Booking::find($id);
    if (!$booking) {
        return response()->json(['success' => false, 'message' => 'Booking not found']);
    }

    $request->validate([
        'status' => 'required|in:Pending,Confirmed,Cancelled'
    ]);

    $booking->status = $request->status;
    $booking->save();

    return response()->json(['success' => true, 'message' => 'Booking status updated successfully']);
}


     public function StoreBookings(Request $request)
{
    $request->validate([
        'first_name' => 'required|max:40',
        'last_name'  => 'required|max:40',
        'date'       => 'required|date|after:today',
        'time'       => 'required',
        'phone'      => 'required|max:40',
        'message'    => 'nullable',
    ]);

    $userId = null;
    $redirectRoute = 'home';

    if (auth('web')->check()) {
        $userId = auth('web')->id();
        $redirectRoute = 'home';
    } elseif (auth('admin')->check()) {
        $userId = auth('admin')->id();
        $redirectRoute = 'all.bookings';
    }

    $booking = Booking::create([
        'user_id'    => $userId,
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'date'       => $request->date,
        'time'       => $request->time,
        'phone'      => $request->phone,
        'message'    => $request->message,
        'status'     => 'Pending',
    ]);

    if ($booking) {
        return redirect()->route($redirectRoute)
                         ->with('success', 'Booking created successfully!');
    } else {
        return redirect()->back()->with('error', 'Failed to book a table.');
    }
}
        public function Help()
        {
            return view('admins.help');
        }

          public function StaffSellForm()
    {
        $products = Product::select()->orderBy('id','asc')->get();
        return view('admins.staffSell', compact('products'));
    }
public function StaffSellProduct(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'payment_status' => 'required|in:Paid,Due',
        'first_name' => 'sometimes|string|max:255',
        'last_name' => 'sometimes|string|max:255',
        'state' => 'sometimes|string|max:255',
    ]);

    $product = Product::find($request->product_id);

    // Check if enough stock
    if ($product->quantity < $request->quantity) {
        return redirect()->back()->with('error', 'Not enough stock!');
    }

    $totalPrice = $product->price * $request->quantity;


    $order = Order::create([
        'product_id' => $product->id,
        'price' => $totalPrice,
        'payment_status' => $request->payment_status ?? 'Due',
        'status' => 'Pending',
        'first_name' => $request->first_name ?? 'Staff',
        'last_name' => $request->last_name ?? '',
        'state' => $request->state ?? '',
        'user_id' => Auth::id(),
    ]);

    // Deduct sold quantity from product stock
    $product->quantity -= $request->quantity;
    $product->save();

    return redirect()->route('staff.sell.form')->with(['success' => 'Product sold successfully!']);
}

public function staffCheckout(Request $request)
{
    $cart = json_decode($request->cart_data, true);
    $paymentMethod = $request->payment_method;
    $orderRef = 'ORD-' . now()->format('YmdHis') . '-' . rand(1000,9999);

    $ordersCreated = [];

    foreach ($cart as $productId => $item) {
        $product = Product::with('rawMaterials')->find($productId);

        if (!$product || $product->quantity < $item['quantity']) {
            continue; // skip if not enough product stock
        }

        // Check if raw materials are sufficient
        $canFulfill = true;
        foreach ($product->rawMaterials as $raw) {
            $requiredQty = $raw->pivot->quantity_required * $item['quantity'];
            if ($raw->quantity < $requiredQty) {
                $canFulfill = false;
                break;
            }
        }
        if (!$canFulfill) {
            continue; // skip this product if raw materials insufficient
        }

        // Create the order
        $order = Order::create([
            'product_id' => $productId,
            'price' => $item['price'] * $item['quantity'],
            'quantity' => $item['quantity'],
            'payment_status' => $paymentMethod === 'cash' ? 'Paid' : 'Paid',
            'status' => 'Paid',
            'first_name' => 'Staff',
            'last_name' => '',
            'state' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'user_id' => Auth::id(),
            'payment_method' => $paymentMethod,
            'order_ref' => $orderRef,
        ]);

        // Deduct product stock
        $product->quantity -= $item['quantity'];
        $product->save();

        // Deduct raw materials stock
        foreach ($product->rawMaterials as $raw) {
            $raw->quantity -= $raw->pivot->quantity_required * $item['quantity'];
            $raw->save();
        }

        $ordersCreated[] = $order;
    }

    // Return response
    if ($paymentMethod === 'cash') {
        return response()->json([
            'success' => true,
            'message' => 'Cash payment completed!',
            'orders' => $ordersCreated,
        ]);
    }

    // QR Payment
    $qrData = route('staff.qr-pay', ['order_ref' => $orderRef]);
    return view('admins.staff-qr', compact('qrData', 'orderRef'));
}



public function qrPay($order_ref)
{
    $orders = Order::where('order_ref', $order_ref)->get();

    if($orders->isEmpty()) {
        return "Order not found!";
    }

    // Mark payment as successful
    foreach($orders as $order){
        $order->update(['payment_status' => 'Paid']);
    }

    return "Payment successful! Thank you.";
}



public function paywithPaypal()
{
    $cart = session('admin_cart', []);
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('admins.paypal-checkout', compact('total'));
}

public function paypalSuccess()
{
    $cart = session('admin_cart', []);
    $total = session('admin_cart_total', 0);

    if (empty($cart)) {
        return redirect()->route('staff.sell.form')->with('error', 'No cart data found!');
    }

    foreach ($cart as $productId => $item) {
        $product = Product::find($productId);
        if (!$product) continue;

        $order = Order::create([
    'product_id' => $product->id,
    'price' => $item['price'] * $item['quantity'],
    'payment_status' => 'Paid',
    'status' => 'Completed',
    'first_name' => 'Staff',
    'last_name' => '',
    'state' => 'POS Sale',
    'user_id' => auth('admin')->id() ?? null,
    'address' => 'N/A',  // mandatory
    'city' => 'N/A',
    'zip_code' => '00000',
    'phone' => '0000000000',
    'email' => 'staff@pos.local'
]);


        // Deduct stock
        if ($product->quantity >= $item['quantity']) {
            $product->quantity -= $item['quantity'];
            $product->save();
        }
    }

    session()->forget('admin_cart');
    session()->forget('admin_cart_total');

    return view('admins.paypal-success')->with('success', 'Payment and order recorded successfully!');
}

public function viewStock()
{
    $products = Product::select('name', DB::raw('MAX(id) as id'), DB::raw('MAX(price) as price'), DB::raw('SUM(quantity) as quantity'))
        ->groupBy('name')
        ->orderBy('id', 'asc')
        ->get();

    return view('admins.stock', compact('products'));
}

public function updateStock(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    $product = Product::findOrFail($id);
    $product->quantity = $request->quantity;
    $product->save();

    return redirect()->route('admin.stock')->with('success', 'Stock updated successfully!');
}



public function salesReport()
{
    $sales = Order::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(price) as total_sales'),
        DB::raw('COUNT(id) as total_orders')
    )
    ->groupBy('date')
    ->orderBy('date', 'desc')
    ->limit(30)
    ->get();

    return view('admins.sales', compact('sales'));


}
public function lowStock()
{
    $lowStockProducts = Product::where('quantity', '<', 5)->get();
    return view('admins.low_stock', compact('lowStockProducts'));
}
public function viewExpenses()
{
    $expenses = DB::table('expenses')->orderBy('created_at', 'desc')->get();
    return view('admins.expenses', compact('expenses'));
}

public function storeExpense(Request $request)
{
    $request->validate([
        'description' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0'
    ]);

    DB::table('expenses')->insert([
        'description' => $request->description,
        'amount' => $request->amount,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('admin.expenses')->with('success', 'Expense recorded successfully!');
}
public function adminLogs()
{
    $logs = DB::table('activity_logs')->orderBy('created_at', 'desc')->limit(100)->get();
    return view('admins.logs', compact('logs'));
}

public function products() {
    return $this->belongsToMany(Product::class, 'product_raw_material')
                ->withPivot('quantity_required');
}
public function orderProduct(Request $request)
{
    $product = Product::findOrFail($request->product_id);
    $quantity = $request->quantity;

    // Check if stock is enough
    foreach ($product->rawMaterials as $material) {
        if ($material->quantity < ($material->pivot->quantity_required * $quantity)) {
            return back()->with('error', $material->name . ' is not enough!');
        }
    }

    // Deduct raw materials
    foreach ($product->rawMaterials as $material) {
        $material->quantity -= $material->pivot->quantity_required * $quantity;
        $material->save();
    }

    // Create order
    Order::create([
        'product_id' => $product->id,
        'quantity' => $quantity,
        'price' => $product->price * $quantity,
        'status' => 'Pending'
    ]);

    return back()->with('success', 'Order placed and stock updated!');
}
// Show raw material stock
public function viewRawMaterials()
{
    $rawMaterials = \App\Models\RawMaterial::orderBy('id', 'asc')->get();
    return view('admins.stock', compact('rawMaterials'));
}



// Update raw material quantity
public function updateRawMaterial(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    $material = RawMaterial::findOrFail($id);
    $material->quantity = $request->quantity;
    $material->save();

    return redirect()->route('admin.raw-material.stock')->with('success', 'Stock updated successfully!');
}













}
