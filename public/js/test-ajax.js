$(document).ready(function () {
    // 1. Memuat data laporan melalui AJAX
    function loadData(id) {
        $.ajax({
            url: `/laporan/${id}/data`,  // Memanggil route untuk mengambil data
            type: 'GET',
            success: function (response) {
                let dataHtml = "<div class='overflow-x-auto'><table class='table'>";
                dataHtml += '<tbody>';

                response.laporan.forEach(item => {
                    // Status apakah Diterima atau Ditolak
                    let statusButton = '';
                    if (response.statusStar[item.id]) {
                        let status = response.statusStar[item.id].status;
                        statusButton = `<button class="btn btn-${status === 'Diterima' ? 'success' : 'danger'}">${status}</button>`;
                    } else {
                        statusButton = `
                            <button class="btn btn-success accept-btn" data-id="${item.id}" data-email="${item.email_mentor}">Terima</button>
                            <button class="btn btn-danger reject-btn" data-id="${item.id}" data-email="${item.email_mentor}">Tolak</button>
                        `;
                    }

                    dataHtml += `
                        <tr>
                            <td>${item.nama_lengkap}</td>
                            <td>${item.kelas}</td>
                            <td>${item.mapel}</td>
                            <td>${item.bab}</td>
                            <td>${item.pertanyaan}</td>
                            <td>${item.jawaban}</td>
                            <td>${item.status}</td>
                            <td>Lihat</td>
                            <td>${statusButton}</td>
                        </tr>
                    `;
                });

                dataHtml += '</tbody></table></div>';
                // Menambahkan form input setelah tabel
                dataHtml += `
                    <div class="mt-4">
                        <h4>Tambah Mentor</h4>
                        <form id="mentorForm">
                            <label for="nama_mentor">Nama Mentor:</label>
                            <input type="text" id="nama_mentor" name="nama_mentor" required><br><br>
                            
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required><br><br>
                            
                            <label for="sekolah">Sekolah:</label>
                            <input type="text" id="sekolah" name="sekolah" required><br><br>
                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                `;

                $('#dataContainer').html(dataHtml);  // Memasukkan data ke dalam #dataContainer
            },
            error: function (xhr, status, error) {
                console.error('Error loading data: ' + error);
            }
        });
    }

    // Panggil loadData dengan ID yang sesuai (misal ID mentor)
    const mentorId = 95; // Ganti sesuai ID mentor yang aktif
    loadData(mentorId);

    // 2. Handle tombol "Terima" via AJAX
    $(document).on('click', '.accept-btn', function () {
        let id = $(this).data('id');
        let email = $(this).data('email');
        $.ajax({
            url: `/laporan/${id}/terima`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token
                email: email
            },
            success: function (response) {
                $('#responseMessage').html('<p>' + response.message + '</p>');
                loadData(mentorId);  // Reload data setelah aksi
            },
            error: function (xhr, status, error) {
                console.error('Error accepting: ' + error);
            }
        });
    });

    // 3. Handle tombol "Tolak" via AJAX
    $(document).on('click', '.reject-btn', function () {
        let id = $(this).data('id');
        let email = $(this).data('email');
        $.ajax({
            url: `/laporan/${id}/tolak`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token
                email: email
            },
            success: function (response) {
                $('#responseMessage').html('<p>' + response.message + '</p>');
                loadData(mentorId);  // Reload data setelah aksi
            },
            error: function (xhr, status, error) {
                console.error('Error rejecting: ' + error);
            }
        });
    });

    // 4. Handle submit form input untuk menambah mentor
    $(document).on('submit', '#mentorForm', function (e) {
        e.preventDefault();  // Mencegah form submit biasa

        let nama_mentor = $('#nama_mentor').val();
        let email = $('#email').val();
        let sekolah = $('#sekolah').val();

        $.ajax({
            url: '/mentor/store',  // Pastikan route sesuai dengan route yang ada di Laravel
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nama_mentor: nama_mentor,
                email: email,
                sekolah: sekolah
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('#responseMessage').html('<p>' + response.message + '</p>');
                    loadData(mentorId);  // Reload data setelah aksi
                    $('#mentorForm')[0].reset();  // Reset form setelah submit
                } else {
                    $('#responseMessage').html('<p>Error: ' + response.message + '</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error submitting form: ' + error);
            }
        });
    });
});
