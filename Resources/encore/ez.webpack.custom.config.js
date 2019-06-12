//yarn encore dev --config-name demoConfigName

const path = require('path');
const Encore = require('@symfony/webpack-encore');

Encore.reset();
Encore.setOutputPath('web/assets/demobundle/build')
    .setPublicPath('/assets/demobundle/build')
    .addEntry('demo-bundle-js', path.resolve(__dirname, '../public/js/demo.js'))
    .addStyleEntry('demo-bundle-css', path.resolve(__dirname, '../public/scss/demo.scss'))
    .enableSassLoader()
    .enableSingleRuntimeChunk();

const customConfig = Encore.getWebpackConfig();

customConfig.name = 'demoConfigName';

module.exports = customConfig;