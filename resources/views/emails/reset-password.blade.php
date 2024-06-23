<x-mail::message>
# Atur Ulang Password

Anda menerima email ini karena kami telah menerima permintaan mengatur ulang password untuk akun Anda.

##### [Atur Ulang Password]({{ route('password.reset', $token) }})

Tautan Pengaturan Ulang Password ini hanya berlaku hingga : 
# {{ Carbon\Carbon::parse($expiry_time)->isoFormat('D MMMM Y, HH:mm:ss') }}

Abaikan email ini jika Anda merasa anda tidak melakukan permintaan untuk mengatur ulang password.

Terima kasih,   
**{{ config('app.name') }}**   

---

#### Jika Anda mengalami kendala dengan tombol "Atur Ulang Password", mohon salin dan tempel URL di bawah ini ke web browser Anda: [{{ route('password.reset', $token) }}]({{ route('password.reset', $token) }})
</x-mail::message>