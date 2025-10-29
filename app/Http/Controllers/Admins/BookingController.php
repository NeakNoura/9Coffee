<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Booking;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
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
}
