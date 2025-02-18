<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index()
    {
        $allProducts = Product::with('category')->get(); // Učitaj proizvode zajedno sa njihovim kategorijama
        return view('login.user', compact('allProducts'));
        // return view('login.user');
    } 

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            if ($user->role->name === 'admin') {
                return redirect()->route('admin')
                    ->with('role', 'admin') // Prosledi informaciju o ulozi
                    ->with('success', 'Successful Login!');
            } else {
                // Ako je korisnik običan korisnik
                return redirect()->route('user')
                    ->with('role', 'user') // Prosledi informaciju o ulozi
                    ->with('success', 'Successful Login!');
            }
            
        }

        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
 
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
