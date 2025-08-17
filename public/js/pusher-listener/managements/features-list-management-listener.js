document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel('featuresList')
        .listen('.features.list', (e) => {
            paginateFeaturesList();
    });
});
