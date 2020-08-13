(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[3],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Profile.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/User/Account/Profile.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "Profile",
  data: function data() {
    return {
      input: this.$page.flash.profile
    };
  },
  methods: {
    submit: function submit() {
      this.$inertia.post(this.$route('user.account.profile.update'), this.input, {
        preserveState: false,
        preserveScroll: true
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true& ***!
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
        _c("div", { staticClass: "card" }, [
          _c(
            "form",
            { staticClass: "needs-validation", attrs: { method: "post" } },
            [
              _c("div", { staticClass: "card-header" }, [
                _c("h4", [_vm._v("Edit Profile")])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "card-body" }, [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "form-group col-12" }, [
                    _c("label", [_vm._v("Username")]),
                    _vm._v(" "),
                    _c("div", { staticClass: "input-group" }, [
                      _c("div", { staticClass: "input-group-prepend" }, [
                        _c("div", { staticClass: "input-group-text" }, [
                          _vm._v(
                            "\n                                            @\n                                        "
                          )
                        ])
                      ]),
                      _vm._v(" "),
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.input.username,
                            expression: "input.username"
                          }
                        ],
                        staticClass: "form-control",
                        attrs: { disabled: "", type: "text" },
                        domProps: { value: _vm.input.username },
                        on: {
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(_vm.input, "username", $event.target.value)
                          }
                        }
                      })
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "form-group col-12" }, [
                    _c("label", [_vm._v("Full Name")]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.input.fullname,
                          expression: "input.fullname"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text" },
                      domProps: { value: _vm.input.fullname },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.input, "fullname", $event.target.value)
                        }
                      }
                    })
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "form-group col-md-7 col-12" }, [
                    _c("label", [_vm._v("Email")]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.input.email,
                          expression: "input.email"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text" },
                      domProps: { value: _vm.input.email },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.input, "email", $event.target.value)
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "form-group col-md-5 col-12" }, [
                    _c("label", [_vm._v("Phone")]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.input.phone,
                          expression: "input.phone"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: { type: "tel" },
                      domProps: { value: _vm.input.phone },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.input, "phone", $event.target.value)
                        }
                      }
                    })
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "form-group col-12" }, [
                    _c("label", [_vm._v("Address")]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.input.alamat,
                          expression: "input.alamat"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: { type: "text" },
                      domProps: { value: _vm.input.alamat },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.input, "alamat", $event.target.value)
                        }
                      }
                    })
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "form-group col-12" }, [
                    _c("label", [_vm._v("Bio")]),
                    _vm._v(" "),
                    _c("textarea", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.input.bio,
                          expression: "input.bio"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: { cols: "30", rows: "10" },
                      domProps: { value: _vm.input.bio },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.input, "bio", $event.target.value)
                        }
                      }
                    })
                  ])
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "card-footer text-right" }, [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-primary",
                    attrs: { type: "button" },
                    on: { click: _vm.submit }
                  },
                  [_vm._v("Save Changes")]
                )
              ])
            ]
          )
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/User/Account/Profile.vue":
/*!*****************************************************!*\
  !*** ./resources/js/Pages/User/Account/Profile.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Profile.vue?vue&type=template&id=f75e9e28&scoped=true& */ "./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true&");
/* harmony import */ var _Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Profile.vue?vue&type=script&lang=js& */ "./resources/js/Pages/User/Account/Profile.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "f75e9e28",
  null
  
)

/* hot reload */
if (true) {
  var api = __webpack_require__(/*! ./node_modules/vue-hot-reload-api/dist/index.js */ "./node_modules/vue-hot-reload-api/dist/index.js")
  api.install(__webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js"))
  if (api.compatible) {
    module.hot.accept()
    if (!api.isRecorded('f75e9e28')) {
      api.createRecord('f75e9e28', component.options)
    } else {
      api.reload('f75e9e28', component.options)
    }
    module.hot.accept(/*! ./Profile.vue?vue&type=template&id=f75e9e28&scoped=true& */ "./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true&", function(__WEBPACK_OUTDATED_DEPENDENCIES__) { /* harmony import */ _Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Profile.vue?vue&type=template&id=f75e9e28&scoped=true& */ "./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true&");
(function () {
      api.rerender('f75e9e28', {
        render: _Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
        staticRenderFns: _Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]
      })
    })(__WEBPACK_OUTDATED_DEPENDENCIES__); }.bind(this))
  }
}
component.options.__file = "resources/js/Pages/User/Account/Profile.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/Pages/User/Account/Profile.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/Pages/User/Account/Profile.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Profile.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Profile.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true&":
/*!************************************************************************************************!*\
  !*** ./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Profile.vue?vue&type=template&id=f75e9e28&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/User/Account/Profile.vue?vue&type=template&id=f75e9e28&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_template_id_f75e9e28_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);