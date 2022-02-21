const CollectionPrototype = {
    elements: [],
    getAll(){
        return this.elements
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
}

const Collection = function (elements){
    this.elements = elements;
}

Collection.prototype = CollectionPrototype;
Collection.prototype.constructor = Collection;

export default Collection;