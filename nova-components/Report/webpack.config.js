const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '@core': path.resolve('src/Modules/Core/Resources/assets/js'),
            '@dragon-tiger': path.resolve('src/Modules/DragonTiger/Resources/assets/js'),
            '@mixed': path.resolve('src/Modules/Mixed/Resources/assets/js'),
        },
    },
};
