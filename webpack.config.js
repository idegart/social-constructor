const path = require('path');

module.exports = {
    resolve: {
        alias: {
            "@js": path.resolve(__dirname, "./resources/js"),
            "@sass": path.resolve(__dirname, "./resources/sass"),
            "@component": path.resolve(__dirname, "./resources/js/components"),
            "@plugin": path.resolve(__dirname, "./resources/js/plugins"),
            "@model": path.resolve(__dirname, "./resources/js/models"),
            "@store": path.resolve(__dirname, "./resources/js/store"),
        },
    },
    output: {
        publicPath: '/',
        filename: '[name].js',
        chunkFilename: `dist/js/chunks/[name].js`,
    },
};
