(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/cash_register_create"],{

/***/ "./resources/js/collections/collection.js":
/*!************************************************!*\
  !*** ./resources/js/collections/collection.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var CollectionPrototype = {
  elements: [],
  getAll: function getAll() {
    return this.elements;
  },
  getLength: function getLength() {
    return this.elements.length;
  },
  setElements: function setElements(elements) {
    this.elements = elements ? elements : [];
  },
  getElementByIndex: function getElementByIndex(index) {
    if (index > -1 && index < this.elements.length) {
      return this.elements[index];
    }

    return undefined;
  },
  deleteElementByIndex: function deleteElementByIndex(index) {
    if (index > -1 && index < this.elements.length) {
      this.elements.splice(index, 1);
    }

    return false;
  },
  pushElement: function pushElement(el) {
    this.elements.push(el);
    return this.elements;
  },
  shiftElement: function shiftElement() {
    return this.elements.shift();
  }
};

var Collection = function Collection(elements) {
  this.elements = elements;
};

Collection.prototype = CollectionPrototype;
Collection.prototype.constructor = Collection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Collection);

/***/ }),

/***/ "./resources/js/collections/moneyRecordCollection.js":
/*!***********************************************************!*\
  !*** ./resources/js/collections/moneyRecordCollection.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./collection */ "./resources/js/collections/collection.js");
/* harmony import */ var _objectCollection__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./objectCollection */ "./resources/js/collections/objectCollection.js");



var MoneyRecordCollection = function MoneyRecordCollection() {
  var elements = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  _objectCollection__WEBPACK_IMPORTED_MODULE_1__["default"].call(this, elements);

  this.pushElement = function (el) {
    el.id = this.getNewID();
    _collection__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.pushElement.call(this, el);
    return el;
  };
};

MoneyRecordCollection.prototype = Object.create(_objectCollection__WEBPACK_IMPORTED_MODULE_1__["default"].prototype);
MoneyRecordCollection.prototype.constructor = MoneyRecordCollection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MoneyRecordCollection);

/***/ }),

/***/ "./resources/js/collections/objectCollection.js":
/*!******************************************************!*\
  !*** ./resources/js/collections/objectCollection.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./collection */ "./resources/js/collections/collection.js");


var ObjectCollection = function ObjectCollection(elements) {
  _collection__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, elements);

  this.removeElementByID = function (id) {
    var index = this.elements.findIndex(function (obj) {
      return obj.id === id;
    });
    console.log(index);

    if (index !== -1) {
      console.log(index);
      this.elements.splice(index, 1);
      return true;
    }

    return false;
  };

  this.getNewID = function () {
    return this.getLength() === 0 ? 0 : this.getElementByIndex(this.getLength() - 1).id + 1;
  };
};

ObjectCollection.prototype = Object.create(_collection__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
ObjectCollection.prototype.constructor = ObjectCollection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ObjectCollection);

/***/ }),

/***/ "./resources/js/components/denominations-table/index.js":
/*!**************************************************************!*\
  !*** ./resources/js/components/denominations-table/index.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");



var DenominationsTable = function DenominationsTable() {
  var _this = this;

  this.init = function (container, name, currency) {
    _this.container = container;
    _this.name = name;
    _this.currency = currency || _constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.DOLLAR;
  };

  this.isContainerDefined = function () {
    return this.container !== null;
  };

  this.getTotal = function () {
    if (!this.isContainerDefined) {
      return 0;
    }

    var tBody = this.container.querySelector('tBody');
    var inputs = tBody.querySelectorAll('input');
    var total = Array.from(inputs).reduce(function (acc, el) {
      var denomination = parseFloat(el.getAttribute('data-denomination'));
      var num = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_0__.formatAmount)(el.value);
      return acc + num * denomination;
    }, 0);
    return total;
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationsTable);

/***/ }),

/***/ "./resources/js/components/money-record-table/ForeignMoneyRecordTable.js":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/money-record-table/ForeignMoneyRecordTable.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MoneyRecordTable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MoneyRecordTable */ "./resources/js/components/money-record-table/MoneyRecordTable.js");
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");



var ForeignMoneyRecordTable = function ForeignMoneyRecordTable() {
  var _this = this;

  _MoneyRecordTable__WEBPACK_IMPORTED_MODULE_0__["default"].call(this);

  this.resetLastInput = function (id) {
    _MoneyRecordTable__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.resetLastInput.call(this, id);

    if (!this.isContainerDefined()) {
      return;
    }

    var row = this.container.querySelector("tr[data-id=\"".concat(id, "\"]"));
    var convertionCol = row.querySelector('td[data-table="convertion-col"]');

    if (convertionCol) {
      convertionCol.innerHTML = "0.00 ".concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.BOLIVAR]);
    }
  };

  this.getTableRowTemplate = function (id, total) {
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(id, ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(total, "</td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(_this.getInputTemplate(id), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                0.00 ").concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.BOLIVAR], "\n            </td>\n            <td class=\"py-4 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-del-row=\"").concat(id, "\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
  };

  this.updateConvertionCol = function (_ref) {
    var rowID = _ref.rowID,
        formatedConvertion = _ref.formatedConvertion;
    var row = this.container.querySelector("tr[data-id=\"".concat(rowID, "\"]"));
    var columnData = row.querySelector('td[data-table="convertion-col"]');
    columnData.innerHTML = formatedConvertion;
  }; // const getTotal = (msg, data) => {
  //     if (!this.container){
  //         return;
  //     }
  //     const tBody = this.container.querySelector('tbody');
  //     let inputs = tBody.querySelectorAll(`input`)
  //     const total = Array.from(inputs).reduce((acc, el) => {
  //         let num = formatAmount(el.value)
  //         return acc + num;
  //     }, 0);
  //     document.getElementById(`total_${this.tableName}`).value = total > 0 ? total : 0;
  // }

};

ForeignMoneyRecordTable.prototype = Object.create(_MoneyRecordTable__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
ForeignMoneyRecordTable.prototype.constructor = ForeignMoneyRecordTable;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ForeignMoneyRecordTable);

/***/ }),

/***/ "./resources/js/components/money-record-table/MoneyRecordTable.js":
/*!************************************************************************!*\
  !*** ./resources/js/components/money-record-table/MoneyRecordTable.js ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _utilities/decimalInput */ "./resources/js/utilities/decimalInput.js");
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");


var MoneyRecordTablePrototype = {
  init: function init(container, tableName, currency) {
    this.container = container;
    this.tableName = tableName;
    this.currency = currency;

    if (!container) {
      return false;
    }

    this.setInitialMask();
    return true;
  },
  addRow: function addRow(_ref) {
    var id = _ref.id,
        total = _ref.total;

    if (!this.isContainerDefined()) {
      return;
    }

    var tBody = this.container.querySelector('tbody');
    tBody.insertAdjacentHTML('beforeend', this.getTableRowTemplate(id, total));
    var input = tBody.querySelector("#".concat(this.tableName, "_").concat(id));
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_0__.decimalInputs[this.currency].mask(input);
  },
  deleteRow: function deleteRow(rowID) {
    if (!this.isContainerDefined()) {
      return;
    }

    var tBody = this.container.querySelector('tbody');
    var row = tBody ? tBody.querySelector("tr[data-id=\"".concat(rowID, "\"]")) : null;
    tBody.removeChild(row);
    this.updateTableIDColumn(tBody);
  },
  resetLastInput: function resetLastInput(id) {
    if (!this.isContainerDefined()) {
      return;
    }

    var tBody = this.container.querySelector('tbody');
    var input = tBody.querySelector("#".concat(this.tableName, "_").concat(id));

    if (input && input !== null && input !== void 0 && input.inputmask) {
      input.value = 0;
    }
  },
  getInputTemplate: function getInputTemplate(id) {
    return "\n        <input type=\"text\" placeholder=\"0.00 ".concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.SIGN[this.currency], "\" id=\"").concat(this.tableName, "_").concat(id, "\" name=\"").concat(this.tableName, "[]\" class=\"w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">");
  },
  getTableRowTemplate: function getTableRowTemplate(id, total) {
    return "\n            <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(id, ">\n                <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(total, "</td>\n                <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                    ").concat(this.getInputTemplate(id), "\n                </td>\n                <td class=\"py-4 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                    <button data-del-row=\"").concat(id, "\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                        <i class=\"fas fa-times  text-white\"></i>                        \n                    </button>\n                </td>\n            </tr>\n        ");
  },
  isContainerDefined: function isContainerDefined() {
    return this.container !== null;
  },
  setInitialMask: function setInitialMask() {
    if (!this.isContainerDefined()) {
      return;
    }

    var tBody = this.container.querySelector('tbody');
    var input = tBody.querySelector('input');
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_0__.decimalInputs[this.currency].mask(input);
  },
  updateTableIDColumn: function updateTableIDColumn(container) {
    var colsID = container.querySelectorAll("td[data-table=\"num-col\"]");

    for (var i = 0; i < colsID.length; i++) {
      colsID[i].innerHTML = i + 1;
    }
  }
};

var MoneyRecordTable = function MoneyRecordTable() {};

MoneyRecordTable.prototype = MoneyRecordTablePrototype;
MoneyRecordTable.prototype.constructor = MoneyRecordTable;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MoneyRecordTable);

/***/ }),

/***/ "./resources/js/constants/currencies.js":
/*!**********************************************!*\
  !*** ./resources/js/constants/currencies.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CURRENCIES": () => (/* binding */ CURRENCIES),
/* harmony export */   "SIGN": () => (/* binding */ SIGN)
/* harmony export */ });
var _Object$freeze;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var CURRENCIES = Object.freeze({
  BOLIVAR: 'bs',
  DOLLAR: 'dollar'
});
var SIGN = Object.freeze((_Object$freeze = {}, _defineProperty(_Object$freeze, CURRENCIES.DOLLAR, '$'), _defineProperty(_Object$freeze, CURRENCIES.BOLIVAR, 'Bs.s'), _Object$freeze));


/***/ }),

/***/ "./resources/js/constants/paymentMethods.js":
/*!**************************************************!*\
  !*** ./resources/js/constants/paymentMethods.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "PAYMENT_METHODS": () => (/* binding */ PAYMENT_METHODS)
/* harmony export */ });
var PAYMENT_METHODS = Object.freeze({
  CASH: 'cash',
  POINT_SALE: 'pointSale',
  ZELLE: 'zelle'
});


/***/ }),

/***/ "./resources/js/models/moneyRecord.js":
/*!********************************************!*\
  !*** ./resources/js/models/moneyRecord.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var MoneyRecord = function MoneyRecord(amount, currency, method) {
  var id = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
  this.id = id;
  this.amount = amount;
  this.currency = currency;
  this.method = method;
};

MoneyRecord.prototype.constructor = MoneyRecord;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MoneyRecord);

/***/ }),

/***/ "./resources/js/pages/cash-register/create.js":
/*!****************************************************!*\
  !*** ./resources/js/pages/cash-register/create.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* export default binding */ __WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");
/* harmony import */ var _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/paymentMethods */ "./resources/js/constants/paymentMethods.js");
/* harmony import */ var _views_MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _views/MoneyRecordModalView */ "./resources/js/views/MoneyRecordModalView.js");
/* harmony import */ var _presenters_MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _presenters/MoneyRecordModalPresenter */ "./resources/js/presenters/MoneyRecordModalPresenter.js");
/* harmony import */ var _components_money_record_table_MoneyRecordTable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! _components/money-record-table/MoneyRecordTable */ "./resources/js/components/money-record-table/MoneyRecordTable.js");
/* harmony import */ var _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! _views/ForeignMoneyRecordModalView */ "./resources/js/views/ForeignMoneyRecordModalView.js");
/* harmony import */ var _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! _presenters/ForeignMoneyRecordModalPresenter */ "./resources/js/presenters/ForeignMoneyRecordModalPresenter.js");
/* harmony import */ var _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! _components/money-record-table/ForeignMoneyRecordTable */ "./resources/js/components/money-record-table/ForeignMoneyRecordTable.js");
/* harmony import */ var _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! _presenters/DenominationModalPresenter */ "./resources/js/presenters/DenominationModalPresenter.js");
/* harmony import */ var _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! _views/DenominationModalView */ "./resources/js/views/DenominationModalView.js");

 // import RecordMoneyModalFactory from '_components/cash-register-modal';
// import DenominationsModal from '_components/denominations-modal';
// import SalePointModal from '_components/sale-point-modal';
// import DecimalInput from '_components/decimal-input';
// import CashRegisterData from '_components/cash-register-dbata'









/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__() {
  var moneyRecordMoneyPresenter = new _presenters_MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_3__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH);
  var moneyRecordMoneyView = new _views_MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_2__["default"](moneyRecordMoneyPresenter);
  var liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
  var moneyRecordTable = new _components_money_record_table_MoneyRecordTable__WEBPACK_IMPORTED_MODULE_4__["default"]();
  moneyRecordMoneyView.init(liquidMoneyBsRegisterModal, 'liquid_money_bolivares', moneyRecordTable);
  var foreignMoneyRecordMoneyPresenter = new _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_6__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH);
  var foreignMoneyRecordMoneyView = new _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_5__["default"](foreignMoneyRecordMoneyPresenter);
  var cashDollarRecordModal = document.querySelector('#liquid_money_dollars');
  var foreignMoneyRecordTable = new _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_7__["default"]();
  foreignMoneyRecordMoneyView.init(cashDollarRecordModal, 'liquid_money_dollars', foreignMoneyRecordTable);
  var bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
  var denominationModalPresenter = new _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_8__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH);
  var denominationModalView = new _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_9__["default"](denominationModalPresenter);
  denominationModalView.init(bsDenominationsModal, 'liquid_money_bolivares_denominations'); // // Cash register data DOM
  // const cashRegisterDataContainer = document.querySelector('#cash_register_data');
  // const cashRegisterData = new CashRegisterData();
  // cashRegisterData.init(cashRegisterDataContainer);
  // // Decimal Input Subscribers
  // let decimalInputDollar = new DecimalInput();
  // decimalInputDollar.init();
  // // Cash register modal DOMs
  // let liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
  // let liquidMoneyDollarRegisterModal = document.querySelector('#liquid_money_dollars');
  // // Cash register modal factory
  // let recordMoneyModalFactory = new RecordMoneyModalFactory();
  // let liquidMoneyBsRegister = recordMoneyModalFactory.create({currency: CURRENCIES.BOLIVAR, method: 'cash'});
  // liquidMoneyBsRegister.init(liquidMoneyBsRegisterModal, 'liquid_money_bolivares');
  // let liquidMoneyDollarRegister = recordMoneyModalFactory.create({currency: CURRENCIES.DOLLAR, method: 'cash'});
  // liquidMoneyDollarRegister.init(liquidMoneyDollarRegisterModal, 'liquid_money_dollars');
  // // Cash register modal total input DOMs
  // let totalLiquidMoneyBolivares = document.querySelector('#total_liquid_money_bolivares');
  // let totalLiquidMoneyDollars = document.querySelector('#total_liquid_money_dollars');
  // PubSub.publish('attachMask', {input: totalLiquidMoneyBolivares, currency: CURRENCIES.BOLIVAR})
  // PubSub.publish('attachMask', {input: totalLiquidMoneyDollars, currency: CURRENCIES.DOLLAR})
  // // Denomination modal DOMs
  // let bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
  // let dollarDenominationsModal = document.querySelector('#liquid_money_dollars_denominations');
  // let bsDenominations = new DenominationsModal('liquid_money_bolivares_denominations', CURRENCIES.BOLIVAR);
  // bsDenominations.init(bsDenominationsModal);
  // let dollarDenominations = new DenominationsModal('liquid_money_dollars_denominations', CURRENCIES.DOLLAR);
  // dollarDenominations.init(dollarDenominationsModal);
  // // Denomination modal total input DOMs
  // let totalbsDenominations = document.querySelector('#total_liquid_money_bolivares_denominations');
  // let totaldollarDenominations = document.querySelector('#total_liquid_money_dollars_denominations');
  // PubSub.publish('attachMask', {input: totalbsDenominations, currency: CURRENCIES.BOLIVAR})
  // PubSub.publish('attachMask', {input: totaldollarDenominations, currency: CURRENCIES.DOLLAR})
  // // Sale point DOM
  // let bsSalePointModal = document.querySelector('#point_sale_bs');
  // let bsSalePoint = new SalePointModal('point_sale_bs', CURRENCIES.BOLIVAR);
  // bsSalePoint.init(bsSalePointModal);
  // // Zelle list DOM
  // let zelleRecordModal = document.querySelector('#zelle_record')
  // let zelleRecord = recordMoneyModalFactory.create({currency: CURRENCIES.DOLLAR, method: 'zelle'});
  // zelleRecord.init(zelleRecordModal, 'zelle_record');
  // // Zelle total input DOMs
  // let totalZelleEl = document.querySelector('#total_zelle_record');
  // PubSub.publish('attachMask', {input: totalZelleEl, currency: CURRENCIES.DOLLAR})
  // document.querySelector('#form').addEventListener('submit', (event) =>{
  //     let allIsNull = true;
  //     for(let i = 0; i < inputs.length; i++){
  //         let el = inputs[i];
  //         if (el.value){
  //             allIsNull = false;
  //             break;
  //         }
  //     }
  //     // Check if there's at least one input filled
  //     if (allIsNull){
  //         event.preventDefault();
  //         alert('Epa, no se ha ingresado ningun ingreso')
  //         return;
  //     }
  // })
}

/***/ }),

/***/ "./resources/js/presenters/DenominationModalPresenter.js":
/*!***************************************************************!*\
  !*** ./resources/js/presenters/DenominationModalPresenter.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var DenominationModalPresenterPrototype = {
  clickOnModal: function clickOnModal(_ref) {
    var target = _ref.target;
    var closest = target.closest('button');

    if (closest && closest.tagName === 'BUTTON') {
      var modaToggleID = closest.getAttribute('data-modal-toggle');

      if (modaToggleID) {
        // Checking if it's closing the modal
        this.view.getTotal();
      }
    }
  },
  setView: function setView(view) {
    this.view = view;
  }
};

var DenominationModalPresenter = function DenominationModalPresenter(currency, method) {
  this.view = null;
  this.currency = currency;
  this.method = method;
};

DenominationModalPresenter.prototype = DenominationModalPresenterPrototype;
DenominationModalPresenter.prototype.constructor = DenominationModalPresenter;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationModalPresenter);

/***/ }),

/***/ "./resources/js/presenters/ForeignMoneyRecordModalPresenter.js":
/*!*********************************************************************!*\
  !*** ./resources/js/presenters/ForeignMoneyRecordModalPresenter.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MoneyRecordModalPresenter */ "./resources/js/presenters/MoneyRecordModalPresenter.js");
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");




var ForeignMoneyRecordModalPresenter = function ForeignMoneyRecordModalPresenter(currency, method) {
  _MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, currency, method);

  this.keyPressedOnModal = function (_ref) {
    var target = _ref.target,
        key = _ref.key;
    _MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.keyPressedOnModal.call(this, {
      target: target,
      key: key
    });

    if (isFinite(key)) {
      // Handle case to convert dollar to Bs.S`
      var rowID = target.closest('tr').getAttribute('data-id');
      var formatedConvertion = getConvertionFormated(target);
      this.view.updateConvertionCol({
        rowID: rowID,
        formatedConvertion: formatedConvertion
      });
    }
  };

  this.keyDownOnModal = function (_ref2) {
    var target = _ref2.target,
        key = _ref2.key;

    if (key === 8 || key === 'Backspace') {
      var rowID = target.closest('tr').getAttribute('data-id');
      var formatedConvertion = getConvertionFormated(target);
      this.view.updateConvertionCol({
        rowID: rowID,
        formatedConvertion: formatedConvertion
      });
    }
  };

  var getConvertionFormated = function getConvertionFormated(target) {
    var inputValue = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__.formatAmount)(target.value);
    var dollarExchangeValue = 4.30;
    return "".concat(calculateConvertion(inputValue, dollarExchangeValue), " ").concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_2__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_2__.CURRENCIES.BOLIVAR]);
  };

  var calculateConvertion = function calculateConvertion(amount, exchangeValue) {
    return Math.round((exchangeValue * amount + Number.EPSILON) * 100) / 100;
  };
};

ForeignMoneyRecordModalPresenter.prototype = Object.create(_MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
ForeignMoneyRecordModalPresenter.prototype.constructor = ForeignMoneyRecordModalPresenter;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ForeignMoneyRecordModalPresenter);

/***/ }),

/***/ "./resources/js/presenters/MoneyRecordModalPresenter.js":
/*!**************************************************************!*\
  !*** ./resources/js/presenters/MoneyRecordModalPresenter.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collections_moneyRecordCollection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _collections/moneyRecordCollection */ "./resources/js/collections/moneyRecordCollection.js");
/* harmony import */ var _models_moneyRecord__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _models/moneyRecord */ "./resources/js/models/moneyRecord.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }



var MoneyRecordModalPresenterPrototype = {
  clickOnModal: function clickOnModal(_ref) {
    var target = _ref.target;
    var button = target.closest('button');

    if (button && button.tagName === 'BUTTON') {
      var rowID = button.getAttribute('data-del-row');
      var modalToggleID = button.getAttribute('data-modal-toggle');

      if (rowID) {
        // Delete a row
        if (this.moneyRecordCollection.getLength() === 1) {
          // Clean the remaining row
          var record = this.moneyRecordCollection.getAll()[0];
          this.view.resetLastInput(record.id);
          console.log('Last record');
          console.log(this.moneyRecordCollection.getAll());
        } else {
          // Delete the entry with the id
          var id = parseInt(rowID);
          this.moneyRecordCollection.removeElementByID(id);
          this.view.deleteRow(rowID);
          console.log('Deleted record');
          console.log(this.moneyRecordCollection.getAll());
        }
      } else if (modalToggleID) {// Checking if it's closing the modal
        // console.log(`getTotal.records.${method}.${currency}`)
        // PubSub.publish(`getTotal.records.${method}.${currency}`);
      }
    }
  },
  keyPressedOnModal: function keyPressedOnModal(_ref2) {
    var target = _ref2.target,
        key = _ref2.key;

    if (key === 13 || key === 'Enter') {
      // Handle new table's row creation
      var moneyRecord = new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_1__["default"](0.00, this.currency, this.method);
      moneyRecord = this.moneyRecordCollection.pushElement(moneyRecord);
      this.view.addRow(_objectSpread(_objectSpread({}, moneyRecord), {}, {
        total: this.moneyRecordCollection.getLength()
      }));
      console.log('Record Added');
      console.log(this.moneyRecordCollection.getAll());
    }
  },
  setView: function setView(view) {
    this.view = view;
  }
};

var MoneyRecordModalPresenter = function MoneyRecordModalPresenter(currency, method) {
  this.view = null;
  this.currency = currency;
  this.method = method;
  this.moneyRecordCollection = new _collections_moneyRecordCollection__WEBPACK_IMPORTED_MODULE_0__["default"]([new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_1__["default"](0.00, this.currency, this.method)]);
};

MoneyRecordModalPresenter.prototype = MoneyRecordModalPresenterPrototype;
MoneyRecordModalPresenter.prototype.constructor = MoneyRecordModalPresenter;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MoneyRecordModalPresenter);

/***/ }),

/***/ "./resources/js/utilities/decimalInput.js":
/*!************************************************!*\
  !*** ./resources/js/utilities/decimalInput.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "decimalInputs": () => (/* binding */ decimalInputs)
/* harmony export */ });
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! inputmask */ "./node_modules/inputmask/dist/inputmask.js");
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(inputmask__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");
var _decimalInputs;

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }



var decimalMaskOptions = {
  alias: 'decimal',
  positionCaretOnClick: "radixFocus",
  digits: 2,
  radixPoint: ",",
  _radixDance: true,
  numericInput: true,
  placeholder: "0",
  definitions: {
    "0": {
      validator: "[0-9\uFF11-\uFF19]"
    }
  }
};
var decimalInputs = (_decimalInputs = {}, _defineProperty(_decimalInputs, _constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.BOLIVAR, new (inputmask__WEBPACK_IMPORTED_MODULE_0___default())(_objectSpread(_objectSpread({}, decimalMaskOptions), {}, {
  suffix: _constants_currencies__WEBPACK_IMPORTED_MODULE_1__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.BOLIVAR]
}))), _defineProperty(_decimalInputs, _constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.DOLLAR, new (inputmask__WEBPACK_IMPORTED_MODULE_0___default())(_objectSpread(_objectSpread({}, decimalMaskOptions), {}, {
  suffix: _constants_currencies__WEBPACK_IMPORTED_MODULE_1__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.DOLLAR]
}))), _decimalInputs);


/***/ }),

/***/ "./resources/js/utilities/mathUtilities.js":
/*!*************************************************!*\
  !*** ./resources/js/utilities/mathUtilities.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "formatAmount": () => (/* binding */ formatAmount)
/* harmony export */ });
var formatAmount = function formatAmount(amount) {
  var _arr$, _arr$2;

  var defaultValue = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '0.00';

  if (!amount) {
    return 0;
  }

  var index = amount.indexOf(" "); // Remove suffix if exists

  if (index !== -1) {
    amount = amount.slice(0, index);
  } // Check if value is zero


  if (amount === defaultValue) {
    return 0;
  }

  var arr = amount.split(',', 2);
  var integer = (_arr$ = arr[0]) !== null && _arr$ !== void 0 ? _arr$ : null;
  var decimal = (_arr$2 = arr[1]) !== null && _arr$2 !== void 0 ? _arr$2 : null; // Check if it is an integer number

  if (!decimal) {
    return parseInt(integer);
  }

  var numberString = integer + '.' + decimal;
  return Math.round((parseFloat(numberString) + Number.EPSILON) * 100) / 100;
};



/***/ }),

/***/ "./resources/js/views/DenominationModalView.js":
/*!*****************************************************!*\
  !*** ./resources/js/views/DenominationModalView.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_denominations_table__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _components/denominations-table */ "./resources/js/components/denominations-table/index.js");


var DenominationModalViewPrototype = {
  init: function init(container, tableName) {
    var tableContainer = container.querySelector('table');
    this.table = new _components_denominations_table__WEBPACK_IMPORTED_MODULE_1__["default"]();
    this.table.init(tableContainer, this.name, this.presenter.currency);
    container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
  },
  getTotal: function getTotal() {
    var total = this.table.getTotal();
    console.log(total);
  },
  clickEventHandlerWrapper: function clickEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.clickOnModal({
        target: event.target
      });
    };
  }
};

var DenominationModalView = function DenominationModalView(presenter) {
  this.presenter = presenter;
  this.presenter.setView(this);
};

DenominationModalView.prototype = DenominationModalViewPrototype;
DenominationModalView.prototype.constructor = DenominationModalView;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationModalView);

/***/ }),

/***/ "./resources/js/views/ForeignMoneyRecordModalView.js":
/*!***********************************************************!*\
  !*** ./resources/js/views/ForeignMoneyRecordModalView.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MoneyRecordModalView */ "./resources/js/views/MoneyRecordModalView.js");
/* harmony import */ var _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _components/money-record-table/ForeignMoneyRecordTable */ "./resources/js/components/money-record-table/ForeignMoneyRecordTable.js");



var ForeignMoneyRecordModalView = function ForeignMoneyRecordModalView(presenter) {
  _MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, presenter);

  this.updateConvertionCol = function (obj) {
    this.table.updateConvertionCol(obj);
  };

  this.init = function (container, name, table) {
    _MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.init.call(this, container, name, table);
    container.addEventListener("keydown", this.keyDownEventHandlerWrapper(this.presenter));
  };

  this.keyDownEventHandlerWrapper = function (presenter) {
    var _this = this;

    return function (event) {
      _this.presenter.keyDownOnModal({
        target: event.target,
        key: event.key || event.keyCode
      });
    };
  };
};

ForeignMoneyRecordModalView.prototype = Object.create(_MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
ForeignMoneyRecordModalView.prototype.constructor = ForeignMoneyRecordModalView;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ForeignMoneyRecordModalView);

/***/ }),

/***/ "./resources/js/views/MoneyRecordModalView.js":
/*!****************************************************!*\
  !*** ./resources/js/views/MoneyRecordModalView.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var MoneyRecordModalViewPrototype = {
  init: function init(container, name, table) {
    if (!container || !table || !this.presenter) {
      return false;
    }

    var tableContainer = container.querySelector('table');
    this.table = table;
    this.table.init(tableContainer, name, this.presenter.currency);
    container.addEventListener("keypress", this.keyPressEventHandlerWrapper(this.presenter));
    container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
  },
  resetLastInput: function resetLastInput(id) {
    this.table.resetLastInput(id);
  },
  addRow: function addRow(obj) {
    this.table.addRow(obj);
  },
  deleteRow: function deleteRow(id) {
    this.table.deleteRow(id);
  },
  keyPressEventHandlerWrapper: function keyPressEventHandlerWrapper(presenter) {
    return function (event) {
      event.preventDefault();
      presenter.keyPressedOnModal({
        target: event.target,
        key: event.key || event.keyCode
      });
    };
  },
  clickEventHandlerWrapper: function clickEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.clickOnModal({
        target: event.target
      });
    };
  }
};

var MoneyRecordModalView = function MoneyRecordModalView(presenter) {
  this.presenter = presenter;
  this.presenter.setView(this);
};

MoneyRecordModalView.prototype = MoneyRecordModalViewPrototype;
MoneyRecordModalView.prototype.constructor = MoneyRecordModalView;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MoneyRecordModalView);

/***/ }),

/***/ "./node_modules/pubsub-js/src/pubsub.js":
/*!**********************************************!*\
  !*** ./node_modules/pubsub-js/src/pubsub.js ***!
  \**********************************************/
/***/ (function(module, exports, __webpack_require__) {

/* module decorator */ module = __webpack_require__.nmd(module);
/**
 * Copyright (c) 2010,2011,2012,2013,2014 Morgan Roderick http://roderick.dk
 * License: MIT - http://mrgnrdrck.mit-license.org
 *
 * https://github.com/mroderick/PubSubJS
 */

(function (root, factory){
    'use strict';

    var PubSub = {};

    if (root.PubSub) {
        PubSub = root.PubSub;
        console.warn("PubSub already loaded, using existing version");
    } else {
        root.PubSub = PubSub;
        factory(PubSub);
    }
    // CommonJS and Node.js module support
    if (true){
        if (module !== undefined && module.exports) {
            exports = module.exports = PubSub; // Node.js specific `module.exports`
        }
        exports.PubSub = PubSub; // CommonJS module 1.1.1 spec
        module.exports = exports = PubSub; // CommonJS
    }
    // AMD support
    /* eslint-disable no-undef */
    else {}

}(( typeof window === 'object' && window ) || this, function (PubSub){
    'use strict';

    var messages = {},
        lastUid = -1,
        ALL_SUBSCRIBING_MSG = '*';

    function hasKeys(obj){
        var key;

        for (key in obj){
            if ( Object.prototype.hasOwnProperty.call(obj, key) ){
                return true;
            }
        }
        return false;
    }

    /**
     * Returns a function that throws the passed exception, for use as argument for setTimeout
     * @alias throwException
     * @function
     * @param { Object } ex An Error object
     */
    function throwException( ex ){
        return function reThrowException(){
            throw ex;
        };
    }

    function callSubscriberWithDelayedExceptions( subscriber, message, data ){
        try {
            subscriber( message, data );
        } catch( ex ){
            setTimeout( throwException( ex ), 0);
        }
    }

    function callSubscriberWithImmediateExceptions( subscriber, message, data ){
        subscriber( message, data );
    }

    function deliverMessage( originalMessage, matchedMessage, data, immediateExceptions ){
        var subscribers = messages[matchedMessage],
            callSubscriber = immediateExceptions ? callSubscriberWithImmediateExceptions : callSubscriberWithDelayedExceptions,
            s;

        if ( !Object.prototype.hasOwnProperty.call( messages, matchedMessage ) ) {
            return;
        }

        for (s in subscribers){
            if ( Object.prototype.hasOwnProperty.call(subscribers, s)){
                callSubscriber( subscribers[s], originalMessage, data );
            }
        }
    }

    function createDeliveryFunction( message, data, immediateExceptions ){
        return function deliverNamespaced(){
            var topic = String( message ),
                position = topic.lastIndexOf( '.' );

            // deliver the message as it is now
            deliverMessage(message, message, data, immediateExceptions);

            // trim the hierarchy and deliver message to each level
            while( position !== -1 ){
                topic = topic.substr( 0, position );
                position = topic.lastIndexOf('.');
                deliverMessage( message, topic, data, immediateExceptions );
            }

            deliverMessage(message, ALL_SUBSCRIBING_MSG, data, immediateExceptions);
        };
    }

    function hasDirectSubscribersFor( message ) {
        var topic = String( message ),
            found = Boolean(Object.prototype.hasOwnProperty.call( messages, topic ) && hasKeys(messages[topic]));

        return found;
    }

    function messageHasSubscribers( message ){
        var topic = String( message ),
            found = hasDirectSubscribersFor(topic) || hasDirectSubscribersFor(ALL_SUBSCRIBING_MSG),
            position = topic.lastIndexOf( '.' );

        while ( !found && position !== -1 ){
            topic = topic.substr( 0, position );
            position = topic.lastIndexOf( '.' );
            found = hasDirectSubscribersFor(topic);
        }

        return found;
    }

    function publish( message, data, sync, immediateExceptions ){
        message = (typeof message === 'symbol') ? message.toString() : message;

        var deliver = createDeliveryFunction( message, data, immediateExceptions ),
            hasSubscribers = messageHasSubscribers( message );

        if ( !hasSubscribers ){
            return false;
        }

        if ( sync === true ){
            deliver();
        } else {
            setTimeout( deliver, 0 );
        }
        return true;
    }

    /**
     * Publishes the message, passing the data to it's subscribers
     * @function
     * @alias publish
     * @param { String } message The message to publish
     * @param {} data The data to pass to subscribers
     * @return { Boolean }
     */
    PubSub.publish = function( message, data ){
        return publish( message, data, false, PubSub.immediateExceptions );
    };

    /**
     * Publishes the message synchronously, passing the data to it's subscribers
     * @function
     * @alias publishSync
     * @param { String } message The message to publish
     * @param {} data The data to pass to subscribers
     * @return { Boolean }
     */
    PubSub.publishSync = function( message, data ){
        return publish( message, data, true, PubSub.immediateExceptions );
    };

    /**
     * Subscribes the passed function to the passed message. Every returned token is unique and should be stored if you need to unsubscribe
     * @function
     * @alias subscribe
     * @param { String } message The message to subscribe to
     * @param { Function } func The function to call when a new message is published
     * @return { String }
     */
    PubSub.subscribe = function( message, func ){
        if ( typeof func !== 'function'){
            return false;
        }

        message = (typeof message === 'symbol') ? message.toString() : message;

        // message is not registered yet
        if ( !Object.prototype.hasOwnProperty.call( messages, message ) ){
            messages[message] = {};
        }

        // forcing token as String, to allow for future expansions without breaking usage
        // and allow for easy use as key names for the 'messages' object
        var token = 'uid_' + String(++lastUid);
        messages[message][token] = func;

        // return token for unsubscribing
        return token;
    };

    PubSub.subscribeAll = function( func ){
        return PubSub.subscribe(ALL_SUBSCRIBING_MSG, func);
    };

    /**
     * Subscribes the passed function to the passed message once
     * @function
     * @alias subscribeOnce
     * @param { String } message The message to subscribe to
     * @param { Function } func The function to call when a new message is published
     * @return { PubSub }
     */
    PubSub.subscribeOnce = function( message, func ){
        var token = PubSub.subscribe( message, function(){
            // before func apply, unsubscribe message
            PubSub.unsubscribe( token );
            func.apply( this, arguments );
        });
        return PubSub;
    };

    /**
     * Clears all subscriptions
     * @function
     * @public
     * @alias clearAllSubscriptions
     */
    PubSub.clearAllSubscriptions = function clearAllSubscriptions(){
        messages = {};
    };

    /**
     * Clear subscriptions by the topic
     * @function
     * @public
     * @alias clearAllSubscriptions
     * @return { int }
     */
    PubSub.clearSubscriptions = function clearSubscriptions(topic){
        var m;
        for (m in messages){
            if (Object.prototype.hasOwnProperty.call(messages, m) && m.indexOf(topic) === 0){
                delete messages[m];
            }
        }
    };

    /**
       Count subscriptions by the topic
     * @function
     * @public
     * @alias countSubscriptions
     * @return { Array }
    */
    PubSub.countSubscriptions = function countSubscriptions(topic){
        var m;
        // eslint-disable-next-line no-unused-vars
        var token;
        var count = 0;
        for (m in messages) {
            if (Object.prototype.hasOwnProperty.call(messages, m) && m.indexOf(topic) === 0) {
                for (token in messages[m]) {
                    count++;
                }
                break;
            }
        }
        return count;
    };


    /**
       Gets subscriptions by the topic
     * @function
     * @public
     * @alias getSubscriptions
    */
    PubSub.getSubscriptions = function getSubscriptions(topic){
        var m;
        var list = [];
        for (m in messages){
            if (Object.prototype.hasOwnProperty.call(messages, m) && m.indexOf(topic) === 0){
                list.push(m);
            }
        }
        return list;
    };

    /**
     * Removes subscriptions
     *
     * - When passed a token, removes a specific subscription.
     *
	 * - When passed a function, removes all subscriptions for that function
     *
	 * - When passed a topic, removes all subscriptions for that topic (hierarchy)
     * @function
     * @public
     * @alias subscribeOnce
     * @param { String | Function } value A token, function or topic to unsubscribe from
     * @example // Unsubscribing with a token
     * var token = PubSub.subscribe('mytopic', myFunc);
     * PubSub.unsubscribe(token);
     * @example // Unsubscribing with a function
     * PubSub.unsubscribe(myFunc);
     * @example // Unsubscribing from a topic
     * PubSub.unsubscribe('mytopic');
     */
    PubSub.unsubscribe = function(value){
        var descendantTopicExists = function(topic) {
                var m;
                for ( m in messages ){
                    if ( Object.prototype.hasOwnProperty.call(messages, m) && m.indexOf(topic) === 0 ){
                        // a descendant of the topic exists:
                        return true;
                    }
                }

                return false;
            },
            isTopic    = typeof value === 'string' && ( Object.prototype.hasOwnProperty.call(messages, value) || descendantTopicExists(value) ),
            isToken    = !isTopic && typeof value === 'string',
            isFunction = typeof value === 'function',
            result = false,
            m, message, t;

        if (isTopic){
            PubSub.clearSubscriptions(value);
            return;
        }

        for ( m in messages ){
            if ( Object.prototype.hasOwnProperty.call( messages, m ) ){
                message = messages[m];

                if ( isToken && message[value] ){
                    delete message[value];
                    result = value;
                    // tokens are unique, so we can just stop here
                    break;
                }

                if (isFunction) {
                    for ( t in message ){
                        if (Object.prototype.hasOwnProperty.call(message, t) && message[t] === value){
                            delete message[t];
                            result = true;
                        }
                    }
                }
            }
        }

        return result;
    };
}));


/***/ })

}]);