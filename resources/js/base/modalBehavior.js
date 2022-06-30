
export default (function(){
    let handleModalOpening =  function(event){
        let closest = event.target.closest('button');
        
        if (closest){
            let modalID = closest.getAttribute('data-modal-toggle');
            
            if (modalID){
                let modal = document.querySelector(`#${modalID}`)

                if (!modal.classList.contains("hidden")) {
                    // Disable scroll
                    main.classList.add("overflow-y-hidden");
                    main.classList.remove("overflow-y-scroll");
                } else {
                    // Enable scroll
                    main.classList.add("overflow-y-scroll");
                    main.classList.remove("overflow-y-hidden");
                }
            }
        }
    }
    let main = document.querySelector('#main');
    
    main.addEventListener('click', handleModalOpening);
})()