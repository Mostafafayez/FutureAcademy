<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    // GET /api/finances
    public function index(Request $request)
    {
        $query = Finance::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $finances = $query
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $income = Finance::where('type', 'income')->sum('amount');
        $expense = Finance::where('type', 'expense')->sum('amount');

        return response()->json([
            'status' => true,
            'data' => $finances,
            'summary' => [
                'total_income' => $income,
                'total_expense' => $expense,
                'balance' => $income - $expense,
            ],
        ]);
    }

    // POST /api/finances
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'person' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $finance = Finance::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'source' => $request->source,
            'description' => $request->description,
            'person' => $request->person,
            'transaction_date' => $request->transaction_date,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Finance transaction created successfully',
            'data' => $finance,
        ], 201);
    }

    // GET /api/finances/{id}
    public function show($id)
    {
        $finance = Finance::find($id);

        if (!$finance) {
            return response()->json([
                'status' => false,
                'message' => 'Finance transaction not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $finance,
        ]);
    }

    // PUT /api/finances/{id}
    public function update(Request $request, $id)
    {
        $finance = Finance::find($id);

        if (!$finance) {
            return response()->json([
                'status' => false,
                'message' => 'Finance transaction not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|in:income,expense',
            'amount' => 'sometimes|numeric|min:0.01',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'person' => 'nullable|string|max:255',
            'transaction_date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $finance->update($request->only([
            'type',
            'amount',
            'source',
            'description',
            'person',
            'transaction_date',
            'notes',
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Finance transaction updated successfully',
            'data' => $finance,
        ]);
    }

    // DELETE /api/finances/{id}
    public function destroy($id)
    {
        $finance = Finance::find($id);

        if (!$finance) {
            return response()->json([
                'status' => false,
                'message' => 'Finance transaction not found',
            ], 404);
        }

        $finance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Finance transaction deleted successfully',
        ]);
    }
}
