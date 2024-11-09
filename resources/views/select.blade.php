<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <title>Laravel Chained Dropdown</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        .input {
            font-size: 18;
            padding: 15px 25px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container col-xxl-6 py-5">

        <div class="card bg-white rounded-4 p-3 border shadow">

            <div class="form-group mb-3">
                <label class="form-label">Pilih Kategori</label>
                <select name="kategori" id="kategori" class="form-select input">
                    <option value="">Pilih</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->kode }}">{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Barang</label>
                <select name="barang" id="barang" class="form-select input"></select>
            </div>

        </div>
    </div>


    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    {{-- <script>
        $(document).ready(function() {
            $('#kategori').on('change', function() {
                var kode_kategori = $(this).val();
                // console.log(kode_kategori);
                if (kode_kategori) {
                    $.ajax({
                        url: '/barang/' + kode_kategori,
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                        }
                    });
                } else {

                }
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $('#kategori').on('change', function() {
                var kode_kategori = $(this).val();
                // console.log(kode_kategori);
                if (kode_kategori) {
                    $.ajax({
                        url: '/barang/' + kode_kategori,
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            // console.log(data);
                            if (data) {
                                $('#barang').empty();
                                $('#barang').append('<option value="">-Pilih-</option>');
                                $.each(data, function(key, barang) {
                                    $('select[name="barang"]').append(
                                        '<option value="' + barang.id + '">' +
                                        barang.nama_barang + '</option>'
                                    );
                                });
                            } else {
                                $('#barang').empty();
                            }
                        }
                    });
                } else {
                    $('#barang').empty();
                }
            });
        });
    </script>
</body>

</html>
