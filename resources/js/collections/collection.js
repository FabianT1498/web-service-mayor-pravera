const CollectionPrototype = {
    getAll(){
        return this.elements
    },
    getLength(){
        return this.elements.length;
    },
    setElements(elements){
        this.elements = elements ? elements : []
    },
    getElement(index){
        if (index > -1 && index < this.elements.length){
            return null;
        }

        return this.elements[index]
    },
    deleteElementByName(name){
        let index = this.elements.findIndex((val) => val === name);
        if (index !== -1) {
            this.elements.splice(index, 1);
        }
    },
    pushElement(name){
        this.elements.push(name);
        return this.elements;
    },
    shiftElement(){
        return this.elements.shift();
    }
}

const Collection = function (){

}

Collection.prototype = CollectionPrototype;
Collection.prototype.constructor = Collection;

export default Collection;