import Utils from '../helpers/utils';
import intersectionObserver from '../helpers/IntersectionObserver';

const HANDLED_CLASS = 'lazyloaded';
const config = {
  elementsClass: '.lazyload',
  handleClass: HANDLED_CLASS,
  observer: {
    rootMargin: '20px 0px',
    threshold: 0.2
  }
};

const loadImage = image => {
  const bg = image.classList.contains('lazybg');
  const { src } = image.dataset;

  Utils.preloadImage(src).then(() => {
    image.classList.add(HANDLED_CLASS);
    if (bg) {
      image.style.backgroundImage = `url(${src})`;
    } else {
      image.src = src;
    }
  }).catch(err => {
    // console.warn(err);
  });
};

const init = () => intersectionObserver(config, loadImage);

export default init;
