<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\NewRecord;
use App\Models\PrevApp;
use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransDetailsNew;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $matric = $user->matric;

        // $prevApp = PrevApp::where('matric', $matric)->first();
        // if (!$prevApp) {
        //     return response()->json(['error' => 'User not found in prev_app table.'], 404);
        // }
        // $newRecord = NewRecord::find($prevApp->user_id);

        // if (!$newRecord) {
        //     return response()->json(['error' => 'User not found in new table.'], 404);
        // }

        $newRecord = TransDetailsNew::where('matric', $matric)->first();
        $fullName = $newRecord->Surname . ' ' . $newRecord->Other_names;

        $cartItems = Cart::where('matric', $user->matric)->get();


        $cartItemCount = $cartItems->count();

        return view('carts.index', compact('cartItems', 'cartItemCount','fullName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         // Retrieve the cart item by ID
         $cartItem = Cart::find($id);


         if (!$cartItem) {
             return redirect()->route('cart.index')->with('error', 'Cart item not found.');
         }


         if ($cartItem->matric_number !== Auth::user()->matric_number) {
             return redirect()->route('cart.index')->with('error', 'Unauthorized action.');
         }


         $cartItem->delete();

         return redirect()->route('cart.index')->with('success', 'Cart item deleted successfully.');

    }
}
