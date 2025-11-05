const toggles = document.getElementsByClassName('toggleButton');
const contentDiv = document.getElementsByClassName('content-accordion');
const icons = document.getElementsByClassName('icon');

// ✅ Buka accordion pertama secara default
if (contentDiv.length > 0) {
    contentDiv[0].style.height = contentDiv[0].scrollHeight + "px";
    icons[0].classList.remove('fa-chevron-up');
    icons[0].classList.add('fa-chevron-down');
}

document.addEventListener("click", function (e) {
    const toggle = e.target.closest('.toggleButton');
    if (!toggle) return;

    for (let i = 0; i < toggles.length; i++) {
        if (toggles[i] === toggle) {
            if (contentDiv[i].style.height && contentDiv[i].style.height !== "0px") {
                contentDiv[i].style.height = "0px";
                toggles[i].style.color = "#111130";
                icons[i].classList.remove('fa-chevron-down');
                icons[i].classList.add('fa-chevron-up');
            } else {
                contentDiv[i].style.height = contentDiv[i].scrollHeight + "px";
                toggles[i].style.color = "";
                icons[i].classList.remove('fa-chevron-up');
                icons[i].classList.add('fa-chevron-down');
            }

            for (let j = 0; j < contentDiv.length; j++) {
                if (j !== i) {
                    contentDiv[j].style.height = "0px";
                    toggles[j].style.color = "#111130";
                    icons[j].classList.remove('fa-chevron-down');
                    icons[j].classList.add('fa-chevron-up');
                }
            }
        }
    }
});
// }
