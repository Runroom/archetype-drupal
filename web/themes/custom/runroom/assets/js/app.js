import { scrollTo, events, touchable } from '@runroom/purejs';

// In order to keep readability and maintainability on bigger projects
// we recommend to use module import method and import it as needed.
import './helpers/polyfills';
// import cookies from './components/Cookies';

touchable();

events.onDocumentReady(() => {
  // cookies();
});
