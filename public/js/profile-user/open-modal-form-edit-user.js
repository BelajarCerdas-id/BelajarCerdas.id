function editPersonalInformation(element, id) {
    const modal = document.getElementById('my_modal_1_' + id);
    const form = element.getAttribute('data-form-edit-personal-user');

    modal.showModal();
}

function editPendidikan(element, id) {
    const modal = document.getElementById('my_modal_2_' + id);
    const form = element.getAttribute('data-form-edit-pendidikan-user');

    modal.showModal();
}

function editReferralCodeStudent(element, id) {
    const modal = document.getElementById('my_modal_3_' + id);
    const form = element.getAttribute('data-form-edit-referral-code-student');

    modal.showModal();
}
