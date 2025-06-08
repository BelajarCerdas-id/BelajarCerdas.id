// untuk mendengarkan event broadcast ketika administrator insert atau update tanya access
document.addEventListener("DOMContentLoaded", () => {
    window.Echo.channel('tanyaAccess')
    .listen('.tanya.access', (e) => {
        fetchDataTanyaAccess();
    })
});
