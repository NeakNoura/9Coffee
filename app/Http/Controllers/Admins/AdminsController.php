<?php
namespace App\Http\Controllers\Admins;
use App\Http\Controllers\Controller;
use App\Models\Product\Booking;
use App\Models\Product\Product;
use App\Models\Product\Order;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AdminsController extends Controller
{

 public function home()
    {
        return view('home');
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

public function Help()
        {
            return view('admins.help');
        }


public function products() {
    return $this->belongsToMany(Product::class, 'product_raw_material')
                ->withPivot('quantity_required');
}

}
