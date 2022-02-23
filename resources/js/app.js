import Alpine from 'alpinejs';

import * as base from './base'

require('./bootstrap');
require('./external')

// require('./charts')
window.Alpine = Alpine;

Alpine.start();

import("./pages/cash-register/create" /* webpackChunkName: "/js/cash_register_create" */).then(({default: defaultCreate}) => {
   defaultCreate()
});
