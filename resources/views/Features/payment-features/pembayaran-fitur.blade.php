@if ($nama_fitur === 'TANYA')
    @include('Features/payment-features/partials/tanya-coin-package')
@elseif ($nama_fitur === 'Soal dan Pembahasan')
    @include('Features/payment-features/partials/soal-pembahasan-package')
@else
    <!-- jika tidak ada fitur -->
@endif
