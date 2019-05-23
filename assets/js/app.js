import { scrollTo, events, touchable } from '@runroom/purejs';

// In order to keep readability and maintainability on bigger projects
// we recommend to use module import method and import it as needed.
import cookies from './components/cookies';
import lazyLoadImages from './components/lazyLoadImages';
import menu from './components/menu';
import './helpers/polyfills';

touchable();
lazyLoadImages();

document.documentElement.classList.remove('no-js');

events.onDocumentReady(() => {
  const anchor = document.querySelector('.js-anchor');

  cookies();
  menu();

  // For small projects or low use of javascript, you can add events in this
  // same file, as follows. Eventhough the module import method is preferred.
  if (anchor) {
    anchor.addEventListener('click', event => {
      const target = event.target.getAttribute('data-anchor');
      scrollTo.animate(target, 300);
    });
  }
});
