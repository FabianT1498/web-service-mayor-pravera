require('./bootstrap');

require('./base')

require('./charts')

require('./external')

require('_components/dollar-exchange-modal')

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import("./pages/cash-register/create" /* webpackChunkName: "/js/cash_register_create" */).then(({default: defaultCreate}) => {
   defaultCreate()
});
