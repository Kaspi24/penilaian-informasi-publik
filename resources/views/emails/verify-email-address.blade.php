<x-mail::message>
# Verifikasi Alamat Email Anda

Berikut adalah 6 digit kode untuk verifikasi alamat email Anda :

###### {{ $token }}

Kode ini hanya berlaku hingga : 
# {{ Carbon\Carbon::parse($expiry_time)->isoFormat('D MMMM Y, HH:mm:ss') }}

Harap segera selesaikan verifikasi untuk dapat mulai mengisi kuesioner.

Terima kasih,   
**{{ config('app.name') }}**
</x-mail::message>