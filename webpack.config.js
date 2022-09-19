const Encore = require('@symfony/webpack-encore');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const ForkTsCheckerWebpackPlugin = require('fork-ts-checker-webpack-plugin');

Encore.setOutputPath('web/themes/custom/runroom/build/')
  .setPublicPath('/themes/custom/runroom/build')
  .copyFiles([
    { from: './assets/img', to: 'img/[name].[ext]', pattern: /\.(png|jpg|jpeg|gif|ico)$/ },
    { from: './assets/img', to: 'svg/[name].svg', pattern: /\.svg$/ },
    { from: './assets/fonts', to: 'fonts/[name].[ext]', pattern: /\.(woff|woff2)$/ }
  ])
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild(['**/*', '!.gitignore'])
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(false) // We do not enable versioning on Drupal
  .enableTypeScriptLoader(tsConfig => {
    tsConfig.compilerOptions = {
      noEmit: false
    };
  })
  .enablePostCssLoader()
  .enableSassLoader(options => {
    options.sourceMap = true;
    options.sassOptions = { sourceComments: !Encore.isProduction() };
  }, {})
  .autoProvidejQuery()
  .enableBuildCache({ config: [__filename] })
  .addExternals({
    Drupal: 'Drupal',
    drupalSettings: 'drupalSettings'
  })
  .enableEslintPlugin()
  .addPlugin(
    new StyleLintPlugin({
      context: 'assets/scss',
      emitWarning: true
    })
  )
  .addPlugin(new ForkTsCheckerWebpackPlugin())
  .addEntry('app', './assets/js/app.ts')
  .addEntry('form', './assets/js/form.ts')
  .addStyleEntry('styles', './assets/scss/styles.scss')
  .addStyleEntry('crp.default', './assets/scss/crp/default.scss')
  .addStyleEntry('crp.billboard', './assets/scss/crp/billboard.scss')
  .addStyleEntry('crp.basic-page', './assets/scss/crp/basic-page.scss');

module.exports = Encore.getWebpackConfig();
