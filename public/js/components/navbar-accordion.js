// open navbar
var navbar = document.querySelector('.navbar-component');
var open = document.getElementById('Show');
var close = document.getElementById('Hide');

function openNavbar() {
    navbar.style.display = "block";
    open.style.display = "none";
    close.style.display = "block";
}

function hideNavbar() {
    close.style.display = "none";
    open.style.display = "block";
    navbar.style.display = "none";
}

// open accordion navbar
let items = document.querySelectorAll('#accordion .item .header');
    let lastClickedItem = null; // Variabel untuk melacak item terakhir yang diklik

    items.forEach((item) => {
        item.addEventListener("click", (e) => {
            const clickedItem = e.currentTarget.closest(".item");

            // Jika item yang diklik adalah item terakhir yang diklik, maka toggle class active
            if (lastClickedItem === clickedItem) {
                // Hapus active class dari item terakhir yang diklik
                clickedItem.classList.remove('active');
                lastClickedItem = null; // Reset variabel item terakhir yang diklik
            } else {
                // Hapus active class dari semua item
                items.forEach((header) => {
                    header.closest('.item').classList.remove('active');
                });

                // Tambahkan active class ke item yang diklik
                clickedItem.classList.add('active');
                lastClickedItem = clickedItem; // Update variabel item terakhir yang diklik
            }
    });
});
