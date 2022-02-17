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

/***/ "./resources/js/collections/bankCollection.js":
/*!****************************************************!*\
  !*** ./resources/js/collections/bankCollection.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./collection */ "./resources/js/collections/collection.js");


var BankCollection = function BankCollection() {
  var banks = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  _collection__WEBPACK_IMPORTED_MODULE_0__["default"].call(this);
  this.elements = banks ? banks : [];
};

BankCollection.prototype = Object.create(_collection__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
BankCollection.prototype.constructor = BankCollection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BankCollection);

/***/ }),

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
  getAll: function getAll() {
    return this.elements;
  },
  getLength: function getLength() {
    return this.elements.length;
  },
  setElements: function setElements(elements) {
    this.elements = elements ? elements : [];
  },
  getElement: function getElement(index) {
    if (index > -1 && index < this.elements.length) {
      return null;
    }

    return this.elements[index];
  },
  deleteElementByName: function deleteElementByName(name) {
    var index = this.elements.findIndex(function (val) {
      return val === name;
    });

    if (index !== -1) {
      this.elements.splice(index, 1);
    }
  },
  pushElement: function pushElement(name) {
    this.elements.push(name);
    return this.elements;
  },
  shiftElement: function shiftElement() {
    return this.elements.shift();
  }
};

var Collection = function Collection() {};

Collection.prototype = CollectionPrototype;
Collection.prototype.constructor = Collection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Collection);

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
          pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("getTotal.records.".concat(currency));
        }
      }
    };
  }
};

var LiquidMoneyModal = function LiquidMoneyModal() {};

LiquidMoneyModal.prototype = LiquidMoneyModalPrototype;
LiquidMoneyModal.prototype.constructor = LiquidMoneyModal;
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
/* harmony import */ var _components_decimal_input__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _components/decimal-input */ "./resources/js/components/decimal-input/index.js");
/* harmony import */ var _assets_currencies__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _assets/currencies */ "./resources/js/assets/currencies.js");





var CashRegisterTable = function CashRegisterTable(tableName, currency) {
  var _this = this;

  var rowsCount = 1;
  var idsList = [0];
  this.tableName = tableName || "";
  this.currency = currency || "dollar";

  this.init = function (container) {
    _this.container = container;

    if (pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().getSubscriptions('attachMask').length === 0) {
      var decimalInputDollar = new _components_decimal_input__WEBPACK_IMPORTED_MODULE_2__["default"]();
      decimalInputDollar.init();
    }

    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("addRow.".concat(_this.currency), addRow);
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("deleteRow.".concat(_this.currency), deleteRow);
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("getTotal.records.".concat(_this.currency), getTotal);
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
      pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('attachMask', {
        input: input,
        currency: _this.currency
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
    document.getElementById("total_".concat(_this.tableName)).value = total > 0 ? total : 0;
  };

  var addRow = function addRow(msg, data) {
    if (!_this.container) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    tBody.insertAdjacentHTML('beforeend', tableRowTemplate(_this.tableName, _this.currency));
    var input = tBody.querySelector("#".concat(_this.tableName, "_").concat(getNewID()));
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('attachMask', {
      input: input,
      currency: _this.currency
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
      columnData.innerHTML = "".concat(Math.round((dollarExchangeBs * amount + Number.EPSILON) * 100) / 100, " ").concat(_assets_currencies__WEBPACK_IMPORTED_MODULE_3__["default"].bs);
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
    return "\n        <input type=\"text\" placeholder=\"0.00 ".concat(_assets_currencies__WEBPACK_IMPORTED_MODULE_3__["default"][currency], "\" id=\"").concat(name, "_").concat(getNewID(), "\" name=\"").concat(name, "[]\" class=\"w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">\n    ");
  };

  var tableRowTemplate = function tableRowTemplate(name, currency) {
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(getNewID(), ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(rowsCount + 1, "</td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency), "\n            </td>\n            ").concat(currency !== 'bs' ? "<td data-table=\"convertion-col\" class=\"py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                    0.00 ".concat(_assets_currencies__WEBPACK_IMPORTED_MODULE_3__["default"].bs, "\n                    </td>") : '', "\n            <td class=\"py-4 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-del-row=\"").concat(getNewID(), "\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
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




var DecimalInput = function DecimalInput() {
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
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("attachMask", attachMask);
  };

  var attachMask = function attachMask(msg, data) {
    var currency = data && data !== null && data !== void 0 && data.currency ? data.currency : 'dollar';
    var suffix = _assets_currencies__WEBPACK_IMPORTED_MODULE_2__["default"][currency] || '$';
    var input = data.input;
    decimalMaskOptions['suffix'] = " ".concat(suffix);
    new (inputmask__WEBPACK_IMPORTED_MODULE_1___default())(decimalMaskOptions).mask(input);
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DecimalInput);

/***/ }),

/***/ "./resources/js/components/denominations-modal/index.js":
/*!**************************************************************!*\
  !*** ./resources/js/components/denominations-modal/index.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_denominations_table__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _components/denominations-table */ "./resources/js/components/denominations-table/index.js");


var DenominationsModalPrototype = {
  init: function init(container) {
    var tableContainer = container.querySelector('table');
    var table = new _components_denominations_table__WEBPACK_IMPORTED_MODULE_1__["default"](this.name, this.currency);
    table.init(tableContainer);
    container.addEventListener("click", this.clickEventHandlerWrapper(this.currency));
  },
  clickEventHandlerWrapper: function clickEventHandlerWrapper(currency) {
    return function (event) {
      var closest = event.target.closest('button');

      if (closest && closest.tagName === 'BUTTON') {
        var modaToggleID = closest.getAttribute('data-modal-toggle');

        if (modaToggleID) {
          // Checking if it's closing the modal
          // get all inputs of the modal
          pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish("getTotal.denominations.".concat(currency));
        }
      }
    };
  }
};

var DenominationsModal = function DenominationsModal(name, currency) {
  this.name = name;
  this.currency = currency;
};

DenominationsModal.prototype = DenominationsModalPrototype;
DenominationsModal.prototype.constructor = DenominationsModal;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationsModal);

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
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");



var DenominationsTable = function DenominationsTable(name, currency) {
  var _this = this;

  this.name = name || "";
  this.currency = currency || "dollar";

  this.init = function (container) {
    _this.container = container;
    pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().subscribe("getTotal.denominations.".concat(_this.currency), getTotal);
  };

  var getTotal = function getTotal(msg, data) {
    if (!_this.container) {
      return;
    }

    var tBody = _this.container.querySelector('tBody');

    var inputs = tBody.querySelectorAll('input');
    var total = Array.from(inputs).reduce(function (acc, el) {
      var denomination = parseFloat(el.getAttribute('data-denomination'));
      var num = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_1__.formatAmount)(el.value);
      return acc + num * denomination;
    }, 0);
    document.getElementById("total_".concat(_this.name)).value = total > 0 ? total : 0;
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationsTable);

/***/ }),

/***/ "./resources/js/components/sale-point-modal/index.js":
/*!***********************************************************!*\
  !*** ./resources/js/components/sale-point-modal/index.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_sale_point_table__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _components/sale-point-table */ "./resources/js/components/sale-point-table/index.js");


var SalePointModalPrototype = {
  init: function init(container) {
    var tableContainer = container.querySelector('table');
    var table = new _components_sale_point_table__WEBPACK_IMPORTED_MODULE_1__["default"](this.name, this.currency);
    table.init(tableContainer);
    container.addEventListener('click', this.handleClickEvent);
    container.addEventListener('change', this.handleOnChangeEvent);
  },
  handleClickEvent: function handleClickEvent(event) {
    var closest = event.target.closest('button');

    if (closest && closest.tagName === 'BUTTON') {
      var action = closest.getAttribute('data-modal');

      if (!action) {
        return;
      }

      if (action === 'add') {
        pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('addRow.salePoint');
      } else if (action === 'remove') {// Logic is here
      }
    }
  },
  handleOnChangeEvent: function handleOnChangeEvent(event) {
    var row = event.target.closest('tr');

    if (row && row.getAttribute('data-id')) {
      var rowID = row.getAttribute('data-id'); // Get the selected index

      var index = event.target.selectedIndex; // Get the new value

      var newSelectValue = event.target.options[index].value;
      pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('changeSelect.salePoint', {
        rowID: rowID,
        newSelectValue: newSelectValue
      });
    }
  }
};

var SalePointModal = function SalePointModal(name, currency) {
  this.name = name;
  this.currency = currency;
};

SalePointModal.prototype = SalePointModalPrototype;
SalePointModal.prototype.constructor = SalePointModal;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SalePointModal);

/***/ }),

/***/ "./resources/js/components/sale-point-table/index.js":
/*!***********************************************************!*\
  !*** ./resources/js/components/sale-point-table/index.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _app_collections_bankCollection__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _app/collections/bankCollection */ "./resources/js/collections/bankCollection.js");
/* harmony import */ var _assets_currencies__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _assets/currencies */ "./resources/js/assets/currencies.js");
/* harmony import */ var _services_banks__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! _services/banks */ "./resources/js/services/banks/index.js");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }



function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }






var SalePointTable = function SalePointTable(name, currency) {
  var _this = this;

  this.name = name || "";
  this.currency = currency || "dollar";
  var rowsCount = 0;
  var idsList = [];
  var oldValueSelects = {};

  this.init = function (container) {
    _this.container = container;
    _this.banks = new _app_collections_bankCollection__WEBPACK_IMPORTED_MODULE_2__["default"]();
    console.log(_this); // fetchInitialData().then(res => {
    //     this.banks = res.banks
    // }).catch(err => {
    //     console.log(err)
    // });

    pubsub_js__WEBPACK_IMPORTED_MODULE_1___default().subscribe('addRow.salePoint', _this.addRow);
    pubsub_js__WEBPACK_IMPORTED_MODULE_1___default().subscribe('changeSelect.salePoint', _this.changeSelect);
  };

  var fetchInitialData = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
      var _banks;

      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              _context.next = 3;
              return (0,_services_banks__WEBPACK_IMPORTED_MODULE_4__.getAllBanks)();

            case 3:
              _banks = _context.sent;
              return _context.abrupt("return", {
                banks: _banks
              });

            case 7:
              _context.prev = 7;
              _context.t0 = _context["catch"](0);
              return _context.abrupt("return", {
                banks: []
              });

            case 10:
            case "end":
              return _context.stop();
          }
        }
      }, _callee, null, [[0, 7]]);
    }));

    return function fetchInitialData() {
      return _ref.apply(this, arguments);
    };
  }();

  this.addRow = function (msg, data) {
    console.log(this); // if (this.banks.getLength() === 0 || !this.container){
    //     return;
    // }

    var tBody = this.container.querySelector("tbody");
    tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.name, this.currency));
    var input_debit = tBody.querySelector("#".concat(this.name, "_debit_").concat(getNewID()));
    var input_credit = tBody.querySelector("#".concat(this.name, "_credit_").concat(getNewID()));
    pubsub_js__WEBPACK_IMPORTED_MODULE_1___default().publish('attachMask', {
      input: input_debit,
      currency: this.currency
    });
    pubsub_js__WEBPACK_IMPORTED_MODULE_1___default().publish('attachMask', {
      input: input_credit,
      currency: this.currency
    });
    rowsCount++;
    var rowsIDS = Object.keys(oldValueSelects);

    if (rowsIDS.length > 0) {
      var selectors = getBankSelectSelectors(rowsIDS);
      updateBankSelects(tBody, selectors);
    }

    oldValueSelects[getNewID()] = banks.shiftBank();
    saveNewID();
  };

  this.changeSelect = function (msg, data) {
    var rowsIDS = Object.keys(oldValueSelects);
    var rowID = data.rowID;
    var newValue = data.newSelectValue;

    if (rowsIDS.length === 1) {
      return false;
    }

    if (!rowID) {
      return false;
    }

    if (oldValueSelects[rowID] === undefined) {
      return false;
    }

    if (this.banks.getLength === 0 || !this.container) {
      return;
    }

    var tBody = this.container.querySelector("tbody"); // Old value is pushed again in collection

    banks.pushElement(oldValueSelects[rowID]); // Remove the new value from available banks

    banks.deleteElement(newValue); // Set the new value in old value select

    oldValueSelects[rowID] = newValue;
    var selectors = getBankSelectSelectors(rowsIDS);
    updateBankSelects(tBody, selectors);
  };

  var inputTemplate = function inputTemplate(name, currency, type) {
    return "\n        <input type=\"text\" placeholder=\"0.00 ".concat(_assets_currencies__WEBPACK_IMPORTED_MODULE_3__["default"][currency], "\" id=\"").concat(name, "_").concat(type, "_").concat(getNewID(), "\" name=\"").concat(name, "_").concat(type, "[]\" class=\"w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">\n    ");
  };

  var tableRowTemplate = function tableRowTemplate(name) {
    var currency = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'bs';
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(getNewID(), ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(rowsCount + 1, "</td>\n            <td class=\"pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                <select class=\"w-full form-select\" name=\"point_sale_bs_bank[]\">\n                    ").concat(_this.banks.getElements().map(function (el) {
      return "<option value=\"".concat(el, "\">").concat(el, "</option>");
    }).join(''), "\n                </select>\n            </td>\n            <td class=\"pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency, 'debit'), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"pl-3 py-4 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency, 'credit'), "\n            </td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-modal=\"delete\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
  };

  var getBankSelectSelectors = function getBankSelectSelectors(rowsIDS) {
    if (!rowsIDS) {
      return '';
    }

    ;

    if (rowsIDS.length === 0) {
      return '';
    }

    ;
    return rowsIDS.map(function (el) {
      return "tr[data-id=\"".concat(el, "\"] select");
    }).join(',');
  };

  var updateBankSelects = function updateBankSelects(container, selectors) {
    if (selectors === '') {
      return false;
    }

    ;
    var selectSelectorsElems = container.querySelectorAll(selectors);
    selectSelectorsElems.forEach(function (el) {
      var options = [el.value].concat(_toConsumableArray(banks.getElements()));
      var html = options.map(function (el) {
        return "<option value=\"".concat(el, "\">").concat(el, "</option>");
      }).join('');
      el.innerHTML = html;
    });
    return true;
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

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SalePointTable);

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
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! pubsub-js */ "./node_modules/pubsub-js/src/pubsub.js");
/* harmony import */ var pubsub_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(pubsub_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_cash_register_modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _components/cash-register-modal */ "./resources/js/components/cash-register-modal/index.js");
/* harmony import */ var _components_denominations_modal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _components/denominations-modal */ "./resources/js/components/denominations-modal/index.js");
/* harmony import */ var _components_sale_point_modal__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _components/sale-point-modal */ "./resources/js/components/sale-point-modal/index.js");
/* harmony import */ var _components_decimal_input__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! _components/decimal-input */ "./resources/js/components/decimal-input/index.js");





/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__() {
  // Decimal Input Subscribers
  var decimalInputDollar = new _components_decimal_input__WEBPACK_IMPORTED_MODULE_4__["default"]();
  decimalInputDollar.init(); // Containers

  var liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
  var liquidMoneyDollarRegisterModal = document.querySelector('#liquid_money_dollars'); // Cash register modal factory

  var liquidMoneyModalFactory = new _components_cash_register_modal__WEBPACK_IMPORTED_MODULE_1__["default"]();
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
  pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('attachMask', {
    input: totalLiquidMoneyBolivares,
    currency: 'bs'
  });
  pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('attachMask', {
    input: totalLiquidMoneyDollars,
    currency: 'dollar'
  }); // Denominations modals Containers

  var bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
  var dollarDenominationsModal = document.querySelector('#liquid_money_dollars_denominations');
  var bsDenominations = new _components_denominations_modal__WEBPACK_IMPORTED_MODULE_2__["default"]('liquid_money_bolivares_denominations', 'bs');
  bsDenominations.init(bsDenominationsModal);
  var dollarDenominations = new _components_denominations_modal__WEBPACK_IMPORTED_MODULE_2__["default"]('liquid_money_dollars_denominations', 'dollar');
  dollarDenominations.init(dollarDenominationsModal); // Total inputs

  var totalbsDenominations = document.querySelector('#total_liquid_money_bolivares_denominations');
  var totaldollarDenominations = document.querySelector('#total_liquid_money_dollars_denominations');
  pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('attachMask', {
    input: totalbsDenominations,
    currency: 'bs'
  });
  pubsub_js__WEBPACK_IMPORTED_MODULE_0___default().publish('attachMask', {
    input: totaldollarDenominations,
    currency: 'dollar'
  }); // Sale points

  var bsSalePointModal = document.querySelector('#point_sale_bs');
  var bsSalePoint = new _components_sale_point_modal__WEBPACK_IMPORTED_MODULE_3__["default"]('point_sale_bs', 'bs');
  bsSalePoint.init(bsSalePointModal);
}

/***/ }),

/***/ "./resources/js/services/banks/index.js":
/*!**********************************************!*\
  !*** ./resources/js/services/banks/index.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getAllBanks": () => (/* binding */ getAllBanks)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utilities_axiosClient__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utilities/axiosClient */ "./resources/js/utilities/axiosClient.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }



var getAllBanks = /*#__PURE__*/function () {
  var _ref = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee(obj) {
    var result;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            _context.prev = 0;
            _context.next = 3;
            return _utilities_axiosClient__WEBPACK_IMPORTED_MODULE_1__["default"].get('/banks');

          case 3:
            result = _context.sent;
            return _context.abrupt("return", result.data.data);

          case 7:
            _context.prev = 7;
            _context.t0 = _context["catch"](0);
            console.log(_context.t0);

          case 10:
          case "end":
            return _context.stop();
        }
      }
    }, _callee, null, [[0, 7]]);
  }));

  return function getAllBanks(_x) {
    return _ref.apply(this, arguments);
  };
}();



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