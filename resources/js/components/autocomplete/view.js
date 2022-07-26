const AutocompleteViewPrototype = {
    init(searchBoxElement){
        if (!searchBoxElement){
            return false;
        }

        let id = searchBoxElement.getAttribute('id')
        
        
        this.hiddenValElement = searchBoxElement.querySelector('#' + id + '_hidden')
        this.inputElement = searchBoxElement.querySelector('#' + id + '_input')
        this.resultsContainer = searchBoxElement.querySelector('#' + id + '_results')
        
        this.inputElement.addEventListener("keyup", this.keyupWrapper());
        
        this.resultsContainer.addEventListener('click', this.handleClickWrapper())
        
        searchBoxElement.addEventListener("mouseenter", this.handleMouseEnterWrapper());
        searchBoxElement.addEventListener("mouseleave", this.handleMouseLeaveWrapper());
    },
    handleClickWrapper(){
        return event => {
            let target = event.target.closest('li')

            if (target && target.getAttribute('data-key')){
                let key = target.getAttribute('data-key')

                if (key !== 'null'){
                    this.hiddenValElement.value = key
                    this.inputElement.value = target.innerHTML;
                    this.hideResultsContainer()
                }
            }
        }
    },
    handleMouseLeaveWrapper(){
        return (event) => {
            console.log(event)
            this.hideResultsContainer()
        }
    },
    handleMouseEnterWrapper(){
        return event => {
            if(this.inputElement.value !== ''){
                this.showResultsContainer()
            }
        }
    },
    keyupWrapper(){
        return (event) => {
            let key = event.key || event.keyCode;
            
            if (key === 13 || key === 'Enter'){
                event.preventDefault()
            }

            this.presenter.onKeyEvent(this.inputElement.value);
        }
    },
    showResults(data){
        let innerHTML = ''

        this.resultsContainer.innerHTML = ''
        
        if (data.length === 0){
            this.resetAutocomplete()
            innerHTML = this.getItemTemplate()
            this.resultsContainer.classList.remove('h-60')
        } else {
            this.resultsContainer.classList.add('h-60')
            innerHTML = data.map(el => this.getItemTemplate(el.Descrip, el.CodProv)).join('')
        }
        this.showResultsContainer();
        this.setListItems(innerHTML)
    },
    setListItems(html){
        this.resultsContainer.insertAdjacentHTML('beforeend', html)
    },
    showResultsContainer(){
        this.resultsContainer.classList.remove('hidden')
    },
    hideResultsContainer(){
        this.resultsContainer.classList.add('hidden')
    },
    resetAutocomplete(){
        this.hiddenValElement.value = ''
    },
    getItemTemplate(value = null, key = null){
        return `<li data-key="${key !== null ? key : 'null'}" class="cursor-pointer hover:bg-slate-400 p-2 transition-colors duration-300 last:rounded-b-md">${value !== null ? this.truncate(value, 25) : 'No hay resultados'}</li>`
    },
    truncate(str, n){
        return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
    }
}

const AutocompleteView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

AutocompleteView.prototype = AutocompleteViewPrototype;
AutocompleteView.prototype.constructor = AutocompleteView;

export default AutocompleteView;