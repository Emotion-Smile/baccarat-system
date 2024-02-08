let mix = require('laravel-mix')

mix
  .setPublicPath('dist')
  .js([
    'resources/js/tool.js',
    'src/Modules/Core/Resources/assets/js/app.js',
    'src/Modules/Mixed/Resources/assets/js/app.js',
    'src/Modules/DragonTiger/Resources/assets/js/app.js',
  ], 'js')
  .sass('resources/sass/tool.scss', 'css')
  .webpackConfig(require('./webpack.config'))
