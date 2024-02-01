addEventListener('DOMContentLoaded', (evt) => {

    const headingCheckbox = document.querySelector('#tl_thememanager .activate-content-headings input[type=checkbox]')
    const headingFields = document.querySelectorAll('#tl_thememanager .heading-field input, #tl_thememanager .heading-field select');

    if (headingCheckbox && headingFields) {
        setFieldsReadonly(headingFields, !headingCheckbox.checked)

        headingCheckbox.addEventListener('change', (e) => {
            setFieldsReadonly(headingFields, !e.currentTarget.checked)
        })
    }
});

function setFieldsReadonly(fields, state) {
    fields.forEach(field => {
        field.readOnly = state;
        field.dataset.readOnly = state;
    })
}
