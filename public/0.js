(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/@dpc-sdp/myvic-bar-chart/InnerChart.js":
/*!*************************************************************!*\
  !*** ./node_modules/@dpc-sdp/myvic-bar-chart/InnerChart.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_chartjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-chartjs */ "./node_modules/vue-chartjs/es/index.js");

const { reactiveProp } = vue_chartjs__WEBPACK_IMPORTED_MODULE_0__["mixins"]

/* harmony default export */ __webpack_exports__["default"] = ({
  extends: vue_chartjs__WEBPACK_IMPORTED_MODULE_0__["Bar"],
  mixins: [reactiveProp],
  props: {
    chartData: {
      type: Object,
      default: null
    },
    options: {
      type: Object,
      default: null
    }
  },
  mounted () {
    this.renderChart(this.chartData, this.options)
  }
});


/***/ })

}]);