import ObjectCollection from "./objectCollection"

const NoteCollection = function(elements = []) {
    ObjectCollection.call(this, elements);
}

NoteCollection.prototype = Object.create(ObjectCollection.prototype);
NoteCollection.prototype.constructor = NoteCollection;

export default NoteCollection;