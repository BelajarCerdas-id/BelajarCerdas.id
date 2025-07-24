    const paymentContent = document.querySelectorAll(".content-method-payment");

    paymentContent.forEach((item, index) => {
        let header = item.querySelector("header");
        header.addEventListener("click", () => {
            item.classList.toggle("open");

            let choosePayment = item.querySelector(".choose-payment");
            if (item.classList.contains("open")) {
                choosePayment.style.height =
                    `${choosePayment.scrollHeight}px`; // scrollHeight prperty returns the height of an element including padding, but excluding borders, scrollbar or margin.
                item.querySelector("i").classList.replace("fa-plus", "fa-minus");
            } else {
                choosePayment.style.height = "0px";
                item.querySelector("i").classList.replace("fa-minus", "fa-plus");
            }
            removeOpen(
                index); // calling the function and also passing the index number of the clicked header
        })

    })

    function removeOpen(index1) {
        paymentContent.forEach((item2, index2) => {
            if (index1 != index2) {
                item2.classList.remove("open");

                let choosePay = item2.querySelector(".choose-payment");
                choosePay.style.height = "0px";
                item2.querySelector("i").classList.replace("fa-minus", "fa-plus");
            }
        })
    }
