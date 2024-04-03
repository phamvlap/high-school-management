window.addEventListener('load', () => {

    // fill data to form
    if (formId) {
        const form = document.querySelector(`#${formId}`);

        document.querySelectorAll(`.${trClass}`).forEach((item) => {
            item.querySelector('.edit_btn').addEventListener('click', () => {
                Object.keys(fields).forEach((key) => {
                    const value = item.querySelector(`.${fields[key]}`).textContent;
                    form.querySelector(`#${key}`).value = value;
                });

                // Scroll up
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0;
            });
        });
    }

    // show toast message when update / add
    const toastMessage = document.querySelector('#toast-message');
    setTimeout(() => {
        toastMessage.classList.remove('show');
    }, 3000);


    const modal = new bootstrap.Modal(document.querySelector('#delete-modal'));
    // show delete confirmation
    const editBtn = document.querySelectorAll('.edit_btn');
    const formDelete = Array.from(editBtn).map((item) => {
        return item.nextElementSibling;
    });
    formDelete.forEach((item) => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            modal.show();

            const confirmSubmit = document.querySelector('#confirm-submit');
            confirmSubmit.addEventListener('click', () => {
                item.submit();
            });
        });
    });
});
