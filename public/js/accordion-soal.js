    let toggles = document.getElementsByClassName('toggleButton-pengayaan');
    let contentDiv = document.getElementsByClassName('content-accordion');
    let icons = document.getElementsByClassName('icon');

    //ini buat buka accordion nya
    for (let i = 0; i < toggles.length; i++) {
        toggles[i].addEventListener('click', () => {
            if (parseInt(contentDiv[i].style.height) !=
                contentDiv[i].scrollHeight) {
                contentDiv[i].style.height = contentDiv[i].scrollHeight + "px";
                [i].scrollHeight + "px";
                toggles[i].style.color = "";
                icons[i].classList.remove('fa-chevron-up');
                icons[i].classList.add('fa-chevron-down');
            }
            //else ini buat nutup accordion nya
            else {
                contentDiv[i].style.height = "0px";
                toggles[i].style.color = "#111130";
                icons[i].classList.remove('fa-chevron-down');
                icons[i].classList.add('fa-chevron-up');
            }

            for (let j = 0; j < contentDiv.length; j++) {
                if (j !== i) {
                    contentDiv[j].style.height = "0px";
                    toggles[j].style.color = "#111130";
                    icons[j].classList.remove('fa-chevron-down');
                    icons[j].classList.add('fa-chevron-up');
                }
            }
        });
    }
