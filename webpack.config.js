const Encore = require('@symfony/webpack-encore');

Encore.setOutputPath('web/themes/custom/runroom/build/')
  .setPublicPath('/themes/custom/runroom/build')
  .copyFiles([
    { from: './assets/img', to: 'img/[name].[ext]', pattern: /\.(png|jpg|jpeg|gif|ico)$/ },
    { from: './assets/img', to: 'svg/[name].svg', pattern: /\.svg$/ },
    { from: './assets/fonts', to: 'fonts/[name].[ext]', pattern: /\.(woff|woff2)$/ }
  ])
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild(options => {
    options.keep = '.gitignore';
  })
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(false) // Do not enable versioning on Drupal
  .enableTypeScriptLoader()
  .enablePostCssLoader()
  .enableSassLoader()
  .enableBuildCache({ config: [__filename] })
  .autoProvidejQuery()
  .addExternals({
    Drupal: 'Drupal',
    drupalSettings: 'drupalSettings'
  })
  .addEntry('app', './assets/js/app.ts')
  .addEntry('form', './assets/js/form.ts')
  .addStyleEntry('styles', './assets/scss/styles.scss')
  .addStyleEntry('crp.default', './assets/scss/crp/default.scss')
  .addStyleEntry('crp.billboard', './assets/scss/crp/billboard.scss')
  .addStyleEntry('crp.basic-page', './assets/scss/crp/basic-page.scss');

module.exports = Encore.getWebpackConfig();
