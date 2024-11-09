function contentUnanswered() {
    document.getElementById('contentUnanswered').style.display = 'block';
    document.getElementById('contentAnswer').style.display = 'none';
    document.getElementById('contentReject').style.display = 'none';
}

function contentAnswer() {
    document.getElementById('contentUnanswered').style.display = 'none';
    document.getElementById('contentAnswer').style.display = 'flex';
    document.getElementById('contentReject').style.display = 'none';
}

function contentReject() {
    document.getElementById('contentUnanswered').style.display = 'none';
    document.getElementById('contentAnswer').style.display = 'none';
    document.getElementById('contentReject').style.display = 'block';
}
                    