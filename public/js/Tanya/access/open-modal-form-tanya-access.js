    function uploadJadwalAccess(element) {
        let modal = document.getElementById("my_modal_1");
        modal.showModal();

        flatpickr("#datepicker-insert-tanggal-mulai", {
            dateFormat: "Y-m-d",
            altInput: false,
            altFormat: "j F, Y",
            appendTo: document.querySelector(".modal-box"),
            position: "below",
            static: true,
            theme: "dark",
            minDate: 'today',
            clickOpens: true, // Mencegah focus otomatis pada input type date
            disableMobile: true // untuk mencegah datepicker bawaan browser mobile, agar tetap menggunakan Flatpickr meskipun di HP
        });

        flatpickr("#datepicker-insert-tanggal-akhir", {
            dateFormat: "Y-m-d",
            altInput: false,
            altFormat: "j F, Y",
            appendTo: document.querySelector(".modal-box"),
            position: "below",
            static: true,
            theme: "dark",
            minDate: 'today',
            clickOpens: true, // Mencegah focus otomatis pada input type date
            disableMobile: true
        });
    }

    function editAccess(element) {
        let modal = document.getElementById("my_modal_2");
        let tanggalMulai = element.getAttribute("data-tanggal-mulai");
        let tanggalAkhir = element.getAttribute("data-tanggal-akhir");

        modal.showModal();

        // document.getElementById("datepicker-tanggal-mulai").value = tanggalMulai;
        // document.getElementById("datepicker-tanggal-akhir").value = tanggalAkhir;

        // Inisialisasi Flatpickr pada input
        flatpickr("#datepicker-tanggal-mulai", {
            dateFormat: "Y-m-d",
            altInput: false,
            altFormat: "j F, Y",
            appendTo: document.querySelector(".modal-box"),
            position: "below",
            static: true,
            theme: "dark",
            minDate: 'today',
            defaultDate: tanggalMulai, // Set nilai default dari data
            clickOpens: true,
            disableMobile: true
        });

        flatpickr("#datepicker-tanggal-akhir", {
            dateFormat: "Y-m-d",
            altInput: false,
            altFormat: "j F, Y",
            appendTo: document.querySelector(".modal-box"),
            position: "below",
            static: true,
            theme: "dark",
            minDate: 'today',
            defaultDate: tanggalAkhir, // Set nilai default dari data
            clickOpens: true,
            disableMobile: true
        });
    }

    function historyTanyaAccess(element) {
        const modal = document.getElementById('my_modal_3');
        const namaLengkap = element.getAttribute('data-nama_lengkap');
        const status = element.getAttribute('data-status');
        const updatedAt = element.getAttribute('data-updated_at');

        document.getElementById('text-nama_lengkap').innerText = namaLengkap;
        document.getElementById('text-status').innerText = status;
        document.getElementById('text-updated_at').innerText = updatedAt;

        modal.showModal();
    }
