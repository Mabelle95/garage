<table>
    <tr>
        <td>destination</td>
        <td>{{ $payWay }}</td>
    </tr>
    <tr>
        <td>Total a payer</td>
        <td>{{ $total }}</td>
    </tr>
    <tr>
        <td>Net a payer </td>
        <td>{{ $montant }}</td>
    </tr>
</table>

<p>le reste a payer a la livraison est : {{ $total - $montant }}</p>
{{ $shortCode }} - 
<a href="tel:{{ $shortCode }}">
{{-- icone de tel portable --}}
<img src="{{ asset('images/phone-icon.png') }}" alt="Composer" style="width:24px;height:24px;">
</a>