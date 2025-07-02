function paginateBankSoalDetail() {
    const container = document.getElementById('container-bank-soal-detail');
    if (!container) return;

    const subBabName = container.dataset.subBab;
    const subBabId = container.dataset.subBabId;
    if (!subBabName) return;
    if (!subBabId) return;

    fetchFilteredDataBankSoalDetail(subBabName, subBabId);

    function fetchFilteredDataBankSoalDetail(subBab, subBabId) {
        $.ajax({
            url: `/soal-pembahasan/paginate/bank-soal/${subBabName}/${subBabId}`,
            method: 'GET',
            // data: {
            //     page: page // Include the page parameter
            // },
            success: function (response) {
                const containerQuestion = $('#grid-list-soal');
                containerQuestion.empty();

                if (response.data.length > 0) {
                    response.data.forEach((group, index) => {
                        // Ambil item pertama buat pertanyaan
                        const first = group[0]; // Karena setiap group itu array dari soal yang sama

                        // Mengiterasi setiap opsi dari soal tersebut
                        // Mengiterasi setiap opsi dari soal tersebut
                        function addClassToImgTags(html, className) {
                            return html
                                .replace(/<img\b(?![^>]*class=)[^>]*>/g, (imgTag) => {
                                    // Tambahkan class jika belum ada atribut class
                                    return imgTag.replace('<img', `<img class="${className}"`);
                                })
                                .replace(/<img\b([^>]*?)class="(.*?)"/g, (imgTag, before, existingClasses) => {
                                    // Tambahkan class ke img yang sudah punya class
                                    return `<img ${before}class="${existingClasses} ${className}"`;
                                });
                        }

                        const optionsHTML = group.map((item) => {
                            const containsImage = /<img\s+[^>]*src=/.test(item.options_value);
                            let content = item.options_value;
                            let optionsValue = '';

                            // Tambahkan class img jika ada gambar
                            if (containsImage) {
                                content = addClassToImgTags(item.options_value, 'max-w-[120px] rounded my-2');
                            }

                            // cek apakah optionsValue mengandung image
                            if (containsImage) {
                                optionsValue = `
                                    <div class="max-w-7xl border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px]
                                            ${item.options_key === item.answer_key ? 'border-green-400 bg-green-400 text-white font-bold' : ''}">
                                            <div class="font-bold min-w-[30px]">${item.options_key}.</div>
                                            <div class="w-full">${content}</div>
                                    </div>
                                `;
                            } else {
                                optionsValue = `
                                    <div class="max-w-7xl border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex gap-[4px]
                                        ${item.options_key === item.answer_key ? 'border-green-400 bg-green-400 text-white font-bold' : ''}">
                                        ${item.options_key}. ${content}
                                    </div>
                                `;
                            }

                            return `
                                ${optionsValue}
                            `;
                        }).join('');

                        // Ambil videoId yang sesuai dengan index pada masing" group soal
                        const videoId = response.videoIds[index];

                        // Tampilkan video jika explanation itu adalah link video, jika tidak tampilkan explanation teks
                        const videoExplanation = videoId ? `
                            <div class="border max-w-sm !h-60 flex justify-start">
                                <div class="w-full h-full">
                                    <iframe class="w-full h-full"
                                        src="https://www.youtube.com/embed/${videoId}"
                                        frameborder="0"
                                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        ` : `<div class="w-max flex flex-col items-start gap-4">${first.explanation}</div>`;

                        // untuk memisahkan teks sebelum dengan img dan text setelah img
                        const splitQuestions = first.questions.split('<img'); // split sebelum <img>
                        const questionTextOnly = splitQuestions[0]; // sebelum <img> ( [0] dan [1] digunakan untuk memisahkan 2 element berbeda )

                        // Inisialisasi variabel kosong untuk menampung elemen gambar dan teks setelah gambar
                        let questionImage = '', textAfterImage = '';

                        // Cek apakah hasil split punya bagian setelah <img (artinya ada gambar)
                        if (splitQuestions.length > 1) {
                            const imgSplit = splitQuestions[1].split('>'); // pisahkan tag <img> dan sisa teks
                            const imgTag = imgSplit[0]; // bagian src dan atribut gambar
                            const restText = imgSplit.slice(1).join('>'); // gabungkan sisa setelah tag img

                            questionImage = `<img class="max-w-[25%]" ${imgTag}>`; // Susun tag <img> lengkap dengan class tambahan
                            textAfterImage = restText.trim(); // Hapus spasi berlebih pada teks setelah gambar
                        }

                        // Gabungkan menjadi HTML: bungkus gambar dan teks
                        const questionHTML = `
                            <div class="flex flex-col gap-10 items-start">
                                ${questionImage}
                                <div>${textAfterImage}</div>
                            </div>
                        `;

                        // untuk edit soal
                        let editQuestion = response.editQuestion.replace(':subBab', subBab).replace(':subBabId', subBabId).replace(':id', first.id);

                        const card = `

                            <div class="w-full flex justify-end gap-2 items-center">
                                <a href="${editQuestion}" class="w-max cursor-pointer text-sm text-[#4189e0] font-bold mx-2 mt-5">
                                    <span>Edit</span>
                                    <i class="fas fa-pen"></i>
                                </a>
                            </div>

                            <div class="wrapper-content-accordion-questions !mt-2">

                                <div class="toggleButton-questions">
                                    <div class="flex gap-1 max-w-[1450px]">
                                        <span>${index + 1}.</span>
                                        <span class="w-full">${questionTextOnly}</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-up icon"></i>
                                </div>

                                <div class="content-accordion">
                                    <div class="max-w-7xl text-sm mt-6">
                                        <div class="">${questionHTML}</div>
                                        <div>${optionsHTML}</div>
                                        <div class="flex flex-col gap-6 mb-8 mt-6">
                                            <div>
                                                <span class="font-bold opacity-70">Jawaban Benar:</span>
                                                <span class="font-bold text-green-400">${first.answer_key}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold opacity-70 mb-4">Penjelasan:</p>
                                                ${videoExplanation}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        containerQuestion.append(card);
                    });

                    initAccordion();
                    $('.pagination-container-bank-soal-detail').html(response.links);
                    // bindPaginationLinks(subBab, subBabId);
                    $('#emptyMessageBankSoalDetail').hide();
                    $('.thead-table-bank-soal-detail').show();
                } else {
                    $('#emptyMessageBankSoalDetail').show();
                    $('.thead-table-bank-soal-detail').hide();
                }
            }

        })
    }
    // function bindPaginationLinks(subBab, subBabId) {
    //     $('.pagination-container-bank-soal-detail').off('click', 'a').on('click', 'a', function(event) {
    //         event.preventDefault(); // Cegah perilaku default link
    //         const page = new URL(this.href).searchParams.get('page'); // Dapatkan nomor halaman dari link
    //         fetchFilteredDataBankSoalDetail(subBab, subBabId, page); // Ambil data yang difilter untuk halaman yang ditentukan
    //     });
    // }
}

$(document).ready(function () {
    paginateBankSoalDetail();
});

                        // Mengiterasi setiap opsi dari soal tersebut
                        // function addClassToImgTags(html, className) {
                        //     return html
                        //         .replace(/<img\b(?![^>]*class=)[^>]*>/g, (imgTag) => {
                        //             // Tambahkan class jika belum ada atribut class
                        //             return imgTag.replace('<img', `<img class="${className}"`);
                        //         })
                        //         .replace(/<img\b([^>]*?)class="(.*?)"/g, (imgTag, before, existingClasses) => {
                        //             // Tambahkan class ke img yang sudah punya class
                        //             return `<img ${before}class="${existingClasses} ${className}"`;
                        //         });
                        // }

                        // const optionsHTML = group.map((item) => {
                        //     const containsImage = /<img\s+[^>]*src=/.test(item.options_value);
                        //     let content = item.options_value;

                        //     // Tambahkan class img jika ada gambar
                        //     if (containsImage) {
                        //         content = addClassToImgTags(item.options_value, 'max-w-[120px] rounded my-2');
                        //     }

                        //     return `
                        //         <div class="${containsImage ? 'max-w-7xl' : 'max-w-7xl'} border border-gray-300 rounded-md p-2 px-4 mb-4 text-sm my-6 flex flex-start gap-[4px]
                        //             ${item.options_key === item.answer_key ? 'border-green-400 bg-green-400 text-white font-bold' : ''}">
                        //             <div class="font-bold min-w-[30px]">${item.options_key}.</div>
                        //             <div class="w-full">${content}</div>
                        //         </div>`;
                        // }).join('');
