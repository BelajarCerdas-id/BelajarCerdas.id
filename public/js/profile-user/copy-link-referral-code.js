    function copyReferralLink() {
        const inputValue = document.getElementById('referral-link');
        const kodeReferral = inputValue.value;
        const baseUrl = inputValue.dataset.baseUrl;
        const fullLink = `${baseUrl}?ref=${kodeReferral}`;

        // Copy link
        const tempInput = document.createElement('input');
        tempInput.value = fullLink;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Bikin toast baru
        const toastContainer = document.getElementById('toast-container');
        const newToast = document.createElement('div');
        newToast.className = 'bg-green-400 text-white py-2 px-4 rounded-md shadow-lg text-sm animate-fadeIn';
        newToast.innerText = 'Link referral berhasil disalin!';

        toastContainer.appendChild(newToast);

        // Auto-hapus toast setelah 3 detik
        setTimeout(() => {
            newToast.remove();
        }, 3000);

        // Scroll otomatis ke bawah kalau banyak
        toastContainer.scrollTop = toastContainer.scrollHeight;
    }
