$('.photo-preview').each((i, el) => {
    const target = el.dataset.target;
    const input = $(target);

    el.addEventListener('click', () => input.trigger('click'));
    input.on('change', function(e) {
        const reader = new FileReader();

        reader.onload = () => el.style.backgroundImage = `url(${reader.result})`;
        reader.readAsDataURL(e.target.files[0]);
    });
});

function renderFormError(form, errors) {
    for(const error in errors) {
        form.find(`input[name=${error}]`).addClass('is-invalid');
        form.find(`.invalid-feedback.invalid-${error}`).text(errors[error]);
    }
}

function resetFormError(form) {
    form.find(`.form-control`).removeClass('is-invalid');
    form.find(`.invalid-feedback`).text();
}