const formatAmount = (amount, defaultValue = '0.00') => {
  
    if (!amount){
        return 0;
    }

    let index = amount.indexOf(" ");

    // Remove suffix if exists
    if (index !== -1){
        amount = amount.slice(0, index);
    }
    
    // Check if value is zero
    if (amount === defaultValue){
        return 0;
    }
    
    let arr = amount.split('.', 2);
    let integer = arr[0] ?? null;
    let decimal = arr[1] ?? null;
            
    // Check if it is an integer number
    if (!decimal){
        return parseInt(integer);
    }

    let numberString = integer + '.' + decimal;

    return (Math.round((parseFloat(numberString) + Number.EPSILON) * 100) / 100)
};

export  { formatAmount };
