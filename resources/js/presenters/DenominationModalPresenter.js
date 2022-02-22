const DenominationModalPresenterPrototype = {
	clickOnModal({ target }) {
		const closest = target.closest('button');

      if(closest && closest.tagName === 'BUTTON'){
          const modaToggleID = closest.getAttribute('data-modal-toggle');
          
         if (modaToggleID){ // Checking if it's closing the modal
         	this.view.getTotal()
         }
      }
   },
	setView(view){
		this.view = view;
	}
}

const DenominationModalPresenter = function (currency, method){
   this.view = null;
	this.currency = currency;
	this.method = method;
}

DenominationModalPresenter.prototype = DenominationModalPresenterPrototype;
DenominationModalPresenter.prototype.constructor = DenominationModalPresenter;

export default DenominationModalPresenter;