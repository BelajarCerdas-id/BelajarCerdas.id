window.addEventListener("beforeunload", function () {
    const questionId = document.getElementById('question-answer')?.getAttribute('data-question-id');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    // Cek apakah ini adalah refresh
    const navigationEntry = performance.getEntriesByType('navigation')[0];
    const isReload = navigationEntry
        ? navigationEntry.type === 'reload'
        : performance.navigation.type === 1; // Fallback untuk browser lama

    if (!questionId || !csrf) {
        return; // ⛔ Skip jika refresh atau tidak ada data
    }

    navigator.sendBeacon(
        `/tanya/${questionId}/mark-viewed-back-button`,
        new Blob(
            [JSON.stringify({
                _token: csrf,
                _method: "PUT"
            })],
            {
                type: 'application/json'
            }
        )
    );
});
