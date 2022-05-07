import Alpine from 'alpinejs';
import { create } from 'lodash';

import * as bootstrap from './bootstrap'
import * as external from './external'

import * as base from './base'

// require('./charts')
window.Alpine = Alpine;

Alpine.start();

import("./pages/cash-register/create" /* webpackChunkName: "/js/cash_register_create" */).then(({default: createCashRegister}) => {
   createCashRegister.init()
});

import("./pages/cash-register/edit" /* webpackChunkName: "/js/cash_register_edit" */).then(({default: editCashRegister}) => {
   editCashRegister.init()
});

import("./pages/cash-register/index" /* webpackChunkName: "/js/cash_register_index" */).then(({default: indexCashRegister}) => {
   indexCashRegister.init()
});

import("./pages/fiscal-bill/index" /* webpackChunkName: "/js/fiscal_bill_index" */).then(({default: indexFiscalBill}) => {
   indexFiscalBill.init()
});

import("./pages/z-bill/index" /* webpackChunkName: "/js/z_bill_index" */).then(({default: indexZBill}) => {
   indexZBill.init()
});

import LogoIsotipo from './../assets/logo-isotipo.svg';
