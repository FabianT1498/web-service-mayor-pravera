const timerDelay = function (fn, ms) {
    let timer = null
    return function (forceStop = false, ...args) {
        let res = null;

        if (forceStop){
            if (timer){
                clearTimeout(timer)
            }

            return res;
        }

        if (timer){
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        } else {
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }

        return res;
    }
}

export { timerDelay };