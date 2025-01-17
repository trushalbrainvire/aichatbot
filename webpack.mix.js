const mix = require('laravel-mix');

mix.options({
  terser: {
    extractComments: false,
  }
});

mix.js('chatbot/app.js', 'extensions/chatbot/assets/')
.react()
.disableSuccessNotifications()
