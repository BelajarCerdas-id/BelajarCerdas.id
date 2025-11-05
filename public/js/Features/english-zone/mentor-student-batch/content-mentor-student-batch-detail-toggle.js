var materi = document.getElementById('content-materi');
var studentList = document.getElementById('content-student-list');
function contentMateri() {
    studentList.style.display = "none";
    materi.style.display = "block";
}

function contentStudentList() {
    materi.style.display = "none";
    studentList.style.display = "block";
}
