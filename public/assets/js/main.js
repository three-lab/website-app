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
