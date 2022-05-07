import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';
import DateRangePicker from '@themesberg/tailwind-datepicker/DateRangePicker';
import es from '@themesberg/tailwind-datepicker/locales/es';

export default {
    DOMElements: {
        dateRangePicker: document.querySelector('#date_range_picker'),
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page')
    },
    init(){
        Object.assign(Datepicker.locales, es);

        let dateRangePicker = new DateRangePicker(this.DOMElements.dateRangePicker, {
            format: 'dd-mm-yyyy',
            language: 'es'
        });
    }
}