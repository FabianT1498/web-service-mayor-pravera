"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/cash_register_edit"],{

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/Datepicker.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/Datepicker.js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Datepicker)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./lib/date-format.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date-format.js");
/* harmony import */ var _lib_event_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./lib/event.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/event.js");
/* harmony import */ var _i18n_base_locales_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./i18n/base-locales.js */ "./node_modules/@themesberg/tailwind-datepicker/js/i18n/base-locales.js");
/* harmony import */ var _options_defaultOptions_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./options/defaultOptions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/options/defaultOptions.js");
/* harmony import */ var _options_processOptions_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./options/processOptions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/options/processOptions.js");
/* harmony import */ var _picker_Picker_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./picker/Picker.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/Picker.js");
/* harmony import */ var _events_functions_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./events/functions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/functions.js");
/* harmony import */ var _events_inputFieldListeners_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./events/inputFieldListeners.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/inputFieldListeners.js");
/* harmony import */ var _events_otherListeners_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./events/otherListeners.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/otherListeners.js");












function stringifyDates(dates, config) {
  return dates
    .map(dt => (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.formatDate)(dt, config.format, config.locale))
    .join(config.dateDelimiter);
}

// parse input dates and create an array of time values for selection
// returns undefined if there are no valid dates in inputDates
// when origDates (current selection) is passed, the function works to mix
// the input dates into the current selection
function processInputDates(datepicker, inputDates, clear = false) {
  const {config, dates: origDates, rangepicker} = datepicker;
  if (inputDates.length === 0) {
    // empty input is considered valid unless origiDates is passed
    return clear ? [] : undefined;
  }

  const rangeEnd = rangepicker && datepicker === rangepicker.datepickers[1];
  let newDates = inputDates.reduce((dates, dt) => {
    let date = (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.parseDate)(dt, config.format, config.locale);
    if (date === undefined) {
      return dates;
    }
    if (config.pickLevel > 0) {
      // adjust to 1st of the month/Jan 1st of the year
      // or to the last day of the monh/Dec 31st of the year if the datepicker
      // is the range-end picker of a rangepicker
      const dt = new Date(date);
      if (config.pickLevel === 1) {
        date = rangeEnd
          ? dt.setMonth(dt.getMonth() + 1, 0)
          : dt.setDate(1);
      } else {
        date = rangeEnd
          ? dt.setFullYear(dt.getFullYear() + 1, 0, 0)
          : dt.setMonth(0, 1);
      }
    }
    if (
      (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.isInRange)(date, config.minDate, config.maxDate)
      && !dates.includes(date)
      && !config.datesDisabled.includes(date)
      && !config.daysOfWeekDisabled.includes(new Date(date).getDay())
    ) {
      dates.push(date);
    }
    return dates;
  }, []);
  if (newDates.length === 0) {
    return;
  }
  if (config.multidate && !clear) {
    // get the synmetric difference between origDates and newDates
    newDates = newDates.reduce((dates, date) => {
      if (!origDates.includes(date)) {
        dates.push(date);
      }
      return dates;
    }, origDates.filter(date => !newDates.includes(date)));
  }
  // do length check always because user can input multiple dates regardless of the mode
  return config.maxNumberOfDates && newDates.length > config.maxNumberOfDates
    ? newDates.slice(config.maxNumberOfDates * -1)
    : newDates;
}

// refresh the UI elements
// modes: 1: input only, 2, picker only, 3 both
function refreshUI(datepicker, mode = 3, quickRender = true) {
  const {config, picker, inputField} = datepicker;
  if (mode & 2) {
    const newView = picker.active ? config.pickLevel : config.startView;
    picker.update().changeView(newView).render(quickRender);
  }
  if (mode & 1 && inputField) {
    inputField.value = stringifyDates(datepicker.dates, config);
  }
}

function setDate(datepicker, inputDates, options) {
  let {clear, render, autohide} = options;
  if (render === undefined) {
    render = true;
  }
  if (!render) {
    autohide = false;
  } else if (autohide === undefined) {
    autohide = datepicker.config.autohide;
  }

  const newDates = processInputDates(datepicker, inputDates, clear);
  if (!newDates) {
    return;
  }
  if (newDates.toString() !== datepicker.dates.toString()) {
    datepicker.dates = newDates;
    refreshUI(datepicker, render ? 3 : 1);
    (0,_events_functions_js__WEBPACK_IMPORTED_MODULE_8__.triggerDatepickerEvent)(datepicker, 'changeDate');
  } else {
    refreshUI(datepicker, 1);
  }
  if (autohide) {
    datepicker.hide();
  }
}

/**
 * Class representing a date picker
 */
class Datepicker {
  /**
   * Create a date picker
   * @param  {Element} element - element to bind a date picker
   * @param  {Object} [options] - config options
   * @param  {DateRangePicker} [rangepicker] - DateRangePicker instance the
   * date picker belongs to. Use this only when creating date picker as a part
   * of date range picker
   */
  constructor(element, options = {}, rangepicker = undefined) {
    element.datepicker = this;
    this.element = element;

    // set up config
    const config = this.config = Object.assign({
      buttonClass: (options.buttonClass && String(options.buttonClass)) || 'button',
      container: document.body,
      defaultViewDate: (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.today)(),
      maxDate: undefined,
      minDate: undefined,
    }, (0,_options_processOptions_js__WEBPACK_IMPORTED_MODULE_6__["default"])(_options_defaultOptions_js__WEBPACK_IMPORTED_MODULE_5__["default"], this));
    this._options = options;
    Object.assign(config, (0,_options_processOptions_js__WEBPACK_IMPORTED_MODULE_6__["default"])(options, this));

    // configure by type
    const inline = this.inline = element.tagName !== 'INPUT';
    let inputField;
    let initialDates;

    if (inline) {
      config.container = element;
      initialDates = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringToArray)(element.dataset.date, config.dateDelimiter);
      delete element.dataset.date;
    } else {
      const container = options.container ? document.querySelector(options.container) : null;
      if (container) {
        config.container = container;
      }
      inputField = this.inputField = element;
      inputField.classList.add('datepicker-input');
      initialDates = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringToArray)(inputField.value, config.dateDelimiter);
    }
    if (rangepicker) {
      // check validiry
      const index = rangepicker.inputs.indexOf(inputField);
      const datepickers = rangepicker.datepickers;
      if (index < 0 || index > 1 || !Array.isArray(datepickers)) {
        throw Error('Invalid rangepicker object.');
      }
      // attach itaelf to the rangepicker here so that processInputDates() can
      // determine if this is the range-end picker of the rangepicker while
      // setting inital values when pickLevel > 0
      datepickers[index] = this;
      // add getter for rangepicker
      Object.defineProperty(this, 'rangepicker', {
        get() {
          return rangepicker;
        },
      });
    }

    // set initial dates
    this.dates = [];
    // process initial value
    const inputDateValues = processInputDates(this, initialDates);
    if (inputDateValues && inputDateValues.length > 0) {
      this.dates = inputDateValues;
    }
    if (inputField) {
      inputField.value = stringifyDates(this.dates, config);
    }

    const picker = this.picker = new _picker_Picker_js__WEBPACK_IMPORTED_MODULE_7__["default"](this);

    if (inline) {
      this.show();
    } else {
      // set up event listeners in other modes
      const onMousedownDocument = _events_otherListeners_js__WEBPACK_IMPORTED_MODULE_10__.onClickOutside.bind(null, this);
      const listeners = [
        [inputField, 'keydown', _events_inputFieldListeners_js__WEBPACK_IMPORTED_MODULE_9__.onKeydown.bind(null, this)],
        [inputField, 'focus', _events_inputFieldListeners_js__WEBPACK_IMPORTED_MODULE_9__.onFocus.bind(null, this)],
        [inputField, 'mousedown', _events_inputFieldListeners_js__WEBPACK_IMPORTED_MODULE_9__.onMousedown.bind(null, this)],
        [inputField, 'click', _events_inputFieldListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickInput.bind(null, this)],
        [inputField, 'paste', _events_inputFieldListeners_js__WEBPACK_IMPORTED_MODULE_9__.onPaste.bind(null, this)],
        [document, 'mousedown', onMousedownDocument],
        [document, 'touchstart', onMousedownDocument],
        [window, 'resize', picker.place.bind(picker)]
      ];
      (0,_lib_event_js__WEBPACK_IMPORTED_MODULE_3__.registerListeners)(this, listeners);
    }
  }

  /**
   * Format Date object or time value in given format and language
   * @param  {Date|Number} date - date or time value to format
   * @param  {String|Object} format - format string or object that contains
   * toDisplay() custom formatter, whose signature is
   * - args:
   *   - date: {Date} - Date instance of the date passed to the method
   *   - format: {Object} - the format object passed to the method
   *   - locale: {Object} - locale for the language specified by `lang`
   * - return:
   *     {String} formatted date
   * @param  {String} [lang=en] - language code for the locale to use
   * @return {String} formatted date
   */
  static formatDate(date, format, lang) {
    return (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.formatDate)(date, format, lang && _i18n_base_locales_js__WEBPACK_IMPORTED_MODULE_4__.locales[lang] || _i18n_base_locales_js__WEBPACK_IMPORTED_MODULE_4__.locales.en);
  }

  /**
   * Parse date string
   * @param  {String|Date|Number} dateStr - date string, Date object or time
   * value to parse
   * @param  {String|Object} format - format string or object that contains
   * toValue() custom parser, whose signature is
   * - args:
   *   - dateStr: {String|Date|Number} - the dateStr passed to the method
   *   - format: {Object} - the format object passed to the method
   *   - locale: {Object} - locale for the language specified by `lang`
   * - return:
   *     {Date|Number} parsed date or its time value
   * @param  {String} [lang=en] - language code for the locale to use
   * @return {Number} time value of parsed date
   */
  static parseDate(dateStr, format, lang) {
    return (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.parseDate)(dateStr, format, lang && _i18n_base_locales_js__WEBPACK_IMPORTED_MODULE_4__.locales[lang] || _i18n_base_locales_js__WEBPACK_IMPORTED_MODULE_4__.locales.en);
  }

  /**
   * @type {Object} - Installed locales in `[languageCode]: localeObject` format
   * en`:_English (US)_ is pre-installed.
   */
  static get locales() {
    return _i18n_base_locales_js__WEBPACK_IMPORTED_MODULE_4__.locales;
  }

  /**
   * @type {Boolean} - Whether the picker element is shown. `true` whne shown
   */
  get active() {
    return !!(this.picker && this.picker.active);
  }

  /**
   * @type {HTMLDivElement} - DOM object of picker element
   */
  get pickerElement() {
    return this.picker ? this.picker.element : undefined;
  }

  /**
   * Set new values to the config options
   * @param {Object} options - config options to update
   */
  setOptions(options) {
    const picker = this.picker;
    const newOptions = (0,_options_processOptions_js__WEBPACK_IMPORTED_MODULE_6__["default"])(options, this);
    Object.assign(this._options, options);
    Object.assign(this.config, newOptions);
    picker.setOptions(newOptions);

    refreshUI(this, 3);
  }

  /**
   * Show the picker element
   */
  show() {
    if (this.inputField) {
      if (this.inputField.disabled) {
        return;
      }
      if (this.inputField !== document.activeElement) {
        this._showing = true;
        this.inputField.focus();
        delete this._showing;
      }
    }
    this.picker.show();
  }

  /**
   * Hide the picker element
   * Not available on inline picker
   */
  hide() {
    if (this.inline) {
      return;
    }
    this.picker.hide();
    this.picker.update().changeView(this.config.startView).render();
  }

  /**
   * Destroy the Datepicker instance
   * @return {Detepicker} - the instance destroyed
   */
  destroy() {
    this.hide();
    (0,_lib_event_js__WEBPACK_IMPORTED_MODULE_3__.unregisterListeners)(this);
    this.picker.detach();
    if (!this.inline) {
      this.inputField.classList.remove('datepicker-input');
    }
    delete this.element.datepicker;
    return this;
  }

  /**
   * Get the selected date(s)
   *
   * The method returns a Date object of selected date by default, and returns
   * an array of selected dates in multidate mode. If format string is passed,
   * it returns date string(s) formatted in given format.
   *
   * @param  {String} [format] - Format string to stringify the date(s)
   * @return {Date|String|Date[]|String[]} - selected date(s), or if none is
   * selected, empty array in multidate mode and untitled in sigledate mode
   */
  getDate(format = undefined) {
    const callback = format
      ? date => (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.formatDate)(date, format, this.config.locale)
      : date => new Date(date);

    if (this.config.multidate) {
      return this.dates.map(callback);
    }
    if (this.dates.length > 0) {
      return callback(this.dates[0]);
    }
  }

  /**
   * Set selected date(s)
   *
   * In multidate mode, you can pass multiple dates as a series of arguments
   * or an array. (Since each date is parsed individually, the type of the
   * dates doesn't have to be the same.)
   * The given dates are used to toggle the select status of each date. The
   * number of selected dates is kept from exceeding the length set to
   * maxNumberOfDates.
   *
   * With clear: true option, the method can be used to clear the selection
   * and to replace the selection instead of toggling in multidate mode.
   * If the option is passed with no date arguments or an empty dates array,
   * it works as "clear" (clear the selection then set nothing), and if the
   * option is passed with new dates to select, it works as "replace" (clear
   * the selection then set the given dates)
   *
   * When render: false option is used, the method omits re-rendering the
   * picker element. In this case, you need to call refresh() method later in
   * order for the picker element to reflect the changes. The input field is
   * refreshed always regardless of this option.
   *
   * When invalid (unparsable, repeated, disabled or out-of-range) dates are
   * passed, the method ignores them and applies only valid ones. In the case
   * that all the given dates are invalid, which is distinguished from passing
   * no dates, the method considers it as an error and leaves the selection
   * untouched.
   *
   * @param {...(Date|Number|String)|Array} [dates] - Date strings, Date
   * objects, time values or mix of those for new selection
   * @param {Object} [options] - function options
   * - clear: {boolean} - Whether to clear the existing selection
   *     defualt: false
   * - render: {boolean} - Whether to re-render the picker element
   *     default: true
   * - autohide: {boolean} - Whether to hide the picker element after re-render
   *     Ignored when used with render: false
   *     default: config.autohide
   */
  setDate(...args) {
    const dates = [...args];
    const opts = {};
    const lastArg = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.lastItemOf)(args);
    if (
      typeof lastArg === 'object'
      && !Array.isArray(lastArg)
      && !(lastArg instanceof Date)
      && lastArg
    ) {
      Object.assign(opts, dates.pop());
    }

    const inputDates = Array.isArray(dates[0]) ? dates[0] : dates;
    setDate(this, inputDates, opts);
  }

  /**
   * Update the selected date(s) with input field's value
   * Not available on inline picker
   *
   * The input field will be refreshed with properly formatted date string.
   *
   * @param  {Object} [options] - function options
   * - autohide: {boolean} - whether to hide the picker element after refresh
   *     default: false
   */
  update(options = undefined) {
    if (this.inline) {
      return;
    }

    const opts = {clear: true, autohide: !!(options && options.autohide)};
    const inputDates = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.stringToArray)(this.inputField.value, this.config.dateDelimiter);
    setDate(this, inputDates, opts);
  }

  /**
   * Refresh the picker element and the associated input field
   * @param {String} [target] - target item when refreshing one item only
   * 'picker' or 'input'
   * @param {Boolean} [forceRender] - whether to re-render the picker element
   * regardless of its state instead of optimized refresh
   */
  refresh(target = undefined, forceRender = false) {
    if (target && typeof target !== 'string') {
      forceRender = target;
      target = undefined;
    }

    let mode;
    if (target === 'picker') {
      mode = 2;
    } else if (target === 'input') {
      mode = 1;
    } else {
      mode = 3;
    }
    refreshUI(this, mode, !forceRender);
  }

  /**
   * Enter edit mode
   * Not available on inline picker or when the picker element is hidden
   */
  enterEditMode() {
    if (this.inline || !this.picker.active || this.editMode) {
      return;
    }
    this.editMode = true;
    this.inputField.classList.add('in-edit', 'border-blue-700');
  }

  /**
   * Exit from edit mode
   * Not available on inline picker
   * @param  {Object} [options] - function options
   * - update: {boolean} - whether to call update() after exiting
   *     If false, input field is revert to the existing selection
   *     default: false
   */
  exitEditMode(options = undefined) {
    if (this.inline || !this.editMode) {
      return;
    }
    const opts = Object.assign({update: false}, options);
    delete this.editMode;
    this.inputField.classList.remove('in-edit', 'border-blue-700');
    if (opts.update) {
      this.update(opts);
    }
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/events/functions.js":
/*!*****************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/events/functions.js ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "triggerDatepickerEvent": () => (/* binding */ triggerDatepickerEvent),
/* harmony export */   "goToPrevOrNext": () => (/* binding */ goToPrevOrNext),
/* harmony export */   "switchView": () => (/* binding */ switchView),
/* harmony export */   "unfocus": () => (/* binding */ unfocus)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");



function triggerDatepickerEvent(datepicker, type) {
  const detail = {
    date: datepicker.getDate(),
    viewDate: new Date(datepicker.picker.viewDate),
    viewId: datepicker.picker.currentView.id,
    datepicker,
  };
  datepicker.element.dispatchEvent(new CustomEvent(type, {detail}));
}

// direction: -1 (to previous), 1 (to next)
function goToPrevOrNext(datepicker, direction) {
  const {minDate, maxDate} = datepicker.config;
  const {currentView, viewDate} = datepicker.picker;
  let newViewDate;
  switch (currentView.id) {
    case 0:
      newViewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addMonths)(viewDate, direction);
      break;
    case 1:
      newViewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addYears)(viewDate, direction);
      break;
    default:
      newViewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addYears)(viewDate, direction * currentView.navStep);
  }
  newViewDate = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.limitToRange)(newViewDate, minDate, maxDate);
  datepicker.picker.changeFocus(newViewDate).render();
}

function switchView(datepicker) {
  const viewId = datepicker.picker.currentView.id;
  if (viewId === datepicker.config.maxView) {
    return;
  }
  datepicker.picker.changeView(viewId + 1).render();
}

function unfocus(datepicker) {
  if (datepicker.config.updateOnBlur) {
    datepicker.update({autohide: true});
  } else {
    datepicker.refresh('input');
    datepicker.hide();
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/events/inputFieldListeners.js":
/*!***************************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/events/inputFieldListeners.js ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "onKeydown": () => (/* binding */ onKeydown),
/* harmony export */   "onFocus": () => (/* binding */ onFocus),
/* harmony export */   "onMousedown": () => (/* binding */ onMousedown),
/* harmony export */   "onClickInput": () => (/* binding */ onClickInput),
/* harmony export */   "onPaste": () => (/* binding */ onPaste)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _functions_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./functions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/functions.js");




// Find the closest date that doesn't meet the condition for unavailable date
// Returns undefined if no available date is found
// addFn: function to calculate the next date
//   - args: time value, amount
// increase: amount to pass to addFn
// testFn: function to test the unavailablity of the date
//   - args: time value; retun: true if unavailable
function findNextAvailableOne(date, addFn, increase, testFn, min, max) {
  if (!(0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.isInRange)(date, min, max)) {
    return;
  }
  if (testFn(date)) {
    const newDate = addFn(date, increase);
    return findNextAvailableOne(newDate, addFn, increase, testFn, min, max);
  }
  return date;
}

// direction: -1 (left/up), 1 (right/down)
// vertical: true for up/down, false for left/right
function moveByArrowKey(datepicker, ev, direction, vertical) {
  const picker = datepicker.picker;
  const currentView = picker.currentView;
  const step = currentView.step || 1;
  let viewDate = picker.viewDate;
  let addFn;
  let testFn;
  switch (currentView.id) {
    case 0:
      if (vertical) {
        viewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addDays)(viewDate, direction * 7);
      } else if (ev.ctrlKey || ev.metaKey) {
        viewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addYears)(viewDate, direction);
      } else {
        viewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addDays)(viewDate, direction);
      }
      addFn = _lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addDays;
      testFn = (date) => currentView.disabled.includes(date);
      break;
    case 1:
      viewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addMonths)(viewDate, vertical ? direction * 4 : direction);
      addFn = _lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addMonths;
      testFn = (date) => {
        const dt = new Date(date);
        const {year, disabled} = currentView;
        return dt.getFullYear() === year && disabled.includes(dt.getMonth());
      };
      break;
    default:
      viewDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addYears)(viewDate, direction * (vertical ? 4 : 1) * step);
      addFn = _lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addYears;
      testFn = date => currentView.disabled.includes((0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.startOfYearPeriod)(date, step));
  }
  viewDate = findNextAvailableOne(
    viewDate,
    addFn,
    direction < 0 ? -step : step,
    testFn,
    currentView.minDate,
    currentView.maxDate
  );
  if (viewDate !== undefined) {
    picker.changeFocus(viewDate).render();
  }
}

function onKeydown(datepicker, ev) {
  if (ev.key === 'Tab') {
    (0,_functions_js__WEBPACK_IMPORTED_MODULE_2__.unfocus)(datepicker);
    return;
  }

  const picker = datepicker.picker;
  const {id, isMinView} = picker.currentView;
  if (!picker.active) {
    switch (ev.key) {
      case 'ArrowDown':
      case 'Escape':
        picker.show();
        break;
      case 'Enter':
        datepicker.update();
        break;
      default:
        return;
    }
  } else if (datepicker.editMode) {
    switch (ev.key) {
      case 'Escape':
        picker.hide();
        break;
      case 'Enter':
        datepicker.exitEditMode({update: true, autohide: datepicker.config.autohide});
        break;
      default:
        return;
    }
  } else {
    switch (ev.key) {
      case 'Escape':
        picker.hide();
        break;
      case 'ArrowLeft':
        if (ev.ctrlKey || ev.metaKey) {
          (0,_functions_js__WEBPACK_IMPORTED_MODULE_2__.goToPrevOrNext)(datepicker, -1);
        } else if (ev.shiftKey) {
          datepicker.enterEditMode();
          return;
        } else {
          moveByArrowKey(datepicker, ev, -1, false);
        }
        break;
      case 'ArrowRight':
        if (ev.ctrlKey || ev.metaKey) {
          (0,_functions_js__WEBPACK_IMPORTED_MODULE_2__.goToPrevOrNext)(datepicker, 1);
        } else if (ev.shiftKey) {
          datepicker.enterEditMode();
          return;
        } else {
          moveByArrowKey(datepicker, ev, 1, false);
        }
        break;
      case 'ArrowUp':
        if (ev.ctrlKey || ev.metaKey) {
          (0,_functions_js__WEBPACK_IMPORTED_MODULE_2__.switchView)(datepicker);
        } else if (ev.shiftKey) {
          datepicker.enterEditMode();
          return;
        } else {
          moveByArrowKey(datepicker, ev, -1, true);
        }
        break;
      case 'ArrowDown':
        if (ev.shiftKey && !ev.ctrlKey && !ev.metaKey) {
          datepicker.enterEditMode();
          return;
        }
        moveByArrowKey(datepicker, ev, 1, true);
        break;
      case 'Enter':
        if (isMinView) {
          datepicker.setDate(picker.viewDate);
        } else {
          picker.changeView(id - 1).render();
        }
        break;
      case 'Backspace':
      case 'Delete':
        datepicker.enterEditMode();
        return;
      default:
        if (ev.key.length === 1 && !ev.ctrlKey && !ev.metaKey) {
          datepicker.enterEditMode();
        }
        return;
    }
  }
  ev.preventDefault();
  ev.stopPropagation();
}

function onFocus(datepicker) {
  if (datepicker.config.showOnFocus && !datepicker._showing) {
    datepicker.show();
  }
}

// for the prevention for entering edit mode while getting focus on click
function onMousedown(datepicker, ev) {
  const el = ev.target;
  if (datepicker.picker.active || datepicker.config.showOnClick) {
    el._active = el === document.activeElement;
    el._clicking = setTimeout(() => {
      delete el._active;
      delete el._clicking;
    }, 2000);
  }
}

function onClickInput(datepicker, ev) {
  const el = ev.target;
  if (!el._clicking) {
    return;
  }
  clearTimeout(el._clicking);
  delete el._clicking;

  if (el._active) {
    datepicker.enterEditMode();
  }
  delete el._active;

  if (datepicker.config.showOnClick) {
    datepicker.show();
  }
}

function onPaste(datepicker, ev) {
  if (ev.clipboardData.types.includes('text/plain')) {
    datepicker.enterEditMode();
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/events/otherListeners.js":
/*!**********************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/events/otherListeners.js ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "onClickOutside": () => (/* binding */ onClickOutside)
/* harmony export */ });
/* harmony import */ var _lib_event_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/event.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/event.js");
/* harmony import */ var _functions_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./functions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/functions.js");



// for the `document` to delegate the events from outside the picker/input field
function onClickOutside(datepicker, ev) {
  const element = datepicker.element;
  if (element !== document.activeElement) {
    return;
  }
  const pickerElem = datepicker.picker.element;
  if ((0,_lib_event_js__WEBPACK_IMPORTED_MODULE_0__.findElementInEventPath)(ev, el => el === element || el === pickerElem)) {
    return;
  }
  (0,_functions_js__WEBPACK_IMPORTED_MODULE_1__.unfocus)(datepicker);
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/events/pickerListeners.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/events/pickerListeners.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "onClickTodayBtn": () => (/* binding */ onClickTodayBtn),
/* harmony export */   "onClickClearBtn": () => (/* binding */ onClickClearBtn),
/* harmony export */   "onClickViewSwitch": () => (/* binding */ onClickViewSwitch),
/* harmony export */   "onClickPrevBtn": () => (/* binding */ onClickPrevBtn),
/* harmony export */   "onClickNextBtn": () => (/* binding */ onClickNextBtn),
/* harmony export */   "onClickView": () => (/* binding */ onClickView),
/* harmony export */   "onClickPicker": () => (/* binding */ onClickPicker)
/* harmony export */ });
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _lib_event_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../lib/event.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/event.js");
/* harmony import */ var _functions_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./functions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/functions.js");




function goToSelectedMonthOrYear(datepicker, selection) {
  const picker = datepicker.picker;
  const viewDate = new Date(picker.viewDate);
  const viewId = picker.currentView.id;
  const newDate = viewId === 1
    ? (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_0__.addMonths)(viewDate, selection - viewDate.getMonth())
    : (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_0__.addYears)(viewDate, selection - viewDate.getFullYear());

  picker.changeFocus(newDate).changeView(viewId - 1).render();
}

function onClickTodayBtn(datepicker) {
  const picker = datepicker.picker;
  const currentDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_0__.today)();
  if (datepicker.config.todayBtnMode === 1) {
    if (datepicker.config.autohide) {
      datepicker.setDate(currentDate);
      return;
    }
    datepicker.setDate(currentDate, {render: false});
    picker.update();
  }
  if (picker.viewDate !== currentDate) {
    picker.changeFocus(currentDate);
  }
  picker.changeView(0).render();
}

function onClickClearBtn(datepicker) {
  datepicker.setDate({clear: true});
}

function onClickViewSwitch(datepicker) {
  (0,_functions_js__WEBPACK_IMPORTED_MODULE_2__.switchView)(datepicker);
}

function onClickPrevBtn(datepicker) {
  (0,_functions_js__WEBPACK_IMPORTED_MODULE_2__.goToPrevOrNext)(datepicker, -1);
}

function onClickNextBtn(datepicker) {
  (0,_functions_js__WEBPACK_IMPORTED_MODULE_2__.goToPrevOrNext)(datepicker, 1);
}

// For the picker's main block to delegete the events from `datepicker-cell`s
function onClickView(datepicker, ev) {
  const target = (0,_lib_event_js__WEBPACK_IMPORTED_MODULE_1__.findElementInEventPath)(ev, '.datepicker-cell');
  if (!target || target.classList.contains('disabled')) {
    return;
  }

  const {id, isMinView} = datepicker.picker.currentView;
  if (isMinView) {
    datepicker.setDate(Number(target.dataset.date));
  } else if (id === 1) {
    goToSelectedMonthOrYear(datepicker, Number(target.dataset.month));
  } else {
    goToSelectedMonthOrYear(datepicker, Number(target.dataset.year));
  }
}

function onClickPicker(datepicker) {
  if (!datepicker.inline && !datepicker.config.disableTouchKeyboard) {
    datepicker.inputField.focus();
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/i18n/base-locales.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/i18n/base-locales.js ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "locales": () => (/* binding */ locales)
/* harmony export */ });
// default locales
const locales = {
  en: {
    days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    titleFormat: "MM y"
  }
};


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/i18n/locales/es.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/i18n/locales/es.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Spanish translation for bootstrap-datepicker
 * Bruno Bonamin <bruno.bonamin@gmail.com>
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  es: {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
    today: "Hoy",
    monthsTitle: "Meses",
    clear: "Borrar",
    weekStart: 1,
    format: "dd/mm/yyyy"
  }
});


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date-format.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/lib/date-format.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "reFormatTokens": () => (/* binding */ reFormatTokens),
/* harmony export */   "reNonDateParts": () => (/* binding */ reNonDateParts),
/* harmony export */   "parseDate": () => (/* binding */ parseDate),
/* harmony export */   "formatDate": () => (/* binding */ formatDate)
/* harmony export */ });
/* harmony import */ var _date_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");



// pattern for format parts
const reFormatTokens = /dd?|DD?|mm?|MM?|yy?(?:yy)?/;
// pattern for non date parts
const reNonDateParts = /[\s!-/:-@[-`{-~年月日]+/;
// cache for persed formats
let knownFormats = {};
// parse funtions for date parts
const parseFns = {
  y(date, year) {
    return new Date(date).setFullYear(parseInt(year, 10));
  },
  m(date, month, locale) {
    const newDate = new Date(date);
    let monthIndex = parseInt(month, 10) - 1;

    if (isNaN(monthIndex)) {
      if (!month) {
        return NaN;
      }

      const monthName = month.toLowerCase();
      const compareNames = name => name.toLowerCase().startsWith(monthName);
      // compare with both short and full names because some locales have periods
      // in the short names (not equal to the first X letters of the full names)
      monthIndex = locale.monthsShort.findIndex(compareNames);
      if (monthIndex < 0) {
        monthIndex = locale.months.findIndex(compareNames);
      }
      if (monthIndex < 0) {
        return NaN;
      }
    }

    newDate.setMonth(monthIndex);
    return newDate.getMonth() !== normalizeMonth(monthIndex)
      ? newDate.setDate(0)
      : newDate.getTime();
  },
  d(date, day) {
    return new Date(date).setDate(parseInt(day, 10));
  },
};
// format functions for date parts
const formatFns = {
  d(date) {
    return date.getDate();
  },
  dd(date) {
    return padZero(date.getDate(), 2);
  },
  D(date, locale) {
    return locale.daysShort[date.getDay()];
  },
  DD(date, locale) {
    return locale.days[date.getDay()];
  },
  m(date) {
    return date.getMonth() + 1;
  },
  mm(date) {
    return padZero(date.getMonth() + 1, 2);
  },
  M(date, locale) {
    return locale.monthsShort[date.getMonth()];
  },
  MM(date, locale) {
    return locale.months[date.getMonth()];
  },
  y(date) {
    return date.getFullYear();
  },
  yy(date) {
    return padZero(date.getFullYear(), 2).slice(-2);
  },
  yyyy(date) {
    return padZero(date.getFullYear(), 4);
  },
};

// get month index in normal range (0 - 11) from any number
function normalizeMonth(monthIndex) {
  return monthIndex > -1 ? monthIndex % 12 : normalizeMonth(monthIndex + 12);
}

function padZero(num, length) {
  return num.toString().padStart(length, '0');
}

function parseFormatString(format) {
  if (typeof format !== 'string') {
    throw new Error("Invalid date format.");
  }
  if (format in knownFormats) {
    return knownFormats[format];
  }

  // sprit the format string into parts and seprators
  const separators = format.split(reFormatTokens);
  const parts = format.match(new RegExp(reFormatTokens, 'g'));
  if (separators.length === 0 || !parts) {
    throw new Error("Invalid date format.");
  }

  // collect format functions used in the format
  const partFormatters = parts.map(token => formatFns[token]);

  // collect parse function keys used in the format
  // iterate over parseFns' keys in order to keep the order of the keys.
  const partParserKeys = Object.keys(parseFns).reduce((keys, key) => {
    const token = parts.find(part => part[0] !== 'D' && part[0].toLowerCase() === key);
    if (token) {
      keys.push(key);
    }
    return keys;
  }, []);

  return knownFormats[format] = {
    parser(dateStr, locale) {
      const dateParts = dateStr.split(reNonDateParts).reduce((dtParts, part, index) => {
        if (part.length > 0 && parts[index]) {
          const token = parts[index][0];
          if (token === 'M') {
            dtParts.m = part;
          } else if (token !== 'D') {
            dtParts[token] = part;
          }
        }
        return dtParts;
      }, {});

      // iterate over partParserkeys so that the parsing is made in the oder
      // of year, month and day to prevent the day parser from correcting last
      // day of month wrongly
      return partParserKeys.reduce((origDate, key) => {
        const newDate = parseFns[key](origDate, dateParts[key], locale);
        // ingnore the part failed to parse
        return isNaN(newDate) ? origDate : newDate;
      }, (0,_date_js__WEBPACK_IMPORTED_MODULE_0__.today)());
    },
    formatter(date, locale) {
      let dateStr = partFormatters.reduce((str, fn, index) => {
        return str += `${separators[index]}${fn(date, locale)}`;
      }, '');
      // separators' length is always parts' length + 1,
      return dateStr += (0,_utils_js__WEBPACK_IMPORTED_MODULE_1__.lastItemOf)(separators);
    },
  };
}

function parseDate(dateStr, format, locale) {
  if (dateStr instanceof Date || typeof dateStr === 'number') {
    const date = (0,_date_js__WEBPACK_IMPORTED_MODULE_0__.stripTime)(dateStr);
    return isNaN(date) ? undefined : date;
  }
  if (!dateStr) {
    return undefined;
  }
  if (dateStr === 'today') {
    return (0,_date_js__WEBPACK_IMPORTED_MODULE_0__.today)();
  }

  if (format && format.toValue) {
    const date = format.toValue(dateStr, format, locale);
    return isNaN(date) ? undefined : (0,_date_js__WEBPACK_IMPORTED_MODULE_0__.stripTime)(date);
  }

  return parseFormatString(format).parser(dateStr, locale);
}

function formatDate(date, format, locale) {
  if (isNaN(date) || (!date && date !== 0)) {
    return '';
  }

  const dateObj = typeof date === 'number' ? new Date(date) : date;

  if (format.toDisplay) {
    return format.toDisplay(dateObj, format, locale);
  }

  return parseFormatString(format).formatter(dateObj, locale);
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "stripTime": () => (/* binding */ stripTime),
/* harmony export */   "today": () => (/* binding */ today),
/* harmony export */   "dateValue": () => (/* binding */ dateValue),
/* harmony export */   "addDays": () => (/* binding */ addDays),
/* harmony export */   "addWeeks": () => (/* binding */ addWeeks),
/* harmony export */   "addMonths": () => (/* binding */ addMonths),
/* harmony export */   "addYears": () => (/* binding */ addYears),
/* harmony export */   "dayOfTheWeekOf": () => (/* binding */ dayOfTheWeekOf),
/* harmony export */   "getWeek": () => (/* binding */ getWeek),
/* harmony export */   "startOfYearPeriod": () => (/* binding */ startOfYearPeriod)
/* harmony export */ });
function stripTime(timeValue) {
  return new Date(timeValue).setHours(0, 0, 0, 0);
}

function today() {
  return new Date().setHours(0, 0, 0, 0);
}

// Get the time value of the start of given date or year, month and day
function dateValue(...args) {
  switch (args.length) {
    case 0:
      return today();
    case 1:
      return stripTime(args[0]);
  }

  // use setFullYear() to keep 2-digit year from being mapped to 1900-1999
  const newDate = new Date(0);
  newDate.setFullYear(...args);
  return newDate.setHours(0, 0, 0, 0);
}

function addDays(date, amount) {
  const newDate = new Date(date);
  return newDate.setDate(newDate.getDate() + amount);
}

function addWeeks(date, amount) {
  return addDays(date, amount * 7);
}

function addMonths(date, amount) {
  // If the day of the date is not in the new month, the last day of the new
  // month will be returned. e.g. Jan 31 + 1 month → Feb 28 (not Mar 03)
  const newDate = new Date(date);
  const monthsToSet = newDate.getMonth() + amount;
  let expectedMonth = monthsToSet % 12;
  if (expectedMonth < 0) {
    expectedMonth += 12;
  }

  const time = newDate.setMonth(monthsToSet);
  return newDate.getMonth() !== expectedMonth ? newDate.setDate(0) : time;
}

function addYears(date, amount) {
  // If the date is Feb 29 and the new year is not a leap year, Feb 28 of the
  // new year will be returned.
  const newDate = new Date(date);
  const expectedMonth = newDate.getMonth();
  const time = newDate.setFullYear(newDate.getFullYear() + amount);
  return expectedMonth === 1 && newDate.getMonth() === 2 ? newDate.setDate(0) : time;
}

// Calculate the distance bettwen 2 days of the week
function dayDiff(day, from) {
  return (day - from + 7) % 7;
}

// Get the date of the specified day of the week of given base date
function dayOfTheWeekOf(baseDate, dayOfWeek, weekStart = 0) {
  const baseDay = new Date(baseDate).getDay();
  return addDays(baseDate, dayDiff(dayOfWeek, weekStart) - dayDiff(baseDay, weekStart));
}

// Get the ISO week of a date
function getWeek(date) {
  // start of ISO week is Monday
  const thuOfTheWeek = dayOfTheWeekOf(date, 4, 1);
  // 1st week == the week where the 4th of January is in
  const firstThu = dayOfTheWeekOf(new Date(thuOfTheWeek).setMonth(0, 4), 4, 1);
  return Math.round((thuOfTheWeek - firstThu) / 604800000) + 1;
}

// Get the start year of the period of years that includes given date
// years: length of the year period
function startOfYearPeriod(date, years) {
  /* @see https://en.wikipedia.org/wiki/Year_zero#ISO_8601 */
  const year = new Date(date).getFullYear();
  return Math.floor(year / years) * years;
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js":
/*!********************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "parseHTML": () => (/* binding */ parseHTML),
/* harmony export */   "isVisible": () => (/* binding */ isVisible),
/* harmony export */   "hideElement": () => (/* binding */ hideElement),
/* harmony export */   "showElement": () => (/* binding */ showElement),
/* harmony export */   "emptyChildNodes": () => (/* binding */ emptyChildNodes),
/* harmony export */   "replaceChildNodes": () => (/* binding */ replaceChildNodes)
/* harmony export */ });
const range = document.createRange();

function parseHTML(html) {
  return range.createContextualFragment(html);
}

// equivalent to jQuery's :visble
function isVisible(el) {
  return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
}

function hideElement(el) {
  if (el.style.display === 'none') {
    return;
  }
  // back up the existing display setting in data-style-display
  if (el.style.display) {
    el.dataset.styleDisplay = el.style.display;
  }
  el.style.display = 'none';
}

function showElement(el) {
  if (el.style.display !== 'none') {
    return;
  }
  if (el.dataset.styleDisplay) {
    // restore backed-up dispay property
    el.style.display = el.dataset.styleDisplay;
    delete el.dataset.styleDisplay;
  } else {
    el.style.display = '';
  }
}

function emptyChildNodes(el) {
  if (el.firstChild) {
    el.removeChild(el.firstChild);
    emptyChildNodes(el);
  }
}

function replaceChildNodes(el, newChildNodes) {
  emptyChildNodes(el);
  if (newChildNodes instanceof DocumentFragment) {
    el.appendChild(newChildNodes);
  } else if (typeof newChildNodes === 'string') {
    el.appendChild(parseHTML(newChildNodes));
  } else if (typeof newChildNodes.forEach === 'function') {
    newChildNodes.forEach((node) => {
      el.appendChild(node);
    });
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/lib/event.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/lib/event.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "registerListeners": () => (/* binding */ registerListeners),
/* harmony export */   "unregisterListeners": () => (/* binding */ unregisterListeners),
/* harmony export */   "findElementInEventPath": () => (/* binding */ findElementInEventPath)
/* harmony export */ });
const listenerRegistry = new WeakMap();
const {addEventListener, removeEventListener} = EventTarget.prototype;

// Register event listeners to a key object
// listeners: array of listener definitions;
//   - each definition must be a flat array of event target and the arguments
//     used to call addEventListener() on the target
function registerListeners(keyObj, listeners) {
  let registered = listenerRegistry.get(keyObj);
  if (!registered) {
    registered = [];
    listenerRegistry.set(keyObj, registered);
  }
  listeners.forEach((listener) => {
    addEventListener.call(...listener);
    registered.push(listener);
  });
}

function unregisterListeners(keyObj) {
  let listeners = listenerRegistry.get(keyObj);
  if (!listeners) {
    return;
  }
  listeners.forEach((listener) => {
    removeEventListener.call(...listener);
  });
  listenerRegistry.delete(keyObj);
}

// Event.composedPath() polyfill for Edge
// based on https://gist.github.com/kleinfreund/e9787d73776c0e3750dcfcdc89f100ec
if (!Event.prototype.composedPath) {
  const getComposedPath = (node, path = []) => {
    path.push(node);

    let parent;
    if (node.parentNode) {
      parent = node.parentNode;
    } else if (node.host) { // ShadowRoot
      parent = node.host;
    } else if (node.defaultView) {  // Document
      parent = node.defaultView;
    }
    return parent ? getComposedPath(parent, path) : path;
  };

  Event.prototype.composedPath = function () {
    return getComposedPath(this.target);
  };
}

function findFromPath(path, criteria, currentTarget, index = 0) {
  const el = path[index];
  if (criteria(el)) {
    return el;
  } else if (el === currentTarget || !el.parentElement) {
    // stop when reaching currentTarget or <html>
    return;
  }
  return findFromPath(path, criteria, currentTarget, index + 1);
}

// Search for the actual target of a delegated event
function findElementInEventPath(ev, selector) {
  const criteria = typeof selector === 'function' ? selector : el => el.matches(selector);
  return findFromPath(ev.composedPath(), criteria, ev.currentTarget);
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "hasProperty": () => (/* binding */ hasProperty),
/* harmony export */   "lastItemOf": () => (/* binding */ lastItemOf),
/* harmony export */   "pushUnique": () => (/* binding */ pushUnique),
/* harmony export */   "stringToArray": () => (/* binding */ stringToArray),
/* harmony export */   "isInRange": () => (/* binding */ isInRange),
/* harmony export */   "limitToRange": () => (/* binding */ limitToRange),
/* harmony export */   "createTagRepeat": () => (/* binding */ createTagRepeat),
/* harmony export */   "optimizeTemplateHTML": () => (/* binding */ optimizeTemplateHTML)
/* harmony export */ });
function hasProperty(obj, prop) {
  return Object.prototype.hasOwnProperty.call(obj, prop);
}

function lastItemOf(arr) {
  return arr[arr.length - 1];
}

// push only the items not included in the array
function pushUnique(arr, ...items) {
  items.forEach((item) => {
    if (arr.includes(item)) {
      return;
    }
    arr.push(item);
  });
  return arr;
}

function stringToArray(str, separator) {
  // convert empty string to an empty array
  return str ? str.split(separator) : [];
}

function isInRange(testVal, min, max) {
  const minOK = min === undefined || testVal >= min;
  const maxOK = max === undefined || testVal <= max;
  return minOK && maxOK;
}

function limitToRange(val, min, max) {
  if (val < min) {
    return min;
  }
  if (val > max) {
    return max;
  }
  return val;
}

function createTagRepeat(tagName, repeat, attributes = {}, index = 0, html = '') {
  const openTagSrc = Object.keys(attributes).reduce((src, attr) => {
    let val = attributes[attr];
    if (typeof val === 'function') {
      val = val(index);
    }
    return `${src} ${attr}="${val}"`;
  }, tagName);
  html += `<${openTagSrc}></${tagName}>`;

  const next = index + 1;
  return next < repeat
    ? createTagRepeat(tagName, repeat, attributes, next, html)
    : html;
}

// Remove the spacing surrounding tags for HTML parser not to create text nodes
// before/after elements
function optimizeTemplateHTML(html) {
  return html.replace(/>\s+/g, '>').replace(/\s+</, '<');
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/options/defaultOptions.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/options/defaultOptions.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// config options updatable by setOptions() and their default values
const defaultOptions = {
  autohide: false,
  beforeShowDay: null,
  beforeShowDecade: null,
  beforeShowMonth: null,
  beforeShowYear: null,
  calendarWeeks: false,
  clearBtn: false,
  dateDelimiter: ',',
  datesDisabled: [],
  daysOfWeekDisabled: [],
  daysOfWeekHighlighted: [],
  defaultViewDate: undefined, // placeholder, defaults to today() by the program
  disableTouchKeyboard: false,
  format: 'mm/dd/yyyy',
  language: 'en',
  maxDate: null,
  maxNumberOfDates: 1,
  maxView: 3,
  minDate: null,
  nextArrow: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>',
  orientation: 'auto',
  pickLevel: 0,
  prevArrow: '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>',
  showDaysOfWeek: true,
  showOnClick: true,
  showOnFocus: true,
  startView: 0,
  title: '',
  todayBtn: false,
  todayBtnMode: 0,
  todayHighlight: false,
  updateOnBlur: true,
  weekStart: 0,
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (defaultOptions);


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/options/processOptions.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/options/processOptions.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ processOptions)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../lib/date-format.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date-format.js");
/* harmony import */ var _lib_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../lib/dom.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js");
/* harmony import */ var _defaultOptions_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./defaultOptions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/options/defaultOptions.js");






const {
  language: defaultLang,
  format: defaultFormat,
  weekStart: defaultWeekStart,
} = _defaultOptions_js__WEBPACK_IMPORTED_MODULE_4__["default"];

// Reducer function to filter out invalid day-of-week from the input
function sanitizeDOW(dow, day) {
  return dow.length < 6 && day >= 0 && day < 7
    ? (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.pushUnique)(dow, day)
    : dow;
}

function calcEndOfWeek(startOfWeek) {
  return (startOfWeek + 6) % 7;
}

// validate input date. if invalid, fallback to the original value
function validateDate(value, format, locale, origValue) {
  const date = (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.parseDate)(value, format, locale);
  return date !== undefined ? date : origValue;
}

// Validate viewId. if invalid, fallback to the original value
function validateViewId(value, origValue, max = 3) {
  const viewId = parseInt(value, 10);
  return viewId >= 0 && viewId <= max ? viewId : origValue;
}

// Create Datepicker configuration to set
function processOptions(options, datepicker) {
  const inOpts = Object.assign({}, options);
  const config = {};
  const locales = datepicker.constructor.locales;
  let {
    format,
    language,
    locale,
    maxDate,
    maxView,
    minDate,
    pickLevel,
    startView,
    weekStart,
  } = datepicker.config || {};

  if (inOpts.language) {
    let lang;
    if (inOpts.language !== language) {
      if (locales[inOpts.language]) {
        lang = inOpts.language;
      } else {
        // Check if langauge + region tag can fallback to the one without
        // region (e.g. fr-CA → fr)
        lang = inOpts.language.split('-')[0];
        if (locales[lang] === undefined) {
          lang = false;
        }
      }
    }
    delete inOpts.language;
    if (lang) {
      language = config.language = lang;

      // update locale as well when updating language
      const origLocale = locale || locales[defaultLang];
      // use default language's properties for the fallback
      locale = Object.assign({
        format: defaultFormat,
        weekStart: defaultWeekStart
      }, locales[defaultLang]);
      if (language !== defaultLang) {
        Object.assign(locale, locales[language]);
      }
      config.locale = locale;
      // if format and/or weekStart are the same as old locale's defaults,
      // update them to new locale's defaults
      if (format === origLocale.format) {
        format = config.format = locale.format;
      }
      if (weekStart === origLocale.weekStart) {
        weekStart = config.weekStart = locale.weekStart;
        config.weekEnd = calcEndOfWeek(locale.weekStart);
      }
    }
  }

  if (inOpts.format) {
    const hasToDisplay = typeof inOpts.format.toDisplay === 'function';
    const hasToValue = typeof inOpts.format.toValue === 'function';
    const validFormatString = _lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.reFormatTokens.test(inOpts.format);
    if ((hasToDisplay && hasToValue) || validFormatString) {
      format = config.format = inOpts.format;
    }
    delete inOpts.format;
  }

  //*** dates ***//
  // while min and maxDate for "no limit" in the options are better to be null
  // (especially when updating), the ones in the config have to be undefined
  // because null is treated as 0 (= unix epoch) when comparing with time value
  let minDt = minDate;
  let maxDt = maxDate;
  if (inOpts.minDate !== undefined) {
    minDt = inOpts.minDate === null
      ? (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(0, 0, 1)  // set 0000-01-01 to prevent negative values for year
      : validateDate(inOpts.minDate, format, locale, minDt);
    delete inOpts.minDate;
  }
  if (inOpts.maxDate !== undefined) {
    maxDt = inOpts.maxDate === null
      ? undefined
      : validateDate(inOpts.maxDate, format, locale, maxDt);
    delete inOpts.maxDate;
  }
  if (maxDt < minDt) {
    minDate = config.minDate = maxDt;
    maxDate = config.maxDate = minDt;
  } else {
    if (minDate !== minDt) {
      minDate = config.minDate = minDt;
    }
    if (maxDate !== maxDt) {
      maxDate = config.maxDate = maxDt;
    }
  }

  if (inOpts.datesDisabled) {
    config.datesDisabled = inOpts.datesDisabled.reduce((dates, dt) => {
      const date = (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.parseDate)(dt, format, locale);
      return date !== undefined ? (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.pushUnique)(dates, date) : dates;
    }, []);
    delete inOpts.datesDisabled;
  }
  if (inOpts.defaultViewDate !== undefined) {
    const viewDate = (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.parseDate)(inOpts.defaultViewDate, format, locale);
    if (viewDate !== undefined) {
      config.defaultViewDate = viewDate;
    }
    delete inOpts.defaultViewDate;
  }

  //*** days of week ***//
  if (inOpts.weekStart !== undefined) {
    const wkStart = Number(inOpts.weekStart) % 7;
    if (!isNaN(wkStart)) {
      weekStart = config.weekStart = wkStart;
      config.weekEnd = calcEndOfWeek(wkStart);
    }
    delete inOpts.weekStart;
  }
  if (inOpts.daysOfWeekDisabled) {
    config.daysOfWeekDisabled = inOpts.daysOfWeekDisabled.reduce(sanitizeDOW, []);
    delete inOpts.daysOfWeekDisabled;
  }
  if (inOpts.daysOfWeekHighlighted) {
    config.daysOfWeekHighlighted = inOpts.daysOfWeekHighlighted.reduce(sanitizeDOW, []);
    delete inOpts.daysOfWeekHighlighted;
  }

  //*** multi date ***//
  if (inOpts.maxNumberOfDates !== undefined) {
    const maxNumberOfDates = parseInt(inOpts.maxNumberOfDates, 10);
    if (maxNumberOfDates >= 0) {
      config.maxNumberOfDates = maxNumberOfDates;
      config.multidate = maxNumberOfDates !== 1;
    }
    delete inOpts.maxNumberOfDates;
  }
  if (inOpts.dateDelimiter) {
    config.dateDelimiter = String(inOpts.dateDelimiter);
    delete inOpts.dateDelimiter;
  }

  //*** pick level & view ***//
  let newPickLevel = pickLevel;
  if (inOpts.pickLevel !== undefined) {
    newPickLevel = validateViewId(inOpts.pickLevel, 2);
    delete inOpts.pickLevel;
  }
  if (newPickLevel !== pickLevel) {
    pickLevel = config.pickLevel = newPickLevel;
  }

  let newMaxView = maxView;
  if (inOpts.maxView !== undefined) {
    newMaxView = validateViewId(inOpts.maxView, maxView);
    delete inOpts.maxView;
  }
  // ensure max view >= pick level
  newMaxView = pickLevel > newMaxView ? pickLevel : newMaxView;
  if (newMaxView !== maxView) {
    maxView = config.maxView = newMaxView;
  }

  let newStartView = startView;
  if (inOpts.startView !== undefined) {
    newStartView = validateViewId(inOpts.startView, newStartView);
    delete inOpts.startView;
  }
  // ensure pick level <= start view <= max view
  if (newStartView < pickLevel) {
    newStartView = pickLevel;
  } else if (newStartView > maxView) {
    newStartView = maxView;
  }
  if (newStartView !== startView) {
    config.startView = newStartView;
  }

  //*** template ***//
  if (inOpts.prevArrow) {
    const prevArrow = (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.parseHTML)(inOpts.prevArrow);
    if (prevArrow.childNodes.length > 0) {
      config.prevArrow = prevArrow.childNodes;
    }
    delete inOpts.prevArrow;
  }
  if (inOpts.nextArrow) {
    const nextArrow = (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.parseHTML)(inOpts.nextArrow);
    if (nextArrow.childNodes.length > 0) {
      config.nextArrow = nextArrow.childNodes;
    }
    delete inOpts.nextArrow;
  }

  //*** misc ***//
  if (inOpts.disableTouchKeyboard !== undefined) {
    config.disableTouchKeyboard = 'ontouchstart' in document && !!inOpts.disableTouchKeyboard;
    delete inOpts.disableTouchKeyboard;
  }
  if (inOpts.orientation) {
    const orientation = inOpts.orientation.toLowerCase().split(/\s+/g);
    config.orientation = {
      x: orientation.find(x => (x === 'left' || x === 'right')) || 'auto',
      y: orientation.find(y => (y === 'top' || y === 'bottom')) || 'auto',
    };
    delete inOpts.orientation;
  }
  if (inOpts.todayBtnMode !== undefined) {
    switch(inOpts.todayBtnMode) {
      case 0:
      case 1:
        config.todayBtnMode = inOpts.todayBtnMode;
    }
    delete inOpts.todayBtnMode;
  }

  //*** copy the rest ***//
  Object.keys(inOpts).forEach((key) => {
    if (inOpts[key] !== undefined && (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(_defaultOptions_js__WEBPACK_IMPORTED_MODULE_4__["default"], key)) {
      config[key] = inOpts[key];
    }
  });

  return config;
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/Picker.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/Picker.js ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Picker)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _lib_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../lib/dom.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js");
/* harmony import */ var _lib_event_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../lib/event.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/event.js");
/* harmony import */ var _templates_pickerTemplate_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./templates/pickerTemplate.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/pickerTemplate.js");
/* harmony import */ var _views_DaysView_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./views/DaysView.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/DaysView.js");
/* harmony import */ var _views_MonthsView_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./views/MonthsView.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/MonthsView.js");
/* harmony import */ var _views_YearsView_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./views/YearsView.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/YearsView.js");
/* harmony import */ var _events_functions_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../events/functions.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/functions.js");
/* harmony import */ var _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../events/pickerListeners.js */ "./node_modules/@themesberg/tailwind-datepicker/js/events/pickerListeners.js");











function processPickerOptions(picker, options) {
  if (options.title !== undefined) {
    if (options.title) {
      picker.controls.title.textContent = options.title;
      (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.showElement)(picker.controls.title);
    } else {
      picker.controls.title.textContent = '';
      (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.hideElement)(picker.controls.title);
    }
  }
  if (options.prevArrow) {
    const prevBtn = picker.controls.prevBtn;
    (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.emptyChildNodes)(prevBtn);
    options.prevArrow.forEach((node) => {
      prevBtn.appendChild(node.cloneNode(true));
    });
  }
  if (options.nextArrow) {
    const nextBtn = picker.controls.nextBtn;
    (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.emptyChildNodes)(nextBtn);
    options.nextArrow.forEach((node) => {
      nextBtn.appendChild(node.cloneNode(true));
    });
  }
  if (options.locale) {
    picker.controls.todayBtn.textContent = options.locale.today;
    picker.controls.clearBtn.textContent = options.locale.clear;
  }
  if (options.todayBtn !== undefined) {
    if (options.todayBtn) {
      (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.showElement)(picker.controls.todayBtn);
    } else {
      (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.hideElement)(picker.controls.todayBtn);
    }
  }
  if ((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'minDate') || (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'maxDate')) {
    const {minDate, maxDate} = picker.datepicker.config;
    picker.controls.todayBtn.disabled = !(0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.isInRange)((0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.today)(), minDate, maxDate);
  }
  if (options.clearBtn !== undefined) {
    if (options.clearBtn) {
      (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.showElement)(picker.controls.clearBtn);
    } else {
      (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.hideElement)(picker.controls.clearBtn);
    }
  }
}

// Compute view date to reset, which will be...
// - the last item of the selected dates or defaultViewDate if no selection
// - limitted to minDate or maxDate if it exceeds the range
function computeResetViewDate(datepicker) {
  const {dates, config} = datepicker;
  const viewDate = dates.length > 0 ? (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.lastItemOf)(dates) : config.defaultViewDate;
  return (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.limitToRange)(viewDate, config.minDate, config.maxDate);
}

// Change current view's view date
function setViewDate(picker, newDate) {
  const oldViewDate = new Date(picker.viewDate);
  const newViewDate = new Date(newDate);
  const {id, year, first, last} = picker.currentView;
  const viewYear = newViewDate.getFullYear();

  picker.viewDate = newDate;
  if (viewYear !== oldViewDate.getFullYear()) {
    (0,_events_functions_js__WEBPACK_IMPORTED_MODULE_8__.triggerDatepickerEvent)(picker.datepicker, 'changeYear');
  }
  if (newViewDate.getMonth() !== oldViewDate.getMonth()) {
    (0,_events_functions_js__WEBPACK_IMPORTED_MODULE_8__.triggerDatepickerEvent)(picker.datepicker, 'changeMonth');
  }

  // return whether the new date is in different period on time from the one
  // displayed in the current view
  // when true, the view needs to be re-rendered on the next UI refresh.
  switch (id) {
    case 0:
      return newDate < first || newDate > last;
    case 1:
      return viewYear !== year;
    default:
      return viewYear < first || viewYear > last;
  }
}

function getTextDirection(el) {
  return window.getComputedStyle(el).direction;
}

// Class representing the picker UI
class Picker {
  constructor(datepicker) {
    this.datepicker = datepicker;

    const template = _templates_pickerTemplate_js__WEBPACK_IMPORTED_MODULE_4__["default"].replace(/%buttonClass%/g, datepicker.config.buttonClass);
    const element = this.element = (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.parseHTML)(template).firstChild;
    const [header, main, footer] = element.firstChild.children;
    const title = header.firstElementChild;
    const [prevBtn, viewSwitch, nextBtn] = header.lastElementChild.children;
    const [todayBtn, clearBtn] = footer.firstChild.children;
    const controls = {
      title,
      prevBtn,
      viewSwitch,
      nextBtn,
      todayBtn,
      clearBtn,
    };
    this.main = main;
    this.controls = controls;

    const elementClass = datepicker.inline ? 'inline' : 'dropdown';
    element.classList.add(`datepicker-${elementClass}`);
    elementClass === 'dropdown' ? element.classList.add('dropdown', 'absolute', 'top-0', 'left-0', 'z-20', 'pt-2') : null;

    processPickerOptions(this, datepicker.config);
    this.viewDate = computeResetViewDate(datepicker);

    // set up event listeners
    (0,_lib_event_js__WEBPACK_IMPORTED_MODULE_3__.registerListeners)(datepicker, [
      [element, 'click', _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickPicker.bind(null, datepicker), {capture: true}],
      [main, 'click', _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickView.bind(null, datepicker)],
      [controls.viewSwitch, 'click', _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickViewSwitch.bind(null, datepicker)],
      [controls.prevBtn, 'click', _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickPrevBtn.bind(null, datepicker)],
      [controls.nextBtn, 'click', _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickNextBtn.bind(null, datepicker)],
      [controls.todayBtn, 'click', _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickTodayBtn.bind(null, datepicker)],
      [controls.clearBtn, 'click', _events_pickerListeners_js__WEBPACK_IMPORTED_MODULE_9__.onClickClearBtn.bind(null, datepicker)],
    ]);

    // set up views
    this.views = [
      new _views_DaysView_js__WEBPACK_IMPORTED_MODULE_5__["default"](this),
      new _views_MonthsView_js__WEBPACK_IMPORTED_MODULE_6__["default"](this),
      new _views_YearsView_js__WEBPACK_IMPORTED_MODULE_7__["default"](this, {id: 2, name: 'years', cellClass: 'year', step: 1}),
      new _views_YearsView_js__WEBPACK_IMPORTED_MODULE_7__["default"](this, {id: 3, name: 'decades', cellClass: 'decade', step: 10}),
    ];
    this.currentView = this.views[datepicker.config.startView];

    this.currentView.render();
    this.main.appendChild(this.currentView.element);
    datepicker.config.container.appendChild(this.element);
  }

  setOptions(options) {
    processPickerOptions(this, options);
    this.views.forEach((view) => {
      view.init(options, false);
    });
    this.currentView.render();
  }

  detach() {
    this.datepicker.config.container.removeChild(this.element);
  }

  show() {
    if (this.active) {
      return;
    }
    this.element.classList.add('active', 'block');
    this.element.classList.remove('hidden');
    this.active = true;

    const datepicker = this.datepicker;
    if (!datepicker.inline) {
      // ensure picker's direction matches input's
      const inputDirection = getTextDirection(datepicker.inputField);
      if (inputDirection !== getTextDirection(datepicker.config.container)) {
        this.element.dir = inputDirection;
      } else if (this.element.dir) {
        this.element.removeAttribute('dir');
      }

      this.place();
      if (datepicker.config.disableTouchKeyboard) {
        datepicker.inputField.blur();
      }
    }
    (0,_events_functions_js__WEBPACK_IMPORTED_MODULE_8__.triggerDatepickerEvent)(datepicker, 'show');
  }

  hide() {
    if (!this.active) {
      return;
    }
    this.datepicker.exitEditMode();
    this.element.classList.remove('active', 'block');
    this.element.classList.add('active', 'block', 'hidden');
    this.active = false;
    (0,_events_functions_js__WEBPACK_IMPORTED_MODULE_8__.triggerDatepickerEvent)(this.datepicker, 'hide');
  }

  place() {
    const {classList, style} = this.element;
    const {config, inputField} = this.datepicker;
    const container = config.container;
    const {
      width: calendarWidth,
      height: calendarHeight,
    } = this.element.getBoundingClientRect();
    const {
      left: containerLeft,
      top: containerTop,
      width: containerWidth,
    } = container.getBoundingClientRect();
    const {
      left: inputLeft,
      top: inputTop,
      width: inputWidth,
      height: inputHeight
    } = inputField.getBoundingClientRect();
    let {x: orientX, y: orientY} = config.orientation;
    let scrollTop;
    let left;
    let top;

    if (container === document.body) {
      scrollTop = window.scrollY;
      left = inputLeft + window.scrollX;
      top = inputTop + scrollTop;
    } else {
      scrollTop = container.scrollTop;
      left = inputLeft - containerLeft;
      top = inputTop - containerTop + scrollTop;
    }

    if (orientX === 'auto') {
      if (left < 0) {
        // align to the left and move into visible area if input's left edge < window's
        orientX = 'left';
        left = 10;
      } else if (left + calendarWidth > containerWidth) {
        // align to the right if canlendar's right edge > container's
        orientX = 'right';
      } else {
        orientX = getTextDirection(inputField) === 'rtl' ? 'right' : 'left';
      }
    }
    if (orientX === 'right') {
      left -= calendarWidth - inputWidth;
    }

    if (orientY === 'auto') {
      orientY = top - calendarHeight < scrollTop ? 'bottom' : 'top';
    }
    if (orientY === 'top') {
      top -= calendarHeight;
    } else {
      top += inputHeight;
    }

    classList.remove(
      'datepicker-orient-top',
      'datepicker-orient-bottom',
      'datepicker-orient-right',
      'datepicker-orient-left'
    );
    classList.add(`datepicker-orient-${orientY}`, `datepicker-orient-${orientX}`);

    style.top = top ? `${top}px` : top;
    style.left = left ? `${left}px` : left;
  }

  setViewSwitchLabel(labelText) {
    this.controls.viewSwitch.textContent = labelText;
  }

  setPrevBtnDisabled(disabled) {
    this.controls.prevBtn.disabled = disabled;
  }

  setNextBtnDisabled(disabled) {
    this.controls.nextBtn.disabled = disabled;
  }

  changeView(viewId) {
    const oldView = this.currentView;
    const newView =  this.views[viewId];
    if (newView.id !== oldView.id) {
      this.currentView = newView;
      this._renderMethod = 'render';
      (0,_events_functions_js__WEBPACK_IMPORTED_MODULE_8__.triggerDatepickerEvent)(this.datepicker, 'changeView');
      this.main.replaceChild(newView.element, oldView.element);
    }
    return this;
  }

  // Change the focused date (view date)
  changeFocus(newViewDate) {
    this._renderMethod = setViewDate(this, newViewDate) ? 'render' : 'refreshFocus';
    this.views.forEach((view) => {
      view.updateFocus();
    });
    return this;
  }

  // Apply the change of the selected dates
  update() {
    const newViewDate = computeResetViewDate(this.datepicker);
    this._renderMethod = setViewDate(this, newViewDate) ? 'render' : 'refresh';
    this.views.forEach((view) => {
      view.updateFocus();
      view.updateSelection();
    });
    return this;
  }

  // Refresh the picker UI
  render(quickRender = true) {
    const renderMethod = (quickRender && this._renderMethod) || 'render';
    delete this._renderMethod;

    this.currentView[renderMethod]();
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/calendarWeeksTemplate.js":
/*!***************************************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/calendarWeeksTemplate.js ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");


const calendarWeeksTemplate = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.optimizeTemplateHTML)(`<div class="calendar-weeks">
  <div class="days-of-week flex"><span class="dow h-6 leading-6 text-sm font-medium text-gray-500 dark:text-gray-400"></span></div>
  <div class="weeks">${(0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.createTagRepeat)('span', 6, {class: 'week block flex-1 leading-9 border-0 rounded-lg cursor-default text-center text-gray-900 font-semibold text-sm'})}</div>
</div>`);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (calendarWeeksTemplate);


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/daysTemplate.js":
/*!******************************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/daysTemplate.js ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");


const daysTemplate = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.optimizeTemplateHTML)(`<div class="days">
  <div class="days-of-week grid grid-cols-7 mb-1">${(0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.createTagRepeat)('span', 7, {class: 'dow block flex-1 leading-9 border-0 rounded-lg cursor-default text-center text-gray-900 font-semibold text-sm'})}</div>
  <div class="datepicker-grid w-64 grid grid-cols-7">${(0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.createTagRepeat)('span', 42 , {class: 'block flex-1 leading-9 border-0 rounded-lg cursor-default text-center text-gray-900 font-semibold text-sm h-6 leading-6 text-sm font-medium text-gray-500 dark:text-gray-400'})}</div>
</div>`);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (daysTemplate);


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/pickerTemplate.js":
/*!********************************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/pickerTemplate.js ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");


const pickerTemplate = (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.optimizeTemplateHTML)(`<div class="datepicker hidden">
  <div class="datepicker-picker inline-block rounded-lg bg-white dark:bg-gray-700 shadow-lg p-4">
    <div class="datepicker-header">
      <div class="datepicker-title bg-white dark:bg-gray-700 dark:text-white px-2 py-3 text-center font-semibold"></div>
      <div class="datepicker-controls flex justify-between mb-2">
        <button type="button" class="bg-white dark:bg-gray-700 rounded-lg text-gray-500 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white text-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-gray-200 prev-btn"></button>
        <button type="button" class="text-sm rounded-lg text-gray-900 dark:text-white bg-white dark:bg-gray-700 font-semibold py-2.5 px-5 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200 view-switch"></button>
        <button type="button" class="bg-white dark:bg-gray-700 rounded-lg text-gray-500 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white text-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-gray-200 next-btn"></button>
      </div>
    </div>
    <div class="datepicker-main p-1"></div>
    <div class="datepicker-footer">
      <div class="datepicker-controls flex space-x-2 mt-2">
        <button type="button" class="%buttonClass% today-btn text-white bg-blue-700 dark:bg-blue-600 hover:bg-blue-800 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center w-1/2"></button>
        <button type="button" class="%buttonClass% clear-btn text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center w-1/2"></button>
      </div>
    </div>
  </div>
</div>`);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (pickerTemplate);


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/DaysView.js":
/*!**********************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/views/DaysView.js ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ DaysView)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../lib/date-format.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date-format.js");
/* harmony import */ var _lib_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../lib/dom.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js");
/* harmony import */ var _templates_daysTemplate_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../templates/daysTemplate.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/daysTemplate.js");
/* harmony import */ var _templates_calendarWeeksTemplate_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../templates/calendarWeeksTemplate.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/templates/calendarWeeksTemplate.js");
/* harmony import */ var _View_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./View.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/View.js");








class DaysView extends _View_js__WEBPACK_IMPORTED_MODULE_6__["default"] {
  constructor(picker) {
    super(picker, {
      id: 0,
      name: 'days',
      cellClass: 'day',
    });
  }

  init(options, onConstruction = true) {
    if (onConstruction) {
      const inner = (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.parseHTML)(_templates_daysTemplate_js__WEBPACK_IMPORTED_MODULE_4__["default"]).firstChild;
      this.dow = inner.firstChild;
      this.grid = inner.lastChild;
      this.element.appendChild(inner);
    }
    super.init(options);
  }

  setOptions(options) {
    let updateDOW;

    if ((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'minDate')) {
      this.minDate = options.minDate;
    }
    if ((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'maxDate')) {
      this.maxDate = options.maxDate;
    }
    if (options.datesDisabled) {
      this.datesDisabled = options.datesDisabled;
    }
    if (options.daysOfWeekDisabled) {
      this.daysOfWeekDisabled = options.daysOfWeekDisabled;
      updateDOW = true;
    }
    if (options.daysOfWeekHighlighted) {
      this.daysOfWeekHighlighted = options.daysOfWeekHighlighted;
    }
    if (options.todayHighlight !== undefined) {
      this.todayHighlight = options.todayHighlight;
    }
    if (options.weekStart !== undefined) {
      this.weekStart = options.weekStart;
      this.weekEnd = options.weekEnd;
      updateDOW = true;
    }
    if (options.locale) {
      const locale = this.locale = options.locale;
      this.dayNames = locale.daysMin;
      this.switchLabelFormat = locale.titleFormat;
      updateDOW = true;
    }
    if (options.beforeShowDay !== undefined) {
      this.beforeShow = typeof options.beforeShowDay === 'function'
        ? options.beforeShowDay
        : undefined;
    }

    if (options.calendarWeeks !== undefined) {
      if (options.calendarWeeks && !this.calendarWeeks) {
        const weeksElem = (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.parseHTML)(_templates_calendarWeeksTemplate_js__WEBPACK_IMPORTED_MODULE_5__["default"]).firstChild;
        this.calendarWeeks = {
          element: weeksElem,
          dow: weeksElem.firstChild,
          weeks: weeksElem.lastChild,
        };
        this.element.insertBefore(weeksElem, this.element.firstChild);
      } else if (this.calendarWeeks && !options.calendarWeeks) {
        this.element.removeChild(this.calendarWeeks.element);
        this.calendarWeeks = null;
      }
    }
    if (options.showDaysOfWeek !== undefined) {
      if (options.showDaysOfWeek) {
        (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.showElement)(this.dow);
        if (this.calendarWeeks) {
          (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.showElement)(this.calendarWeeks.dow);
        }
      } else {
        (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.hideElement)(this.dow);
        if (this.calendarWeeks) {
          (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_3__.hideElement)(this.calendarWeeks.dow);
        }
      }
    }

    // update days-of-week when locale, daysOfweekDisabled or weekStart is changed
    if (updateDOW) {
      Array.from(this.dow.children).forEach((el, index) => {
        const dow = (this.weekStart + index) % 7;
        el.textContent = this.dayNames[dow];
        el.className = this.daysOfWeekDisabled.includes(dow) ? 'dow disabled text-center h-6 leading-6 text-sm font-medium text-gray-500 dark:text-gray-400 cursor-not-allowed' : 'dow text-center h-6 leading-6 text-sm font-medium text-gray-500 dark:text-gray-400';
      });
    }
  }

  // Apply update on the focused date to view's settings
  updateFocus() {
    const viewDate = new Date(this.picker.viewDate);
    const viewYear = viewDate.getFullYear();
    const viewMonth = viewDate.getMonth();
    const firstOfMonth = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(viewYear, viewMonth, 1);
    const start = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dayOfTheWeekOf)(firstOfMonth, this.weekStart, this.weekStart);

    this.first = firstOfMonth;
    this.last = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(viewYear, viewMonth + 1, 0);
    this.start = start;
    this.focused = this.picker.viewDate;
  }

  // Apply update on the selected dates to view's settings
  updateSelection() {
    const {dates, rangepicker} = this.picker.datepicker;
    this.selected = dates;
    if (rangepicker) {
      this.range = rangepicker.dates;
    }
  }

   // Update the entire view UI
  render() {
    // update today marker on ever render
    this.today = this.todayHighlight ? (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.today)() : undefined;
    // refresh disabled dates on every render in order to clear the ones added
    // by beforeShow hook at previous render
    this.disabled = [...this.datesDisabled];

    const switchLabel = (0,_lib_date_format_js__WEBPACK_IMPORTED_MODULE_2__.formatDate)(this.focused, this.switchLabelFormat, this.locale);
    this.picker.setViewSwitchLabel(switchLabel);
    this.picker.setPrevBtnDisabled(this.first <= this.minDate);
    this.picker.setNextBtnDisabled(this.last >= this.maxDate);

    if (this.calendarWeeks) {
      // start of the UTC week (Monday) of the 1st of the month
      const startOfWeek = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dayOfTheWeekOf)(this.first, 1, 1);
      Array.from(this.calendarWeeks.weeks.children).forEach((el, index) => {
        el.textContent = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.getWeek)((0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addWeeks)(startOfWeek, index));
      });
    }
    Array.from(this.grid.children).forEach((el, index) => {
      const classList = el.classList;
      const current = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.addDays)(this.start, index);
      const date = new Date(current);
      const day = date.getDay();

      el.className = `datepicker-cell hover:bg-gray-100 dark:hover:bg-gray-600 block flex-1 leading-9 border-0 rounded-lg cursor-pointer text-center text-gray-900 dark:text-white font-semibold text-sm ${this.cellClass}`;
      el.dataset.date = current;
      el.textContent = date.getDate();

      if (current < this.first) {
        classList.add('prev', 'text-gray-500', 'dark:text-white');
      } else if (current > this.last) {
        classList.add('next', 'text-gray-500', 'dark:text-white');
      }
      if (this.today === current) {
        classList.add('today', 'bg-gray-100', 'dark:bg-gray-600', 'dark:bg-gray-600');
      }
      if (current < this.minDate || current > this.maxDate || this.disabled.includes(current)) {
        classList.add('disabled', 'cursor-not-allowed');
      }
      if (this.daysOfWeekDisabled.includes(day)) {
        classList.add('disabled', 'cursor-not-allowed');
        (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.pushUnique)(this.disabled, current);
      }
      if (this.daysOfWeekHighlighted.includes(day)) {
        classList.add('highlighted');
      }
      if (this.range) {
        const [rangeStart, rangeEnd] = this.range;
        if (current > rangeStart && current < rangeEnd) {
          classList.add('range', 'bg-gray-200', 'dark:bg-gray-600');
          classList.remove('rounded-lg', 'rounded-l-lg', 'rounded-r-lg')
        }
        if (current === rangeStart) {
          classList.add('range-start', 'bg-gray-100', 'dark:bg-gray-600', 'rounded-l-lg');
          classList.remove('rounded-lg', 'rounded-r-lg');
        }
        if (current === rangeEnd) {
          classList.add('range-end', 'bg-gray-100', 'dark:bg-gray-600', 'rounded-r-lg');
          classList.remove('rounded-lg', 'rounded-l-lg');
        }
      }
      if (this.selected.includes(current)) {
        classList.add('selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white');
        classList.remove('text-gray-900', 'text-gray-500', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-600');
      }
      if (current === this.focused) {
        classList.add('focused');
      }

      if (this.beforeShow) {
        this.performBeforeHook(el, current, current);
      }
    });
  }

  // Update the view UI by applying the changes of selected and focused items
  refresh() {
    const [rangeStart, rangeEnd] = this.range || [];
    this.grid
      .querySelectorAll('.range, .range-start, .range-end, .selected, .focused')
      .forEach((el) => {
        el.classList.remove('range', 'range-start', 'range-end', 'selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white', 'focused', 'bg-gray-100', 'dark:bg-gray-600');
        el.classList.add('text-gray-900', 'rounded-lg', 'dark:text-white');
      });
    Array.from(this.grid.children).forEach((el) => {
      const current = Number(el.dataset.date);
      const classList = el.classList;
      if (current > rangeStart && current < rangeEnd) {
        classList.add('range', 'bg-gray-200', 'dark:bg-gray-600');
        classList.remove('rounded-lg');
      }
      if (current === rangeStart) {
        classList.add('range-start', 'bg-gray-200', 'dark:bg-gray-600', 'rounded-l-lg');
        classList.remove('rounded-lg', 'rounded-r-lg');
      }
      if (current === rangeEnd) {
        classList.add('range-end', 'bg-gray-200', 'dark:bg-gray-600', 'rounded-r-lg');
        classList.remove('rounded-lg', 'rounded-l-lg');
      }
      if (this.selected.includes(current)) {
        classList.add('selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white');
        classList.remove('text-gray-900', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-600');
      }
      if (current === this.focused) {
        classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
      }
    });
  }

  // Update the view UI by applying the change of focused item
  refreshFocus() {
    const index = Math.round((this.focused - this.start) / 86400000);
    this.grid.querySelectorAll('.focused').forEach((el) => {
      el.classList.remove('focused', 'bg-gray-100', 'dark:bg-gray-600');
    });
    this.grid.children[index].classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/MonthsView.js":
/*!************************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/views/MonthsView.js ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ MonthsView)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _lib_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../lib/dom.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js");
/* harmony import */ var _View_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./View.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/View.js");





function computeMonthRange(range, thisYear) {
  if (!range || !range[0] || !range[1]) {
    return;
  }

  const [[startY, startM], [endY, endM]] = range;
  if (startY > thisYear || endY < thisYear) {
    return;
  }
  return [
    startY === thisYear ? startM : -1,
    endY === thisYear ? endM : 12,
  ];
}

class MonthsView extends _View_js__WEBPACK_IMPORTED_MODULE_3__["default"] {
  constructor(picker) {
    super(picker, {
      id: 1,
      name: 'months',
      cellClass: 'month',
    });
  }

  init(options, onConstruction = true) {
    if (onConstruction) {
      this.grid = this.element;
      this.element.classList.add('months', 'datepicker-grid', 'w-64', 'grid', 'grid-cols-4');
      this.grid.appendChild((0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.parseHTML)((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.createTagRepeat)('span', 12, {'data-month': ix => ix})));
    }
    super.init(options);
  }

  setOptions(options) {
    if (options.locale) {
      this.monthNames = options.locale.monthsShort;
    }
    if ((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'minDate')) {
      if (options.minDate === undefined) {
        this.minYear = this.minMonth = this.minDate = undefined;
      } else {
        const minDateObj = new Date(options.minDate);
        this.minYear = minDateObj.getFullYear();
        this.minMonth = minDateObj.getMonth();
        this.minDate = minDateObj.setDate(1);
      }
    }
    if ((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'maxDate')) {
      if (options.maxDate === undefined) {
        this.maxYear = this.maxMonth = this.maxDate = undefined;
      } else {
        const maxDateObj = new Date(options.maxDate);
        this.maxYear = maxDateObj.getFullYear();
        this.maxMonth = maxDateObj.getMonth();
        this.maxDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(this.maxYear, this.maxMonth + 1, 0);
      }
    }
    if (options.beforeShowMonth !== undefined) {
      this.beforeShow = typeof options.beforeShowMonth === 'function'
        ? options.beforeShowMonth
        : undefined;
    }
  }

  // Update view's settings to reflect the viewDate set on the picker
  updateFocus() {
    const viewDate = new Date(this.picker.viewDate);
    this.year = viewDate.getFullYear();
    this.focused = viewDate.getMonth();
  }

  // Update view's settings to reflect the selected dates
  updateSelection() {
    const {dates, rangepicker} = this.picker.datepicker;
    this.selected = dates.reduce((selected, timeValue) => {
      const date = new Date(timeValue);
      const year = date.getFullYear();
      const month = date.getMonth();
      if (selected[year] === undefined) {
        selected[year] = [month];
      } else {
        (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.pushUnique)(selected[year], month);
      }
      return selected;
    }, {});
    if (rangepicker && rangepicker.dates) {
      this.range = rangepicker.dates.map(timeValue => {
        const date = new Date(timeValue);
        return isNaN(date) ? undefined : [date.getFullYear(), date.getMonth()];
      });
    }
  }

  // Update the entire view UI
  render() {
    // refresh disabled months on every render in order to clear the ones added
    // by beforeShow hook at previous render
    this.disabled = [];

    this.picker.setViewSwitchLabel(this.year);
    this.picker.setPrevBtnDisabled(this.year <= this.minYear);
    this.picker.setNextBtnDisabled(this.year >= this.maxYear);

    const selected = this.selected[this.year] || [];
    const yrOutOfRange = this.year < this.minYear || this.year > this.maxYear;
    const isMinYear = this.year === this.minYear;
    const isMaxYear = this.year === this.maxYear;
    const range = computeMonthRange(this.range, this.year);

    Array.from(this.grid.children).forEach((el, index) => {
      const classList = el.classList;
      const date = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(this.year, index, 1);

      el.className = `datepicker-cell hover:bg-gray-100 dark:hover:bg-gray-600 block flex-1 leading-9 border-0 rounded-lg cursor-pointer text-center text-gray-900 dark:text-white font-semibold text-sm ${this.cellClass}`;
      if (this.isMinView) {
        el.dataset.date = date;
      }
      // reset text on every render to clear the custom content set
      // by beforeShow hook at previous render
      el.textContent = this.monthNames[index];

      if (
        yrOutOfRange
        || isMinYear && index < this.minMonth
        || isMaxYear && index > this.maxMonth
      ) {
        classList.add('disabled');
      }
      if (range) {
        const [rangeStart, rangeEnd] = range;
        if (index > rangeStart && index < rangeEnd) {
          classList.add('range');
        }
        if (index === rangeStart) {
          classList.add('range-start');
        }
        if (index === rangeEnd) {
          classList.add('range-end');
        }
      }
      if (selected.includes(index)) {
        classList.add('selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white');
        classList.remove('text-gray-900', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-600');
      }
      if (index === this.focused) {
        classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
      }

      if (this.beforeShow) {
        this.performBeforeHook(el, index, date);
      }
    });
  }

  // Update the view UI by applying the changes of selected and focused items
  refresh() {
    const selected = this.selected[this.year] || [];
    const [rangeStart, rangeEnd] = computeMonthRange(this.range, this.year) || [];
    this.grid
      .querySelectorAll('.range, .range-start, .range-end, .selected, .focused')
      .forEach((el) => {
        el.classList.remove('range', 'range-start', 'range-end', 'selected', 'bg-blue-700', 'dark:bg-blue-600', 'dark:text-white', 'text-white', 'focused', 'bg-gray-100', 'dark:bg-gray-600');
        el.classList.add('text-gray-900', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-600');
      });
    Array.from(this.grid.children).forEach((el, index) => {
      const classList = el.classList;
      if (index > rangeStart && index < rangeEnd) {
        classList.add('range');
      }
      if (index === rangeStart) {
        classList.add('range-start');
      }
      if (index === rangeEnd) {
        classList.add('range-end');
      }
      if (selected.includes(index)) {
        classList.add('selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white');
        classList.remove('text-gray-900', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-600');
      }
      if (index === this.focused) {
        classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
      }
    });
  }

  // Update the view UI by applying the change of focused item
  refreshFocus() {
    this.grid.querySelectorAll('.focused').forEach((el) => {
      el.classList.remove('focused', 'bg-gray-100'), 'dark:bg-gray-600';
    });
    this.grid.children[this.focused].classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
  }
}

/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/View.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/views/View.js ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ View)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_dom_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../lib/dom.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js");



// Base class of the view classes
class View {
  constructor(picker, config) {
    Object.assign(this, config, {
      picker,
      element: (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_1__.parseHTML)(`<div class="datepicker-view flex"></div>`).firstChild,
      selected: [],
    });
    this.init(this.picker.datepicker.config);
  }

  init(options) {
    if (options.pickLevel !== undefined) {
      this.isMinView = this.id === options.pickLevel;
    }
    this.setOptions(options);
    this.updateFocus();
    this.updateSelection();
  }

  // Execute beforeShow() callback and apply the result to the element
  // args:
  // - current - current value on the iteration on view rendering
  // - timeValue - time value of the date to pass to beforeShow()
  performBeforeHook(el, current, timeValue) {
    let result = this.beforeShow(new Date(timeValue));
    switch (typeof result) {
      case 'boolean':
        result = {enabled: result};
        break;
      case 'string':
        result = {classes: result};
    }

    if (result) {
      if (result.enabled === false) {
        el.classList.add('disabled');
        (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.pushUnique)(this.disabled, current);
      }
      if (result.classes) {
        const extraClasses = result.classes.split(/\s+/);
        el.classList.add(...extraClasses);
        if (extraClasses.includes('disabled')) {
          (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.pushUnique)(this.disabled, current);
        }
      }
      if (result.content) {
        (0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_1__.replaceChildNodes)(el, result.content);
      }
    }
  }
}


/***/ }),

/***/ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/YearsView.js":
/*!***********************************************************************************!*\
  !*** ./node_modules/@themesberg/tailwind-datepicker/js/picker/views/YearsView.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ YearsView)
/* harmony export */ });
/* harmony import */ var _lib_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../lib/utils.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/utils.js");
/* harmony import */ var _lib_date_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../lib/date.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/date.js");
/* harmony import */ var _lib_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../lib/dom.js */ "./node_modules/@themesberg/tailwind-datepicker/js/lib/dom.js");
/* harmony import */ var _View_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./View.js */ "./node_modules/@themesberg/tailwind-datepicker/js/picker/views/View.js");





function toTitleCase(word) {
  return [...word].reduce((str, ch, ix) => str += ix ? ch : ch.toUpperCase(), '');
}

// Class representing the years and decades view elements
class YearsView extends _View_js__WEBPACK_IMPORTED_MODULE_3__["default"] {
  constructor(picker, config) {
    super(picker, config);
  }

  init(options, onConstruction = true) {
    if (onConstruction) {
      this.navStep = this.step * 10;
      this.beforeShowOption = `beforeShow${toTitleCase(this.cellClass)}`;
      this.grid = this.element;
      this.element.classList.add(this.name, 'datepicker-grid', 'w-64', 'grid', 'grid-cols-4');
      this.grid.appendChild((0,_lib_dom_js__WEBPACK_IMPORTED_MODULE_2__.parseHTML)((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.createTagRepeat)('span', 12)));
    }
    super.init(options);
  }

  setOptions(options) {
    if ((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'minDate')) {
      if (options.minDate === undefined) {
        this.minYear = this.minDate = undefined;
      } else {
        this.minYear = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.startOfYearPeriod)(options.minDate, this.step);
        this.minDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(this.minYear, 0, 1);
      }
    }
    if ((0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.hasProperty)(options, 'maxDate')) {
      if (options.maxDate === undefined) {
        this.maxYear = this.maxDate = undefined;
      } else {
        this.maxYear = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.startOfYearPeriod)(options.maxDate, this.step);
        this.maxDate = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(this.maxYear, 11, 31);
      }
    }
    if (options[this.beforeShowOption] !== undefined) {
      const beforeShow = options[this.beforeShowOption];
      this.beforeShow = typeof beforeShow === 'function' ? beforeShow : undefined;
    }
  }

  // Update view's settings to reflect the viewDate set on the picker
  updateFocus() {
    const viewDate = new Date(this.picker.viewDate);
    const first = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.startOfYearPeriod)(viewDate, this.navStep);
    const last = first + 9 * this.step;

    this.first = first;
    this.last = last;
    this.start = first - this.step;
    this.focused = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.startOfYearPeriod)(viewDate, this.step);
  }

  // Update view's settings to reflect the selected dates
  updateSelection() {
    const {dates, rangepicker} = this.picker.datepicker;
    this.selected = dates.reduce((years, timeValue) => {
      return (0,_lib_utils_js__WEBPACK_IMPORTED_MODULE_0__.pushUnique)(years, (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.startOfYearPeriod)(timeValue, this.step));
    }, []);
    if (rangepicker && rangepicker.dates) {
      this.range = rangepicker.dates.map(timeValue => {
        if (timeValue !== undefined) {
          return (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.startOfYearPeriod)(timeValue, this.step);
        }
      });
    }
  }

  // Update the entire view UI
  render() {
    // refresh disabled years on every render in order to clear the ones added
    // by beforeShow hook at previous render
    this.disabled = [];

    this.picker.setViewSwitchLabel(`${this.first}-${this.last}`);
    this.picker.setPrevBtnDisabled(this.first <= this.minYear);
    this.picker.setNextBtnDisabled(this.last >= this.maxYear);

    Array.from(this.grid.children).forEach((el, index) => {
      const classList = el.classList;
      const current = this.start + (index * this.step);
      const date = (0,_lib_date_js__WEBPACK_IMPORTED_MODULE_1__.dateValue)(current, 0, 1);

      el.className = `datepicker-cell hover:bg-gray-100 dark:hover:bg-gray-600 block flex-1 leading-9 border-0 rounded-lg cursor-pointer text-center text-gray-900 dark:text-white font-semibold text-sm ${this.cellClass}`;
      if (this.isMinView) {
        el.dataset.date = date;
      }
      el.textContent = el.dataset.year = current;

      if (index === 0) {
        classList.add('prev');
      } else if (index === 11) {
        classList.add('next');
      }
      if (current < this.minYear || current > this.maxYear) {
        classList.add('disabled');
      }
      if (this.range) {
        const [rangeStart, rangeEnd] = this.range;
        if (current > rangeStart && current < rangeEnd) {
          classList.add('range');
        }
        if (current === rangeStart) {
          classList.add('range-start');
        }
        if (current === rangeEnd) {
          classList.add('range-end');
        }
      }
      if (this.selected.includes(current)) {
        classList.add('selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white');
        classList.remove('text-gray-900', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-600');
      }
      if (current === this.focused) {
        classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
      }

      if (this.beforeShow) {
        this.performBeforeHook(el, current, date);
      }
    });
  }

  // Update the view UI by applying the changes of selected and focused items
  refresh() {
    const [rangeStart, rangeEnd] = this.range || [];
    this.grid
      .querySelectorAll('.range, .range-start, .range-end, .selected, .focused')
      .forEach((el) => {
        el.classList.remove('range', 'range-start', 'range-end', 'selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white', 'focused', 'bg-gray-100', 'dark:bg-gray-600');
      });
    Array.from(this.grid.children).forEach((el) => {
      const current = Number(el.textContent);
      const classList = el.classList;
      if (current > rangeStart && current < rangeEnd) {
        classList.add('range');
      }
      if (current === rangeStart) {
        classList.add('range-start');
      }
      if (current === rangeEnd) {
        classList.add('range-end');
      }
      if (this.selected.includes(current)) {
        classList.add('selected', 'bg-blue-700', 'text-white', 'dark:bg-blue-600', 'dark:text-white');
        classList.remove('text-gray-900', 'hover:bg-gray-100', 'dark:text-white', 'dark:hover:bg-gray-600');
      }
      if (current === this.focused) {
        classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
      }
    });
  }

  // Update the view UI by applying the change of focused item
  refreshFocus() {
    const index = Math.round((this.focused - this.start) / this.step);
    this.grid.querySelectorAll('.focused').forEach((el) => {
      el.classList.remove('focused', 'bg-gray-100', 'dark:bg-gray-600');
    });
    this.grid.children[index].classList.add('focused', 'bg-gray-100', 'dark:bg-gray-600');
  }
}


/***/ }),

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

var Collection = function Collection() {
  var elements = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
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

/***/ "./resources/js/collections/poinSaleCollection.js":
/*!********************************************************!*\
  !*** ./resources/js/collections/poinSaleCollection.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _objectCollection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./objectCollection */ "./resources/js/collections/objectCollection.js");


var PointSaleCollection = function PointSaleCollection() {
  var elements = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
  _objectCollection__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, elements);

  this.removeElementByBankID = function (bank) {
    var index = this.elements.findIndex(function (val) {
      return val.bank.id === bank.id;
    });

    if (index === -1) {
      return false;
    }

    this.elements.splice(index, 1);
    return true;
  };

  this.getIndexByBankID = function (bank) {
    return this.elements.findIndex(function (val) {
      return val.bank.id === bank.id;
    });
  };
};

PointSaleCollection.prototype = Object.create(_objectCollection__WEBPACK_IMPORTED_MODULE_0__["default"].prototype);
PointSaleCollection.prototype.constructor = PointSaleCollection;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PointSaleCollection);

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
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(id, ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(total, "</td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(_this.getInputTemplate(id), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                0.00 ").concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_1__.CURRENCIES.BOLIVAR], "\n            </td>\n            <td class=\"py-4 pl-3 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-del-row=\"").concat(id, "\" data-modal=\"remove\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
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
    return "\n            <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(id, ">\n                <td data-table=\"num-col\" class=\"py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(total, "</td>\n                <td class=\"py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                    ").concat(this.getInputTemplate(id), "\n                </td>\n                <td class=\"py-4 pl-3 pr-6 text-sm text-center font-medium whitespace-nowrap\">\n                    <button data-modal=\"remove\" data-del-row=\"").concat(id, "\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                        <i class=\"fas fa-times  text-white\"></i>                        \n                    </button>\n                </td>\n            </tr>\n        ");
  },
  isContainerDefined: function isContainerDefined() {
    return this.container !== null;
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
/* harmony import */ var _constants_point_sale_type__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/point-sale-type */ "./resources/js/constants/point-sale-type.js");
/* harmony import */ var _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _utilities/decimalInput */ "./resources/js/utilities/decimalInput.js");
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

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_2__.decimalInputs[_this.currency].mask(input_debit);

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_2__.decimalInputs[_this.currency].mask(input_credit);

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
    return "\n        <input type=\"text\" data-point-sale-type=\"".concat(type, "\" value=\"0\" placeholder=\"0.00 ").concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.SIGN[_this.currency], "\" id=\"").concat(_this.name, "_").concat(type, "_").concat(id, "\" name=\"").concat(_this.name, "_").concat(type, "[]\" class=\"w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50\">\n    ");
  };

  this.getTableRowTemplate = function (id, availableBanks, currentBank, total) {
    return "\n        <tr class=\"hover:bg-gray-100 dark:hover:bg-gray-700\" data-id=".concat(id, ">\n            <td data-table=\"num-col\" class=\"py-4 pl-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white\">").concat(total, "</td>\n            <td class=\"pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                <select class=\"w-full form-select\" name=\"").concat(_this.name, "_bank[]\">\n                    <option value=\"").concat(currentBank, "\">").concat(currentBank, "</option>\n                    ").concat(availableBanks.map(function (el) {
      return "<option value=\"".concat(el, "\">").concat(el, "</option>");
    }).join(''), "\n                </select>\n            </td>\n            <td class=\"pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white\">\n                ").concat(_this.getInputTemplate(id, _constants_point_sale_type__WEBPACK_IMPORTED_MODULE_1__["default"].DEBIT), "\n            </td>\n            <td data-table=\"convertion-col\" class=\"pl-3 py-4 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white\">\n                ").concat(_this.getInputTemplate(id, _constants_point_sale_type__WEBPACK_IMPORTED_MODULE_1__["default"].CREDIT), "\n            </td>\n            <td class=\"py-4 pl-3 text-sm text-center font-medium whitespace-nowrap\">\n                <button data-modal=\"delete\" type=\"button\" class=\"bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500\">\n                    <i class=\"fas fa-times  text-white\"></i>                        \n                </button>\n            </td>\n        </tr>\n    ");
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

/***/ "./resources/js/constants/point-sale-type.js":
/*!***************************************************!*\
  !*** ./resources/js/constants/point-sale-type.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var POINT_SALE_TYPE = Object.freeze({
  DEBIT: 'debit',
  CREDIT: 'credit'
});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (POINT_SALE_TYPE);

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

/***/ "./resources/js/models/PointSaleRecord.js":
/*!************************************************!*\
  !*** ./resources/js/models/PointSaleRecord.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Bank__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Bank */ "./resources/js/models/Bank.js");


var PointSaleRecord = function PointSaleRecord(currency) {
  var total = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  var bank = arguments.length > 2 ? arguments[2] : undefined;
  var id = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
  this.id = id;
  this.total = total;
  this.currency = currency;
  this.bank = bank instanceof _Bank__WEBPACK_IMPORTED_MODULE_0__["default"] ? bank : null;
};

PointSaleRecord.prototype.constructor = PointSaleRecord;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PointSaleRecord);

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

/***/ "./resources/js/pages/cash-register/edit.js":
/*!**************************************************!*\
  !*** ./resources/js/pages/cash-register/edit.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _constants_currencies__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! _constants/currencies */ "./resources/js/constants/currencies.js");
/* harmony import */ var _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _constants/paymentMethods */ "./resources/js/constants/paymentMethods.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _store */ "./resources/js/store/index.js");
/* harmony import */ var _store_action__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _store/action */ "./resources/js/store/action.js");
/* harmony import */ var _views_MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! _views/MoneyRecordModalView */ "./resources/js/views/MoneyRecordModalView.js");
/* harmony import */ var _presenters_MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! _presenters/MoneyRecordModalPresenter */ "./resources/js/presenters/MoneyRecordModalPresenter.js");
/* harmony import */ var _components_money_record_table_MoneyRecordTable__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! _components/money-record-table/MoneyRecordTable */ "./resources/js/components/money-record-table/MoneyRecordTable.js");
/* harmony import */ var _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! _views/ForeignMoneyRecordModalView */ "./resources/js/views/ForeignMoneyRecordModalView.js");
/* harmony import */ var _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! _presenters/ForeignMoneyRecordModalPresenter */ "./resources/js/presenters/ForeignMoneyRecordModalPresenter.js");
/* harmony import */ var _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! _components/money-record-table/ForeignMoneyRecordTable */ "./resources/js/components/money-record-table/ForeignMoneyRecordTable.js");
/* harmony import */ var _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! _presenters/DenominationModalPresenter */ "./resources/js/presenters/DenominationModalPresenter.js");
/* harmony import */ var _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! _views/DenominationModalView */ "./resources/js/views/DenominationModalView.js");
/* harmony import */ var _presenters_SalePointModalPresenter__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! _presenters/SalePointModalPresenter */ "./resources/js/presenters/SalePointModalPresenter.js");
/* harmony import */ var _views_SalePointModalView__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! _views/SalePointModalView */ "./resources/js/views/SalePointModalView.js");
/* harmony import */ var _presenters_CashRegisterDataPresenter__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! _presenters/CashRegisterDataPresenter */ "./resources/js/presenters/CashRegisterDataPresenter.js");
/* harmony import */ var _views_CashRegisterDataView__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! _views/CashRegisterDataView */ "./resources/js/views/CashRegisterDataView.js");
/* harmony import */ var _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! _utilities/decimalInput */ "./resources/js/utilities/decimalInput.js");
/* harmony import */ var _utilities_numericInput__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! _utilities/numericInput */ "./resources/js/utilities/numericInput.js");
/* harmony import */ var _models_moneyRecord__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! _models/moneyRecord */ "./resources/js/models/moneyRecord.js");
/* harmony import */ var _models_DenominationRecord__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! _models/DenominationRecord */ "./resources/js/models/DenominationRecord.js");
/* harmony import */ var _models_PointSaleRecord__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! _models/PointSaleRecord */ "./resources/js/models/PointSaleRecord.js");
/* harmony import */ var _models_Bank__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! _models/Bank */ "./resources/js/models/Bank.js");






















/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  totalInputDOMS: {
    liquidMoneyBs: document.querySelector('#total_bs_cash'),
    liquidMoneyDollar: document.querySelector('#total_dollar_cash'),
    denominationsBs: document.querySelector('#total_bs_denominations'),
    denominationsDollar: document.querySelector('#total_dollar_denominations'),
    zelleDollar: document.querySelector('#total_zelle'),
    pointSaleDollar: document.querySelector('#total_point_sale_dollar'),
    pointSaleBs: document.querySelector('#total_point_sale_bs')
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
  setTotalPointSaleBs: function setTotalPointSaleBs(total) {
    this.proxy.pointSaleBs = total;
  },
  setPropWrapper: function setPropWrapper(fn) {
    return fn.bind(this);
  },
  init: function init() {
    var _this = this;

    var cashRegisterContainer = document.querySelector('#cash_register_data');
    var cashRegisterUser = cashRegisterContainer.querySelector('#cash_register_id').value;
    var casgRegisterDate = cashRegisterContainer.querySelector('#date').value;
    var cashRegisterDataPresenter = new _presenters_CashRegisterDataPresenter__WEBPACK_IMPORTED_MODULE_14__["default"](casgRegisterDate, cashRegisterUser);
    var cashRegisterDataView = new _views_CashRegisterDataView__WEBPACK_IMPORTED_MODULE_15__["default"](cashRegisterDataPresenter);
    cashRegisterDataView.init(cashRegisterContainer); // Cash records bs

    var liquidMoneyBsRegisterModal = document.querySelector('#bs_cash_record');
    var cashBsRecordsElements = liquidMoneyBsRegisterModal.querySelector('tbody').children;
    var cashBsRecords = Array.prototype.map.call(cashBsRecordsElements, function (el, key) {
      var input = el.querySelector('input[id^="bs_cash_record_"]');
      _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(input);
      var amount = parseFloat(input.value);
      return new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_18__["default"](amount, _constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, key);
    });
    var bolivarRecordMoneyPresenter = new _presenters_MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_5__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyBs), cashBsRecords);
    var bolivarRecordMoneyView = new _views_MoneyRecordModalView__WEBPACK_IMPORTED_MODULE_4__["default"](bolivarRecordMoneyPresenter);
    var moneyRecordTable = new _components_money_record_table_MoneyRecordTable__WEBPACK_IMPORTED_MODULE_6__["default"]();
    bolivarRecordMoneyView.init(liquidMoneyBsRegisterModal, 'bs_cash_record', moneyRecordTable); // Cash records dollar

    var cashDollarRecordModal = document.querySelector('#dollar_cash_record');
    var cashDollarRecordsElements = cashDollarRecordModal.querySelector('tbody').children;
    var cashDollarRecords = Array.prototype.map.call(cashDollarRecordsElements, function (el, key) {
      var input = el.querySelector('input[id^="dollar_cash_record_"]');
      _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(input);
      var amount = parseFloat(input.value);
      return new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_18__["default"](amount, _constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, key);
    });
    var dollarRecordMoneyPresenter = new _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_8__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyDollar), cashDollarRecords);
    var dollarRecordMoneyView = new _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_7__["default"](dollarRecordMoneyPresenter);
    var dollarRecordTable = new _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_9__["default"]();
    dollarRecordMoneyView.init(cashDollarRecordModal, 'dollar_cash_record', dollarRecordTable); // Bs denomination records

    var bsDenominationsModal = document.querySelector('#bs_denominations_record');
    var bsDenominationRecordsElements = bsDenominationsModal.querySelector('tbody').children;
    var bsDenominationRecords = Array.prototype.map.call(bsDenominationRecordsElements, function (el, key) {
      var input = el.querySelector('input');
      _utilities_numericInput__WEBPACK_IMPORTED_MODULE_17__["default"].mask(input);
      var amount = input.value !== '' ? parseInt(input.value) : 0;
      var denomination = parseFloat(input.getAttribute('data-denomination'));
      var total = Math.round((denomination * amount + Number.EPSILON) * 100) / 100;
      return new _models_DenominationRecord__WEBPACK_IMPORTED_MODULE_19__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, denomination, total, amount, key);
    });
    var bolivarDenominationModalPresenter = new _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_10__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationBs), bsDenominationRecords);
    var bolivarDenominationModalView = new _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_11__["default"](bolivarDenominationModalPresenter);
    bolivarDenominationModalView.init(bsDenominationsModal, 'bs_denominations_record'); // Dollar denomination records

    var dollarDenominationsModal = document.querySelector('#dollar_denominations_record');
    var dollarDenominationRecordsElements = dollarDenominationsModal.querySelector('tbody').children;
    var dollarDenominationRecords = Array.prototype.map.call(dollarDenominationRecordsElements, function (el, key) {
      var input = el.querySelector('input');
      _utilities_numericInput__WEBPACK_IMPORTED_MODULE_17__["default"].mask(input);
      var amount = input.value !== '' ? parseInt(input.value) : 0;
      var denomination = parseFloat(input.getAttribute('data-denomination'));
      var total = Math.round((denomination * amount + Number.EPSILON) * 100) / 100;
      return new _models_DenominationRecord__WEBPACK_IMPORTED_MODULE_19__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, denomination, total, amount, key);
    });
    var dollarDenominationModalPresenter = new _presenters_DenominationModalPresenter__WEBPACK_IMPORTED_MODULE_10__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationDollar), dollarDenominationRecords);
    var dollarDenominationModalView = new _views_DenominationModalView__WEBPACK_IMPORTED_MODULE_11__["default"](dollarDenominationModalPresenter);
    dollarDenominationModalView.init(dollarDenominationsModal, 'dollar_denominations_record'); // Point of sale bs records

    var salePointModal = document.querySelector('#point_sale_bs');
    var pointSaleBsRecordsElements = salePointModal.querySelector('tbody').children;
    var pointSaleBsRecords = {
      'credit': [],
      'debit': [],
      'bank': [],
      'availableBanks': []
    };

    if (pointSaleBsRecordsElements.length > 0) {
      // Get the availables banks
      var bankSelectEl = salePointModal.querySelector('tbody tr select[name^="point_sale_bs_bank"]');

      if (bankSelectEl.options.length > 1) {
        for (var i = 1; i < bankSelectEl.options.length; i++) {
          pointSaleBsRecords['availableBanks'].push(bankSelectEl.options[i].value);
        }
      }

      pointSaleBsRecords = Array.prototype.reduce.call(pointSaleBsRecordsElements, function (obj, curr, index) {
        var bank = curr.querySelector('select[name^="point_sale_bs_bank"]').value;
        var bankObj = new _models_Bank__WEBPACK_IMPORTED_MODULE_21__["default"](bank, index);
        var creditInput = curr.querySelector('input[id^="point_sale_bs_credit_"]');
        var debitInput = curr.querySelector('input[id^="point_sale_bs_debit_"]');
        _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(creditInput);
        _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(debitInput);
        var credit = parseFloat(creditInput.value);
        var debit = parseFloat(debitInput.value);
        obj['credit'].push(new _models_PointSaleRecord__WEBPACK_IMPORTED_MODULE_20__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, credit, bankObj, index));
        obj['debit'].push(new _models_PointSaleRecord__WEBPACK_IMPORTED_MODULE_20__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, debit, bankObj, index));
        obj['bank'].push(bankObj);
        return obj;
      }, pointSaleBsRecords);
    }

    var salePointModalPresenter = new _presenters_SalePointModalPresenter__WEBPACK_IMPORTED_MODULE_12__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR, this.setPropWrapper(this.setTotalPointSaleBs), pointSaleBsRecords);
    var salePointModalView = new _views_SalePointModalView__WEBPACK_IMPORTED_MODULE_13__["default"](salePointModalPresenter);
    salePointModalView.init(salePointModal, 'point_sale_bs'); // Zelle records

    var zelleRecordModal = document.querySelector('#zelle_record');
    var zelleRecordsElements = zelleRecordModal.querySelector('tbody').children;
    var zelleRecords = Array.prototype.map.call(zelleRecordsElements, function (el, key) {
      var input = el.querySelector('input[id^="zelle_record_"]');
      _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(input);
      var amount = parseFloat(input.value);
      return new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_18__["default"](amount, _constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.ZELLE, key);
    });
    var zelleRecordMoneyPresenter = new _presenters_ForeignMoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_8__["default"](_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR, _constants_paymentMethods__WEBPACK_IMPORTED_MODULE_1__.PAYMENT_METHODS.ZELLE, this.setPropWrapper(this.setTotalZelleDollar), zelleRecords);
    var zelleRecordMoneyView = new _views_ForeignMoneyRecordModalView__WEBPACK_IMPORTED_MODULE_7__["default"](zelleRecordMoneyPresenter);
    var zelleRecordTable = new _components_money_record_table_ForeignMoneyRecordTable__WEBPACK_IMPORTED_MODULE_9__["default"]();
    zelleRecordMoneyView.init(zelleRecordModal, 'zelle_record', zelleRecordTable); // // Cash register modal total input DOMs

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.liquidMoneyBs);
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.liquidMoneyDollar); // // Denomination modal total input DOMs

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.denominationsBs);
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.denominationsDollar); // // Zelle total input DOMs

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.zelleDollar); // Point sale input DOMS

    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.DOLLAR].mask(this.totalInputDOMS.pointSaleDollar);
    _utilities_decimalInput__WEBPACK_IMPORTED_MODULE_16__.decimalInputs[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.pointSaleBs);

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
    _store__WEBPACK_IMPORTED_MODULE_2__.store.subscribe(function () {
      var state = _store__WEBPACK_IMPORTED_MODULE_2__.store.getState();

      if (state.lastAction === _store_action__WEBPACK_IMPORTED_MODULE_3__.STORE_DOLLAR_EXCHANGE_VALUE) {
        document.querySelector('p[data-dollar-exchange="dollar_exchange_date"]').innerText = state.dollarExchange.createdAt;
        document.querySelector('p[data-dollar-exchange="dollar_exchange_value"]').innerText = "".concat(state.dollarExchange.value, " ").concat(_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.SIGN[_constants_currencies__WEBPACK_IMPORTED_MODULE_0__.CURRENCIES.BOLIVAR]);
      }
    });
  }
});

/***/ }),

/***/ "./resources/js/presenters/CashRegisterDataPresenter.js":
/*!**************************************************************!*\
  !*** ./resources/js/presenters/CashRegisterDataPresenter.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _themesberg_tailwind_datepicker_Datepicker__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @themesberg/tailwind-datepicker/Datepicker */ "./node_modules/@themesberg/tailwind-datepicker/js/Datepicker.js");
/* harmony import */ var _services_cash_register__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _services/cash-register */ "./resources/js/services/cash-register/index.js");


var CashRegisterDataPresenterPrototype = {
  changeOnView: function changeOnView(_ref) {
    var target = _ref.target;
    var id = target.getAttribute('id');

    if (id === 'cash_register_worker_exist_check') {
      target.value = target.value === "0" ? "1" : "0";
      this.view.toggleNewWorkerContainer();
    } else if (id === 'cash_register_id') {
      this.selectedCashRegisterUser = target.value;
      this.getTotalsToCashRegisterUserOption(this.selectedDate, this.selectedCashRegisterUser);
    }
  },
  changeDateOnView: function changeDateOnView(_ref2) {
    var date = _ref2.date;
    var newDate = _themesberg_tailwind_datepicker_Datepicker__WEBPACK_IMPORTED_MODULE_0__["default"].formatDate(date, 'yyyy-mm-dd');
    this.selectedDate = newDate;
    this.getUsersWithoutRecord(newDate);
  },
  getTotalsToCashRegisterUserOption: function getTotalsToCashRegisterUserOption(date, cashRegisterUser) {
    var _this = this;

    this.setTotalAmounts(null);
    (0,_services_cash_register__WEBPACK_IMPORTED_MODULE_1__.getTotalsToCashRegisterUser)({
      date: date,
      cashRegisterUser: cashRegisterUser
    }).then(function (res) {
      if ([201, 200].includes(res.status)) {
        var data = res.data.data;
        console.log(data);

        _this.setTotalAmounts(data);
      }
    })["catch"](function (err) {
      console.log(err);
    });
  },
  getUsersWithoutRecord: function getUsersWithoutRecord(date) {
    var _this2 = this;

    this.view.showLoading();
    this.view.hideCashRegisterUsersNoAvailable();
    (0,_services_cash_register__WEBPACK_IMPORTED_MODULE_1__.getCashRegisterUsersWithoutRecords)(date).then(function (res) {
      _this2.view.hideLoading();

      if ([201, 200].includes(res.status)) {
        var data = res.data.data; // If there's a stored date on component, then the user is 
        // editing a cash register

        if (_this2.defaultDate && _this2.defaultCashRegisterUser && _this2.defaultDate === date) {
          data.unshift({
            key: _this2.defaultCashRegisterUser,
            value: _this2.defaultCashRegisterUser
          });
        }

        if (data.length === 0) {
          _this2.view.showCashRegisterUsersNoAvailable();
        }

        _this2.view.setCashRegisterUsersElements(data);
      }
    })["catch"](function (err) {
      console.log(err);
    });
  },
  setView: function setView(view) {
    this.view = view;
  }
};

var CashRegisterDataPresenter = function CashRegisterDataPresenter(setTotalAmounts) {
  var date = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
  var cashRegisterUser = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
  this.view = null;
  var today = new Date();
  this.defaultDate = date ? date.split('-').reverse().join('-') : today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
  this.defaultCashRegisterUser = cashRegisterUser;
  this.selectedDate = this.defaultDate;
  this.selectedCashRegisterUser = this.defaultCashRegisterUser;
  this.setTotalAmounts = setTotalAmounts;
};

CashRegisterDataPresenter.prototype = CashRegisterDataPresenterPrototype;
CashRegisterDataPresenter.prototype.constructor = CashRegisterDataPresenter;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CashRegisterDataPresenter);

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
  var denominationRecord = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : [];
  this.view = null;
  this.currency = currency;
  this.method = method;
  this.setTotalAmount = setTotalAmount;

  if (denominationRecord.length === 0) {
    denominationRecord = _constants_currenciesDenominations__WEBPACK_IMPORTED_MODULE_2__["default"][currency].map(function (el, index) {
      return new _models_DenominationRecord__WEBPACK_IMPORTED_MODULE_1__["default"](currency, el, 0, 0, index);
    });
  }

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

  var moneyRecordCollection = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : [];
  _MoneyRecordModalPresenter__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, currency, method, setTotalAmount, moneyRecordCollection);
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
      var action = button.getAttribute('data-modal');
      var modalToggleID = button.getAttribute('data-modal-toggle');

      if (action) {
        if (action === 'add') {
          if (this.moneyRecordCollection.getAll().findIndex(function (el) {
            return el.amount === 0;
          }) !== -1) {
            // Check If there's a zero value
            return;
          }

          var moneyRecord = new _models_moneyRecord__WEBPACK_IMPORTED_MODULE_1__["default"](0, this.currency, this.method);
          moneyRecord = this.moneyRecordCollection.pushElement(moneyRecord);
          this.view.addRow(_objectSpread(_objectSpread({}, moneyRecord), {}, {
            total: this.moneyRecordCollection.getLength()
          }));
        } else if (action === 'remove') {
          // Remove element
          var rowID = button.getAttribute('data-del-row');
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
      if (this.moneyRecordCollection.getAll().findIndex(function (el) {
        return el.amount === 0;
      }) !== -1) {
        // Check If there's a zero value
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
    var value = !isNaN(inputValue) ? (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_2__.formatAmount)(inputValue) : 0;
    this.moneyRecordCollection.setElementAtIndex(index, {
      amount: value
    });
  }
};

var MoneyRecordModalPresenter = function MoneyRecordModalPresenter(currency, method, setTotalAmount) {
  var moneyRecordCollection = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : [];
  this.view = null;
  this.currency = currency;
  this.method = method;
  this.moneyRecordCollection = new _collections_moneyRecordCollection__WEBPACK_IMPORTED_MODULE_0__["default"](moneyRecordCollection);
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
/* harmony import */ var _collections_poinSaleCollection__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! _collections/poinSaleCollection */ "./resources/js/collections/poinSaleCollection.js");
/* harmony import */ var _collections_bankCollection__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! _collections/bankCollection */ "./resources/js/collections/bankCollection.js");
/* harmony import */ var _services_banks__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! _services/banks */ "./resources/js/services/banks/index.js");
/* harmony import */ var _constants_point_sale_type__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! _constants/point-sale-type */ "./resources/js/constants/point-sale-type.js");
/* harmony import */ var _utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! _utilities/mathUtilities */ "./resources/js/utilities/mathUtilities.js");
/* harmony import */ var _models_PointSaleRecord__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! _models/PointSaleRecord */ "./resources/js/models/PointSaleRecord.js");
/* harmony import */ var _models_Bank__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! _models/Bank */ "./resources/js/models/Bank.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }








var SalePointModalPresenterPrototype = {
  clickOnModal: function clickOnModal(_ref) {
    var target = _ref.target;
    var button = target.closest('button');

    if (button && button.tagName === 'BUTTON') {
      var action = button.getAttribute('data-modal');
      var modalToggleID = button.getAttribute('data-modal-toggle');

      if (action) {
        if (action === 'add') {
          if (this.banks.length === 0) {
            return;
          }

          var idArr = [];
          var bank = new _models_Bank__WEBPACK_IMPORTED_MODULE_7__["default"](this.banks.shift());

          if (this.selectedBanks.getLength() > 0) {
            idArr = this.selectedBanks.getAll().map(function (el) {
              return el.id;
            });
            bank = this.selectedBanks.pushElement(bank);
          } else {
            bank = this.selectedBanks.pushElement(bank);
          } // Add point sale records to collection


          this.pointSaleDebit.pushElement(new _models_PointSaleRecord__WEBPACK_IMPORTED_MODULE_6__["default"](this.currency, 0, bank));
          this.pointSaleCredit.pushElement(new _models_PointSaleRecord__WEBPACK_IMPORTED_MODULE_6__["default"](this.currency, 0, bank));
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

          this.pointSaleDebit.removeElementByBankID(_bank);
          this.pointSaleCredit.removeElementByBankID(_bank);
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
      } else if (modalToggleID) {
        var totalCredit = this.pointSaleCredit.getAll().reduce(function (acc, curr) {
          return acc + curr.total;
        }, 0);
        var totalDebit = this.pointSaleDebit.getAll().reduce(function (acc, curr) {
          return acc + curr.total;
        }, 0);
        this.setTotalAmount(totalCredit + totalDebit);
      }
    }
  },
  changeOnModal: function changeOnModal(_ref2) {
    var target = _ref2.target;

    if (target.tagName !== 'SELECT') {
      return;
    }

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
      }); // indexOld it's the same to pointSaleDebit and pointSaleCredit

      this.pointSaleCredit.setElementAtIndex(indexOld, {
        bank: _objectSpread(_objectSpread({}, bank), {}, {
          name: newSelectedValue
        })
      });
      this.pointSaleDebit.setElementAtIndex(indexOld, {
        bank: _objectSpread(_objectSpread({}, bank), {}, {
          name: newSelectedValue
        })
      });
      console.log(this.pointSaleCredit.getElementByIndex(indexOld));
      this.view.changeSelect({
        prevIDArr: this.selectedBanks.getAll().map(function (el) {
          return el.id;
        }),
        availableBanks: this.banks
      });
    }
  },
  keyPressedOnModal: function keyPressedOnModal(_ref3) {
    var target = _ref3.target,
        key = _ref3.key;

    if (isFinite(key)) {
      var id = target.closest('tr').getAttribute('data-id');
      var type = target.getAttribute('data-point-sale-type');
      this.updatePointSaleRecord(parseInt(id), type, target.value);
    }
  },
  keyDownOnModal: function keyDownOnModal(_ref4) {
    var target = _ref4.target,
        key = _ref4.key;

    if (key === 8 || key === 'Backspace') {
      var id = target.closest('tr').getAttribute('data-id');
      var type = target.getAttribute('data-point-sale-type');
      this.updatePointSaleRecord(parseInt(id), type, target.value);
    }
  },
  updatePointSaleRecord: function updatePointSaleRecord(id, type, inputValue) {
    var index = this.selectedBanks.getIndexByID(id);
    var value = !isNaN(inputValue) ? (0,_utilities_mathUtilities__WEBPACK_IMPORTED_MODULE_5__.formatAmount)(inputValue) : 0;

    if (type === _constants_point_sale_type__WEBPACK_IMPORTED_MODULE_4__["default"].DEBIT) {
      this.pointSaleDebit.setElementAtIndex(index, {
        total: value
      });
      console.log(this.pointSaleDebit.getElementByIndex(index));
    } else if (type === _constants_point_sale_type__WEBPACK_IMPORTED_MODULE_4__["default"].CREDIT) {
      this.pointSaleCredit.setElementAtIndex(index, {
        total: value
      });
      console.log(this.pointSaleCredit.getElementByIndex(index));
    }
  },
  setView: function setView(view) {
    this.view = view;
  },
  fetchInitialData: function () {
    var _fetchInitialData = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
      var banks;
      return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              _context.next = 3;
              return (0,_services_banks__WEBPACK_IMPORTED_MODULE_3__.getAllBanks)();

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

    function fetchInitialData() {
      return _fetchInitialData.apply(this, arguments);
    }

    return fetchInitialData;
  }()
};

var SalePointModalPresenter = function SalePointModalPresenter(currency, setTotalAmount) {
  var _this = this;

  var pointSaleRecords = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  this.view = null;
  this.currency = currency;
  this.banks = [];
  this.setTotalAmount = setTotalAmount;
  this.selectedBanks = new _collections_bankCollection__WEBPACK_IMPORTED_MODULE_2__["default"]();
  this.pointSaleDebit = new _collections_poinSaleCollection__WEBPACK_IMPORTED_MODULE_1__["default"]();
  this.pointSaleCredit = new _collections_poinSaleCollection__WEBPACK_IMPORTED_MODULE_1__["default"]();

  if (Object.keys(pointSaleRecords).length > 0 && "bank" in pointSaleRecords && pointSaleRecords['bank'].length > 0 && "credit" in pointSaleRecords && "debit" in pointSaleRecords && "availableBanks" in pointSaleRecords) {
    this.selectedBanks.setElements(pointSaleRecords['bank']);
    this.pointSaleDebit.setElements(pointSaleRecords['debit']);
    this.pointSaleCredit.setElements(pointSaleRecords['credit']);
    this.banks = pointSaleRecords['availableBanks'];
  } else {
    this.fetchInitialData().then(function (res) {
      _this.banks = res.banks;
    })["catch"](function (err) {
      console.log(err);
    });
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

/***/ "./resources/js/services/cash-register/index.js":
/*!******************************************************!*\
  !*** ./resources/js/services/cash-register/index.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getCashRegisterUsersWithoutRecords": () => (/* binding */ getCashRegisterUsersWithoutRecords),
/* harmony export */   "getTotalsToCashRegisterUser": () => (/* binding */ getTotalsToCashRegisterUser)
/* harmony export */ });
/* harmony import */ var _utilities_axiosClient__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utilities/axiosClient */ "./resources/js/utilities/axiosClient.js");


var getCashRegisterUsersWithoutRecords = function getCashRegisterUsersWithoutRecords(date) {
  return asyncFunction(_utilities_axiosClient__WEBPACK_IMPORTED_MODULE_0__["default"].get("/cash_register/users_without_record/".concat(date)));
};

var getTotalsToCashRegisterUser = function getTotalsToCashRegisterUser(obj) {
  return asyncFunction(_utilities_axiosClient__WEBPACK_IMPORTED_MODULE_0__["default"].get("/cash_register/totals/".concat(obj.cashRegisterUser, "/").concat(obj.date)));
};

var asyncFunction = function asyncFunction(promise) {
  return promise.then(function (res) {
    return res;
  })["catch"](function (err) {
    return err;
  });
};



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

/***/ "./resources/js/views/CashRegisterDataView.js":
/*!****************************************************!*\
  !*** ./resources/js/views/CashRegisterDataView.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _themesberg_tailwind_datepicker_Datepicker__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @themesberg/tailwind-datepicker/Datepicker */ "./node_modules/@themesberg/tailwind-datepicker/js/Datepicker.js");
/* harmony import */ var _themesberg_tailwind_datepicker_locales_es__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @themesberg/tailwind-datepicker/locales/es */ "./node_modules/@themesberg/tailwind-datepicker/js/i18n/locales/es.js");


var CashRegisterDataViewPrototype = {
  init: function init(container) {
    this.container = container;
    var date = this.container.querySelector('#date');
    Object.assign(_themesberg_tailwind_datepicker_Datepicker__WEBPACK_IMPORTED_MODULE_0__["default"].locales, _themesberg_tailwind_datepicker_locales_es__WEBPACK_IMPORTED_MODULE_1__["default"]);
    new _themesberg_tailwind_datepicker_Datepicker__WEBPACK_IMPORTED_MODULE_0__["default"](date, {
      format: 'dd-mm-yyyy',
      language: 'es'
    });
    this.container.addEventListener("change", this.changeEventHandlerWrapper(this.presenter));
    date.addEventListener('changeDate', this.changeDateEventHandlerWrapper(this.presenter));
  },
  changeEventHandlerWrapper: function changeEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.changeOnView({
        target: event.target
      });
    };
  },
  changeDateEventHandlerWrapper: function changeDateEventHandlerWrapper(presenter) {
    return function (event) {
      presenter.changeDateOnView({
        date: event.detail.date
      });
    };
  },
  toggleNewWorkerContainer: function toggleNewWorkerContainer() {
    var workersSelectEl = this.container.querySelector('#cash_register_worker');
    var newCashRegisterWorkerContainer = this.container.querySelector('#new_cash_register_worker_container');

    if (newCashRegisterWorkerContainer && workersSelectEl) {
      newCashRegisterWorkerContainer.classList.toggle('hidden');
      newCashRegisterWorkerContainer.querySelector('input').toggleAttribute('required');
      workersSelectEl.disabled = !workersSelectEl.disabled;
      workersSelectEl.toggleAttribute('required');

      if (workersSelectEl.disabled) {
        workersSelectEl.selectedIndex = "0";
      }
    }
  },
  showCashRegisterUsersNoAvailable: function showCashRegisterUsersNoAvailable() {
    this.container.querySelector('#cash_register_users_message').classList.remove('hidden');
  },
  hideCashRegisterUsersNoAvailable: function hideCashRegisterUsersNoAvailable() {
    this.container.querySelector('#cash_register_users_message').classList.add('hidden');
  },
  showLoading: function showLoading() {
    var el = this.container.querySelector('#cash_register_users_status').children.item(0);

    if (el.classList.contains('loading')) {
      el.classList.remove('hidden');
    }
  },
  hideLoading: function hideLoading() {
    var el = this.container.querySelector('#cash_register_users_status').children.item(0);
    el.classList.add('hidden');
  },
  setCashRegisterUsersElements: function setCashRegisterUsersElements() {
    var elements = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    var cashRegisterUsersSelect = this.container.querySelector('#cash_register_id');

    if (elements.length === 0) {
      cashRegisterUsersSelect.disabled = true;
      cashRegisterUsersSelect.innerHTML = "<option hidden disabled value selected>No hay elementos</option>";
    } else {
      cashRegisterUsersSelect.disabled = false;
      cashRegisterUsersSelect.innerHTML = '<option hidden disabled value selected>Selecione una caja</option>';
      cashRegisterUsersSelect.insertAdjacentHTML('beforeend', elements.map(function (el) {
        return "<option value=\"".concat(el.key, "\"> ").concat(el.value, "</option>");
      }).join(''));
    }
  }
};
/**
 * event.target.value = event.target.value === "0" ? "1" : "0"
            let workersSelectEl = container.querySelector('#cash_register_worker');
            let newCashRegisterWorkerContainer = container.querySelector('#new_cash_register_worker_container');
            
            if (newCashRegisterWorkerContainer && workersSelectEl){
                newCashRegisterWorkerContainer.classList.toggle('hidden');
                newCashRegisterWorkerContainer?.lastElementChild?.toggleAttribute('required');

                workersSelectEl.disabled = !workersSelectEl.disabled;
                workersSelectEl.toggleAttribute('required');
                
                if (workersSelectEl.disabled){
                    workersSelectEl.selectedIndex = "0"
                }
            }
 */

/**
 * It represents the cash register data component
 * @constructor
 */

var CashRegisterDataView = function CashRegisterDataView(presenter) {
  this.presenter = presenter;
  this.presenter.setView(this);
};

CashRegisterDataView.prototype = CashRegisterDataViewPrototype;
CashRegisterDataView.prototype.constructor = CashRegisterDataView;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CashRegisterDataView);

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
    this.table.init(tableContainer, tableName, this.presenter.currency);
    container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
    container.addEventListener('change', this.changeEventHandlerWrapper(this.presenter));
    container.addEventListener("keypress", this.keyPressEventHandlerWrapper(this.presenter));
    container.addEventListener("keydown", this.keyDownEventHandlerWrapper(this.presenter));
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

var SalePointModalView = function SalePointModalView(presenter) {
  this.presenter = presenter;
  this.presenter.setView(this);
};

SalePointModalView.prototype = SalePointModalViewPrototype;
SalePointModalView.prototype.constructor = SalePointModalView;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SalePointModalView);

/***/ })

}]);