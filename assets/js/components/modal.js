import forEach from '@runroom/purejs/lib/forEach';

const MODAL_CLASS = 'modal';
const MODAL_CLOSE_CLASS = 'modal__close';
const MODAL_BODY_CLASS = 'modal__body';

const modal = () => {
  const modals = document.querySelectorAll(`.${MODAL_CLASS}`);

  forEach(modals, element => {
    const close = element.querySelector(`.${MODAL_CLOSE_CLASS}`);

    close.addEventListener('click', () => {
      element.remove();
    });

    element.addEventListener('click', event => {
      if (!event.target.closest(`.${MODAL_BODY_CLASS}`)) {
        element.remove();
      }
    });
  });
};

export default modal;
