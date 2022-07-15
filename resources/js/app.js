import Alpine from 'alpinejs';
import { create } from 'lodash';

import * as bootstrap from './bootstrap'
import * as external from './external'

import * as base from './base'

// require('./charts')
window.Alpine = Alpine;

Alpine.start();

import("./pages/products/index" /* webpackChunkName: "/js/products_index" */).then(({default: productsIndex}) => {
   productsIndex.init()
});

import LogoIsotipo from './../assets/logo-isotipo.svg';
