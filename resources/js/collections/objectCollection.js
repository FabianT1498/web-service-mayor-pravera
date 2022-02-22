import Collection from "./collection";

const ObjectCollection = function(elements) {
    Collection.call(this, elements);

    this.pushElement = function(el){
        el.id = el?.id ? el.id : this.getNewID();
        Collection.prototype.pushElement.call(this, el);
        return el;
    }
    
    this.removeElementByID = function(id){
        const index = this.elements.findIndex((obj) => obj.id === id)
        console.log(index);
        if (index !== -1){
            console.log(index);
            this.elements.splice(index, 1)
            return true;
        }
        
        return false;
    }

    this.getNewID = function(){
        return this.getLength() === 0 ? 0 : (this.getElementByIndex((this.getLength() - 1)).id + 1)
    }
}

ObjectCollection.prototype = Object.create(Collection.prototype);
ObjectCollection.prototype.constructor = ObjectCollection;

export default ObjectCollection;