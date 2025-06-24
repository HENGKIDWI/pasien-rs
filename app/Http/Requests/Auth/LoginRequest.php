<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // --- LOGIKA LOGIN MANUAL DIMULAI DI SINI ---

        // 1. Cari pengguna di VIEW 'users' berdasarkan email
        $userFromView = DB::table('users')->where('email', $this->input('email'))->first();

        // 2. Jika pengguna ditemukan DAN password cocok
        if ($userFromView && Hash::check($this->input('password'), $userFromView->password)) {
            
            // 3. Ambil model User yang sebenarnya agar bisa login
            $user = User::find($userFromView->id);
            
            if ($user) {
                // 4. Login pengguna secara manual.
                // PENTING: Parameter kedua (remember) sengaja dihilangkan untuk
                // mencegah Laravel mencoba menulis 'remember_token' ke VIEW.
                Auth::login($user);
                
                RateLimiter::clear($this->throttleKey());
                return; // Hentikan eksekusi jika berhasil
            }
        }
        
        // --- LOGIKA LOGIN MANUAL SELESAI ---
        
        // Jika gagal, jalankan prosedur error standar
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
