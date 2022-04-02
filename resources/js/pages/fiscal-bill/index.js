import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';
import DateRangePicker from '@themesberg/tailwind-datepicker/DateRangePicker';
import es from '@themesberg/tailwind-datepicker/locales/es';
export default {
    DOMElements: {
        dateRangePicker: document.querySelector('#date_range_picker'),

    },
    init(){
        Object.assign(Datepicker.locales, es);

        new DateRangePicker(this.DOMElements.dateRangePicker, {
            format: 'dd-mm-yyyy',
            language: 'es'
        });
    }
}
