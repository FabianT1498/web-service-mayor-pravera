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

        let changeWrapper = (form) => {
            return (event) => {
                form.submit();
            }
        }

        let changeDateEventHandlerWrapper = (form) => {
            return (event) => {
                form.submit();
            }
        }

        dateRangePicker.datepickers[0].element.addEventListener('hide', changeDateEventHandlerWrapper(this.DOMElements.formFilter));
        dateRangePicker.datepickers[1].element.addEventListener('hide', changeDateEventHandlerWrapper(this.DOMElements.formFilter));

        this.DOMElements.formFilter.addEventListener('change', changeWrapper(this.DOMElements.formFilter));

        if (this.DOMElements.pagesLinksContainer){
            this.DOMElements.pagesLinksContainer.addEventListener('click', function(pageInput, form){
                return (e) => {
                    e.preventDefault();
                    let anchor = e.target.closest('a');
                    if (anchor){
                        let url = new URL(anchor.href);
                        let params = new URLSearchParams(url.search);
                        let page = params.get("page");
                        pageInput.value = page;
                        form.submit();
                    }
                }
            }(this.DOMElements.pageInput, this.DOMElements.formFilter));
        }
    }
}