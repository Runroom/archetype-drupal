import jQuery from 'jquery';

jQuery(document).on('cv-jquery-validate-options-update', (event, jQueryValidateSettings) => {
  jQueryValidateSettings.errorElement = 'span';
  jQueryValidateSettings.errorClass = 'form__message--invalid';
  jQueryValidateSettings.errorPlacement = (error, element) => {
    if (element.attr('type') === 'radio') {
      element = element.parent();
    }

    element.parent().append(error);
  };
  jQueryValidateSettings.highlight = (element, errorClass, validClass) => {
    element.classList.add('form__state--invalid');
  };
  jQueryValidateSettings.unhighlight = (element, errorClass, validClass) => {
    element.classList.remove('form__state--invalid');
  };
  jQueryValidateSettings.normalizer = value => (value ? value.trim() : '');
});
