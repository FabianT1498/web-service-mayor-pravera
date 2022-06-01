const Note = function(title, description, id = null){
	this.id = id;
	this.title = title;
    this.description = description;
}

Note.prototype.constructor = Note;

export default Note;