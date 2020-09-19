(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{"KHd+":function(t,e,r){"use strict";function s(t,e,r,s,n,a,o,i){var l,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=r,c._compiled=!0),s&&(c.functional=!0),a&&(c._scopeId="data-v-"+a),o?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),n&&n.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(o)},c._ssrRegister=l):n&&(l=i?function(){n.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:n),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(t,e){return l.call(e),u(t,e)}}else{var d=c.beforeCreate;c.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:c}}r.d(e,"a",(function(){return s}))},MEZy:function(t,e,r){"use strict";var s={methods:{isActive:function(t){return location.pathname.substr(1).startsWith(t)}}},n=r("KHd+"),a=Object(n.a)(s,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("header",{staticClass:"relative border-b border-gray-200 bg-blue-600 flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8"},[r("div",{staticClass:"text-blue-100"},[t._v("Hooglandia Feature Picker")]),t._v(" "),r("div",{staticClass:"flex justify-end space-x-4"},[r("inertia-link",{class:t.isActive("players")?"text-white":"text-blue-200",attrs:{href:t.route("players.index")}},[t._v("\n                Players\n            ")]),t._v(" "),r("inertia-link",{class:t.isActive("tags")?"text-white":"text-blue-200",attrs:{href:t.route("tags.index")}},[t._v("\n                Tags\n            ")]),t._v(" "),r("inertia-link",{class:t.isActive("formulas")?"text-white":"text-blue-200",attrs:{href:t.route("formulas.index")}},[t._v("\n                Formulas\n            ")])],1)]),t._v(" "),r("main",[r("div",{staticClass:"flex-1 px-4 py-8 md:p-12 overflow-y-auto",attrs:{"scroll-region":""}},[t._t("default")],2)])])}),[],!1,null,null,null);e.a=a.exports},Tt6d:function(t,e,r){"use strict";r.r(e);var s=r("MEZy"),n=r("UOK+");function a(t){return function(t){if(Array.isArray(t))return o(t)}(t)||function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}(t)||function(t,e){if(!t)return;if("string"==typeof t)return o(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);"Object"===r&&t.constructor&&(r=t.constructor.name);if("Map"===r||"Set"===r)return Array.from(t);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return o(t,e)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function o(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,s=new Array(e);r<e;r++)s[r]=t[r];return s}var i={layout:s.a,components:{Checkbox:n.a},props:{columns:{required:!0,type:Array},tags:{required:!0,type:Array},formulas:{required:!0,type:Array},players:{required:!0,type:Array}},data:function(){return{sizes:{wins:"20",draws:"20",losses:"20",points:"20"},sortable:["points","wins","losses","draws"].concat(a(this.formulas.map((function(t){return t.name})))),sortBy:{col:"points",asc:!1}}},computed:{filteredData:function(){var t=this;return this.players.slice(0).sort((function(e,r){var s=e[t.sortBy.col]-r[t.sortBy.col];return t.sortBy.asc?s:-1*s}))}},methods:{sort:function(t){this.sortBy.col===t?this.sortBy.asc=!this.sortBy.asc:(this.sortBy.col=t,this.sortBy.asc=!0)},uploadPlayers:function(t){var e=t.target.files||t.dataTransfer.files;if(e.length){var r=new FormData;r.append("data",e[0]),this.$inertia.post(route("players.store"),r)}},toggleTag:function(t,e){this.$inertia.put(route("players.tags.update",[t,e]))},deletePlayers:function(){this.$inertia.delete(route("players.delete"))}}},l=r("KHd+"),c=Object(l.a)(i,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"space-y-6"},[r("table",{staticClass:"w-full max-w-full table-fixed border-collapse border border-solid border-gray-300"},[r("thead",[r("tr",[t._l(t.columns,(function(e){return r("th",{key:e,staticClass:"px-4 py-2 border border-solid border-gray-300",class:t.sizes[e]?"w-"+t.sizes[e]:""},[r("div",{staticClass:"flex justify-between space-x-1",on:{click:function(r){return t.sort(e)}}},[r("span",[t._v(t._s(e.charAt(0).toUpperCase()+e.slice(1)))]),t._v(" "),t.sortable.includes(e)?[t.sortBy.col===e?r("FontAwesomeIcon",{attrs:{icon:t.sortBy.asc?"sort-up":"sort-down"}}):r("FontAwesomeIcon",{attrs:{icon:"sort"}})]:t._e()],2)])})),t._v(" "),t._l(t.tags,(function(e){return r("th",{key:e.name,staticClass:"px-4 py-2 border border-solid border-gray-300"},[t._v("\n                    "+t._s(e.name.charAt(0).toUpperCase()+e.name.slice(1))+"\n                ")])})),t._v(" "),t._l(t.formulas,(function(e){return r("th",{key:e.name,staticClass:"px-4 py-2 border border-solid border-gray-300"},[r("div",{staticClass:"flex space-x-1",on:{click:function(r){return t.sort(e.name)}}},[r("span",[t._v("\n                            "+t._s(e.name.charAt(0).toUpperCase()+e.name.slice(1))+"\n                        ")]),t._v(" "),r("FontAwesomeIcon",{directives:[{name:"tooltip",rawName:"v-tooltip",value:e.equation,expression:"formula.equation"}],attrs:{icon:"info-circle"}}),t._v(" "),t.sortBy.col===e.name?r("FontAwesomeIcon",{staticClass:"self-end",attrs:{icon:t.sortBy.asc?"sort-up":"sort-down"}}):r("FontAwesomeIcon",{staticClass:"self-end",attrs:{icon:"sort"}})],1)])}))],2)]),t._v(" "),r("tbody",[t.players.length>0?t._l(t.filteredData,(function(e){return r("tr",{key:e.id},[t._l(t.columns,(function(s){return r("td",{key:s,staticClass:"px-4 py-2 border border-solid border-gray-300"},[t._v("\n                        "+t._s(e[s])+"\n                    ")])})),t._v(" "),t._l(t.tags,(function(s){return r("td",{key:s.name,staticClass:"px-4 py-2 border border-solid border-gray-300"},[r("div",{staticClass:"flex justify-center items-center"},[r("Checkbox",{attrs:{checked:e.tag_values[s.name]},on:{"update:checked":function(r){return t.toggleTag(e,s.name)}}})],1)])})),t._v(" "),t._l(t.formulas,(function(s){return r("td",{key:s.name,staticClass:"px-4 py-2 border border-solid border-gray-300"},[t._v("\n                        "+t._s(e[s.name])+"\n                    ")])}))],2)})):[r("tr",[r("td",{staticClass:"text-center px-4 py-2 border border-solid border-gray-300",attrs:{colspan:t.columns.length}},[t._v("\n                        No players entered!\n                    ")])])]],2)]),t._v(" "),r("div",{staticClass:"flex justify-end items-center space-x-4"},[r("input",{ref:"playersCsv",staticClass:"hidden",attrs:{type:"file"},on:{change:t.uploadPlayers}}),t._v(" "),r("button",{staticClass:"rounded-md px-4 py-3 bg-blue-700 text-blue-100 hover:bg-blue-800",on:{click:function(e){return t.$refs.playersCsv.click()}}},[t._v("\n            Upload Players CSV\n        ")]),t._v(" "),r("button",{staticClass:"rounded-md px-4 py-3 bg-red-700 text-white hover:bg-red-800",on:{click:t.deletePlayers}},[t._v("\n            Delete all players\n        ")])])])}),[],!1,null,null,null);e.default=c.exports},"UOK+":function(t,e,r){"use strict";var s={props:{checked:{required:!0,type:Boolean}}},n=r("KHd+"),a=Object(n.a)(s,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"rounded-md h-8 w-8 flex justify-center items-center cursor-pointer",class:t.checked?"bg-green-500":"bg-gray-500",on:{click:function(e){return t.$emit("update:checked",!t.checked)}}},[r("FontAwesomeIcon",{staticClass:"text-white",attrs:{icon:t.checked?"check":"ban"}})],1)}),[],!1,null,null,null);e.a=a.exports}}]);
//# sourceMappingURL=2.js.map?id=61a1b0ea2461c44199e2