<?php

namespace App\Models;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AccountEmailVerificationNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $expiry = config('auth.verification.expire', 60);

        return (new MailMessage)
            ->subject('Verifikasi Alamat Email - Layanan Statistik BPS Kota Jambi')
            ->greeting('Yth. '.$notifiable->name)
            ->line('Silakan verifikasi alamat email akun Anda pada Sistem Informasi Layanan Statistik BPS Kota Jambi.')
            ->action('Verifikasi Alamat Email', $this->verificationUrl($notifiable))
            ->line('Tautan ini berlaku selama '.$expiry.' menit.')
            ->line('Jika Anda tidak melakukan perubahan atau tidak meminta verifikasi, abaikan email ini.');
    }
}
