<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['book', 'user'])->get();
        return response()->json(['success' => true, 'data' => $loans]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'book_id' => 'required|exists:books,id',
                'user_id' => 'required|exists:users,id',
                'loan_date' => 'required|date',
                'return_date' => 'nullable|date|after_or_equal:loan_date',
                'status' => 'required|string',
            ]);

            $loan = Loan::create($validated);

            return response()->json(['success' => true, 'data' => $loan], 201);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show($id)
    {
        try {
            $loan = Loan::with(['book', 'user'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $loan]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Loan not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $loan = Loan::findOrFail($id);

            $validated = $request->validate([
                'return_date' => 'nullable|date|after_or_equal:loan_date',
                'status' => 'required|string',
            ]);

            $loan->update($validated);

            return response()->json(['success' => true, 'data' => $loan]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Loan not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $loan->delete();

            return response()->json(['success' => true, 'message' => 'Loan deleted']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Loan not found'], 404);
        }
    }
}
