import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';
import DateRangePicker from '@themesberg/tailwind-datepicker/DateRangePicker';
import es from '@themesberg/tailwind-datepicker/locales/es';

export default {
    DOMElements: {
        dateRangePicker: document.querySelector('#dateRangePicker'),
    },
    isDateSelectedCollapsed: false,
    changeDateEventHandlerWrapper: (otherDatePicker) => {
        return (event) => {
            let targetID = event.detail.datepicker.element.id;
            let targetDate = event.detail.datepicker.getDate('yyyy-mm-dd')
            let otherDatepickerDate = otherDatePicker.picker.datepicker.getDate('yyyy-mm-dd')
            
            if(targetID === 'startDate'){
                let topDate = event.detail.datepicker.getDate().addDays(7)
                otherDatePicker.picker.datepicker.setDate(topDate);
            } else if(targetID === 'endDate'){
                let lowerDate = event.detail.datepicker.getDate().diffDays(7)
                otherDatePicker.picker.datepicker.setDate(lowerDate);
            } 
        }
    },
    init(){
        Object.assign(Datepicker.locales, es);

        let dateRangePicker = new DateRangePicker(this.DOMElements.dateRangePicker, {
            format: 'dd-mm-yyyy',
            language: 'es',
            allowOneSidedRange: true
        });

        dateRangePicker.datepickers[0].element.addEventListener('hide', this.changeDateEventHandlerWrapper(dateRangePicker.datepickers[1]));
        dateRangePicker.datepickers[1].element.addEventListener('hide', this.changeDateEventHandlerWrapper(dateRangePicker.datepickers[0]));
    }
}