"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/cash_register_create"],{

/***/ "./resources/js/collections/bankCollection.js":
/*!****************************************************!*\
  !*** ./resources/js/collections/bankCollection.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _objectCollection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./objectCollection */ "./resources/js/collections/objectCollection.js");


var BankCollection = function BankCollection() {
  var elements = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  _objectCollection__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, elements);
};

BankCollection.prototype = Object.create(_objectCollection__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
BankCollection.prototype.constructor = BankCollection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BankCollection);

/***/ }),

/***/ "./resources/js/collections/collection.js":
/*!************************************************!*\
  !*** ./resources/js/collections/collection.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/collections/denominationRecordCollection.js":
/*!******************************************************************!*\
  !*** ./resources/js/collections/denominationRecordCollection.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _objectCollection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./objectCollection */ "./resources/js/collections/objectCollection.js");


var DenominationRecordCollection = function DenominationRecordCollection() {
  var elements = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  _objectCollection__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, elements);

  this.getIndexByDenomination = function (denomination) {
    return this.elements.findIndex(function (val) {
      return val.denomination === denomination;
    });
  };
};

DenominationRecordCollection.prototype = Object.create(_objectCollection__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
DenominationRecordCollection.prototype.constructor = DenominationRecordCollection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationRecordCollection);

/***/ }),

/***/ "./resources/js/collections/moneyRecordCollection.js":
/*!***********************************************************!*\
  !*** ./resources/js/collections/moneyRecordCollection.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./collection */ "./resources/js/collections/collection.js");
/* harmony import */ var _objectCollection__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./objectCollection */ "./resources/js/collections/objectCollection.js");



var MoneyRecordCollection = function MoneyRecordCollection() {
  var elements = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  _objectCollection__WEBPACK_IMPORTED_MODULE_1__["default"].call(this, elements);
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

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./collection */ "./resources/js/collections/collection.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }



var ObjectCollection = function ObjectCollection(elements) {
  _collection__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, elements);

  this.pushElement = function (el) {
    el.id = el !== null && el !== void 0 && el.id ? el.id : this.getNewID();
    _collection__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.pushElement.call(this, el);
    return el;
  };

  this.getElementByID = function (id) {
    return this.elements.find(function (val) {
      return val.id === id;
    });
  };

  this.getIndexByID = function (id) {
    return this.elements.findIndex(function (val) {
      return val.id === id;
    });
  };

  this.setElementAtIndex = function (index, obj) {
    this.elements[index] = _objectSpread(_objectSpread({}, this.elements[index]), obj);
  };

  this.removeElementByID = function (id) {
    var index = this.elements.findIndex(function (obj) {
      return obj.id === id;
    });

    if (index !== -1) {
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

/***/ "./resources/js/components/cash-register-data/index.js":
/*!*************************************************************!*\
  !*** ./resources/js/components/cash-register-data/index.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var CashRegisterDataPrototype = {
  /**
   * Add event listeners to container
   * @function init
   * @param {Element} container - The DOM Element that wrapping many inputs.
   * @constructor
   */
  init: function init(container) {
    container.addEventListener("change", this.changeEventHandlerWrapper(container));
  },
  changeEventHandlerWrapper: function changeEventHandlerWrapper(container) {
    return function (event) {
      var workersSelectEl = container.querySelector('#cash_register_worker');
      var newCashRegisterWorkerContainer = container.querySelector('#new_cash_register_worker_container');

      if (newCashRegisterWorkerContainer && workersSelectEl) {
        var _newCashRegisterWorke;

        newCashRegisterWorkerContainer.classList.toggle('hidden');
        workersSelectEl.disabled = !workersSelectEl.disabled;
        newCashRegisterWorkerContainer === null || newCashRegisterWorkerContainer === void 0 ? void 0 : (_newCashRegisterWorke = newCashRegisterWorkerContainer.lastElementChild) === null || _newCashRegisterWorke === void 0 ? void 0 : _newCashRegisterWorke.toggleAttribute('required');

        if (workersSelectEl.disabled) {
          workersSelectEl.selectedIndex = "0";
        }
      }
    };
  }
};
/**
 * It represents the cash register data component
 * @constructor
 */

var CashRegisterData = function CashRegisterData() {};

CashRegisterData.prototype = CashRegisterDataPrototype;
CashRegisterData.prototype.constructor = CashRegisterData;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CashRegisterData);

/***/ }),

/***/ "./resources/js/components/denominations-table/index.js":
/*!**************************************************************!*\
  !*** ./resources/js/components/denominations-table/index.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
/* harmony import */ var _utilities_numericInput__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _utilities/numericInput */ "./resources/js/utilities/numericInput.js");
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");




var DenominationsTable = function DenominationsTable() {
  var _this = this;

  this.init = function (container, name, currency) {
    _this.container = container;
    _this.name = name;
    _this.currency = currency || _constants_currencies__WEBPACK_IMPORTED_MODULE_2__.CURRENCIES.DOLLAR;
    setInitialMask();
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

  var setInitialMask = function setInitialMask() {
    if (!_this.isContainerDefined()) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    var inputs = tBody.querySelectorAll('input');
    inputs.forEach(function (el) {
      _utilities_numericInput__WEBPACK_IMPORTED_MODULE_1__["default"].mask(el);
    });
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationsTable);

/***/ }),

/***/ "./resources/js/components/money-record-table/ForeignMoneyRecordTable.js":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/money-record-table/ForeignMoneyRecordTable.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

  this.updateConvertion = function (_ref) {
    var rowID = _ref.rowID,
        formatedConvertion = _ref.formatedConvertion;
    var row = this.container.querySelector("tr[data-id=\"".concat(rowID, "\"]"));
    var columnData = row.querySelector('td[data-table="convertion-col"]');
    columnData.innerHTML = formatedConvertion;
  };

  this.updateConvertionCol = function () {
    var formatedConvertion = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    var tBody = this.container.querySelector('tbody');
    var convertionCol = tBody.querySelectorAll('td[data-table="convertion-col"]');
    convertionCol.forEach(function (el, index) {
      el.innerHTML = formatedConvertion[index];
    });
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

/***/ "./resources/js/components/sale-point-table/index.js":
/*!***********************************************************!*\
  !*** ./resources/js/components/sale-point-table/index.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");
/* harmony import */ var _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _utilities/decimalInput */ "./resources/js/utilities/decimalInput.js");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }




var SalePointTable = function SalePointTable() {
  var _this = this;

  this.init = function (container, name, currency) {
    _this.container = container;
    _this.name = name || "";
    _this.currency = currency || _constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR;
  };

  this.addRow = function (_ref) {
    var prevIDArr = _ref.prevIDArr,
        newID = _ref.newID,
        availableBanks = _ref.availableBanks,
        currentBank = _ref.currentBank,
        totalElements = _ref.totalElements;

    if (!_this.isContainerDefined()) {
      return;
    }

    var tBody = _this.container.querySelector("tbody");

    tBody.insertAdjacentHTML('beforeend', _this.getTableRowTemplate(newID, availableBanks, currentBank, totalElements));
    var input_debit = tBody.querySelector("#".concat(_this.name, "_debit_").concat(newID));
    var input_credit = tBody.querySelector("#".concat(_this.name, "_credit_").concat(newID));

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_1__.decimalInputs[_this.currency].mask(input_debit);

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_1__.decimalInputs[_this.currency].mask(input_credit);

    if (prevIDArr.length > 0) {
      var selectors = getBankSelectSelectors(prevIDArr);
      updateBankSelects(tBody, availableBanks, selectors);
    }
  };

  this.deleteRow = function (_ref2) {
    var prevIDArr = _ref2.prevIDArr,
        deleteID = _ref2.deleteID,
        availableBanks = _ref2.availableBanks,
        totalElements = _ref2.totalElements;

    if (!_this.isContainerDefined()) {
      return;
    }
    /**
     * Eliminar un pv
     * 1. Obtener fila y su id
     * 2. Buscar en los valores antiguos el banco
     * 3. Remover el id de los valores antiguos
     * 4. Agregar banco antiguo a la coleccion
     * 5. Eliminar fila de la tabla
     * 6. Actualizar los selects restantes
     */


    var tBody = _this.container.querySelector('tbody');

    var row = tBody.querySelector("tr[data-id=\"".concat(deleteID, "\"]"));
    tBody.removeChild(row);

    if (prevIDArr.length > 0) {
      var selectors = getBankSelectSelectors(prevIDArr);
      updateBankSelects(tBody, availableBanks, selectors);
      updateTableIDColumn(tBody);
    }
  };

  this.changeSelect = function (_ref3) {
    var prevIDArr = _ref3.prevIDArr,
        availableBanks = _ref3.availableBanks;

    if (!_this.isContainerDefined()) {
      return;
    }

    var tBody = _this.container.querySelector('tbody');

    if (prevIDArr.length > 1) {
      var selectors = getBankSelectSelectors(prevIDArr);
      updateBankSelects(tBody, availableBanks, selectors);
    }
  };

  this.getInputTemplate = function (id, type) {
    return "\n        <input type=\"text\" placeholder=\"0.00 ".concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.SIGN[_this.currency], "\" id=\"").concat(_this.name, "_").concat(type, "_").concat(id, "\" name=\"").concat(_this.name, "_").concat(type, "[]\" class=\"w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">\n    ");
  };

  this.getTableRowTemplate = function (id, availableBanks, currentBank, total) {
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(id, ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(total, "</td>\n            <td class=\"pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                <select class=\"w-full form-select\" name=\"point_sale_bs_bank[]\">\n                    <option value=\"").concat(currentBank, "\">").concat(currentBank, "</option>\n                    ").concat(availableBanks.map(function (el) {
      return "<option value=\"".concat(el, "\">").concat(el, "</option>");
    }).join(''), "\n                </select>\n            </td>\n            <td class=\"pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(_this.getInputTemplate(id, 'debit'), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"pl-3 py-4 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                ").concat(_this.getInputTemplate(id, 'credit'), "\n            </td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-modal=\"delete\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
  };

  this.isContainerDefined = function () {
    return _this.container !== null;
  };

  var getBankSelectSelectors = function getBankSelectSelectors(rowsIDS) {
    if (rowsIDS && rowsIDS.length > 0) {
      return rowsIDS.map(function (el) {
        return "tr[data-id=\"".concat(el, "\"] select");
      }).join(',');
    }

    return '';
  };

  var updateBankSelects = function updateBankSelects() {
    var container = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
    var availableBanks = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
    var selectors = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';

    if (selectors === '') {
      return false;
    }

    ;
    var selectSelectorsElems = container.querySelectorAll(selectors);
    selectSelectorsElems.forEach(function (el) {
      var options = [el.value].concat(_toConsumableArray(availableBanks));
      var html = options.map(function (el) {
        return "<option value=\"".concat(el, "\">").concat(el, "</option>");
      }).join('');
      el.innerHTML = html;
    });
    return true;
  };

  var updateTableIDColumn = function updateTableIDColumn(container) {
    var colsID = container.querySelectorAll("td[data-table=\"num-col\"]");

    for (var i = 0; i < colsID.length; i++) {
      colsID[i].innerHTML = i + 1;
    }
  };
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SalePointTable);

/***/ }),

/***/ "./resources/js/constants/currenciesDenominations.js":
/*!***********************************************************!*\
  !*** ./resources/js/constants/currenciesDenominations.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _currencies__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./currencies */ "./resources/js/constants/currencies.js");
var _Object$freeze;

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


var CURRENCIES_DENOMINATIONS = Object.freeze((_Object$freeze = {}, _defineProperty(_Object$freeze, _currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, [0.50, 1, 2, 5, 10, 20, 50, 100]), _defineProperty(_Object$freeze, _currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, [0.50, 1, 2, 5, 10, 20, 50, 100, 200, 500]), _Object$freeze));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CURRENCIES_DENOMINATIONS);

/***/ }),

/***/ "./resources/js/constants/paymentMethods.js":
/*!**************************************************!*\
  !*** ./resources/js/constants/paymentMethods.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/models/Bank.js":
/*!*************************************!*\
  !*** ./resources/js/models/Bank.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var Bank = function Bank(name) {
  var id = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  this.id = id;
  this.name = name;
};

Bank.prototype.constructor = Bank;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Bank);

/***/ }),

/***/ "./resources/js/models/DenominationRecord.js":
/*!***************************************************!*\
  !*** ./resources/js/models/DenominationRecord.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var DenominationRecord = function DenominationRecord(currency, denomination) {
  var total = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
  var amount = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
  var id = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
  this.id = id;
  this.amount = amount;
  this.total = total;
  this.currency = currency;
  this.denomination = denomination;
};

DenominationRecord.prototype.constructor = DenominationRecord;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DenominationRecord);

/***/ }),

/***/ "./resources/js/models/moneyRecord.js":
/*!********************************************!*\
  !*** ./resources/js/models/moneyRecord.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var MoneyRecord = function MoneyRecord(amount, currency, method) {
  var id = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
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

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");
/* harmony import */ var _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/paymentMethods */ "./resources/js/constants/paymentMethods.js");
/* harmony import */ var _components_cash_register_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _components/cash-register-data */ "./resources/js/components/cash-register-data/index.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _store */ "./resources/js/store/index.js");
/* harmony import */ var _store_action__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! _store/action */ "./resources/js/store/action.js");
/* harmony import */ var _views_MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! _views/MoneyRecordModalView */ "./resources/js/views/MoneyRecordModalView.js");
/* harmony import */ var _presenters_MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! _presenters/MoneyRecordModalPresenter */ "./resources/js/presenters/MoneyRecordModalPresenter.js");
/* harmony import */ var _components_money_record_table_MoneyRecordTable__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! _components/money-record-table/MoneyRecordTable */ "./resources/js/components/money-record-table/MoneyRecordTable.js");
/* harmony import */ var _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! _views/ForeignMoneyRecordModalView */ "./resources/js/views/ForeignMoneyRecordModalView.js");
/* harmony import */ var _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! _presenters/ForeignMoneyRecordModalPresenter */ "./resources/js/presenters/ForeignMoneyRecordModalPresenter.js");
/* harmony import */ var _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! _components/money-record-table/ForeignMoneyRecordTable */ "./resources/js/components/money-record-table/ForeignMoneyRecordTable.js");
/* harmony import */ var _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! _presenters/DenominationModalPresenter */ "./resources/js/presenters/DenominationModalPresenter.js");
/* harmony import */ var _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! _views/DenominationModalView */ "./resources/js/views/DenominationModalView.js");
/* harmony import */ var _presenters_SalePointModalPresenter__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! _presenters/SalePointModalPresenter */ "./resources/js/presenters/SalePointModalPresenter.js");
/* harmony import */ var _views_SalePointModalView__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! _views/SalePointModalView */ "./resources/js/views/SalePointModalView.js");
/* harmony import */ var _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! _utilities/decimalInput */ "./resources/js/utilities/decimalInput.js");
















/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  totalInputDOMS: {
    liquidMoneyBs: document.querySelector('#total_liquid_money_bolivares'),
    liquidMoneyDollar: document.querySelector('#total_liquid_money_dollars'),
    denominationsBs: document.querySelector('#total_liquid_money_bolivares_denominations'),
    denominationsDollar: document.querySelector('#total_liquid_money_dollars_denominations'),
    zelleDollar: document.querySelector('#total_zelle_record'),
    pointSaleDollar: document.querySelector('#point_sale_dollar')
  },
  proxy: null,
  setTotalLiquidMoneyBs: function setTotalLiquidMoneyBs(total) {
    this.proxy.liquidMoneyBs = total;
  },
  setTotalLiquidMoneyDollar: function setTotalLiquidMoneyDollar(total) {
    this.proxy.liquidMoneyDollar = total;
  },
  setTotalDenominationBs: function setTotalDenominationBs(total) {
    this.proxy.denominationsBs = total;
  },
  setTotalDenominationDollar: function setTotalDenominationDollar(total) {
    this.proxy.denominationsDollar = total;
  },
  setTotalZelleDollar: function setTotalZelleDollar(total) {
    this.proxy.zelleDollar = total;
  },
  setPropWrapper: function setPropWrapper(fn) {
    return fn.bind(this);
  },
  init: function init() {
    var _this = this;

    var cashRegisterContainer = document.querySelector('#cash_register_data');
    var cashRegister = new _components_cash_register_data__WEBPACK_IMPORTED_MODULE_2__["default"]();
    cashRegister.init(cashRegisterContainer);
    var liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
    var bolivarRecordMoneyPresenter = new _presenters_MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_6__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyBs));
    var bolivarRecordMoneyView = new _views_MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_5__["default"](bolivarRecordMoneyPresenter);
    var moneyRecordTable = new _components_money_record_table_MoneyRecordTable__WEBPACK_IMPORTED_MODULE_7__["default"]();
    bolivarRecordMoneyView.init(liquidMoneyBsRegisterModal, 'liquid_money_bolivares', moneyRecordTable);
    var cashDollarRecordModal = document.querySelector('#liquid_money_dollars');
    var dollarRecordMoneyPresenter = new _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_9__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyDollar));
    var dollarRecordMoneyView = new _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_8__["default"](dollarRecordMoneyPresenter);
    var dollarRecordTable = new _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_10__["default"]();
    dollarRecordMoneyView.init(cashDollarRecordModal, 'liquid_money_dollars', dollarRecordTable);
    var bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
    var bolivarDenominationModalPresenter = new _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_11__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationBs));
    var bolivarDenominationModalView = new _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_12__["default"](bolivarDenominationModalPresenter);
    bolivarDenominationModalView.init(bsDenominationsModal, 'liquid_money_bolivares_denominations');
    var dollarDenominationsModal = document.querySelector('#liquid_money_dollars_denominations');
    var dollarDenominationModalPresenter = new _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_11__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationDollar));
    var dollarDenominationModalView = new _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_12__["default"](dollarDenominationModalPresenter);
    dollarDenominationModalView.init(dollarDenominationsModal, 'liquid_money_dollars_denominations');
    var salePointModal = document.querySelector('#point_sale_bs');
    var salePointModalPresenter = new _presenters_SalePointModalPresenter__WEBPACK_IMPORTED_MODULE_13__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH);
    var salePointModalView = new _views_SalePointModalView__WEBPACK_IMPORTED_MODULE_14__["default"](salePointModalPresenter);
    salePointModalView.init(salePointModal, 'point_sale_bs');
    var zelleRecordModal = document.querySelector('#zelle_record');
    var zelleRecordMoneyPresenter = new _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_9__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.ZELLE, this.setPropWrapper(this.setTotalZelleDollar));
    var zelleRecordMoneyView = new _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_8__["default"](zelleRecordMoneyPresenter);
    var zelleRecordTable = new _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_10__["default"]();
    zelleRecordMoneyView.init(zelleRecordModal, 'zelle_record', zelleRecordTable); // // Cash register modal total input DOMs

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_15__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.liquidMoneyBs);
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_15__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.liquidMoneyDollar); // // Denomination modal total input DOMs

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_15__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.denominationsBs);
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_15__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.denominationsDollar); // // Zelle total input DOMs

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_15__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.zelleDollar);
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_15__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.pointSaleDollar);

    var handlerWrapper = function handlerWrapper() {
      var self = _this;
      return {
        set: function set(target, key, value) {
          target[key] = value;
          self.totalInputDOMS[key].value = value;
          return true;
        }
      };
    };

    var keys = Object.keys(this.totalInputDOMS).reduce(function (obj, key) {
      obj[key] = 0;
      return obj;
    }, {});
    this.proxy = new Proxy(keys, handlerWrapper());
    _store__WEBPACK_IMPORTED_MODULE_3__.store.subscribe(function () {
      var state = _store__WEBPACK_IMPORTED_MODULE_3__.store.getState();

      if (state.lastAction === _store_action__WEBPACK_IMPORTED_MODULE_4__.STORE_DOLLAR_EXCHANGE_VALUE) {
        document.querySelector('p[data-dollar-exchange="dollar_exchange_date"]').innerText = state.dollarExchange.createdAt;
        document.querySelector('p[data-dollar-exchange="dollar_exchange_value"]').innerText = "".concat(state.dollarExchange.value, " ").concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR]);
      }
    });
  }
});

/***/ }),

/***/ "./resources/js/presenters/DenominationModalPresenter.js":
/*!***************************************************************!*\
  !*** ./resources/js/presenters/DenominationModalPresenter.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collections_denominationRecordCollection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _collections/denominationRecordCollection */ "./resources/js/collections/denominationRecordCollection.js");
/* harmony import */ var _models_DenominationRecord__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _models/DenominationRecord */ "./resources/js/models/DenominationRecord.js");
/* harmony import */ var _constants_currenciesDenominations__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _constants/currenciesDenominations */ "./resources/js/constants/currenciesDenominations.js");



var DenominationModalPresenterPrototype = {
  clickOnModal: function clickOnModal(_ref) {
    var target = _ref.target;
    var closest = target.closest('button');

    if (closest && closest.tagName === 'BUTTON') {
      var modaToggleID = closest.getAttribute('data-modal-toggle');

      if (modaToggleID) {
        // Checking if it's closing the modal
        var total = this.denominationRecord.getAll().reduce(function (acc, curr) {
          return acc + curr.total;
        }, 0);
        this.setTotalAmount(total);
      }
    }
  },
  keyPressedOnModal: function keyPressedOnModal(_ref2) {
    var target = _ref2.target,
        key = _ref2.key;

    if (isFinite(key)) {
      var denomination = target.getAttribute('data-denomination');
      this.updateDenominationRecord(denomination, target.value);
    }
  },
  keyDownOnModal: function keyDownOnModal(_ref3) {
    var target = _ref3.target,
        key = _ref3.key;

    if (key === 8 || key === 'Backspace') {
      var denomination = target.getAttribute('data-denomination');
      this.updateDenominationRecord(denomination, target.value);
    }
  },
  setView: function setView(view) {
    this.view = view;
  },
  updateDenominationRecord: function updateDenominationRecord(denomination, amount) {
    if (isNaN(amount) || amount === '') {
      amount = 0;
    }

    var amountInt = parseInt(amount);
    var denominationFloat = parseFloat(denomination);
    var index = this.denominationRecord.getIndexByDenomination(denominationFloat);
    var total = this.calculateTotal(amountInt, denominationFloat);

    if (index !== -1) {
      this.denominationRecord.setElementAtIndex(index, {
        amount: amountInt,
        total: total
      });
      console.log(this.denominationRecord.getElementByIndex(index));
    }
  },
  calculateTotal: function calculateTotal(amount, denomination) {
    return Math.round((denomination * amount + Number.EPSILON) * 100) / 100;
  }
};

var DenominationModalPresenter = function DenominationModalPresenter(currency, method, setTotalAmount) {
  this.view = null;
  this.currency = currency;
  this.method = method;
  this.setTotalAmount = setTotalAmount;
  var denominationRecord = _constants_currenciesDenominations__WEBPACK_IMPORTED_MODULE_2__["default"][currency].map(function (el, index) {
    return new _models_DenominationRecord__WEBPACK_IMPORTED_MODULE_1__["default"](currency, el, 0, 0, index);
  });
  this.denominationRecord = new _collections_denominationRecordCollection__WEBPACK_IMPORTED_MODULE_0__["default"](denominationRecord);
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

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MoneyRecordModalPresenter */ "./resources/js/presenters/MoneyRecordModalPresenter.js");
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _store */ "./resources/js/store/index.js");
/* harmony import */ var _store_action__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! _store/action */ "./resources/js/store/action.js");






var ForeignMoneyRecordModalPresenter = function ForeignMoneyRecordModalPresenter(currency, method, setTotalAmount) {
  var _this = this;

  _MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, currency, method, setTotalAmount);
  _store__WEBPACK_IMPORTED_MODULE_3__.store.subscribe(function () {
    var state = _store__WEBPACK_IMPORTED_MODULE_3__.store.getState();

    if (state.lastAction === _store_action__WEBPACK_IMPORTED_MODULE_4__.STORE_DOLLAR_EXCHANGE_VALUE && _this.moneyRecordCollection.getLength() > 0) {
      var convertions = getAllConvertions(_this.moneyRecordCollection.getAll(), state.dollarExchange.value);

      _this.view.updateConvertionCol(convertions);
    }
  });

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
      var amount = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_2__.formatAmount)(target.value);
      var dollarExchangeValue = _store__WEBPACK_IMPORTED_MODULE_3__.store.getState().dollarExchange.value;
      var formatedConvertion = getConvertionFormated(amount, dollarExchangeValue);
      this.view.updateConvertion({
        rowID: rowID,
        formatedConvertion: formatedConvertion
      });
    }
  };

  this.keyDownOnModal = function (_ref2) {
    var target = _ref2.target,
        key = _ref2.key;
    _MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.keyDownOnModal.call(this, {
      target: target,
      key: key
    });

    if (key === 8 || key === 'Backspace') {
      var rowID = target.closest('tr').getAttribute('data-id');
      var amount = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_2__.formatAmount)(target.value);
      var dollarExchangeValue = _store__WEBPACK_IMPORTED_MODULE_3__.store.getState().dollarExchange.value;
      var formatedConvertion = getConvertionFormated(amount, dollarExchangeValue);
      this.view.updateConvertion({
        rowID: rowID,
        formatedConvertion: formatedConvertion
      });
    }
  };

  var getConvertionFormated = function getConvertionFormated(amount, dollarExchange) {
    return "".concat(calculateConvertion(amount, dollarExchange), " ").concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.BOLIVAR]);
  };

  var calculateConvertion = function calculateConvertion(amount, exchangeValue) {
    return Math.round((exchangeValue * amount + Number.EPSILON) * 100) / 100;
  };

  var getAllConvertions = function getAllConvertions(records, exchangeValue) {
    return records.map(function (el) {
      return getConvertionFormated(el.amount, exchangeValue);
    });
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

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _collections_moneyRecordCollection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _collections/moneyRecordCollection */ "./resources/js/collections/moneyRecordCollection.js");
/* harmony import */ var _models_moneyRecord__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _models/moneyRecord */ "./resources/js/models/moneyRecord.js");
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
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
          this.moneyRecordCollection.setElementAtIndex(0, {
            amount: 0
          });
          var record = this.moneyRecordCollection.getElementByIndex(0);
          this.view.resetLastInput(record.id);
        } else {
          // Delete the entry with the id
          var id = parseInt(rowID);
          this.moneyRecordCollection.removeElementByID(id);
          this.view.deleteRow(rowID);
        }
      } else if (modalToggleID) {
        // Checking if it's closing the modal
        var total = this.moneyRecordCollection.getAll().reduce(function (acc, curr) {
          return acc + curr.amount;
        }, 0);
        this.setTotalAmount(total);
      }
    }
  },
  keyPressedOnModal: function keyPressedOnModal(_ref2) {
    var target = _ref2.target,
        key = _ref2.key;

    if (key === 13 || key === 'Enter') {
      // Handle new table's row creation
      var amount = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_2__.formatAmount)(target.value);

      if (amount <= 0) {
        // If target value is zero, then don't to create a new row
        return;
      }

      var moneyRecord = new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_1__["default"](0, this.currency, this.method);
      moneyRecord = this.moneyRecordCollection.pushElement(moneyRecord);
      this.view.addRow(_objectSpread(_objectSpread({}, moneyRecord), {}, {
        total: this.moneyRecordCollection.getLength()
      }));
    } else if (isFinite(key)) {
      var id = target.closest('tr').getAttribute('data-id');
      this.updateMoneyRecord(parseInt(id), target.value);
    }
  },
  keyDownOnModal: function keyDownOnModal(_ref3) {
    var target = _ref3.target,
        key = _ref3.key;

    if (key === 8 || key === 'Backspace') {
      var id = target.closest('tr').getAttribute('data-id');
      this.updateMoneyRecord(parseInt(id), target.value);
    }
  },
  setView: function setView(view) {
    this.view = view;
  },
  updateMoneyRecord: function updateMoneyRecord(id, inputValue) {
    var index = this.moneyRecordCollection.getIndexByID(parseInt(id));
    var value = (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_2__.formatAmount)(inputValue);
    this.moneyRecordCollection.setElementAtIndex(index, {
      amount: value
    });
  }
};

var MoneyRecordModalPresenter = function MoneyRecordModalPresenter(currency, method, setTotalAmount) {
  this.view = null;
  this.currency = currency;
  this.method = method;
  this.moneyRecordCollection = new _collections_moneyRecordCollection__WEBPACK_IMPORTED_MODULE_0__["default"]([new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_1__["default"](0.00, this.currency, this.method, 0)]);
  this.setTotalAmount = setTotalAmount;
};

MoneyRecordModalPresenter.prototype = MoneyRecordModalPresenterPrototype;
MoneyRecordModalPresenter.prototype.constructor = MoneyRecordModalPresenter;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MoneyRecordModalPresenter);

/***/ }),

/***/ "./resources/js/presenters/SalePointModalPresenter.js":
/*!************************************************************!*\
  !*** ./resources/js/presenters/SalePointModalPresenter.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _app_collections_bankCollection__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _app/collections/bankCollection */ "./resources/js/collections/bankCollection.js");
/* harmony import */ var _services_banks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _services/banks */ "./resources/js/services/banks/index.js");
/* harmony import */ var _models_Bank__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _models/Bank */ "./resources/js/models/Bank.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }




var SalePointModalPresenterPrototype = {
  clickOnModal: function clickOnModal(_ref) {
    var target = _ref.target;
    var button = target.closest('button');

    if (button && button.tagName === 'BUTTON') {
      var action = button.getAttribute('data-modal');

      if (!action) {
        return;
      }

      if (action === 'add') {
        if (this.banks.length === 0) {
          return;
        }

        var idArr = [];
        var bank = new _models_Bank__WEBPACK_IMPORTED_MODULE_3__["default"](this.banks.shift());

        if (this.selectedBanks.getLength() > 0) {
          idArr = this.selectedBanks.getAll().map(function (el) {
            return el.id;
          });
          bank = this.selectedBanks.pushElement(bank);
        } else {
          bank = this.selectedBanks.pushElement(bank);
        }

        this.view.addRow({
          prevIDArr: idArr,
          newID: bank.id,
          currentBank: bank.name,
          availableBanks: this.banks,
          totalElements: this.selectedBanks.getLength()
        });
      } else if (action === 'delete') {
        var row = button.closest('tr');
        var id = row ? parseInt(row.getAttribute('data-id')) : null;

        var _bank = this.selectedBanks.getElementByID(id);

        if (_bank === undefined) {
          return false;
        }

        this.selectedBanks.removeElementByID(id);
        this.banks.push(_bank.name);

        var _idArr = this.selectedBanks.getAll().map(function (el) {
          return el.id;
        });

        this.view.deleteRow({
          prevIDArr: _idArr,
          deleteID: id,
          availableBanks: this.banks,
          totalElements: this.selectedBanks.getLength()
        });
      }
    }
  },
  changeOnModal: function changeOnModal(_ref2) {
    var target = _ref2.target;

    if (this.banks.length === 0) {
      return;
    }

    var row = target.closest('tr');
    var id = row && row.getAttribute('data-id') ? parseInt(row.getAttribute('data-id')) : null;

    if (id !== null) {
      // Old bank selected
      var bank = this.selectedBanks.getElementByID(id); // New Bank selected

      var index = target.selectedIndex;
      var newSelectedValue = target.options[index].value; // Old value is pushed again in banks array

      this.banks.push(bank.name); // Remove the new value from available banks

      var indexNew = this.banks.indexOf(newSelectedValue);
      this.banks.splice(indexNew, 1); // Set the new value in old value select

      var indexOld = this.selectedBanks.getIndexByID(id);
      this.selectedBanks.setElementAtIndex(indexOld, {
        name: newSelectedValue
      });
      console.log(this.selectedBanks.getAll());
      this.view.changeSelect({
        prevIDArr: this.selectedBanks.getAll().map(function (el) {
          return el.id;
        }),
        availableBanks: this.banks
      });
    }
  },
  setView: function setView(view) {
    this.view = view;
  }
};

var SalePointModalPresenter = function SalePointModalPresenter(currency) {
  var _this = this;

  this.view = null;
  this.currency = currency;
  this.banks = [];
  this.selectedBanks = new _app_collections_bankCollection__WEBPACK_IMPORTED_MODULE_1__["default"]();
  fetchInitialData().then(function (res) {
    _this.banks = res.banks;
  })["catch"](function (err) {
    console.log(err);
  });

  function fetchInitialData() {
    return _fetchInitialData.apply(this, arguments);
  }

  function _fetchInitialData() {
    _fetchInitialData = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
      var banks;
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              _context.next = 3;
              return (0,_services_banks__WEBPACK_IMPORTED_MODULE_2__.getAllBanks)();

            case 3:
              banks = _context.sent;
              return _context.abrupt("return", {
                banks: banks
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
    return _fetchInitialData.apply(this, arguments);
  }
};

SalePointModalPresenter.prototype = SalePointModalPresenterPrototype;
SalePointModalPresenter.prototype.constructor = SalePointModalPresenter;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SalePointModalPresenter);

/***/ }),

/***/ "./resources/js/services/banks/index.js":
/*!**********************************************!*\
  !*** ./resources/js/services/banks/index.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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

/***/ "./resources/js/utilities/numericInput.js":
/*!************************************************!*\
  !*** ./resources/js/utilities/numericInput.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! inputmask */ "./node_modules/inputmask/dist/inputmask.js");
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(inputmask__WEBPACK_IMPORTED_MODULE_0__);

var numericInput = inputmask__WEBPACK_IMPORTED_MODULE_0___default()("(999){+|1}", {
  numericInput: true,
  placeholder: "0",
  definitions: {
    "0": {
      validator: "[0-9\uFF11-\uFF19]"
    }
  }
});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (numericInput);

/***/ }),

/***/ "./resources/js/views/DenominationModalView.js":
/*!*****************************************************!*\
  !*** ./resources/js/views/DenominationModalView.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_denominations_table__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _components/denominations-table */ "./resources/js/components/denominations-table/index.js");

var DenominationModalViewPrototype = {
  init: function init(container, tableName) {
    var tableContainer = container.querySelector('table');
    this.table = new _components_denominations_table__WEBPACK_IMPORTED_MODULE_0__["default"]();
    this.table.init(tableContainer, tableName, this.presenter.currency);
    container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
    container.addEventListener("keypress", this.keypressEventHandlerWrapper(this.presenter));
    container.addEventListener("keydown", this.keydownEventHandlerWrapper(this.presenter));
  },
  clickEventHandlerWrapper: function clickEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.clickOnModal({
        target: event.target
      });
    };
  },
  keypressEventHandlerWrapper: function keypressEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.keyPressedOnModal({
        key: event.key,
        target: event.target
      });
    };
  },
  keydownEventHandlerWrapper: function keydownEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.keyDownOnModal({
        key: event.key,
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

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MoneyRecordModalView */ "./resources/js/views/MoneyRecordModalView.js");


var ForeignMoneyRecordModalView = function ForeignMoneyRecordModalView(presenter) {
  _MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, presenter);

  this.updateConvertion = function (obj) {
    this.table.updateConvertion(obj);
  };

  this.updateConvertionCol = function (convertions) {
    this.table.updateConvertionCol(convertions);
  };

  this.init = function (container, name, table) {
    _MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.init.call(this, container, name, table);
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
    container.addEventListener("keydown", this.keyDownEventHandlerWrapper(this.presenter));
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
  },
  keyDownEventHandlerWrapper: function keyDownEventHandlerWrapper(presenter) {
    var _this = this;

    return function (event) {
      _this.presenter.keyDownOnModal({
        target: event.target,
        key: event.key || event.keyCode
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

/***/ "./resources/js/views/SalePointModalView.js":
/*!**************************************************!*\
  !*** ./resources/js/views/SalePointModalView.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_sale_point_table__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _components/sale-point-table */ "./resources/js/components/sale-point-table/index.js");

var SalePointModalViewPrototype = {
  init: function init(container, tableName) {
    var tableContainer = container.querySelector('table');
    this.table = new _components_sale_point_table__WEBPACK_IMPORTED_MODULE_0__["default"]();
    this.table.init(tableContainer, this.name, this.presenter.currency);
    container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
    container.addEventListener('change', this.changeEventHandlerWrapper(this.presenter));
  },
  addRow: function addRow(obj) {
    this.table.addRow(obj);
  },
  deleteRow: function deleteRow(obj) {
    this.table.deleteRow(obj);
  },
  changeSelect: function changeSelect(obj) {
    this.table.changeSelect(obj);
  },
  clickEventHandlerWrapper: function clickEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.clickOnModal({
        target: event.target
      });
    };
  },
  changeEventHandlerWrapper: function changeEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.changeOnModal({
        target: event.target
      });
    };
  }
};

var SalePointModalView = function SalePointModalView(presenter) {
  this.presenter = presenter;
  this.presenter.setView(this);
};

SalePointModalView.prototype = SalePointModalViewPrototype;
SalePointModalView.prototype.constructor = SalePointModalView;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SalePointModalView);

/***/ })

}]);