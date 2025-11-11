<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * View all expenses
     */
    public function viewExpenses()
    {
        // Auto-calculate today’s expense first
        $this->autoCalculateExpenses();

        // Fetch all expenses (auto + manual)
        $expenses = DB::table('expenses')->orderBy('created_at', 'desc')->get();

        return view('admins.expenses', compact('expenses'));
    }

    /**
     * Manual addition of an expense
     */
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

        return redirect()->route('admin.expenses')
                         ->with('success', 'Expense recorded successfully!');
    }

    /**
     * Auto-calculate today’s expense
     */
    public function autoCalculateExpenses()
    {
        // 1. Calculate total sales (or any formula for expenses)
        $todaySales = DB::table('orders')
            ->whereDate('created_at', now())
            ->sum('price');

        // Example: 10% of sales as expense
        $expenseAmount = round($todaySales * 0.1, 2);

        if ($expenseAmount <= 0) {
            // Nothing to insert/update
            return 0;
        }

        // 2. Check if today's auto expense exists
        $todayExpense = DB::table('expenses')
            ->whereDate('created_at', now())
            ->where('description', 'like', 'Auto-calculated expense%')
            ->first();

        if ($todayExpense) {
            // Update the existing auto expense
            DB::table('expenses')
                ->where('id', $todayExpense->id)
                ->update([
                    'amount' => $expenseAmount,
                    'updated_at' => now()
                ]);
        } else {
            // Insert new auto expense
            DB::table('expenses')->insert([
                'description' => 'Auto-calculated expense for ' . now()->format('Y-m-d'),
                'amount' => $expenseAmount,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return $expenseAmount;
    }
}
