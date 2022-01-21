const tailwindConfig = require('../base.config');

module.exports = {
  ...tailwindConfig,
  content: [
    'web/themes/custom/runroom/templates/components/billboard.html.twig',
    'web/themes/custom/runroom/templates/components/header.html.twig',
    'web/themes/custom/runroom/templates/components/hamburguer.html.twig',
    'web/themes/custom/runroom/templates/components/skip-link.html.twig',
    'web/themes/custom/runroom/templates/helpers/inline-svg.html.twig',
    'web/themes/custom/runroom/templates/layouts/*'
  ]
};
