const CollectionPrototype = {
    elements: [],
    getAll(){
        return this.elements
    },
    getLast(){
        if (this.getLength() === 0){
            return null;
        }

        return this.elements[this.getLength() - 1]
    },
    getLength(){
        return this.elements.length;
    },
    setElements(elements){
        this.elements = elements ? elements : []
    },
    getElementByIndex(index){
        if (index > -1 && index < this.elements.length){
            return this.elements[index]
        }

        return undefined;        
    },
    deleteElementByIndex(index){
        if (index > -1 && index < this.elements.length){
            this.elements.splice(index, 1);
        }

        return false;
    },
    pushElement(el){
        this.elements.push(el);
        return this.elements;
    },
    shiftElement(){
        return this.elements.shift();
    },
    unshiftElement(el){
        return this.elements.unshift(el);
    },
}

const Collection = function (elements = []){
    this.elements = elements;
}

Collection.prototype = CollectionPrototype;
Collection.prototype.constructor = Collection;

export default Collection;