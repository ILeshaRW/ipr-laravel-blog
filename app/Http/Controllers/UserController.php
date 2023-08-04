<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AuthRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Контроллер для работы с пользователями
 */
class UserController extends Controller
{

    /**
     * Страница регистрации
     *
     * @return View
     */
    public function register(): View
    {
        return view('user.register');
    }

    /**
     * Регистрация пользователя
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function create(RegisterRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('login');
    }

    /**
     * Страница аутентификации
     *
     * @return View
     */
    public function loginView(): View
    {
        return view('user.login');
    }


    /**
     * Аутентификация пользователя
     *
     * @param AuthRequest $request
     * @return RedirectResponse
     */
    public function authenticate(AuthRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('index');
        }

        return redirect()
            ->route('login')
            ->withErrors('Неверный логин или пароль');
    }

    /**
     * Страница личного кабинета
     *
     * @return View
     */
    public function index(): View
    {
        return view('user.index');
    }

    /**
     * Выход из ЛК
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
