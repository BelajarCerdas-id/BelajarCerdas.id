function modalLogin(element, dummyId) {
    const modal = document.getElementById('my_modal_1');
    const input = document.getElementById('email');


    modal.showModal();
    // Alihkan fokus agar browser tidak auto-focus ke input pertama
    setTimeout(() => {
        dummy?.focus();
    }, 1); // Delay supaya browser sempat jalanin autofokus-nya dulu
}


document.getElementById('submit').addEventListener('click', function() {
    const form = document.querySelector('#my_modal_1 form[action]');
    form.submit(); // Submit the form
});

