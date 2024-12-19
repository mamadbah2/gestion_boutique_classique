document.addEventListener("DOMContentLoaded", (event) => {
    console.log("DOM fully loaded and parsed");
    const check = document.querySelector('#client_form_addAccount')
    const form = document.querySelector('#form_user')
    form.classList.add("hidden")
    show(check.checked)
    check.addEventListener('change', (e) =>{
        show(check.checked)
    })
    function show(ok) {
        if (ok) {
            if (form.classList.contains("hidden")) {
                form.classList.remove("hidden");
            } 
        } else {
            if (!form.classList.contains("hidden")) {
                form.classList.add("hidden");
            } 
        }
    }
});