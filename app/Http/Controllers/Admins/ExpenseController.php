<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
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
}
