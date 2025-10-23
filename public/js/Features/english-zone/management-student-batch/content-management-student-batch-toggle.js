var nonSchoolPartner = document.getElementById('content-non-school-partner');
var schoolPartner = document.getElementById('content-school-partner');
function contentNonSchoolPartner() {
    schoolPartner.style.display = "none";
    nonSchoolPartner.style.display = "block";
    
    paginateStudentBatchNonSchoolPartner();
}

function contentSchoolPartner() {
    nonSchoolPartner.style.display = "none";
    schoolPartner.style.display = "block";
    
    paginateStudentBatchSchoolPartner();
}
