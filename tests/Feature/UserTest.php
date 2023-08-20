<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\Register;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Тесты пользователей.
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Успешный логин
     *
     * @return void
     * @throws \JsonException
     */
    public function test_user_login_successful(): void
    {
        User::withoutEvents(
            fn () => User::factory()->create(
                [
                    'name' => 'test',
                    'last_name' => 'test',
                    'email' => 'example@example.com',
                    'password' => \Hash::make('qwerty'),
                ]
            )
        );

        $this->post('login', ['email' => 'example@example.com', 'password' => 'qwerty'])
            ->assertRedirectToRoute('index')
            ->assertSessionHasNoErrors();
        $this->assertAuthenticated();
    }

    /**
     * Ошибка авторизации
     *
     * @return void
     */
    public function test_user_login_fail(): void
    {
        User::withoutEvents(
            fn () => User::factory()->create(
                [
                    'name' => 'test',
                    'last_name' => 'test',
                    'email' => 'example@example.com',
                    'password' => \Hash::make('qwerty'),
                ]
            )
        );

        $this->post('login', ['email' => 'example@example.com', 'password' => 'qwert'])
            ->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Ошибка регистрации
     *
     * @return void
     */
    public function test_user_register_fail(): void
    {
        Notification::fake();
        $this->post(
            'register',
            [
                'email' => 'example@example.com',
                'password' => 'qwert',
                'name' => 'test',
                'last_name' => 'test',
            ]
        )
            ->assertSessionHasErrors('password');

        Notification::assertNothingSent();
    }

    /**
     * Успешная регистрация
     *
     * @return void
     * @throws \JsonException
     */
    public function test_user_register_success(): void
    {
        Notification::fake();
        $this->post(
            'register',
            [
                'email' => 'example@example.com',
                'password' => 'qwerty123',
                'password_confirmation' => 'qwerty123',
                'name' => 'test',
                'last_name' => 'test',
            ]
        )
            ->assertSessionHasNoErrors()
            ->assertRedirectToRoute('login');

        Notification::assertSentTo(User::first(), Register::class);
    }
}
