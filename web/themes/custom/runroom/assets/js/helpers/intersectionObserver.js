const defaults = {
  root: null,
  rootMargin: '50px 0px',
  threshold: 0.8
};

const intersectionObserver = (config, callback) => {
  const elements = document.querySelectorAll(config.elementsClass);
  let observer;

  if ('IntersectionObserver' in window) {
    observer = new IntersectionObserver((changes, obs) => {
      changes.forEach(change => {
        if (change.intersectionRatio > 0) {
          callback(change.target);
          obs.unobserve(change.target);
        }
      });
    }, { ...defaults, ...config.observer });
    elements.forEach(element => observer.observe(element));
  } else {
    elements.forEach(element => {
      callback(element);
    });
  }
};

export default intersectionObserver;
