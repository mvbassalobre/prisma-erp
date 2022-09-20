let mix = require("laravel-mix");
mix.webpackConfig({
    resolve: {
        modules: ["node_modules"],
		fallback: {
            "crypto": require.resolve("crypto-browserify"),
            "stream": require.resolve("stream-browserify")
        }
    }
});

mix.options({
    processCssUrls: true
});

mix.autoload({
    jquery: ["$", "window.jQuery", "jQuery", "jquery"]
});

mix.js("resources/js/app.js", "public/assets/backend/js/app.js").vue({ version: 2 })
    .sass("resources/sass/app.scss", "public/assets/backend/css")
    .extract(["jquery", "vue"]);

mix.babelConfig({
    plugins: ['@babel/plugin-proposal-class-properties', '@babel/plugin-syntax-dynamic-import'],
});

if (mix.inProduction()) {
    mix.version();
}
    