@props(['url'])
<tr>
    <td class="header" style="">
        <a href="{{ $url }}" style="width: 100%; display: flex; justify-content: flex-start; align-items: center; gap: 10px !important; padding-left: 16px; padding-right: 16px;">
            <img src="{{ asset('logo/KEMENHUB64.png') }}" style="width: 40px; height: 40px;" alt="KEMENHUB Logo">
            <div style="margin-top: 4px; text-align: left !important; line-height: 1.375rem;">
                <span>
                    {{ $slot }}
                </span>
                <p style="font-size: 6.2pt; margin-top: 4px">KEMENTERIAN PERHUBUNGAN REPUBLIK INDONESA</p>
            </div>
        </a>
    </td>
</tr>
