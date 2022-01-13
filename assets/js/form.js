import jQuery from 'jquery';

jQuery(document).on('cv-jquery-validate-options-update', (event, jQueryValidateSettings) => {
  jQueryValidateSettings.errorElement = 'span';
  jQueryValidateSettings.errorClass = 'form__message--invalid';
  jQueryValidateSettings.errorPlacement = (error, element) => {
    let el = element;

    if (element.attr('type') === 'radio') {
      el = element.parent();
    }

    el.parent().append(error);
  };
  jQueryValidateSettings.highlight = element => {
    element.classList.add('form__state--invalid');
  };
  jQueryValidateSettings.unhighlight = element => {
    element.classList.remove('form__state--invalid');
  };
  jQueryValidateSettings.normalizer = value => (value ? value.trim() : '');
});
