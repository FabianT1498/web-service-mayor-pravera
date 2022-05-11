import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';
import DateRangePicker from '@themesberg/tailwind-datepicker/DateRangePicker';
import es from '@themesberg/tailwind-datepicker/locales/es';

export default {
    DOMElements: {
        dateRangePicker: document.querySelector('#date_range_picker'),
        linkReportExcel: document.querySelector('#report_excel'),
    },
    init(){
        Object.assign(Datepicker.locales, es);

        let dateRangePicker = new DateRangePicker(this.DOMElements.dateRangePicker, {
            format: 'dd-mm-yyyy',
            language: 'es'
        });

        let changeDateEventHandlerWrapper = (DOMElements, dateRangePicker) => {
            return (event) => {
         

                let queryString = DOMElements.linkReportExcel.search;

                let dateQueries = queryString.split("&");

                dateQueries[0] = dateQueries[0].split('=')[0]
                dateQueries[1] = dateQueries[1].split('=')[0]

                queryString = dateQueries[0] + '=' + dateRangePicker.datepickers[0].inputField.value 
                    + '&' + dateQueries[1] + '=' + dateRangePicker.datepickers[1].inputField.value

                DOMElements.linkReportExcel.href = DOMElements.linkReportExcel.href.slice(0, DOMElements.linkReportExcel.href.indexOf("?")) + queryString
            }
        }

        dateRangePicker.datepickers[0].element.addEventListener('hide', changeDateEventHandlerWrapper(this.DOMElements, dateRangePicker));
        dateRangePicker.datepickers[1].element.addEventListener('hide', changeDateEventHandlerWrapper(this.DOMElements, dateRangePicker));
    }
}