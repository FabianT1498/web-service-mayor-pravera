

const charReplace = (text = '', charToReplace = '/', newChar = '-') => {
    
    if (text === ''){
        return ''
    }

    let textSplitted = text.split(charToReplace);

    return textSplitted.join(newChar)
}

const isADateFormatDDMMYYYY = (date, dateSeparator = '-') => {

    let dateSplitted = date.split(dateSeparator);

    let removedChar = ''

    if(dateSplitted.length === 3){

        if (isNaN(parseInt(date[0]))){
            removedChar = date[0]
        }
        
        let dateCleaned = dateSplitted.map(el => el.replace(/\D/g, ''))

        let isADate = true;
    
        dateCleaned.forEach(el => {
            if (isNaN(parseInt(el))){
                isADate = false;
                return false;
            }
        })

        if (!isADate){
            return false;
        }

        return true
    }

    return false;
}

export { charReplace, isADateFormatDDMMYYYY };
