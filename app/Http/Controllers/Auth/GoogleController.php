<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    //redirect to google
    public function redirectToGoogle()
    {
        /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
        $driver = Socialite::driver('google');
        
        return $driver->with(['prompt' => 'select_account'])->redirect(); 
    }

    //handle callback from google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            //cari user berdasarkan id_google atau email
            $user = User::where('id_google', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if (!$user) {
                //jika user belum ada, buat user baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'id_google' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)), //buat password random karena tidak digunakan
                ]);
            } else {
                //jika user sudah ada, update id_google kalo belum terisi
                if (!$user->id_google) {
                    $user->update(['id_google' => $googleUser->getId()]);
                }
            }

            //generate OTP 6 digit
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

            //simpan otp ke database dengan expired 5 menit
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(5),
            ]);

            //kirim otp ke email user
            $this->sendOtpEmail($user, $otp);

            //simpen user_id di session untuk verifikasi otp
            session(['otp_user_id' => $user->id]);

            //redirect ke halaman input otp
            return redirect()->route('otp.form')
                        ->with('success', 'kode otp telah dikirim ke email '. $user->email);
        } catch (\Exception $e) {
            return redirect()->route('login')
                        ->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    //kirim otp ke email user
    private function sendOtpEmail($user, $otp)
    {
        Mail::raw("Kode OTP Anda adalah: {$otp}\n\nKode ini berlaku selama 5 menit.", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });
    }

    //tampilkan form input OTP
    public function showOtpForm()
    {
        //cek apakah ada user_id di session
        if (!session('otp_user_id')) {
            return redirect()->route('login')
                        ->with('error', 'Silakan login google terlebih dahulu.');
        }

        return view('auth.otp');
    }

    //verifikasi OTP
    public function verifyOtp()
    {
        $request = request();

        //validasi input
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        //ambil user_id dari session
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')
                        ->with('error', 'Session expired. Silakan login ulang.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                        ->with('error', 'User tidak ditemukan.');
        }

        // cek apakah otp valid dan belum expired
        if ($user->otp !== $request->otp) {
            return back()->with('error', 'Kode OTP tidak valid.');
        } 

        if ($user->otp_expires_at < Carbon::now()) {
            return back()->with('error', 'Kode OTP sudah expaired. Silahkan login ulang.');
        }

        // hapus OTP setelah verifikasi berhasil
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        //hapus session
        session()->forget('otp_user_id');

        //login user
        Auth::login($user);

        return redirect()->route('home')
                    ->with('success', 'Login berhasil!');
    }

    //resend OTP
    public function resendOtp()
    {        //ambil user_id dari session
        $userId = session('otp_user_id');   

        if (!$userId) {
            return redirect()->route('login')
                        ->with('error', 'Session expired. Silakan login ulang.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')
                        ->with('error', 'User tidak ditemukan.');
        }

        //generate OTP baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        //kirim otp ke email user
        $this->sendOtpEmail($user, $otp);
        return back()->with('success', 'Kode OTP baru telah dikirim ke email '. $user->email);
    }
}

