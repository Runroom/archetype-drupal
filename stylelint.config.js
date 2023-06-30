const stylelintConfig = require('@runroom/npm-scripts').stylelintConfig;

stylelintConfig.extends = [...stylelintConfig.extends, 'stylelint-config-standard-scss'];
stylelintConfig.rules['at-rule-no-unknown'] = null;
stylelintConfig.rules['scss/at-rule-no-unknown'] = true;
stylelintConfig.rules['selector-class-pattern'] = null;
stylelintConfig.rules['scss/operator-no-unspaced'] = null;
stylelintConfig.rules['scss/operator-no-newline-after'] = null;

module.exports = stylelintConfig;
