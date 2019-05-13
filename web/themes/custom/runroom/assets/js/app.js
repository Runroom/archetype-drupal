import { scrollTo, events, touchable } from '@runroom/purejs';
import fastclick from 'fastclick';

// In order to keep readability and maintainability on bigger projects
// we recommend to use module import method and import it as needed.
import './helpers/polyfills';
import cookies from './components/Cookies';

touchable();
fastclick.attach(document.body);

events.onDocumentReady(() => {
  cookies();
});
