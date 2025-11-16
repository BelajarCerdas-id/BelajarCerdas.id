var materi = document.getElementById('content-materi');
var studentList = document.getElementById('content-attendance-history');
function contentMateri() {
    studentList.style.display = "none";
    materi.style.display = "block";
}

function contentAttendanceHistory() {
    materi.style.display = "none";
    studentList.style.display = "block";
}
