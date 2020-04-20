const Encore = require('@symfony/webpack-encore');
const StyleLintPlugin = require('stylelint-webpack-plugin');

Encore.setOutputPath('web/themes/custom/runroom/build/')
  .setPublicPath('/themes/custom/runroom/build')
  .copyFiles([
    { from: './assets/img', to: 'img/[name].[ext]', pattern: /\.(png|jpg|jpeg|gif|ico)$/ },
    { from: './assets/img', to: 'svg/[name].svg', pattern: /\.svg$/ },
    { from: './assets/fonts', to: 'fonts/[name].[ext]', pattern: /\.(woff|woff2)$/ },
  ])
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(false)
  .enableSassLoader()
  .enableEslintLoader()
  .addExternals({
    jQuery: 'jQuery',
    Drupal: 'Drupal',
    drupalSettings: 'drupalSettings'
  })
  .addPlugin(
    new StyleLintPlugin({
      configFile: '.stylelintrc',
      context: 'assets/scss',
      files: '**/*.scss',
      failOnError: false,
      quiet: false
    })
  )
  .enablePostCssLoader()
  .addEntry('app', './assets/js/app.js')
  .addEntry('form', './assets/js/form.js')
  .addEntry('development-scripts', './assets/js/development.js')
  .addEntry('styleguide-scripts', './assets/js/styleguide.js')
  .addStyleEntry('styles', './assets/scss/styles.scss')
  .addStyleEntry('development', './assets/scss/development.scss')
  .addStyleEntry('styleguide', './assets/scss/styleguide.scss')
  .addStyleEntry('crp.default', './assets/scss/crp/default.scss')
  .addStyleEntry('crp.billboard', './assets/scss/crp/billboard.scss')
  .addStyleEntry('crp.basic-page', './assets/scss/crp/basic-page.scss');

module.exports = Encore.getWebpackConfig();
