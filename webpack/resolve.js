const path = require('path')

module.exports = {
  modules: [path.join(__dirname, '..', 'resources/js'), path.join(__dirname, '..', 'node_modules')],
  extensions: ['.js'],
  alias: {
    _app: path.resolve(__dirname, '..', 'resources/js'),
    _components: path.resolve(__dirname, '..', 'resources/js/components'),
    _pages: path.resolve(__dirname, '..', 'resources/js/pages'),
    _collections: path.resolve(__dirname, '..', 'resources/js/collections'),
    _models: path.resolve(__dirname, '..', 'resources/js/models'),
    _presenters: path.resolve(__dirname, '..', 'resources/js/presenters'),
    _views: path.resolve(__dirname, '..', 'resources/js/views'),
    _services: path.resolve(__dirname, '..', 'resources/js/services'),
    _utilities: path.resolve(__dirname, '..', 'resources/js/utilities'),
    _constants: path.resolve(__dirname, '..', 'resources/js/constants'),
    _store: path.resolve(__dirname, '..', 'resources/js/store'),
  },
}