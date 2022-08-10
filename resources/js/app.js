import Alpine from 'alpinejs';
import { create } from 'lodash';

import * as bootstrap from './bootstrap'
import * as external from './external'

import * as base from './base'

// require('./charts')
window.Alpine = Alpine;

Alpine.start();

/** ---- BILLS PAYABLE SCRIPTS --- */
import("./pages/bills-payable/index" /* webpackChunkName: "/js/bills_payable_index" */).then(({default: billsPayableIndex}) => {
   billsPayableIndex.init()
});

import("./pages/bill-payable-schedules/create" /* webpackChunkName: "/js/bill_payable_schedules_create" */).then(({default: createSchedule}) => {
   createSchedule.init()
});

/** ---- PRODUCTS SCRIPTS --- */
import("./pages/products/index" /* webpackChunkName: "/js/products_index" */).then(({default: productsIndex}) => {
   productsIndex.init()
});

/** ---- CASH REGISTER SCRIPTS --- */
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

import("./pages/igtf-tax/index" /* webpackChunkName: "/js/igtf_tax_index" */).then(({default: indexIGTF}) => {
   indexIGTF.init()
});

import("./pages/zelle-report/index" /* webpackChunkName: "/js/zelle_report_index" */).then(({default: indexZelleReport}) => {
   indexZelleReport.init()
});

import("./components/dollar-exchange-modal/index" /* webpackChunkName: "/js/dollar_exchange_modal_index" */).then(({default: indexDollarExchangeModal}) => {
   indexDollarExchangeModal.init()
});

import LogoIsotipo from './../assets/logo-isotipo.svg';
import ProductBanner from './../assets/products.jpg';
import CashRegisterBanner from './../assets/cash-register.jpg';
