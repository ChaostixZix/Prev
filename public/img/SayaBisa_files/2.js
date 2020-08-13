(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[2],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Account.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/User/Account/Account.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Depan_App__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../Depan/App */ "./resources/js/Pages/Depan/App.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  name: "Account",
  components: {
    App: _Depan_App__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      complete: false
    };
  },
  mounted: function mounted() {
    var _this = this;

    var obj = this.$page.flash.profile;
    Object.keys(obj).forEach(function (key) {
      if (typeof obj[key] === "undefined" || obj[key] === null) {
        _this.complete = false;
        return;
      }

      _this.complete = true;
    });
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("App", [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-12" }, [
        _c("div", { staticClass: "profile-wrapper-area py-3" }, [
          _c("div", { staticClass: "card user-info-card" }, [
            _c(
              "div",
              { staticClass: "card-body p-4 d-flex align-items-center" },
              [
                _c("div", { staticClass: "user-profile mr-3" }, [
                  _c("img", {
                    attrs: {
                      src: _vm.$route("depan.index") + "img/bg-img/9.jpg",
                      alt: ""
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "user-info" }, [
                  _c("h5", { staticClass: "mb-0" }, [
                    _vm._v(_vm._s(_vm.$page.flash.username))
                  ]),
                  _vm._v(" "),
                  _c("p", [_vm._v(_vm._s(_vm.$page.flash.profile.bio))])
                ])
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "card user-data-card" }, [
            _c("div", { staticClass: "card-body" }, [
              _c(
                "div",
                {
                  staticClass:
                    "single-profile-data d-flex align-items-center justify-content-between"
                },
                [
                  _c(
                    "div",
                    { staticClass: "title d-flex align-items-center" },
                    [
                      _c("i", { staticClass: "fa fa-user" }),
                      _c("span", [_vm._v("Username")])
                    ]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "data-content" }, [
                    _vm._v("@" + _vm._s(_vm.$page.flash.username))
                  ])
                ]
              ),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass:
                    "single-profile-data d-flex align-items-center justify-content-between"
                },
                [
                  _c(
                    "div",
                    { staticClass: "title d-flex align-items-center" },
                    [
                      _c("i", { staticClass: "fa fa-envelope" }),
                      _c("span", [_vm._v("Email")])
                    ]
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "data-content" }, [
                    _vm._v(_vm._s(_vm.$page.flash.profile.email))
                  ])
                ]
              ),
              _vm._v(" "),
              _vm.$page.flash.profile.phone !== null
                ? _c(
                    "div",
                    {
                      staticClass:
                        "single-profile-data d-flex align-items-center justify-content-between"
                    },
                    [
                      _c(
                        "div",
                        { staticClass: "title d-flex align-items-center" },
                        [
                          _c("i", { staticClass: "fa fa-phone" }),
                          _c("span", [_vm._v("Phone")])
                        ]
                      ),
                      _vm._v(" "),
                      _c("div", { staticClass: "data-content" }, [
                        _vm._v(_vm._s(_vm.$page.flash.profile.phone))
                      ])
                    ]
                  )
                : _vm._e()
            ])
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "edit-profile-btn mt-3" },
            [
              _c(
                "inertia-link",
                {
                  staticClass: "btn btn-primary col-12 text-white mb-3",
                  attrs: { href: _vm.$route("user.topup.depan") }
                },
                [
                  _c("i", { staticClass: "fa fa-dollar" }),
                  _vm._v(
                    "\n                        Topup\n                    "
                  )
                ]
              ),
              _vm._v(" "),
              !_vm.complete
                ? _c(
                    "inertia-link",
                    {
                      staticClass: "btn btn-warning col-12 text-white mb-3",
                      attrs: { href: _vm.$route("user.account.profile.depan") }
                    },
                    [
                      _c("i", { staticClass: "fa fa-pencil" }),
                      _vm._v(
                        "\n                        Complete Profile\n                    "
                      )
                    ]
                  )
                : _c(
                    "inertia-link",
                    {
                      staticClass: "btn btn-primary col-12 text-white mb-3",
                      attrs: { href: _vm.$route("user.account.profile.depan") }
                    },
                    [
                      _c("i", { staticClass: "fa fa-pencil" }),
                      _vm._v(
                        "\n                        Edit Profile\n                    "
                      )
                    ]
                  )
            ],
            1
          )
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/User/Account/Account.vue":
/*!*****************************************************!*\
  !*** ./resources/js/Pages/User/Account/Account.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Account.vue?vue&type=template&id=a54c6120&scoped=true& */ "./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true&");
/* harmony import */ var _Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Account.vue?vue&type=script&lang=js& */ "./resources/js/Pages/User/Account/Account.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "a54c6120",
  null
  
)

/* hot reload */
if (true) {
  var api = __webpack_require__(/*! ./node_modules/vue-hot-reload-api/dist/index.js */ "./node_modules/vue-hot-reload-api/dist/index.js")
  api.install(__webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js"))
  if (api.compatible) {
    module.hot.accept()
    if (!api.isRecorded('a54c6120')) {
      api.createRecord('a54c6120', component.options)
    } else {
      api.reload('a54c6120', component.options)
    }
    module.hot.accept(/*! ./Account.vue?vue&type=template&id=a54c6120&scoped=true& */ "./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true&", function(__WEBPACK_OUTDATED_DEPENDENCIES__) { /* harmony import */ _Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Account.vue?vue&type=template&id=a54c6120&scoped=true& */ "./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true&");
(function () {
      api.rerender('a54c6120', {
        render: _Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
        staticRenderFns: _Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]
      })
    })(__WEBPACK_OUTDATED_DEPENDENCIES__); }.bind(this))
  }
}
component.options.__file = "resources/js/Pages/User/Account/Account.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/User/Account/Account.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/User/Account/Account.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Account.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Account.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true&":
/*!************************************************************************************************!*\
  !*** ./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Account.vue?vue&type=template&id=a54c6120&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Account.vue?vue&type=template&id=a54c6120&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Account_vue_vue_type_template_id_a54c6120_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);