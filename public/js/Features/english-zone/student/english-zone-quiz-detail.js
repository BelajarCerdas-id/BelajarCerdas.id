function quizDetailView() {
    const container = document.getElementById('container-list-quiz');
    if (!container) return;

    const levelId = container.dataset.levelId;
    if (!levelId) return;

    fetchQuizDetailView(levelId);

    function fetchQuizDetailView(levelId) {
        $.ajax({
            url: `/english-zone/${levelId}/quiz-detail/fetch`,
            method: 'GET',
            success: function (response) {
                const containerCard = $('#grid-list-quiz');
                containerCard.empty();

                const readingPracticeTest = response.readingPracticeTest.replace(':levelId', levelId);
                const readingExamTest = response.readingExamTest.replace(':levelId', levelId);
                const listeningPracticeTest = response.listeningPracticeTest.replace(':levelId', levelId);
                const listeningExamTest = response.listeningExamTest.replace(':levelId', levelId);
                const writingPracticeTest = response.writingPracticeTest.replace(':levelId', levelId);

                const card = `
                    <!-- Reading -->
                    <div class="bg-white shadow-md hover:shadow-xl transition-all duration-300 rounded-xl border border-gray-200 py-6 px-5 flex flex-col h-full group">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fa-solid fa-book-open bg-[#3BA55D] text-white w-8 h-8 flex items-center justify-center rounded-full text-sm"></i>
                                <span>
                                    <p class="text-sm font-semibold text-[#3BA55D]">Reading</p>
                                    <p class="text-xs font-bold opacity-70"> ${response.data ?? '-'} </p>
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-tight">
                                Memahami teks bacaan dan meningkatkan vocabulary.
                            </p>
                        </div>

                        <div class="flex gap-2 mt-auto pt-5">
                            <div class="w-full">
                                <a href="${readingPracticeTest}">
                                    <button
                                        class="bg-[#3BA55D] hover:bg-[#2E8D4A] text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                        Latihan
                                    </button>
                                </a>
                            </div>
                            <div class="w-full">
                                <a href="${readingExamTest}">
                                    <button
                                        class="border border-[#3BA55D] text-[#3BA55D] hover:bg-[#3BA55D] hover:text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                        Ujian
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Writing -->
                    <div class="bg-white shadow-md hover:shadow-xl transition-all duration-300 rounded-xl border border-gray-200 py-6 px-5 flex flex-col h-full group">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fa-solid fa-pencil bg-[#C9A33B] text-white w-8 h-8 flex items-center justify-center rounded-full text-sm"></i>
                                <span>
                                    <p class="text-sm font-semibold text-[#C9A33B]">Writing</p>
                                    <p class="text-xs font-bold opacity-70"> ${response.data ?? '-'} </p>
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-tight">
                                Latihan menulis kalimat & struktur grammar.
                            </p>
                        </div>

                        <div class="flex gap-2 mt-auto pt-5">
                            <div class="w-full">
                                <a href="${writingPracticeTest}">
                                    <button
                                        class="bg-[#C9A33B] hover:bg-[#A58931] text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                        Latihan
                                    </button>
                                </a>
                            </div>
                            <button
                                class="border border-[#C9A33B] text-[#C9A33B] hover:bg-[#C9A33B] hover:text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                Ujian
                            </button>
                        </div>
                    </div>

                    <!-- Listening -->
                    <div class="bg-white shadow-md hover:shadow-xl transition-all duration-300 rounded-xl border border-gray-200 py-6 px-5 flex flex-col h-full group">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fa-solid fa-headphones bg-[#4189E0] text-white w-8 h-8 flex items-center justify-center rounded-full text-sm"></i>
                                <span>
                                    <p class="text-sm font-semibold text-[#4189E0]">Listening</p>
                                    <p class="text-xs font-bold opacity-70"> ${response.data ?? '-'} </p>
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-tight">
                                Latihan memahami audio bahasa Inggris.
                            </p>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex gap-2 mt-auto pt-5">
                            <div class="w-full">
                                <a href="${listeningPracticeTest}">
                                    <button
                                        class="bg-[#4189E0] hover:bg-[#3573BA] text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                        Latihan
                                    </button>
                                </a>
                            </div>
                            <div class="w-full">
                                <a href="${listeningExamTest}">
                                    <button
                                        class="border border-[#4189E0] text-[#4189E0] hover:bg-[#4189E0] hover:text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                        Ujian
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Speaking -->
                    <div class="bg-white shadow-md hover:shadow-xl transition-all duration-300 rounded-xl border border-gray-200 py-6 px-5 flex flex-col h-full group">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fa-solid fa-microphone bg-[#E05A47] text-white w-8 h-8 flex items-center justify-center rounded-full text-sm"></i>
                                <span>
                                    <p class="text-sm font-semibold text-[#E05A47]">Speaking</p>
                                    <p class="text-xs font-bold opacity-70"> ${response.data ?? '-'} </p>
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 leading-tight">
                                Tingkatkan kemampuan berbicara & pronunciation.
                            </p>
                        </div>

                        <div class="flex gap-2 mt-auto pt-5">
                            <button
                                class="bg-[#E05A47] hover:bg-[#C84B3A] text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                Latihan
                            </button>
                            <button
                                class="border border-[#E05A47] text-[#E05A47] hover:bg-[#E05A47] hover:text-white px-4 py-1.5 rounded-lg text-xs font-semibold w-full transition">
                                Ujian
                            </button>
                        </div>
                    </div>
                `;

                containerCard.append(card);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }
}

$(document).ready(function () {
    quizDetailView();
});