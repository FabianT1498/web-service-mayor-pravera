require('./bootstrap');

require('./base')

require('./charts')

require('./external')

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
