/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/app-admin.js":
/***/ (function(module, exports) {

$('#addRoleSubmitter').click(function () {
    form = $('#addRoleForm');
    data = JSON.stringify(form.serializeArray());
    axios.post(form.attr('action'), {
        name: form.find('input[name=name]').val(),
        description: form.find('input[name=description]').val(),
        discord_id: form.find('input[name=discord_id]').val(),
        icon: form.find('input[name=icon]').val(),
        slug: form.find('input[name=slug]').val(),
        _token: form.find('input[name=_token]').val()
    }).then(function (response) {
        $.notify("role added", 'success');
        console.log(response);
    }).catch(function (error) {
        $.notify("error", 'error');
        console.log(error);
    });
    form.find("input").val("");
});

$('.restrictRoleButton').click(function () {
    axios.get('/mods/tokens/scopes').then(function (response) {
        options = [];
        Object.keys(response.data).forEach(function (key) {
            options.push({ text: response.data[key].description, value: response.data[key].id });
        });
        bootbox.prompt({
            title: "Choose Restrictions",
            inputType: 'checkbox',
            inputOptions: options,
            callback: function (result) {
                console.log(result);
                console.log(this);
                var role_id = $(this).attr('data-id');
                axios.post('/roles/restrict', {
                    role: role_id,
                    permissions: result
                }).then(function (response) {
                    $.notify("restricted", 'success');
                }).catch(function (error) {
                    $.notify("error", 'error');
                });
            }.bind(this)

        });
    }.bind(this));
});

/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/assets/js/app-admin.js");


/***/ })

/******/ });