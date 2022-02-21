import Collection from "./collection";

const ObjectCollection = function() {
    Collection.call(this);
    
    this.removeElementByID = function(id){
        const index = this.elements.findIndex((obj) => obj?.id && obj.id === id)
        return index !== -1 ? this.elements.slice(index, 1) : -1;
    }

    this.getNewID = function(){
        return this.getLength() === 0 ? 0 : (this.getElementByIndex((this.getLength() - 1)).id + 1)
    }
}

ObjectCollection.prototype = Object.create(Collection.prototype);
ObjectCollection.prototype.constructor = ObjectCollection;

export default ObjectCollection;