function leaderboardRankTanyaStudent() {
    $.ajax({
        url: '/leaderboard-rank-tanya-student',
        method: 'GET',
        success: function (data) {
            $('#tbody-leaderboard-rank-tanya-student').empty();
            $('.pagination-container-leaderboard-rank-tanya-student').empty();

            const container = $('#container-leaderboard-rank-tanya-user');
            container.empty();

            if (data.data.length > 0) {
                let rankUser = ``;

                if (data.rankingTanyaUser == 1) {
                    rankUser = `
                        <i class='fa-solid fa-crown text-yellow-400 font-bold text-lg'></i>
                    `
                } else if (data.rankingTanyaUser == 2) {
                    rankUser = `
                        <i class='fa-solid fa-crown text-gray-400 font-bold text-lg'></i>
                    `
                } else if (data.rankingTanyaUser == 3) {
                    rankUser = `
                        <i class='fa-solid fa-crown text-amber-800 font-bold text-lg'></i>
                    `
                } else {
                    rankUser = `
                        ${data.rankingTanyaUser ?? '?'}
                    `
                }

                $.each(data.data, function (index, item) {
                    $('#tbody-leaderboard-rank-tanya-student').append(`
                        <tr>
                            <td class="border border-gray-300 px-3 py-2 text-center">${item.rankIcon ?? ''}</td>
                            <td class="border border-gray-300 px-3 py-2 text-center">${item.student_profiles?.nama_lengkap ?? ''}</td>
                            <td class="border border-gray-300 px-3 py-2 text-center">${item.student_profiles?.kelas?.kelas ?? ''}</td>
                            <td class="border border-gray-300 px-3 py-2 text-center">${item.student_profiles?.sekolah ?? ''}</td>
                            <td class="border border-gray-300 px-3 py-2 text-center">${item.jumlah_koin ?? 0}</td>
                            <td class="border border-gray-300 px-3 py-2 text-center">${item.jumlah_tanya ?? ''}</td>
                        </tr>
                    `);
                });
                // Insert pagination HTML
                $('.pagination-container-leaderboard-rank-tanya-student').html(data.links);
                $('#empty-message-leaderboard-rank-tanya-student').hide();
                $('.thead-table-leaderboard-rank-tanya-student').show();

                const card = `
                    <div class="flex items-center gap-4 h-full">
                        <div class="text-sm sm:text-base text-gray-700 border-r-2 h-full flex flex-col items-center justify-center border w-17 hidden xl:flex">
                            <span class="font-bold opacity-70 text-sm">Rank :</span>
                            <span class="font-semibold">
                                ${rankUser}
                            </span>
                        </div>
                        <div class="text-md font-bold opacity-70 ml-2 flex justify-center w-full xl:w-auto">
                            ${data.user.student_profiles?.nama_lengkap ?? ''}
                        </div>
                    </div>

                    <div class="flex justify-between w-full border-t py-2 xl:hidden">
                        <div class="text-sm sm:text-base text-gray-700 border-r-2 h-full flex flex-col items-center justify-center w-17 w-full">
                            <span class="font-bold opacity-70 text-sm">Rank :</span>
                            <span class="font-semibold">
                                ${rankUser}
                            </span>
                        </div>
                        <div
                            class="text-sm sm:text-base text-gray-700 w-45 h-full px-4 flex flex-col items-center justify-center w-full">
                                <span class="font-bold opacity-70 text-sm">Total berTANYA:</span>
                                <span class="font-bold opacity-70">${data.countDataTanyaUserLogin ?? 0}</span>
                        </div>
                    </div>

                    <div
                        class="text-sm sm:text-base text-gray-700 border-l-2 w-45 h-full px-4 flex flex-col items-center justify-center hidden xl:flex">
                            <span class="font-bold opacity-70 text-sm">Total berTANYA:</span>
                            <span class="font-bold opacity-70">${data.countDataTanyaUserLogin ?? 0}</span>
                    </div>
                `;
                container.append(card);
            } else {
                $('#empty-message-leaderboard-rank-tanya-student').show();
                $('.thead-table-leaderboard-rank-tanya-student').hide();
            }
        }
    });
}

$(document).ready(function () {
    leaderboardRankTanyaStudent();
});
