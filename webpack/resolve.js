const path = require('path')

module.exports = {
  modules: [path.join(__dirname, '..', 'resources/js'), path.join(__dirname, '..', 'node_modules')],
  extensions: ['.js'],
  alias: {
    _app: path.resolve(__dirname, '..', 'resources/js'),
    _components: path.resolve(__dirname, '..', 'resources/js/components'),
    _pages: path.resolve(__dirname, '..', 'resources/js/pages'),
    _services: path.resolve(__dirname, '..', 'resources/js/services'),
    _utilities: path.resolve(__dirname, '..', 'resources/js/utilities'),
    _assets: path.resolve(__dirname, '..', 'resources/js/assets'),
  },
}