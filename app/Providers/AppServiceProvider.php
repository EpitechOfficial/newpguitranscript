<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\TransDetailsNew;
use App\Models\ZmainApp;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (request()->path() !== '/') {
                $user = Auth::user();
                
                // Check if the user is authenticated
                if ($user) {
                    $matric = $user->matric;
    
                    // Retrieve the cart items and transaction details based on the matric
                    $cartItems = Cart::where('matric', $matric)->get();
                    $cartItemCount = $cartItems->count();
                    $transDetails = TransDetailsNew::where('matric', $matric)->get();
                    $transDetailsExists = TransDetailsNew::where('matric', $matric)->exists();
    
                    // Pass the data to the view
                    $view->with([
                        'cartItemCount' => $cartItemCount,
                        'transDetails' => $transDetails,
                        'transDetailsExists' => $transDetailsExists,
                    ]);
                } else {
                    // Optionally, handle the case when no user is authenticated
                    $view->with([
                        'cartItemCount' => 0,
                        'transDetails' => null,
                        'transDetailsExists' => false,
                    ]);
                }
            }
        });
    }
    

}
