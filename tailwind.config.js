const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin')

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
        './resources/js/views/*.js',
    ],

    theme: {
        screens: {
          'sm': {'max': '639px'},
    
          'md': {'max': '900px'},
    
          'lg': {'max': '1200px'},
    
          'xl': {'max': '1800px'},
        },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
              '72': '18rem',
              '84': '21rem',
              '96': '24rem',
              '108': '27rem',
              '132': '34rem'
            },
        },
    },

    plugins: [
      require('flowbite/plugin'),
      require('tailwindcss-tables')({
        tableHoverBackgroundColor: 'rgba(0,0,0,.075)',  // default: rgba(0,0,0,.075)
        cellPadding: '.25rem',
        verticalAlign: 'center'
      }),
      require('@tailwindcss/forms'),
      require('tailwindcss-animatecss')({
        settings: {
          animatedSpeed: 1000,
          heartBeatSpeed: 1000,
          hingeSpeed: 2000,
          bounceInSpeed: 750,
          bounceOutSpeed: 750,
          animationDelaySpeed: 1000,
          verticalAlign: 'center',
        },
        variants: ['responsive'],
      }),
      require('tailwind-scrollbar'),
    ],
    variants: {
      scrollbar: ['rounded']
    }
};
