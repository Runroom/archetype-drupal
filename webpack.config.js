const Encore = require('@symfony/webpack-encore');
const glob = require('glob-all');
const path = require('path');
const PurgeCss = require('purgecss-webpack-plugin');
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
  .enableEslintLoader({ configFile: './.eslintrc' })
  .addPlugin(
    new StyleLintPlugin({
      configFile: '.stylelintrc',
      context: 'assets/scss',
      files: '**/*.scss',
      failOnError: false,
      quiet: false
    })
  )
  .addPlugin(
    new PurgeCss({
      paths: glob.sync([path.join(__dirname, '/web/themes/custom/runroom/templates/**/*.html.twig')]),
      whitelist: [
        'lazyloaded',
        'is-opened',
        'non-touch',
      ],
      whitelistPatterns: [
        /^is-/,
        /^has-/,
        /^u-/,
        /^grid/,
      ],
      whitelistPatterns: [/^js-/, /^u-/],
      extractors: [{
        extractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
        extensions: ['twig']
      }]
    })
  )
  .enablePostCssLoader()
  .addEntry('app', './assets/js/app.js')
  .addEntry('development-scripts', './assets/js/development.js')
  .addEntry('styleguide-scripts', './assets/js/styleguide.js')
  .addStyleEntry('styles', './assets/scss/styles.scss')
  .addStyleEntry('development', './assets/scss/development.scss')
  .addStyleEntry('styleguide', './assets/scss/styleguide.scss')
  .addStyleEntry('crp.default', './assets/scss/crp/default.scss')
  .addStyleEntry('crp.billboard', './assets/scss/crp/billboard.scss');

module.exports = Encore.getWebpackConfig();
