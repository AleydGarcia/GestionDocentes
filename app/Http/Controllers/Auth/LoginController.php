<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /*
     * se ejecuta cuando alguien va al login
     */
    public function showLoginForm()
    {
        // Si el usuario está autenticado lo envía al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Sino muestra la vista welcome (de nuevo el formulario)
        return view('welcome');
    }

    /**
     * Autenticar al usuario, se ejecuta cuando envía el formulario
     */
    public function authenticate(Request $request)
    {
        // campos no vacíos
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'], 
        ]);

        // verificar
        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'correo' : 'usuario';

        // Crear el array de credenciales
        $credentials = [
            $field => $request->input('login'),
            'password' => $request->input('password'),
        ];

        //busca yverifica
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // falla
        return back()
            ->withErrors([
                'login' => 'El usuario o la contraseña son incorrectos. Por favor, verifique sus datos',
            ])
            ->onlyInput('login');
    }

    //Cerrar sesión

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}