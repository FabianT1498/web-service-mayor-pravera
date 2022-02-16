require('./bootstrap');

require('./base')

require('./charts')

require('./external')

require('./component/dollar-exchange-modal')

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import("./pages/cash-register" /* webpackChunkName: "/js/cash_register_create" */).then(({default: defaultCreate}) => {
   defaultCreate()
});
