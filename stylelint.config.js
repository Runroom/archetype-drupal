const stylelintConfig = require('@runroom/npm-scripts').stylelintConfig;

stylelintConfig.extends = ['stylelint-config-standard-scss', 'stylelint-prettier/recommended'];
stylelintConfig.rules['selector-class-pattern'] = null;
stylelintConfig.rules['scss/operator-no-unspaced'] = null;
stylelintConfig.rules['scss/operator-no-newline-after'] = null;

module.exports = stylelintConfig;
