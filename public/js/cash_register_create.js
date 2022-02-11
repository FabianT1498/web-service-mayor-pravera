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
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! inputmask */ "./node_modules/inputmask/dist/inputmask.js");
/* harmony import */ var inputmask__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(inputmask__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _services_banks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../../services/banks */ "./resources/js/services/banks/index.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }



/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__() {
  return _ref.apply(this, arguments);
}

function _ref() {
  _ref = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
    var decimalMaskOptions, modalsID, denominationModalsID, getModalsID, keyDownEventHandler, keypressEventHandler, clickEventHandler, updateConvertionCol, handleClickEventDenominationsModal, handleClickEventPointSaleModal, banks, getNewInputID, saveNewInputID, removeInputID, updateTableIDColumn, inputTemplate, tableRowTemplate, formatAmount, pointSaletableRowTemplate, existCashRegisterWorker, cashRegisterWorkerSelect, newCashRegisterWorkerContainer, handleChangeExistWorker, form, submit, inputs;
    return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            decimalMaskOptions = {
              alias: 'decimal',
              suffix: ' $',
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
            modalsID = {
              'liquid_money_dollars': [0],
              'liquid_money_dollars_count': 1,
              'liquid_money_bolivares': [0],
              'liquid_money_bolivares_count': 1
            };
            denominationModalsID = ['liquid_money_dollars_denominations', 'liquid_money_bolivares_denominations'];

            getModalsID = function getModalsID(obj) {
              return Object.keys(obj).reduce(function (arr, val) {
                if (!val.includes('_count')) {
                  arr.push("".concat(val));
                }

                return arr;
              }, []);
            }; // --- HANDLING LIST ENTRANCE MODAL ---


            keyDownEventHandler = function keyDownEventHandler(event) {
              var key = event.key || event.keyCode;

              if (key === 8 || key === 'Backspace') {
                // Handle case to convert dollar to Bs.S
                updateConvertionCol(event);
              }
            };

            keypressEventHandler = function keypressEventHandler(event) {
              event.preventDefault();
              var key = event.key || event.keyCode;

              if (isFinite(key)) {
                // Handle case to convert dollar to Bs.S
                updateConvertionCol(event);
              } else if (key === 13 || key === 'Enter') {
                // Handle new table's row creation
                var currency = this.getAttribute('data-currency');
                var tBody = document.querySelector("#".concat(this.id, " tbody"));
                tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.id, currency));
                var input = document.querySelector("#".concat(this.id, "_").concat(getNewInputID(this.id)));
                decimalMaskOptions.suffix = currency;
                new (inputmask__WEBPACK_IMPORTED_MODULE_1___default())(decimalMaskOptions).mask(input);
                modalsID["".concat(this.id, "_count")]++;
                saveNewInputID(this.id);
              }
            };

            clickEventHandler = function clickEventHandler(event) {
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
                      var _closest$closest;

                      input.value = 0;
                      var convertionCol = closest === null || closest === void 0 ? void 0 : (_closest$closest = closest.closest('tr')) === null || _closest$closest === void 0 ? void 0 : _closest$closest.children[2];
                      var dataConvertionCol = convertionCol ? convertionCol === null || convertionCol === void 0 ? void 0 : convertionCol.getAttribute('data-table') : null;

                      if (dataConvertionCol && dataConvertionCol === 'convertion-col') {
                        convertionCol.innerHTML = "0.00 Bs.s";
                      } // input.inputmask.remove();
                      // decimalMaskOptions.suffix = this.getAttribute('data-currency');
                      // (new Inputmask(decimalMaskOptions)).mask(input);

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
                  document.getElementById("total_".concat(this.id)).value = total > 0 ? total : 0;
                }
              }
            };

            updateConvertionCol = function updateConvertionCol(event) {
              var convertionCol = event.target.closest('tr').children[2];
              var dataConvertionCol = convertionCol ? convertionCol === null || convertionCol === void 0 ? void 0 : convertionCol.getAttribute('data-table') : null;

              if (dataConvertionCol && dataConvertionCol === 'convertion-col') {
                var dollarExchangeBs = parseFloat(document.querySelector("#last-dollar-exchange-bs-val").value);
                var value = formatAmount(event.target.value);
                convertionCol.innerHTML = "".concat(Math.round((dollarExchangeBs * value + Number.EPSILON) * 100) / 100, " Bs.s");
              }
            }; // --- HANDLING MODAL TO LIQUID MONEY DENOMINATIONS --- //


            handleClickEventDenominationsModal = function handleClickEventDenominationsModal(event) {
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
            }; // --- HANDLING POINT SALE MODAL --- //


            handleClickEventPointSaleModal = function handleClickEventPointSaleModal(event) {
              var closest = event.target.closest('button');

              if (closest && closest.tagName === 'BUTTON') {
                // const idRow = closest.getAttribute('data-del-row');
                // const modaToggleID = closest.getAttribute('data-modal-toggle');
                var action = closest.getAttribute('data-modal');

                if (action && action === 'add') {
                  console.log(banks.getBanks());
                  var tBody = document.querySelector("#".concat(this.id, " tbody"));
                  tBody.insertAdjacentHTML('beforeend', pointSaletableRowTemplate(this.id));
                } // if (modaToggleID){ // Checking if it's closing the modal
                //     // get all inputs of the modal
                //     let inputs = document.querySelectorAll(`#${this.id} input`)
                //     const total = Array.from(inputs).reduce((acc, el) => {
                //         let denomination = parseFloat(el.getAttribute('data-denomination'));
                //         let num = formatAmount(el.value)
                //         return acc + (num * denomination);
                //     }, 0);
                //     document.getElementById(`total_${this.id}`).value = total > 0 ? total : 0;
                // }

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
                new (inputmask__WEBPACK_IMPORTED_MODULE_1___default())(decimalMaskOptions).mask(el);
              }); // Get Modal Elements

              var modals = document.querySelectorAll(modalsID.map(function (el) {
                return "#".concat(el);
              }).join(','));
              var currencies = []; // Attach events to modals

              modals.forEach(function (el) {
                el.addEventListener("keydown", keyDownEventHandler);
                el.addEventListener("keypress", keypressEventHandler);
                el.addEventListener("click", clickEventHandler);
                currencies.push(" ".concat(el.getAttribute('data-currency')));
              }); // Get the default input IDs in modals

              var defaultInputsID = modalsID.map(function (el) {
                return "#".concat(el, "_0");
              }); // Get the default Input Elements in modals

              var defaultInputs = document.querySelectorAll(defaultInputsID.join(',')); // Apply the mask to default inputs

              defaultInputs.forEach(function (el, key) {
                decimalMaskOptions.suffix = currencies[key];
                new (inputmask__WEBPACK_IMPORTED_MODULE_1___default())(decimalMaskOptions).mask(el);
              }); // Get the IDs from total denomination inputs

              var totalInputsDenominationsID = modalsID.map(function (el) {
                return "#total_".concat(el, "_denominations");
              }); // Get the total denomination Input Elements

              var totalInputDenominations = document.querySelectorAll(totalInputsDenominationsID.join(',')); // Apply mask to default total denominations input

              totalInputDenominations.forEach(function (el) {
                // Setting up currency suffix for each input
                decimalMaskOptions.suffix = el.getAttribute('data-currency');
                new (inputmask__WEBPACK_IMPORTED_MODULE_1___default())(decimalMaskOptions).mask(el);
              }); // Get the denomination Modals Elements

              var denominationModals = document.querySelectorAll(denominationModalsID.map(function (el) {
                return "#".concat(el);
              }).join(',')); // Attach event handlers to denominations money modals

              denominationModals.forEach(function (el) {
                el.addEventListener("click", handleClickEventDenominationsModal);
              }); // get Point Sale Bs Modal

              document.querySelector('#point_sale_bs').addEventListener('click', handleClickEventPointSaleModal);
            })(modalsID, denominationModalsID);

            banks = function () {
              var banks = [];
              (0,_services_banks__WEBPACK_IMPORTED_MODULE_2__.getAllBanks)().then(function (res) {
                return banks = res.data.data;
              })["catch"](function (e) {
                return console.log(e);
              });

              var getBanks = function getBanks() {
                return banks;
              };

              var deleteBank = function deleteBank(name) {
                var index = banks.findIndex(function (val) {
                  return val === name;
                });

                if (index !== -1) {
                  banks.splice(index, 1);
                }
              };

              var pushBank = function pushBank(name) {
                banks.push(name);
                return banks;
              };

              return {
                getBanks: getBanks,
                deleteBank: deleteBank,
                pushBank: pushBank
              };
            }();

            getNewInputID = function getNewInputID(name) {
              return modalsID[name].length === 0 ? 0 : modalsID[name][modalsID[name].length - 1] + 1;
            };

            saveNewInputID = function saveNewInputID(name) {
              modalsID[name].push(getNewInputID(name));
            };

            removeInputID = function removeInputID(name, id) {
              var index = modalsID[name].findIndex(function (val) {
                return val == id;
              });
              return index !== -1 ? modalsID[name].slice(index, 1) : -1;
            };

            updateTableIDColumn = function updateTableIDColumn(name) {
              var colsID = document.querySelectorAll("#".concat(name, " td[data-table=\"num-col\"]"));

              for (var i = 0; i < modalsID["".concat(name, "_count")]; i++) {
                colsID[i].innerHTML = i + 1;
              }
            };

            inputTemplate = function inputTemplate(name, currency) {
              return "\n        <input type=\"text\" placeholder=\"0.00 ".concat(currency, "\" id=\"").concat(name, "_").concat(getNewInputID(name), "\" name=\"").concat(name, "[]\" class=\"w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">\n    ");
            };

            tableRowTemplate = function tableRowTemplate(name, currency) {
              return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(getNewInputID(name), ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(modalsID["".concat(name, "_count")] + 1, "</td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                0.00 bs.s\n            </td>\n            <td class=\"py-4 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-del-row=\"").concat(getNewInputID(name), "\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
            };

            formatAmount = function formatAmount(amount) {
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
            }; // --- HANDLING POINT SALE


            pointSaletableRowTemplate = function pointSaletableRowTemplate(name) {
              var currency = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'Bs.S';
              return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">1</td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                <select name=\"point_sale_bs_bank[]\">\n                    ".concat(banks.getBanks().map(function (el) {
                return "<option value=\"".concat(el, "\">").concat(el, "</option>");
              }), "\n                </select>\n            </td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                ").concat(inputTemplate(name, currency), "\n            </td>\n            <td class=\"py-4 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-modal=\"delete\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
            }; // --- HANDLING INPUTS TO CREATE A NEW CASH REGISTER WORKER ---


            existCashRegisterWorker = document.getElementById('exist_cash_register_worker');
            cashRegisterWorkerSelect = document.getElementById('cash_register_worker');
            newCashRegisterWorkerContainer = document.getElementById('hidden-new-cash-register-worker-container');

            handleChangeExistWorker = function handleChangeExistWorker(event) {
              newCashRegisterWorkerContainer.classList.toggle('hidden');
              cashRegisterWorkerSelect.disabled = !cashRegisterWorkerSelect.disabled;
              newCashRegisterWorkerContainer.lastElementChild.toggleAttribute('required');

              if (cashRegisterWorkerSelect.disabled) {
                cashRegisterWorkerSelect.selectedIndex = "0";
              }
            };

            existCashRegisterWorker.addEventListener('change', handleChangeExistWorker); // --- HANDLING FORM SUBMIT ---

            form = document.querySelector('#form');

            submit = function submit(event) {
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

            inputs = document.querySelectorAll('[data-currency^="amount"]');

          case 29:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));
  return _ref.apply(this, arguments);
}

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
            return _context.abrupt("return", result);

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



/***/ })

}]);