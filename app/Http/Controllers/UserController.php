<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AuthRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Контроллер для работы с пользователями
 */
class UserController extends Controller
{

    public function __construct(protected UserService $service)
    {}

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
        $user = $this->service->create($request->validated());

        event(new Registered($user));
        Auth::login($user);

        return redirect()
            ->route('index');
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

    public function verifyEmail (EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect('/lk');
    }
}
