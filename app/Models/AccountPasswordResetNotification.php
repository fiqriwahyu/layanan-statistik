<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AccountPasswordResetNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $expiry = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Permintaan Atur Ulang Kata Sandi - Layanan Statistik BPS Kota Jambi')
            ->greeting('Yth. '.$notifiable->name)
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda pada Sistem Informasi Layanan Statistik BPS Kota Jambi.')
            ->action('Atur Ulang Kata Sandi', $this->resetUrl($notifiable))
            ->line('Tautan ini berlaku selama '.$expiry.' menit dan hanya dapat digunakan satu kali.')
            ->line('Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini. Kata sandi Anda tidak akan berubah.');
    }
}
