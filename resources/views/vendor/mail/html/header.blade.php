@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: flex; justify-content: center; align-items: center; gap: 8px">
            <img src="{{ asset('logo/KEMENHUB.png') }}" style="width: 32px; height: auto;" alt="KEMENHUB Logo">
            <div style="margin-top: 4px">
                {{ $slot }}
                <p style="font-size: 6.2pt;">KEMENTERIAN PERHUBUNGAN REPUBLIK INDONESA</p>
            </div>
        </a>
    </td>
</tr>
