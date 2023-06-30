import jQuery from 'jquery';

jQuery(document).on('cv-jquery-validate-options-update', (event, jQueryValidateSettings) => {
  jQueryValidateSettings.errorElement = 'span';
  jQueryValidateSettings.errorClass = 'form__message--invalid';
  jQueryValidateSettings.errorPlacement = (
    // eslint-disable-next-line no-undef
    error: JQuery<HTMLElement>,
    // eslint-disable-next-line no-undef
    element: JQuery<HTMLElement>
  ) => {
    let el = element;

    if (element.attr('type') === 'radio') {
      el = element.parent();
    }

    el.parent().append(error);
  };
  jQueryValidateSettings.highlight = (element: HTMLElement) => {
    element.classList.add('form__state--invalid');
  };
  jQueryValidateSettings.unhighlight = (element: HTMLElement) => {
    element.classList.remove('form__state--invalid');
  };
  // @ts-expect-error: Missing method in types
  jQueryValidateSettings.normalizer = value => (value ? value.trim() : '');
});
