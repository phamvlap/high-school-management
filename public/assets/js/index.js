window.addEventListener('load', () => {
    // fill data to form
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

    // show toast message when update / add
    const toastMessage = document.querySelector('#toast-message');
    setTimeout(() => {
        toastMessage.classList.remove('show');
    }, 3000);
});
