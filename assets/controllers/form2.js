document.addEventListener("DOMContentLoaded", (event) => {
    console.log("DOM fully loaded and parsed");
    const check = document.querySelector('#client_form_addAccount')
    const form = document.querySelector('#form_client')
    const form_user = document.querySelector('#form_user')

    // check.checked ? form.submit() : null;
    check.addEventListener('change', (e) => {
        if (check.checked) {
          form.submit(); 
        } else {
            form_user.classList.add("hidden");
        }

      });
    // form.classList.add("hidden")
    // show(check.checked)

    // function show(ok) {
    //     if (ok) {
    //         if (form.classList.contains("hidden")) {
    //             form.classList.remove("hidden");
    //         } 
    //     } else {
    //         if (!form.classList.contains("hidden")) {
    //             form.classList.add("hidden");
    //         } 
    //     }
    // }
});