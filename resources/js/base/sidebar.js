import { store } from '_store'
import { STORE_DOLLAR_EXCHANGE_VALUE } from '_store/action'
import { CURRENCIES, SIGN as CURRENCY_SIGN_MAP} from '_constants/currencies';

export default (function(){
    // work with sidebar
    var btn     = document.getElementById('sliderBtn'),
    sideBar = document.getElementById('sideBar'),
    sideBarHideBtn = document.getElementById('sideBarHideBtn');

    // show sidebar 
    btn.addEventListener('click' , function(){    
        if (sideBar.classList.contains('md:-ml-64')) {
            sideBar.classList.replace('md:-ml-64' , 'md:ml-0');
            sideBar.classList.remove('md:slideOutLeft');
            sideBar.classList.add('md:slideInLeft');
        };
    });

    // hide sideBar    
    sideBarHideBtn.addEventListener('click' , function(){            
        if (sideBar.classList.contains('md:ml-0' , 'animate__slideInLeft')) {      
            var _class = function(){
                sideBar.classList.remove('md:slideInLeft');
                sideBar.classList.add('md:slideOutLeft');
        
                console.log('hide');              
            };
            var animate = async function(){
                await _class();

                setTimeout(function(){
                    sideBar.classList.replace('md:ml-0' , 'md:-ml-64');
                    console.log('animated');
                } , 300);                                                
                
            };            
                    
            _class(); 
            animate();
        };
    });
    // end with sidebar

    store.subscribe(() => {
        let state = store.getState();

        if (state.lastAction === STORE_DOLLAR_EXCHANGE_VALUE ){
          document.querySelector('span[data-dollar-exchange="dollar_exchange_date"]').innerText = state.dollarExchange.createdAt
          document.querySelector('span[data-dollar-exchange="dollar_exchange_value"]').innerText = `${state.dollarExchange.value} ${CURRENCY_SIGN_MAP[CURRENCIES.BOLIVAR]}`
        }
    });
})()