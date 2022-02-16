(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/cash_register_create"],{

/***/ "./resources/js/assets/currencies.js":
/*!*******************************************!*\
  !*** ./resources/js/assets/currencies.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  'dollar': '$',
  'bs': 'Bs.s'
});

/***/ }),

/***/ "./resources/js/components/cash-register-modal/index.js":
/*!**************************************************************!*\
  !*** ./resources/js/components/cash-register-modal/index.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./types */ "./resources/js/components/cash-register-modal/types/index.js");

var CURRENCIES = {
  'bs': _types__WEBPACK_IMPORTED_MODULE_0__.LiquidMoneyModalBolivares,
  'dollar': _types__WEBPACK_IMPORTED_MODULE_0__.LiquidMoneyModalDollars
};

var LiquidMoneyModalFactory = function LiquidMoneyModalFactory() {
  // Our Factory method for creating new Modal instances
  this.create = function (options) {
    this.modalClass = CURRENCIES[options.currency] || _types__WEBPACK_IMPORTED_MODULE_0__.LiquidMoneyModalBolivares;
    return new this.modalClass(options);
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LiquidMoneyModalFactory);

/***/ }),

/***/ "./resources/js/components/cash-register-modal/types/LiquidMoneyModal.js":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/cash-register-modal/types/LiquidMoneyModal.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_cash_register_table__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _components/cash-register-table */ "./resources/js/components/cash-register-table/index.js");


var LiquidMoneyModalPrototype = {
  init: function init(container, tableName) {
    container.addEventListener("keypress", this.keypressEventHandler);
    container.addEventListener("click", this.clickEventHandlerWrapper(this.currency));
    var tableContainer = container.querySelector('table');
    var table = new _components_cash_register_table__WEBPACK_IMPORTED_MODULE_1__["default"](tableName, this.currency);
    table.init(tableContainer);
  },
  clickEventHandlerWrapper: function clickEventHandlerWrapper(currency) {
    // Closure
    return function (event) {
      var button = event.target.closest('button');

      if (button && button.tagName === 'BUTTON') {
        var rowID = button.getAttribute('data-del-row');
        var modalToggleID = button.getAttribute('data-modal-toggle');

        if (rowID) {
          // Checking if it's Deleting a row
          var row = button.closest('tr');
          pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("deleteRow.".concat(currency), {
            row: row,
            rowID: rowID
          });
        } else if (modalToggleID) {
          // Checking if it's closing the modal
          pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("getTotal.".concat(currency));
        }
      }
    };
  }
};

var LiquidMoneyModal = function LiquidMoneyModal() {};

LiquidMoneyModal.prototype = LiquidMoneyModalPrototype;
LiquidMoneyModal.prototype.constructor = LiquidMoneyModal; // LiquidMoneyModal.prototype.init =  function (container, tableName){
//     container.addEventListener("keypress", this.keypressEventHandler);
//     container.addEventListener("click", this.clickEventHandler);
//     const tableContainer = container.querySelector('table')
//     const table = new CashRegisterTable(tableName);
//     table.init(tableContainer);
// }
// LiquidMoneyModal.prototype.clickEventHandler = function clickEventHandler(event){
//     const button = event.target.closest('button');
//     if(button && button.tagName === 'BUTTON'){
//         const rowID = button.getAttribute('data-del-row');
//         const modalToggleID = button.getAttribute('data-modal-toggle');
//         if (rowID){ // Checking if it's Deleting a row
//             const row = button.closest('tr');
//             PubSub.publish('deleteRow', { row, rowID});
//         } else if (modalToggleID){ // Checking if it's closing the modal
//             // get all inputs of the modal
//             let inputs = document.querySelectorAll(`#${this.id} input`)
//             const total = Array.from(inputs).reduce((acc, el) => {
//                 let num = formatAmount(el.value)
//                 return acc + num;
//             }, 0);
//             document.getElementById(`total_${this.id}`).value = total > 0 ? total : 0;
//         }
//     }
// }

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LiquidMoneyModal);

/***/ }),

/***/ "./resources/js/components/cash-register-modal/types/LiquidMoneyModalBolivares.js":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/cash-register-modal/types/LiquidMoneyModalBolivares.js ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LiquidMoneyModal */ "./resources/js/components/cash-register-modal/types/LiquidMoneyModal.js");



var LiquidMoneyModalBolivares = function LiquidMoneyModalBolivares(_ref) {
  var currency = _ref.currency;
  _LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_1__["default"].call(this);
  this.currency = currency || 'Bs.S';

  this.init = function (container) {
    _LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_1__["default"].prototype.init.call(this, container, "liquid_money_bolivares");
  };

  this.keypressEventHandler = function (event) {
    event.preventDefault();
    var key = event.key || event.keyCode;

    if (key === 13 || key === 'Enter') {
      // Handle new table's row creation
      pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("addRow.".concat(currency));
    }
  };
};

LiquidMoneyModalBolivares.prototype = Object.create(_LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_1__["default"].prototype);
LiquidMoneyModalBolivares.prototype.constructor = LiquidMoneyModalBolivares;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LiquidMoneyModalBolivares);

/***/ }),

/***/ "./resources/js/components/cash-register-modal/types/LiquidMoneyModalDollars.js":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/cash-register-modal/types/LiquidMoneyModalDollars.js ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
/* harmony import */ var _LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./LiquidMoneyModal */ "./resources/js/components/cash-register-modal/types/LiquidMoneyModal.js");




var LiquidMoneyModalDollars = function LiquidMoneyModalDollars(_ref) {
  var currency = _ref.currency;
  this.currency = currency || '$';
  _LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_2__["default"].call(this);

  this.init = function (container) {
    _LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_2__["default"].prototype.init.call(this, container, "liquid_money_dollars");
    container.addEventListener("keydown", this.keyDownEventHandler);
  };

  var handleUpdateConvertionColEvent = function handleUpdateConvertionColEvent(event) {
    var row = event.target.closest('tr');
    var lastDollarExchangeValEl = document.querySelector("#last-dollar-exchange-bs-val");
    var lastDollarExchangeVal = lastDollarExchangeValEl ? parseFloat(lastDollarExchangeValEl.value) : 0;
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('updateConvertionCol', {
      row: row,
      lastDollarExchangeVal: lastDollarExchangeVal,
      amount: (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__.formatAmount)(event.target.value)
    });
  };

  this.keypressEventHandler = function (event) {
    event.preventDefault();
    var key = event.key || event.keyCode;

    if (isFinite(key)) {
      // Handle case to convert dollar to Bs.S
      handleUpdateConvertionColEvent(event);
    } else if (key === 13 || key === 'Enter') {
      // Handle new table's row creation
      pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("addRow.".concat(currency));
    }
  };

  this.keyDownEventHandler = function (event) {
    var key = event.key || event.keyCode;

    if (key === 8 || key === 'Backspace') {
      // Handle case to convert dollar to Bs.S
      handleUpdateConvertionColEvent(event);
    }
  };
};

LiquidMoneyModalDollars.prototype = Object.create(_LiquidMoneyModal__WEBPACK_IMPORTED_MODULE_2__["default"].prototype);
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LiquidMoneyModalDollars);

/***/ }),

/***/ "./resources/js/components/cash-register-modal/types/index.js":
/*!********************************************************************!*\
  !*** ./resources/js/components/cash-register-modal/types/index.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "LiquidMoneyModalBolivares": () => (/* reexport safe */ _LiquidMoneyModalBolivares__WEBPACK_IMPORTED_MODULE_0__["default"]),
/* harmony export */   "LiquidMoneyModalDollars": () => (/* reexport safe */ _LiquidMoneyModalDollars__WEBPACK_IMPORTED_MODULE_1__["default"])
/* harmony export */ });
/* harmony import */ var _LiquidMoneyModalBolivares__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LiquidMoneyModalBolivares */ "./resources/js/components/cash-register-modal/types/LiquidMoneyModalBolivares.js");
/* harmony import */ var _LiquidMoneyModalDollars__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LiquidMoneyModalDollars */ "./resources/js/components/cash-register-modal/types/LiquidMoneyModalDollars.js");



/***/ }),

/***/ "./resources/js/components/cash-register-table/index.js":
/*!**************************************************************!*\
  !*** ./resources/js/components/cash-register-table/index.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
/* harmony import */ var _assets_currencies__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _assets/currencies */ "./resources/js/assets/currencies.js");




var CashRegisterTable = function CashRegisterTable(tableName, currency) {
  var _this = this;

  var rowsCount = 1;
  var idsList = [0];
  this.tableName = tableName || "";
  this.currency = currency || "dollar";

  this.init = function (container) {
    _this.container = container;
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("addRow.".concat(_this.currency), addRow);
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("deleteRow.".concat(_this.currency), deleteRow);
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("getTotal.".concat(_this.currency), getTotal);
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe('updateConvertionCol', updateConvertionCol);
    setInitialMask();
  };

  var setInitialMask = function setInitialMask() {
    if (!_this.container) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    if (tBody && tBody.children.length === 1) {
      var input = tBody.querySelector('input');
      pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("attachMask.".concat(_this.currency), {
        input: input
      });
    }
  };

  var getTotal = function getTotal(msg, data) {
    if (!_this.container) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    var inputs = tBody.querySelectorAll("input");
    var total = Array.from(inputs).reduce(function (acc, el) {
      var num = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__.formatAmount)(el.value);
      return acc + num;
    }, 0);
    console.log("total_".concat(_this.tableName));
    document.getElementById("total_".concat(_this.tableName)).value = total > 0 ? total : 0;
  };

  var addRow = function addRow(msg, data) {
    if (!_this.container) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    tBody.insertAdjacentHTML('beforeend', tableRowTemplate(_this.tableName, _this.currency));
    var input = document.querySelector("#".concat(_this.tableName, "_").concat(getNewID()));
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("attachMask.".concat(_this.currency), {
      input: input
    });
    rowsCount++;
    saveNewID();
  };

  var deleteRow = function deleteRow(msg, data) {
    if (!_this.container) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    var row = data.row;
    var rowID = data.rowID;

    if (tBody.children.length === 1) {
      var input = row.querySelector("#".concat(_this.tableName, "_").concat(rowID));

      if (input && input !== null && input !== void 0 && input.inputmask) {
        input.value = 0; // Update convertion col only in foreign currency tables

        var convertionCol = row.querySelector('td[data-table="convertion-col"]');

        if (convertionCol) {
          convertionCol.innerHTML = '0.00 Bs.s';
        }
      }
    } else {
      tBody.removeChild(row);
      rowsCount--;
      removeID(rowID);
      updateTableIDColumn();
    }
  };

  var updateConvertionCol = function updateConvertionCol(msg, data) {
    var rowElement = data.row;
    var dollarExchangeBs = data.lastDollarExchangeVal;
    var amount = data.amount;
    var columnData = rowElement.querySelector('td[data-table="convertion-col"]');

    if (columnData) {
      columnData.innerHTML = "".concat(Math.round((dollarExchangeBs * amount + Number.EPSILON) * 100) / 100, " ").concat(_assets_currencies__WEBPACK_IMPORTED_MODULE_2__["default"].bs);
    }
  };

  var updateTableIDColumn = function updateTableIDColumn() {
    if (!_this.container) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    var colsID = tBody.querySelectorAll("td[data-table=\"num-col\"]");

    for (var i = 0; i < rowsCount; i++) {
      colsID[i].innerHTML = i + 1;
    }
  };

  var inputTemplate = function inputTemplate(name, currency) {
    return "\n        <input type=\"text\" placeholder=\"0.00 ".concat(_assets_currencies__WEBPACK_IMPORTED_MODULE_2__["default"][currency], "\" id=\"").concat(name, "_").concat(getNewID(), "\" name=\"").concat(name, "[]\" class=\"w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">\n    ");
  };

  var tableRowTemplate = function tableRowTemplate(name, currency) {
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(getNewID(), ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(rowsCount + 1, "</td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency), "\n            </td>\n            ").concat(currency !== 'bs' ? "<td data-table=\"convertion-col\" class=\"py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                    0.00 ".concat(_assets_currencies__WEBPACK_IMPORTED_MODULE_2__["default"].bs, "\n                    </td>") : '', "\n            <td class=\"py-4 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-del-row=\"").concat(getNewID(), "\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
  };

  var getNewID = function getNewID() {
    return idsList.length === 0 ? 0 : idsList[idsList.length - 1] + 1;
  };

  var saveNewID = function saveNewID() {
    idsList.push(getNewID());
  };

  var removeID = function removeID(id) {
    var index = idsList.findIndex(function (val) {
      return val == id;
    });
    return index !== -1 ? idsList.slice(index, 1) : -1;
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CashRegisterTable);

/***/ }),

/***/ "./resources/js/components/decimal-input/index.js":
/*!********************************************************!*\
  !*** ./resources/js/components/decimal-input/index.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! inputmask */ "./node_modules/inputmask/dist/inputmask.js");
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(inputmask__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _assets_currencies__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _assets/currencies */ "./resources/js/assets/currencies.js");




var DecimalInput = function DecimalInput(currency) {
  var _this = this;

  this.currency = currency ? currency : 'dollar';
  this.suffix = _assets_currencies__WEBPACK_IMPORTED_MODULE_2__["default"][currency] || '$';
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

  this.init = function () {
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("attachMask.".concat(_this.currency), attachMask);
  };

  var attachMask = function attachMask(msg, data) {
    var input = data.input;
    decimalMaskOptions['suffix'] = " ".concat(_this.suffix);
    new (inputmask__WEBPACK_IMPORTED_MODULE_1___default())(decimalMaskOptions).mask(input);
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DecimalInput);

/***/ }),

/***/ "./resources/js/pages/cash-register/index.js":
/*!***************************************************!*\
  !*** ./resources/js/pages/cash-register/index.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* export default binding */ __WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_cash_register_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _components/cash-register-modal */ "./resources/js/components/cash-register-modal/index.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_decimal_input__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _components/decimal-input */ "./resources/js/components/decimal-input/index.js");



/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__() {
  document.addEventListener('DOMContentLoaded', function () {
    // Decimal Input Subscribers
    var decimalInputDollar = new _components_decimal_input__WEBPACK_IMPORTED_MODULE_2__["default"]('dollar');
    decimalInputDollar.init();
    var decimalInputBs = new _components_decimal_input__WEBPACK_IMPORTED_MODULE_2__["default"]('bs');
    decimalInputBs.init(); // Containers

    var liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
    var liquidMoneyDollarRegisterModal = document.querySelector('#liquid_money_dollars'); // Cash register modal factory

    var liquidMoneyModalFactory = new _components_cash_register_modal__WEBPACK_IMPORTED_MODULE_0__["default"]();
    var liquidMoneyBsRegister = liquidMoneyModalFactory.create({
      currency: 'bs'
    });
    liquidMoneyBsRegister.init(liquidMoneyBsRegisterModal);
    var liquidMoneyDollarRegister = liquidMoneyModalFactory.create({
      currency: 'dollar'
    });
    liquidMoneyDollarRegister.init(liquidMoneyDollarRegisterModal); // Total inputs

    var totalLiquidMoneyBolivares = document.querySelector('#total_liquid_money_bolivares');
    var totalLiquidMoneyDollars = document.querySelector('#total_liquid_money_dollars');
    pubsub_js__WEBPACK_IMPORTED_MODULE_1___default().publish('attachMask.bs', {
      input: totalLiquidMoneyBolivares
    });
    pubsub_js__WEBPACK_IMPORTED_MODULE_1___default().publish('attachMask.dollar', {
      input: totalLiquidMoneyDollars
    });
  });
}

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