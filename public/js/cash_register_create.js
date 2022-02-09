"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/cash_register_create"],{

/***/ "./resources/js/pages/cash-register/create.js":
/*!****************************************************!*\
  !*** ./resources/js/pages/cash-register/create.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* export default binding */ __WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! inputmask */ "./node_modules/inputmask/dist/inputmask.js");
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(inputmask__WEBPACK_IMPORTED_MODULE_0__);

/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__() {
  var decimalMaskOptions = {
    alias: 'decimal',
    suffix: '$',
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
  var modalsID = {
    'liquid_money_dollars': [0],
    'liquid_money_dollars_count': 1,
    'liquid_money_bolivares': [0],
    'liquid_money_bolivares_count': 1
  };
  var denominationModalsID = ['liquid_money_dollars_denominations', 'liquid_money_bolivares_denominations'];

  var getModalsID = function getModalsID(obj) {
    return Object.keys(obj).reduce(function (arr, val) {
      if (!val.includes('_count')) {
        arr.push("".concat(val));
      }

      return arr;
    }, []);
  };

  var keypressEventHandler = function keypressEventHandler(event) {
    var key = event.key || event.keyCode;

    if (key === 13 || key === 'Enter') {
      event.preventDefault();
      var currency = this.getAttribute('data-currency');
      var tBody = document.querySelector("#".concat(this.id, " tbody"));
      tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.id, currency));
      var input = document.querySelector("#".concat(this.id, "_").concat(getNewInputID(this.id)));
      console.log(this.getAttribute('data-currency'));
      decimalMaskOptions.suffix = currency;
      new (inputmask__WEBPACK_IMPORTED_MODULE_0___default())(decimalMaskOptions).mask(input);
      modalsID["".concat(this.id, "_count")]++;
      saveNewInputID(this.id);
    }
  };

  var clickEventHandler = function clickEventHandler(event) {
    var closest = event.target.closest('button');

    if (closest && closest.tagName === 'BUTTON') {
      var idRow = closest.getAttribute('data-del-row');
      var modaToggleID = closest.getAttribute('data-modal-toggle');

      if (idRow) {
        // Checking if it's Deleting a row
        var parent = document.querySelector("#".concat(this.id, " tbody"));

        if (parent.children.length === 1) {
          var input = document.getElementById("".concat(this.id, "_").concat(idRow));

          if (input !== null && input !== void 0 && input.inputmask) {
            input.value = 0;
            input.inputmask.remove();
            decimalMaskOptions.suffix = this.getAttribute('data-currency');
            new (inputmask__WEBPACK_IMPORTED_MODULE_0___default())(decimalMaskOptions).mask(input);
          }
        } else {
          var child = document.querySelector("#".concat(this.id, " tr[data-id=\"").concat(idRow, "\"]"));
          parent.removeChild(child);
          modalsID["".concat(this.id, "_count")]--;
          removeInputID(this.id, idRow);
          updateTableIDColumn(this.id);
        }
      } else if (modaToggleID) {
        // Checking if it's closing the modal
        // get all inputs of the modal
        var _inputs = document.querySelectorAll("#".concat(this.id, " input"));

        var total = Array.from(_inputs).reduce(function (acc, el) {
          var num = formatAmount(el.value);
          return acc + num;
        }, 0);
        console.log(total);
        document.getElementById("total_".concat(this.id)).value = total > 0 ? total : 0;
      }
    }
  }; // --- HANDLING MODAL TO LIQUID MONEY DENOMINATIONS --- //


  var handleClickEventDenominationsModal = function handleClickEventDenominationsModal(event) {
    var closest = event.target.closest('button');

    if (closest && closest.tagName === 'BUTTON') {
      var idRow = closest.getAttribute('data-del-row');
      var modaToggleID = closest.getAttribute('data-modal-toggle');

      if (modaToggleID) {
        // Checking if it's closing the modal
        // get all inputs of the modal
        var _inputs2 = document.querySelectorAll("#".concat(this.id, " input"));

        var total = Array.from(_inputs2).reduce(function (acc, el) {
          var denomination = parseFloat(el.getAttribute('data-denomination'));
          var num = formatAmount(el.value);
          return acc + num * denomination;
        }, 0);
        document.getElementById("total_".concat(this.id)).value = total > 0 ? total : 0;
      }
    }
  };

  (function (modalState, denominationModalsID) {
    // Get the modals ID
    var modalsID = getModalsID(modalState); // Get the total input IDs

    var totalInputsID = modalsID.map(function (el) {
      return "#total_".concat(el);
    }); // Get the total Input Elements

    var totalInputs = document.querySelectorAll(totalInputsID.join(',')); // Apply the mask to total inputs

    totalInputs.forEach(function (el) {
      // Setting up currency suffix for each input
      decimalMaskOptions.suffix = el.getAttribute('data-currency');
      new (inputmask__WEBPACK_IMPORTED_MODULE_0___default())(decimalMaskOptions).mask(el);
    }); // Get Modal Elements

    var modals = document.querySelectorAll(modalsID.map(function (el) {
      return "#".concat(el);
    }).join(','));
    var currencies = []; // Attach events to modals

    modals.forEach(function (el) {
      el.addEventListener("keypress", keypressEventHandler);
      el.addEventListener("click", clickEventHandler);
      currencies.push(el.getAttribute('data-currency'));
    }); // Get the default input IDs in modals

    var defaultInputsID = modalsID.map(function (el) {
      return "#".concat(el, "_0");
    }); // Get the default Input Elements in modals

    var defaultInputs = document.querySelectorAll(defaultInputsID.join(',')); // Apply the mask to default inputs

    defaultInputs.forEach(function (el, key) {
      console.log(key);
      console.log(currencies);
      decimalMaskOptions.suffix = currencies[key];
      new (inputmask__WEBPACK_IMPORTED_MODULE_0___default())(decimalMaskOptions).mask(el);
    }); // Apply mask to default total denominations input

    var totalInputsDenominationsID = modalsID.map(function (el) {
      return "#total_".concat(el, "_denominations");
    });
    var totalInputDenominations = document.querySelectorAll(totalInputsDenominationsID.join(',')); // Apply the mask to total denominations inputs

    totalInputDenominations.forEach(function (el) {
      // Setting up currency suffix for each input
      decimalMaskOptions.suffix = el.getAttribute('data-currency');
      new (inputmask__WEBPACK_IMPORTED_MODULE_0___default())(decimalMaskOptions).mask(el);
    }); // Attach event handlers to denominations money modals

    var denominationModals = document.querySelectorAll(denominationModalsID.map(function (el) {
      return "#".concat(el);
    }).join(','));
    denominationModals.forEach(function (el) {
      el.addEventListener("click", handleClickEventDenominationsModal);
    });
  })(modalsID, denominationModalsID);

  var getNewInputID = function getNewInputID(name) {
    return modalsID[name].length === 0 ? 0 : modalsID[name][modalsID[name].length - 1] + 1;
  };

  var saveNewInputID = function saveNewInputID(name) {
    modalsID[name].push(getNewInputID(name));
  };

  var removeInputID = function removeInputID(name, id) {
    var index = modalsID[name].findIndex(function (val) {
      return val == id;
    });
    return index !== -1 ? modalsID[name].slice(index, 1) : -1;
  };

  var updateTableIDColumn = function updateTableIDColumn(name) {
    var colsID = document.querySelectorAll("#".concat(name, " td[data-table=\"num-col\"]"));

    for (var i = 0; i < modalsID["".concat(name, "_count")]; i++) {
      colsID[i].innerHTML = i + 1;
    }
  };

  var inputTemplate = function inputTemplate(name, currency) {
    return "\n        <input type=\"text\" placeholder=\"0.00 ".concat(currency, "\" id=\"").concat(name, "_").concat(getNewInputID(name), "\" name=\"").concat(name, "[]\" class=\"w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">\n    ");
  };

  var tableRowTemplate = function tableRowTemplate(name, currency) {
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(getNewInputID(name), ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(modalsID["".concat(name, "_count")] + 1, "</td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                0.00 bs.s\n            </td>\n            <td class=\"py-4 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-del-row=\"").concat(getNewInputID(name), "\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
  };

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
    var decimal = (_arr$2 = arr[1]) !== null && _arr$2 !== void 0 ? _arr$2 : null;
    var integerStr = integer.split(".").join(); // Check if it is an integer number

    if (!decimal) {
      return parseInt(integerStr);
    }

    var numberString = integerStr + '.' + decimal;
    return Math.round((parseFloat(numberString) + Number.EPSILON) * 100) / 100;
  }; // --- HANDLING INPUTS TO CREATE A NEW CASH REGISTER WORKER ---


  var existCashRegisterWorker = document.getElementById('exist_cash_register_worker');
  var cashRegisterWorkerSelect = document.getElementById('cash_register_worker');
  var newCashRegisterWorkerContainer = document.getElementById('hidden-new-cash-register-worker-container');

  var handleChangeExistWorker = function handleChangeExistWorker(event) {
    newCashRegisterWorkerContainer.classList.toggle('hidden');
    cashRegisterWorkerSelect.disabled = !cashRegisterWorkerSelect.disabled;
    newCashRegisterWorkerContainer.lastElementChild.toggleAttribute('required');

    if (cashRegisterWorkerSelect.disabled) {
      cashRegisterWorkerSelect.selectedIndex = "0";
    }
  };

  existCashRegisterWorker.addEventListener('change', handleChangeExistWorker); // --- HANDLING FORM SUBMIT ---

  var form = document.querySelector('#form');

  var submit = function submit(event) {
    console.log(event);
    var allIsNull = true;

    for (var i = 0; i < inputs.length; i++) {
      var el = inputs[i];

      if (el.value) {
        allIsNull = false;
        break;
      }
    } // Check if there's at least one input filled


    if (allIsNull) {
      event.preventDefault();
      alert('Epa, no se ha ingresado ningun ingreso');
      return;
    }
  };

  form.addEventListener('submit', submit); // --- HANDLING INPUT MASKS ---

  var inputs = document.querySelectorAll('[data-currency^="amount"]');
}

/***/ })

}]);