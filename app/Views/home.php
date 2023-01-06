<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src=<?= site_url() . "js_script/home.js"?>></script>
  <link rel="stylesheet" href=<?= site_url() . "css/home.css"?>>
  <title>Hot genre DEV</title>
</head>

<?php
  $carrouselProduits = array(7, 8, 9);
  $carrouselImages = array();

	foreach ($carrouselProduits as $idProduit) {
		$imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $idProduit . DIRECTORY_SEPARATOR . "images/image_1.png";
		
		$headers = @get_headers($imageURL);
		
		// On vérifie si l'url existe
		if(!$headers  || strpos($headers[0], '404')) {
			$imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $idProduit . DIRECTORY_SEPARATOR . "images/image_1.jpg";
		}

		$carrouselImages[] = $imageURL;
	}


  $produitsPlusVendusImages = array();

	foreach ($produitsPlusPopulaires as $produit) {
    $idProduit = $produit->id_produit;

		$imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $idProduit . DIRECTORY_SEPARATOR . "images/image_1.png";
		
		$headers = @get_headers($imageURL);
		
		// On vérifie si l'url existe
		if(!$headers  || strpos($headers[0], '404')) {
			$imageURL = site_url() . "images/produits" . DIRECTORY_SEPARATOR . $idProduit . DIRECTORY_SEPARATOR . "images/image_1.jpg";
		}

		$produitsPlusVendusImages[$idProduit] = $imageURL;
	}
?>

<body >

<div id="loader">

<div class="blackribbontop"> </div>
  <svg id="eTs4f9PrBm11" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 125 110.7" shape-rendering="geometricPrecision" text-rendering="geometricPrecision"><defs><filter id="eTs4f9PrBm12-filter" x="0%" width="100%" y="0%" height="100%"><feColorMatrix id="eTs4f9PrBm12-filter-grayscale-0" type="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0" result="result"/><feComponentTransfer id="eTs4f9PrBm12-filter-invert-0" result="result"><feFuncR id="eTs4f9PrBm12-filter-invert-0-R" type="table" tableValues="0 1"/><feFuncG id="eTs4f9PrBm12-filter-invert-0-G" type="table" tableValues="0 1"/><feFuncB id="eTs4f9PrBm12-filter-invert-0-B" type="table" tableValues="0 1" class="loader"/></feComponentTransfer><feGaussianBlur id="eTs4f9PrBm12-filter-blur-0" stdDeviation="0,0" result="result"/></filter></defs><g id="eTs4f9PrBm12" transform="matrix(.08 0 0-.08 0 0)" filter="url(#eTs4f9PrBm12-filter)"><path d="M878.171749,1143.055878c-14.104121-33.472424-38.652432-80.512337-59.09448-113.092162-44.45476-70.69376-91.49825-116.216256-135.238877-130.676343-12.943655-4.37373-19.013783-5.266328-33.74277-5.266328-14.907519,0-23.744911,1.695936-36.777833,6.873004-3.12433,1.249638-5.980861,2.320755-6.248661,2.320755s-.803399-3.927431-1.160466-8.6582c-3.749196-53.466619-29.457973-139.602322-68.646004-230.558055l-6.427194-14.727867-59.09448-.267779c-34.099836-.08926-59.09448.178519-59.09448.624818c0,.803339,75.073199,161.024674,81.05406,172.985487c9.997858,19.726415,29.993573,51.681422,45.436692,72.389695c32.135971,43.023222,68.021139,75.335268,102.299508,92.02685c8.926658,4.37373,21.781046,9.283019,31.421838,12.050073c2.231664.624818-2.677998.267779-36.152967-2.499274-40.884097-3.481133-74.2698-5.266328-86.588589-4.73077-12.854388.535559-21.334714,2.677794-30.439905,7.408563-25.798044,13.56749-33.028637,46.861394-20.531315,93.633527c1.517532,5.355588,2.588731,9.907838,2.410198,9.997098-.089267.178519-11.78319-24.546445-25.976577-54.805516-33.296436-71.318577-30.707705-65.87373-118.546026-245.464441l-75.162465-153.79463h-32.67157-32.582304l-16.514318-28.652395c-9.015925-15.798984-16.514319-29.009434-16.692852-29.366473-.089266-.357039,27.404842-.892598,61.147611-1.249637s61.504678-.803338,61.683211-.892598c.089267-.178519-14.37192-32.936865-32.135971-72.925254l-32.225237-72.657474-12.140256-12.942671C130.296294,323.829463,53.52703,190.475327,17.552596,61.58418C14.874598,52.033382,12.821467,44.08926,13,44c.178533-.17852,3.035064,5.980406,6.427194,13.746009c7.85546,17.851959,24.816111,52.038461,34.367636,69.354862c66.503606,119.965167,137.470542,180.840348,201.385417,172.717707c9.730058-1.160378,20.709848-4.105951,28.386774-7.497823l4.998929-2.231495v5.087808c0,2.767054.624866,10.086357,1.338999,16.245283c7.409127,63.642235,39.812897,160.846154,89.177319,267.333092l4.909662,10.711175l59.451546.08926c37.4027.08926,59.36228-.17852,59.36228-.714078c0-1.249637-84.71399-181.732947-96.854246-206.45791-40.437763-82.029754-96.497179-147.367925-150.235663-174.859942-11.693923-5.891147-16.603585-7.944122-28.743841-11.871553-6.784261-2.142235-8.301792-2.856314-5.355995-2.499275c7.05206.803339,43.472827,3.927432,64.718275,5.444848c11.604656.892598,31.064771,1.606676,44.633292,1.606676c20.709848.08926,24.905378-.178519,29.81504-1.517416c18.388917-5.266328,30.618439-16.602322,36.420767-34.007983c2.320931-6.873004,2.410198-8.033381,2.410198-25.082003c0-19.012336-.892666-25.349782-6.070128-43.380261-1.160466-4.105951-2.053131-7.587083-1.963865-7.676342.089267-.17852,8.837392,18.119738,19.281583,40.613207c10.44419,22.404209,25.798043,54.894775,33.921302,72.032656c13.389988,28.027576,171.213312,350.880261,184.692566,377.83672l5.445262,10.889695h47.757623c31.064772,0,47.757624-.267779,47.757624-.892598c0-.446299-20.531315-45.343976-45.615226-99.703193-31.064772-67.391146-46.507891-101.666908-48.203956-107.647315-8.480326-28.295355-27.137042-65.78447-46.775691-93.901306-25.35171-36.239477-55.791616-65.78447-95.96158-93.455007l-2.231664-1.517417h3.570663c11.51539-.178519,51.685353,5.177068,76.769264,10.264877C677.589731,224.21553,777.568307,268.13135,866.567093,332.309144l11.604656,8.390421.2678-6.426706c.357066-7.497823-1.071199-32.044267-3.035064-50.967344-6.694994-66.677068-24.280512-139.066763-48.471756-199.941945-2.945798-7.319303-5.177462-13.478229-5.088196-13.567489.089267-.178519,18.210384,36.775037,40.25923,82.119013c21.959581,45.254717,46.329358,94.972424,54.006285,110.414369s35.974434,73.282293,62.932943,128.534107s49.900021,101.845429,50.97122,103.541365s5.534529,10.800435,9.997858,20.083454c4.374063,9.372279,10.622724,22.493469,13.925587,29.277213l5.802328,12.228592h-109.5301-109.530101l-14.728987-29.277213c-8.123259-16.066763-14.818253-29.455733-14.996786-29.812772-.178533-.446299,26.869242-.714078,60.076412-.714078h60.344212l-1.606799-2.767054c-8.658858-15.084906-21.42398-30.70537-39.72363-48.55733-26.065843-25.528301-54.988217-47.396952-93.997715-71.140058-30.707705-18.566037-78.822395-43.023222-113.100764-57.394049l-3.39213-1.338897l3.035064,6.426705c7.498393,16.334543,222.987931,475.040639,229.415125,488.518868c26.244376,54.359217,66.325073,109.96807,102.210241,141.923077c10.265657,9.1045,27.761908,22.582729,33.832036,26.063861l3.39213,1.874456l12.675855-26.777939l12.586589-26.867199-76.858531-159.864296c-42.223095-87.9209-78.554595-163.345428-80.696993-167.629899s-3.92773-8.211901-3.92773-8.74746c0-1.071117,50.257088-1.160377,114.796829-.267779l42.758695.535559l12.765122,28.652394c6.962793,15.798984,12.675855,28.920175,12.675855,29.277214s-12.408056.803338-27.672642.892598l-27.583375.267779l42.401628,91.937591c50.435621,109.343251,45.079626,97.650218,118.992359,258.853411c32.403771,70.604499,58.82668,128.534107,58.648147,128.712627-.089267.178519-2.588731-4.016691-5.445262-9.372279-23.834178-43.91582-60.790545-96.400581-95.42598-135.317852-11.336856-12.674891-32.760837-35.34688-33.47497-35.34688-.357066,0-5.802328,11.514514-12.140255,25.706822-6.248661,14.103048-11.693923,25.974601-12.050989,26.33164-.357067.446299-12.318789-.267779-26.42291-1.517417-56.148682-4.998548-92.480182-6.962264-131.132614-6.962264-38.473899,0-58.023281,2.231495-64.986074,7.408563-1.428266.981858-2.945798,1.517417-3.302864,1.160378-.357067-.446299-39.366564-79.798259-86.588588-176.377359l-85.963722-175.66328h-48.918089c-27.761909,0-49.007356.357039-49.007356.803338s10.265657,23.832366,22.762979,51.949202l22.76298,51.235123l9.640791,9.818578c17.228451,17.40566,37.1349,40.613208,56.505749,65.605951c75.073198,96.936139,129.168749,202.17344,158.358923,307.857039c2.231665,8.122641,3.92773,14.817126,3.749197,14.906386-.178534.17852-1.785332-3.391872-3.749197-7.944122Z" transform="translate(-13-1151)"/></g>
<script><![CDATA[
!function(t,n){"object"==typeof exports&&"undefined"!=typeof module?module.exports=n():"function"==typeof define&&define.amd?define(n):((t="undefined"!=typeof globalThis?globalThis:t||self).__SVGATOR_PLAYER__=t.__SVGATOR_PLAYER__||{},t.__SVGATOR_PLAYER__["5c7f360c"]=n())}(this,(function(){"use strict";function t(t,n){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);n&&(r=r.filter((function(n){return Object.getOwnPropertyDescriptor(t,n).enumerable}))),e.push.apply(e,r)}return e}function n(n){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?t(Object(r),!0).forEach((function(t){o(n,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(n,Object.getOwnPropertyDescriptors(r)):t(Object(r)).forEach((function(t){Object.defineProperty(n,t,Object.getOwnPropertyDescriptor(r,t))}))}return n}function e(t){return(e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function r(t,n){if(!(t instanceof n))throw new TypeError("Cannot call a class as a function")}function i(t,n){for(var e=0;e<n.length;e++){var r=n[e];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function u(t,n,e){return n&&i(t.prototype,n),e&&i(t,e),t}function o(t,n,e){return n in t?Object.defineProperty(t,n,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[n]=e,t}function a(t){return(a=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}function l(t,n){return(l=Object.setPrototypeOf||function(t,n){return t.__proto__=n,t})(t,n)}function s(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}function f(t,n,e){return(f=s()?Reflect.construct:function(t,n,e){var r=[null];r.push.apply(r,n);var i=new(Function.bind.apply(t,r));return e&&l(i,e.prototype),i}).apply(null,arguments)}function c(t,n){if(n&&("object"==typeof n||"function"==typeof n))return n;if(void 0!==n)throw new TypeError("Derived constructors may only return object or undefined");return function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t)}function h(t,n,e){return(h="undefined"!=typeof Reflect&&Reflect.get?Reflect.get:function(t,n,e){var r=function(t,n){for(;!Object.prototype.hasOwnProperty.call(t,n)&&null!==(t=a(t)););return t}(t,n);if(r){var i=Object.getOwnPropertyDescriptor(r,n);return i.get?i.get.call(e):i.value}})(t,n,e||t)}function v(t){return function(t){if(Array.isArray(t))return y(t)}(t)||function(t){if("undefined"!=typeof Symbol&&null!=t[Symbol.iterator]||null!=t["@@iterator"])return Array.from(t)}(t)||function(t,n){if(!t)return;if("string"==typeof t)return y(t,n);var e=Object.prototype.toString.call(t).slice(8,-1);"Object"===e&&t.constructor&&(e=t.constructor.name);if("Map"===e||"Set"===e)return Array.from(t);if("Arguments"===e||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e))return y(t,n)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function y(t,n){(null==n||n>t.length)&&(n=t.length);for(var e=0,r=new Array(n);e<n;e++)r[e]=t[e];return r}Number.isInteger||(Number.isInteger=function(t){return"number"==typeof t&&isFinite(t)&&Math.floor(t)===t}),Number.EPSILON||(Number.EPSILON=2220446049250313e-31);var g=d(Math.pow(10,-6));function d(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:6;if(Number.isInteger(t))return t;var e=Math.pow(10,n);return Math.round((+t+Number.EPSILON)*e)/e}function p(t,n){var e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:g;return Math.abs(t-n)<e}var m=Math.PI/180;function b(t){return t}function w(t,n,e){var r=1-e;return 3*e*r*(t*r+n*e)+e*e*e}function x(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:1,r=arguments.length>3&&void 0!==arguments[3]?arguments[3]:1;return t<0||t>1||e<0||e>1?null:p(t,n)&&p(e,r)?b:function(i){if(i<=0)return t>0?i*n/t:0===n&&e>0?i*r/e:0;if(i>=1)return e<1?1+(i-1)*(r-1)/(e-1):1===e&&t<1?1+(i-1)*(n-1)/(t-1):1;for(var u,o=0,a=1;o<a;){var l=w(t,e,u=(o+a)/2);if(p(i,l))break;l<i?o=u:a=u}return w(n,r,u)}}function A(){return 1}function k(t){return 1===t?1:0}function _(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0;if(1===t){if(0===n)return k;if(1===n)return A}var e=1/t;return function(t){return t>=1?1:(t+=n*e)-t%e}}var S=Math.sin,O=Math.cos,j=Math.acos,M=Math.asin,P=Math.tan,E=Math.atan2,I=Math.PI/180,R=180/Math.PI,F=Math.sqrt,N=function(){function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,i=arguments.length>2&&void 0!==arguments[2]?arguments[2]:0,u=arguments.length>3&&void 0!==arguments[3]?arguments[3]:1,o=arguments.length>4&&void 0!==arguments[4]?arguments[4]:0,a=arguments.length>5&&void 0!==arguments[5]?arguments[5]:0;r(this,t),this.m=[n,e,i,u,o,a],this.i=null,this.w=null,this.s=null}return u(t,[{key:"determinant",get:function(){var t=this.m;return t[0]*t[3]-t[1]*t[2]}},{key:"isIdentity",get:function(){if(null===this.i){var t=this.m;this.i=1===t[0]&&0===t[1]&&0===t[2]&&1===t[3]&&0===t[4]&&0===t[5]}return this.i}},{key:"point",value:function(t,n){var e=this.m;return{x:e[0]*t+e[2]*n+e[4],y:e[1]*t+e[3]*n+e[5]}}},{key:"translateSelf",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0;if(!t&&!n)return this;var e=this.m;return e[4]+=e[0]*t+e[2]*n,e[5]+=e[1]*t+e[3]*n,this.w=this.s=this.i=null,this}},{key:"rotateSelf",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0;if(t%=360){var n=S(t*=I),e=O(t),r=this.m,i=r[0],u=r[1];r[0]=i*e+r[2]*n,r[1]=u*e+r[3]*n,r[2]=r[2]*e-i*n,r[3]=r[3]*e-u*n,this.w=this.s=this.i=null}return this}},{key:"scaleSelf",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:1;if(1!==t||1!==n){var e=this.m;e[0]*=t,e[1]*=t,e[2]*=n,e[3]*=n,this.w=this.s=this.i=null}return this}},{key:"skewSelf",value:function(t,n){if(n%=360,(t%=360)||n){var e=this.m,r=e[0],i=e[1],u=e[2],o=e[3];t&&(t=P(t*I),e[2]+=r*t,e[3]+=i*t),n&&(n=P(n*I),e[0]+=u*n,e[1]+=o*n),this.w=this.s=this.i=null}return this}},{key:"resetSelf",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:0,r=arguments.length>3&&void 0!==arguments[3]?arguments[3]:1,i=arguments.length>4&&void 0!==arguments[4]?arguments[4]:0,u=arguments.length>5&&void 0!==arguments[5]?arguments[5]:0,o=this.m;return o[0]=t,o[1]=n,o[2]=e,o[3]=r,o[4]=i,o[5]=u,this.w=this.s=this.i=null,this}},{key:"recomposeSelf",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,r=arguments.length>3&&void 0!==arguments[3]?arguments[3]:null,i=arguments.length>4&&void 0!==arguments[4]?arguments[4]:null;return this.isIdentity||this.resetSelf(),t&&(t.x||t.y)&&this.translateSelf(t.x,t.y),n&&this.rotateSelf(n),e&&(e.x&&this.skewSelf(e.x,0),e.y&&this.skewSelf(0,e.y)),!r||1===r.x&&1===r.y||this.scaleSelf(r.x,r.y),i&&(i.x||i.y)&&this.translateSelf(i.x,i.y),this}},{key:"decompose",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,e=this.m,r=e[0]*e[0]+e[1]*e[1],i=[[e[0],e[1]],[e[2],e[3]]],u=F(r);if(0===u)return{origin:{x:d(e[4]),y:d(e[5])},translate:{x:d(t),y:d(n)},scale:{x:0,y:0},skew:{x:0,y:0},rotate:0};i[0][0]/=u,i[0][1]/=u;var o=e[0]*e[3]-e[1]*e[2]<0;o&&(u=-u);var a=i[0][0]*i[1][0]+i[0][1]*i[1][1];i[1][0]-=i[0][0]*a,i[1][1]-=i[0][1]*a;var l=F(i[1][0]*i[1][0]+i[1][1]*i[1][1]);if(0===l)return{origin:{x:d(e[4]),y:d(e[5])},translate:{x:d(t),y:d(n)},scale:{x:d(u),y:0},skew:{x:0,y:0},rotate:0};i[1][0]/=l,i[1][1]/=l,a/=l;var s=0;return i[1][1]<0?(s=j(i[1][1])*R,i[0][1]<0&&(s=360-s)):s=M(i[0][1])*R,o&&(s=-s),a=E(a,F(i[0][0]*i[0][0]+i[0][1]*i[0][1]))*R,o&&(a=-a),{origin:{x:d(e[4]),y:d(e[5])},translate:{x:d(t),y:d(n)},scale:{x:d(u),y:d(l)},skew:{x:d(a),y:0},rotate:d(s)}}},{key:"clone",value:function(){var t=this.m;return new this.constructor(t[0],t[1],t[2],t[3],t[4],t[5])}},{key:"toString",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:" ";if(null===this.s){var n=this.m.map((function(t){return d(t)}));1===n[0]&&0===n[1]&&0===n[2]&&1===n[3]?this.s="translate("+n[4]+t+n[5]+")":this.s="matrix("+n.join(t)+")"}return this.s}}],[{key:"create",value:function(t){return t?Array.isArray(t)?f(this,v(t)):t instanceof this?t.clone():(new this).recomposeSelf(t.origin,t.rotate,t.skew,t.scale,t.translate):new this}}]),t}();function T(t,n,e){return t>=.5?e:n}function q(t,n,e){return 0===t||n===e?n:t*(e-n)+n}function B(t,n,e){var r=q(t,n,e);return r<=0?0:r}function L(t,n,e){var r=q(t,n,e);return r<=0?0:r>=1?1:r}function C(t,n,e){return 0===t?n:1===t?e:{x:q(t,n.x,e.x),y:q(t,n.y,e.y)}}function D(t,n,e){var r=function(t,n,e){return Math.round(q(t,n,e))}(t,n,e);return r<=0?0:r>=255?255:r}function z(t,n,e){return 0===t?n:1===t?e:{r:D(t,n.r,e.r),g:D(t,n.g,e.g),b:D(t,n.b,e.b),a:q(t,null==n.a?1:n.a,null==e.a?1:e.a)}}function V(t,n){for(var e=[],r=0;r<t;r++)e.push(n);return e}function G(t,n){if(--n<=0)return t;var e=(t=Object.assign([],t)).length;do{for(var r=0;r<e;r++)t.push(t[r])}while(--n>0);return t}var Y,$=function(){function t(n){r(this,t),this.list=n,this.length=n.length}return u(t,[{key:"setAttribute",value:function(t,n){for(var e=this.list,r=0;r<this.length;r++)e[r].setAttribute(t,n)}},{key:"removeAttribute",value:function(t){for(var n=this.list,e=0;e<this.length;e++)n[e].removeAttribute(t)}},{key:"style",value:function(t,n){for(var e=this.list,r=0;r<this.length;r++)e[r].style[t]=n}}]),t}(),U=/-./g,Q=function(t,n){return n.toUpperCase()};function H(t){return"function"==typeof t?t:T}function J(t){return t?"function"==typeof t?t:Array.isArray(t)?function(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:b;if(!Array.isArray(t))return n;switch(t.length){case 1:return _(t[0])||n;case 2:return _(t[0],t[1])||n;case 4:return x(t[0],t[1],t[2],t[3])||n}return n}(t,null):function(t,n){var e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:b;switch(t){case"linear":return b;case"steps":return _(n.steps||1,n.jump||0)||e;case"bezier":case"cubic-bezier":return x(n.x1||0,n.y1||0,n.x2||0,n.y2||0)||e}return e}(t.type,t.value,null):null}function Z(t,n,e){var r=arguments.length>3&&void 0!==arguments[3]&&arguments[3],i=n.length-1;if(t<=n[0].t)return r?[0,0,n[0].v]:n[0].v;if(t>=n[i].t)return r?[i,1,n[i].v]:n[i].v;var u,o=n[0],a=null;for(u=1;u<=i;u++){if(!(t>n[u].t)){a=n[u];break}o=n[u]}return null==a?r?[i,1,n[i].v]:n[i].v:o.t===a.t?r?[u,1,a.v]:a.v:(t=(t-o.t)/(a.t-o.t),o.e&&(t=o.e(t)),r?[u,t,e(t,o.v,a.v)]:e(t,o.v,a.v))}function K(t,n){var e=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null;return t&&t.length?"function"!=typeof n?null:("function"!=typeof e&&(e=null),function(r){var i=Z(r,t,n);return null!=i&&e&&(i=e(i)),i}):null}function W(t,n){return t.t-n.t}function X(t,n,r,i,u){var o,a="@"===r[0],l="#"===r[0],s=Y[r],f=T;switch(a?(o=r.substr(1),r=o.replace(U,Q)):l&&(r=r.substr(1)),e(s)){case"function":if(f=s(i,u,Z,J,r,a,n,t),l)return f;break;case"string":f=K(i,H(s));break;case"object":if((f=K(i,H(s.i),s.f))&&"function"==typeof s.u)return s.u(n,f,r,a,t)}return f?function(t,n,e){if(arguments.length>3&&void 0!==arguments[3]&&arguments[3])return t instanceof $?function(r){return t.style(n,e(r))}:function(r){return t.style[n]=e(r)};if(Array.isArray(n)){var r=n.length;return function(i){var u=e(i);if(null==u)for(var o=0;o<r;o++)t[o].removeAttribute(n);else for(var a=0;a<r;a++)t[a].setAttribute(n,u)}}return function(r){var i=e(r);null==i?t.removeAttribute(n):t.setAttribute(n,i)}}(n,r,f,a):null}function tt(t,n,r,i){if(!i||"object"!==e(i))return null;var u=null,o=null;return Array.isArray(i)?o=function(t){if(!t||!t.length)return null;for(var n=0;n<t.length;n++)t[n].e&&(t[n].e=J(t[n].e));return t.sort(W)}(i):(o=i.keys,u=i.data||null),o?X(t,n,r,o,u):null}function nt(t,n,e){if(!e)return null;var r=[];for(var i in e)if(e.hasOwnProperty(i)){var u=tt(t,n,i,e[i]);u&&r.push(u)}return r.length?r:null}function et(t,n){if(!n.settings.duration||n.settings.duration<0)return null;var e,r,i,u,o,a=function(t,n){if(!n)return null;var e=[];if(Array.isArray(n))for(var r=n.length,i=0;i<r;i++){var u=n[i];if(2===u.length){var o=null;if("string"==typeof u[0])o=t.getElementById(u[0]);else if(Array.isArray(u[0])){o=[];for(var a=0;a<u[0].length;a++)if("string"==typeof u[0][a]){var l=t.getElementById(u[0][a]);l&&o.push(l)}o=o.length?1===o.length?o[0]:new $(o):null}if(o){var s=nt(t,o,u[1]);s&&(e=e.concat(s))}}}else for(var f in n)if(n.hasOwnProperty(f)){var c=t.getElementById(f);if(c){var h=nt(t,c,n[f]);h&&(e=e.concat(h))}}return e.length?e:null}(t,n.elements);return a?(e=a,r=n.settings,i=r.duration,u=e.length,o=null,function(t,n){var a=r.iterations||1/0,l=(r.alternate&&a%2==0)^r.direction>0?i:0,s=t%i,f=1+(t-s)/i;n*=r.direction,r.alternate&&f%2==0&&(n=-n);var c=!1;if(f>a)s=l,c=!0,-1===r.fill&&(s=r.direction>0?0:i);else if(n<0&&(s=i-s),s===o)return!1;o=s;for(var h=0;h<u;h++)e[h](s);return c}):null}function rt(t,n){if(Y=n,!t||!t.root||!Array.isArray(t.animations))return null;var e=function(t){for(var n=document.getElementsByTagName("svg"),e=0;e<n.length;e++)if(n[e].id===t.root&&!n[e].svgatorAnimation)return n[e].svgatorAnimation=!0,n[e];return null}(t);if(!e)return null;var r=t.animations.map((function(t){return et(e,t)})).filter((function(t){return!!t}));return r.length?{svg:e,animations:r,animationSettings:t.animationSettings,options:t.options||void 0}:null}function it(t){return+("0x"+(t.replace(/[^0-9a-fA-F]+/g,"")||27))}function ut(t,n,e){return!t||!e||n>t.length?t:t.substring(0,n)+ut(t.substring(n+1),e,e)}function ot(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:27;return!t||t%n?t%n:ot(t/n,n)}function at(t,n,e){if(t&&t.length){var r=it(e),i=it(n),u=ot(r)+5,o=ut(t,ot(r,5),u);return o=o.replace(/\x7c$/g,"==").replace(/\x2f$/g,"="),o=function(t,n,e){var r=+("0x"+t.substring(0,4));t=t.substring(4);for(var i=n%r+e%27,u=[],o=0;o<t.length;o+=2)if("|"!==t[o]){var a=+("0x"+t[o]+t[o+1])-i;u.push(a)}else{var l=+("0x"+t.substring(o+1,o+1+4))-i;o+=3,u.push(l)}return String.fromCharCode.apply(String,u)}(o=(o=atob(o)).replace(/[\x41-\x5A]/g,""),i,r),o=JSON.parse(o)}}var lt=[{key:"alternate",def:!1},{key:"fill",def:1},{key:"iterations",def:0},{key:"direction",def:1},{key:"speed",def:1},{key:"fps",def:100}],st=function(){function t(n,e){var i=this,u=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null;r(this,t),this._id=0,this._running=!1,this._rollingBack=!1,this._animations=n,this._settings=e,(!u||u<"2022-05-02")&&delete this._settings.speed,lt.forEach((function(t){i._settings[t.key]=i._settings[t.key]||t.def})),this.duration=e.duration,this.offset=e.offset||0,this.rollbackStartOffset=0}return u(t,[{key:"alternate",get:function(){return this._settings.alternate}},{key:"fill",get:function(){return this._settings.fill}},{key:"iterations",get:function(){return this._settings.iterations}},{key:"direction",get:function(){return this._settings.direction}},{key:"speed",get:function(){return this._settings.speed}},{key:"fps",get:function(){return this._settings.fps}},{key:"maxFiniteDuration",get:function(){return this.iterations>0?this.iterations*this.duration:this.duration}},{key:"_apply",value:function(t){for(var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},e=this._animations,r=e.length,i=0,u=0;u<r;u++)n[u]?i++:(n[u]=e[u](t,1),n[u]&&i++);return i}},{key:"_rollback",value:function(t){var n=this,e=1/0,r=null;this.rollbackStartOffset=t,this._rollingBack=!0,this._running=!0;this._id=window.requestAnimationFrame((function i(u){if(n._rollingBack){null==r&&(r=u);var o=Math.round(t-(u-r)*n.speed);if(o>n.duration&&e!==1/0){var a=!!n.alternate&&o/n.duration%2>1,l=o%n.duration;o=(l+=a?n.duration:0)||n.duration}var s=(n.fps?1e3/n.fps:0)*n.speed,f=Math.max(0,o);f<=e-s&&(n.offset=f,e=f,n._apply(f));var c=n.iterations>0&&-1===n.fill&&o>=n.maxFiniteDuration;(o<=0||n.offset<o||c)&&n.stop(),n._id=window.requestAnimationFrame(i)}}))}},{key:"_start",value:function(){var t=this,n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,e=-1/0,r=null,i={};this._running=!0;var u=function u(o){null==r&&(r=o);var a=Math.round((o-r)*t.speed+n),l=(t.fps?1e3/t.fps:0)*t.speed;if(a>=e+l&&!t._rollingBack&&(t.offset=a,e=a,t._apply(a,i)===t._animations.length))return void t.pause(!0);t._id=window.requestAnimationFrame(u)};this._id=window.requestAnimationFrame(u)}},{key:"_pause",value:function(){this._id&&window.cancelAnimationFrame(this._id),this._running=!1}},{key:"play",value:function(){if(!this._running)return this._rollingBack?this._rollback(this.offset):this._start(this.offset)}},{key:"stop",value:function(){this._pause(),this.offset=0,this.rollbackStartOffset=0,this._rollingBack=!1,this._apply(0)}},{key:"reachedToEnd",value:function(){return this.iterations>0&&this.offset>=this.iterations*this.duration}},{key:"restart",value:function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];this.stop(t),this.play(t)}},{key:"pause",value:function(){this._pause()}},{key:"reverse",value:function(){this.direction=-this.direction}}],[{key:"build",value:function(t,n){delete t.animationSettings,t.options=at(t.options,t.root,"5c7f360c"),t.animations.map((function(n){n.settings=at(n.s,t.root,"5c7f360c"),delete n.s,t.animationSettings||(t.animationSettings=n.settings)}));var e=t.version;if(!(t=rt(t,n)))return null;var r=t.options||{},i=new this(t.animations,t.animationSettings,e);return{el:t.svg,options:r,player:i}}},{key:"push",value:function(t){return this.build(t)}},{key:"init",value:function(){var t=this,n=window.__SVGATOR_PLAYER__&&window.__SVGATOR_PLAYER__["5c7f360c"];Array.isArray(n)&&n.splice(0).forEach((function(n){return t.build(n)}))}}]),t}();function ft(t){return d(t)+""}function ct(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:" ";return t&&t.length?t.map(ft).join(n):""}function ht(t){if(!t)return"transparent";if(null==t.a||t.a>=1){var n=function(t){return 1===(t=parseInt(t).toString(16)).length?"0"+t:t},e=function(t){return t.charAt(0)===t.charAt(1)},r=n(t.r),i=n(t.g),u=n(t.b);return e(r)&&e(i)&&e(u)&&(r=r.charAt(0),i=i.charAt(0),u=u.charAt(0)),"#"+r+i+u}return"rgba("+t.r+","+t.g+","+t.b+","+t.a+")"}function vt(t){return t?"url(#"+t+")":"none"}!function(){for(var t=0,n=["ms","moz","webkit","o"],e=0;e<n.length&&!window.requestAnimationFrame;++e)window.requestAnimationFrame=window[n[e]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[n[e]+"CancelAnimationFrame"]||window[n[e]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(n){var e=Date.now(),r=Math.max(0,16-(e-t)),i=window.setTimeout((function(){n(e+r)}),r);return t=e+r,i},window.cancelAnimationFrame=window.clearTimeout)}();var yt={f:null,i:function(t,n,e){return 0===t?n:1===t?e:{x:B(t,n.x,e.x),y:B(t,n.y,e.y)}},u:function(t,n){return function(e){var r=n(e);t.setAttribute("rx",ft(r.x)),t.setAttribute("ry",ft(r.y))}}},gt={f:null,i:function(t,n,e){return 0===t?n:1===t?e:{width:B(t,n.width,e.width),height:B(t,n.height,e.height)}},u:function(t,n){return function(e){var r=n(e);t.setAttribute("width",ft(r.width)),t.setAttribute("height",ft(r.height))}}};Object.freeze({M:2,L:2,Z:0,H:1,V:1,C:6,Q:4,T:2,S:4,A:7});var dt={},pt=null;function mt(t){var n=function(){if(pt)return pt;if("object"!==("undefined"==typeof document?"undefined":e(document))||!document.createElementNS)return{};var t=document.createElementNS("http://www.w3.org/2000/svg","svg");return t&&t.style?(t.style.position="absolute",t.style.opacity="0.01",t.style.zIndex="-9999",t.style.left="-9999px",t.style.width="1px",t.style.height="1px",pt={svg:t}):{}}().svg;if(!n)return function(t){return null};var r=document.createElementNS(n.namespaceURI,"path");r.setAttributeNS(null,"d",t),r.setAttributeNS(null,"fill","none"),r.setAttributeNS(null,"stroke","none"),n.appendChild(r);var i=r.getTotalLength();return function(t){var n=r.getPointAtLength(i*t);return{x:n.x,y:n.y}}}function bt(t){return dt[t]?dt[t]:dt[t]=mt(t)}function wt(t,n,e,r){if(!t||!r)return!1;var i=["M",t.x,t.y];if(n&&e&&(i.push("C"),i.push(n.x),i.push(n.y),i.push(e.x),i.push(e.y)),n?!e:e){var u=n||e;i.push("Q"),i.push(u.x),i.push(u.y)}return n||e||i.push("L"),i.push(r.x),i.push(r.y),i.join(" ")}function xt(t,n,e,r){var i=arguments.length>4&&void 0!==arguments[4]?arguments[4]:1,u=wt(t,n,e,r),o=bt(u);try{return o(i)}catch(t){return null}}function At(t,n,e){return t+(n-t)*e}function kt(t,n,e){var r=arguments.length>3&&void 0!==arguments[3]&&arguments[3],i={x:At(t.x,n.x,e),y:At(t.y,n.y,e)};return r&&(i.a=_t(t,n)),i}function _t(t,n){return Math.atan2(n.y-t.y,n.x-t.x)}function St(t,n,e,r){var i=1-r;return i*i*t+2*i*r*n+r*r*e}function Ot(t,n,e,r){return 2*(1-r)*(n-t)+2*r*(e-n)}function jt(t,n,e,r){var i=arguments.length>4&&void 0!==arguments[4]&&arguments[4],u=xt(t,n,null,e,r);return u||(u={x:St(t.x,n.x,e.x,r),y:St(t.y,n.y,e.y,r)}),i&&(u.a=Mt(t,n,e,r)),u}function Mt(t,n,e,r){return Math.atan2(Ot(t.y,n.y,e.y,r),Ot(t.x,n.x,e.x,r))}function Pt(t,n,e,r,i){var u=i*i;return i*u*(r-t+3*(n-e))+3*u*(t+e-2*n)+3*i*(n-t)+t}function Et(t,n,e,r,i){var u=1-i;return 3*(u*u*(n-t)+2*u*i*(e-n)+i*i*(r-e))}function It(t,n,e,r,i){var u=arguments.length>5&&void 0!==arguments[5]&&arguments[5],o=xt(t,n,e,r,i);return o||(o={x:Pt(t.x,n.x,e.x,r.x,i),y:Pt(t.y,n.y,e.y,r.y,i)}),u&&(o.a=Rt(t,n,e,r,i)),o}function Rt(t,n,e,r,i){return Math.atan2(Et(t.y,n.y,e.y,r.y,i),Et(t.x,n.x,e.x,r.x,i))}function Ft(t,n,e){var r=arguments.length>3&&void 0!==arguments[3]&&arguments[3];if(Tt(n)){if(qt(e))return jt(n,e.start,e,t,r)}else if(Tt(e)){if(Bt(n))return jt(n,n.end,e,t,r)}else{if(Bt(n))return qt(e)?It(n,n.end,e.start,e,t,r):jt(n,n.end,e,t,r);if(qt(e))return jt(n,e.start,e,t,r)}return kt(n,e,t,r)}function Nt(t,n,e){var r=Ft(t,n,e,!0);return r.a=function(t){return arguments.length>1&&void 0!==arguments[1]&&arguments[1]?t+Math.PI:t}(r.a)/m,r}function Tt(t){return!t.type||"corner"===t.type}function qt(t){return null!=t.start&&!Tt(t)}function Bt(t){return null!=t.end&&!Tt(t)}var Lt=new N;var Ct={f:ft,i:q},Dt={f:ft,i:L};function zt(t,n,e){return t.map((function(t){return function(t,n,e){var r=t.v;if(!r||"g"!==r.t||r.s||!r.v||!r.r)return t;var i=e.getElementById(r.r),u=i&&i.querySelectorAll("stop")||[];return r.s=r.v.map((function(t,n){var e=u[n]&&u[n].getAttribute("offset");return{c:t,o:e=d(parseInt(e)/100)}})),delete r.v,t}(t,0,e)}))}var Vt={gt:"gradientTransform",c:{x:"cx",y:"cy"},rd:"r",f:{x:"x1",y:"y1"},to:{x:"x2",y:"y2"}};function Gt(t,n,r,i,u,o,a,l){return zt(t,0,l),n=function(t,n,e){for(var r,i,u,o=t.length-1,a={},l=0;l<=o;l++)(r=t[l]).e&&(r.e=n(r.e)),r.v&&"g"===(i=r.v).t&&i.r&&(u=e.getElementById(i.r))&&(a[i.r]={e:u,s:u.querySelectorAll("stop")});return a}(t,i,l),function(i){var u=r(i,t,Yt);if(!u)return"none";if("c"===u.t)return ht(u.v);if("g"===u.t){if(!n[u.r])return vt(u.r);var o=n[u.r];return function(t,n){for(var e=t.s,r=e.length;r<n.length;r++){var i=e[e.length-1].cloneNode();i.id=Qt(i.id),t.e.appendChild(i),e=t.s=t.e.querySelectorAll("stop")}for(var u=0,o=e.length,a=n.length-1;u<o;u++)e[u].setAttribute("stop-color",ht(n[Math.min(u,a)].c)),e[u].setAttribute("offset",n[Math.min(u,a)].o)}(o,u.s),Object.keys(Vt).forEach((function(t){if(void 0!==u[t])if("object"!==e(Vt[t])){var n,r="gt"===t?(n=u[t],Array.isArray(n)?"matrix("+n.join(" ")+")":""):u[t],i=Vt[t];o.e.setAttribute(i,r)}else Object.keys(Vt[t]).forEach((function(n){if(void 0!==u[t][n]){var e=u[t][n],r=Vt[t][n];o.e.setAttribute(r,e)}}))})),vt(u.r)}return"none"}}function Yt(t,e,r){if(0===t)return e;if(1===t)return r;if(e&&r){var i=e.t;if(i===r.t)switch(e.t){case"c":return{t:i,v:z(t,e.v,r.v)};case"g":if(e.r===r.r){var u={t:i,s:$t(t,e.s,r.s),r:e.r};return e.gt&&r.gt&&(u.gt=function(t,n,e){var r=n.length;if(r!==e.length)return T(t,n,e);for(var i=new Array(r),u=0;u<r;u++)i[u]=q(t,n[u],e[u]);return i}(t,e.gt,r.gt)),e.c?(u.c=C(t,e.c,r.c),u.rd=B(t,e.rd,r.rd)):e.f&&(u.f=C(t,e.f,r.f),u.to=C(t,e.to,r.to)),u}}if("c"===e.t&&"g"===r.t||"c"===r.t&&"g"===e.t){var o="c"===e.t?e:r,a="g"===e.t?n({},e):n({},r),l=a.s.map((function(t){return{c:o.v,o:t.o}}));return a.s="c"===e.t?$t(t,l,a.s):$t(t,a.s,l),a}}return T(t,e,r)}function $t(t,n,e){if(n.length===e.length)return n.map((function(n,r){return Ut(t,n,e[r])}));for(var r=Math.max(n.length,e.length),i=[],u=0;u<r;u++){var o=Ut(t,n[Math.min(u,n.length-1)],e[Math.min(u,e.length-1)]);i.push(o)}return i}function Ut(t,n,e){return{o:L(t,n.o,e.o||0),c:z(t,n.c,e.c||{})}}function Qt(t){return t.replace(/-fill-([0-9]+)$/,(function(t,n){return"-fill-"+(+n+1)}))}var Ht={fill:Gt,"fill-opacity":Dt,stroke:Gt,"stroke-opacity":Dt,"stroke-width":Ct,"stroke-dashoffset":{f:ft,i:q},"stroke-dasharray":{f:function(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:" ";return t&&t.length>0&&(t=t.map((function(t){return d(t,4)}))),ct(t,n)},i:function(t,n,e){var r,i,u,o=n.length,a=e.length;if(o!==a)if(0===o)n=V(o=a,0);else if(0===a)a=o,e=V(o,0);else{var l=(u=(r=o)*(i=a)/function(t,n){for(var e;n;)e=n,n=t%n,t=e;return t||1}(r,i))<0?-u:u;n=G(n,Math.floor(l/o)),e=G(e,Math.floor(l/a)),o=a=l}for(var s=[],f=0;f<o;f++)s.push(d(B(t,n[f],e[f])));return s}},opacity:Dt,transform:function(t,n,r,i){if(!(t=function(t,n){if(!t||"object"!==e(t))return null;var r=!1;for(var i in t)t.hasOwnProperty(i)&&(t[i]&&t[i].length?(t[i].forEach((function(t){t.e&&(t.e=n(t.e))})),r=!0):delete t[i]);return r?t:null}(t,i)))return null;var u=function(e,i,u){var o=arguments.length>3&&void 0!==arguments[3]?arguments[3]:null;return t[e]?r(i,t[e],u):n&&n[e]?n[e]:o};return n&&n.a&&t.o?function(n){var e=r(n,t.o,Nt);return Lt.recomposeSelf(e,u("r",n,q,0)+e.a,u("k",n,C),u("s",n,C),u("t",n,C)).toString()}:function(t){return Lt.recomposeSelf(u("o",t,Ft,null),u("r",t,q,0),u("k",t,C),u("s",t,C),u("t",t,C)).toString()}},r:Ct,"#size":gt,"#radius":yt,_:function(t,n){if(Array.isArray(t))for(var e=0;e<t.length;e++)this[t[e]]=n;else this[t]=n}},Jt=function(t){!function(t,n){if("function"!=typeof n&&null!==n)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(n&&n.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),n&&l(t,n)}(o,t);var n,e,i=(n=o,e=s(),function(){var t,r=a(n);if(e){var i=a(this).constructor;t=Reflect.construct(r,arguments,i)}else t=r.apply(this,arguments);return c(this,t)});function o(){return r(this,o),i.apply(this,arguments)}return u(o,null,[{key:"build",value:function(t){var n=h(a(o),"build",this).call(this,t,Ht);if(!n)return null;n.el,n.options,function(t,n,e){t.play()}(n.player)}}]),o}(st);return Jt.init(),Jt}));
(function(s,i,o,w,a,b){w[o]=w[o]||{};w[o][s]=w[o][s]||[];w[o][s].push(i);})('5c7f360c',{"root":"eTs4f9PrBm11","version":"2022-05-04","animations":[{"elements":{"eTs4f9PrBm12":{"transform":{"data":{"r":-180},"keys":{"s":[{"t":0,"v":{"x":-0.08,"y":0.08}},{"t":300,"v":{"x":-0.1,"y":0.1}}]}},"opacity":[{"t":0,"v":1,"e":[0.645,0.045,0.355,1]},{"t":500,"v":0.75,"e":[0.645,0.045,0.355,1]},{"t":1000,"v":1,"e":[0.85,0.14,0.215,0.865]}]}},"s":"MHDA1ZGMxNjhhYWJiYjIhhN2JhYWZiNWI0NjhSOODA3Nzc2NzY3NjcyTNjhhYWFmYjhhYmE5YWmFhZmI1YjRHNjg4MDDc3RjcyNjhhZlBiYWFBiYjhhN2JhYWZiNWI0NYjk2ODgwNzZLNzI2OXFhhY2FmYjJiMjY4ODUA3NzcyNjhhN2IyYmFWhYmI4YjRhN2JhYWI2NODgwSmFjYTdiMmI5YUWI3MlY2OGI5RWI2YWCJhYmFhNjg4MDc3NzIP2OGFjYjZiOTY4ODA3KNzc2NzZjMw|"}],"options":"MSDAxMDg4MmZPODA4MUXk2ZTdmODEyZjQ3MmYI3OTdjNmU3MTJmOGE/C"},'__SVGATOR_PLAYER__',window)
]]></script>
</svg>

  <div class="blackribbonbot"> </div>
</div>

<script>

  document.onreadystatechange = function showPage() {
  if(document.readyState == "loading"){
    document.getElementById("loader").style.display = "block";
    document.getElementById("myDiv").style.display = "none";
  }else{
    document.getElementById("loader").style.display = "none";
    document.getElementById("myDiv").style.display = "block";
  }
}



</script>


<div style="display:none;" id="myDiv">

<?php include 'header.php';?>

<!-- <script>
  const el = document.querySelector(".sticky_detection")

  const header = document.querySelector(".header")
  const logo = document.querySelector(".logo")

  const observer = new IntersectionObserver( 
    ([e]) => e.target.classList.toggle("isSticky", e.intersectionRatio < 1),
    { threshold: [1] }
  );

  const logoObserver = new IntersectionObserver( 
    ([e]) => e.target.style.display = 'none'
  );

  observer.observe(el);
  logoObserver.observe(logo);
</script>
-->
<!-- <script>
  const el = document.querySelector(".sticky_detection")

  const observer = new IntersectionObserver( 
    ([e]) => e.target.classList.toggle("isSticky", e.intersectionRatio < 1),
    { threshold: [1] }
  );

  observer.observe(el);
</script> -->

  <!--
  <div id="carrousel">
      <div class="hideLeft">
       <img src="https://i1.sndcdn.com/artworks-000165384395-rhrjdn-t500x500.jpg">
     </div>

     <div class="prevLeftSecond">
       <img src="https://i1.sndcdn.com/artworks-000185743981-tuesoj-t500x500.jpg">
     </div>

     <div class="prev">
       <img src="https://i1.sndcdn.com/artworks-000158708482-k160g1-t500x500.jpg">
     </div>

     <div class="selected">
       <img src="https://i1.sndcdn.com/artworks-000062423439-lf7ll2-t500x500.jpg">
     </div>

     <div class="next">
       <img src="https://i1.sndcdn.com/artworks-000028787381-1vad7y-t500x500.jpg">
     </div>

     <div class="nextRightSecond">
       <img src="https://i1.sndcdn.com/artworks-000108468163-dp0b6y-t500x500.jpg">
     </div>

     <div class="hideRight">
       <img src="https://i1.sndcdn.com/artworks-000064920701-xrez5z-t500x500.jpg">
     </div>

   </div>
 -->

  <div class="icon_container admin <?= ($estAdmin == true) ? "" : "not_admin" ?>">
    <!-- <a onclick="passwordPrompt()"> -->
    <a href="<?= url_to('AdminController::adminView') ?>">
      <div class="icon_logo_background">

        <img class="icon_logo" src="<?= site_url() . "images/icons/admin.png" ?>">
        <img class="hover_image" src="<?= site_url() . "images/icons/admin_blanc.png" ?>">
      </div>
    </a>

    <!-- <a onclick="passwordPrompt()"> -->
    <a href="<?= url_to('AdminController::adminView') ?>">
      <h3 class="underline_animation">Admin</h3>
    </a>
  </div>


 <div class="carrousel">
  <a onclick="PreviousClicked()">
    <div class="carrousel_prev carrousel_button">
            <span class="arrowicon_carroussel">
                <i class="arrow_carroussel left"></i>
            </span>
    </div>
  </a>
  
  <div class="carrousel_images">
    <a href="<?= url_to('Product::display', $carrouselProduits[0]) ?>">
      <img id="carrousel_previous_button" class="previous" src="<?= $carrouselImages[0] ?>">
    </a>
    
    <a href="<?= url_to('Product::display', $carrouselProduits[1]) ?>">
      <img class="current" src="<?= $carrouselImages[1] ?>">
    </a>
  
    <a href="<?= url_to('Product::display', $carrouselProduits[2]) ?>">
      <img id="carrousel_next_button" class="next" src="<?= $carrouselImages[2] ?>">
    </a>
  </div>

  <a onclick="NextClicked()">
    <div class="carrousel_next carrousel_button">
            <span class="arrowicon_carroussel">
                <i class="arrow_carroussel right"></i>
            </span>
    </div>
  </a>
</div>


<div class="categories">
<div class="sweats">
  <a href="<?= url_to('Product::trouverToutDeCategorie', 'sweat') ?>"> 
    <div class="sweats_image">
      <img src="<?= site_url() . "images/home/categories/sweat.png" ?>" alt="Sweats">
    </div>
  </a>
  
  <h2>Sweats</h2>
</div>

 <div class="tshirts">
   <a href="<?= url_to('Product::trouverToutDeCategorie', 'tshirt') ?>"> 
  <div class="tshirts_image">
   <img src="<?= site_url() . "images/home/categories/tshirt.png" ?>" alt="T-shirts">
  </div>
  </a>
  <h2>T-shirts</h2>
 </div>

 <div class="pantalons">
   <a href="<?= url_to('Product::trouverToutDeCategorie', 'pantalon') ?>"> 
  <div class="pantalons_image">
   <img src="<?= site_url() . "images/home/categories/pantalon.png" ?>" alt="Pantalons">
  </div>
  </a>
  <h2>Pantalons</h2>
 </div>

 <div class="accessoires">
 <a href="<?= url_to('Product::trouverToutDeCategorie', 'accessoire') ?>"> 
  <div class="accessoires_image">
   <img src="<?= site_url() . "images/home/categories/accessoire.jpeg" ?>" alt="Accessoires">
  </div>
  </a>
  <h2>Accessoires</h2>
 </div>

 <div class="posters">
 <a href="<?= url_to('Product::trouverToutDeCategorie', 'poster') ?>"> 
  <div class="posters_image">
   <img src="<?= site_url() . "images/home/categories/poster.jpg" ?>" alt="Posters">
  </div>
  </a>
  <h2>Posters</h2>
 </div>
</div>


<div class="populaires_container">
 <h2>Les plus populaires</h2>

 <div class="populaires">

  <?php foreach($produitsPlusPopulaires as $produit) : ?>
    <div>

      <a href="<?= url_to('Product::display', $produit->id_produit) ?>">
        <img src="<?= $produitsPlusVendusImages[$produit->id_produit]?> ">
      </a>

     <div class="populaires_details">
      <h3><?= $produit->nom ?></h3>

      <a href="<?= url_to('Product::display', $produit->id_produit) ?>">
        <div>ACHETER</div>
      </a>
     </div>
    </div>
  <?php endforeach; ?>
 </div>
</div>


<div class="collection_container">
 <h2>Notre dernière collection</h2>

 <div class="collection">
  
  <!-- <script>
    var element = document.getElementById('collection');
    console.log(element.clientWidth)
    element.clientHeight = element.clientWidth;
    // element.style.height = element.clientWidth;
    console.log(element.style.height)
    console.log(element.clientHeight)
  </script> -->

  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 1; grid-row: 1 / 3"><div class="one"></div></a>
  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 2 / 4; grid-row: 1 / 3"><div class="two"></div></a>
  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 4 / 5; grid-row: 1"><div class="three"></div></a>
  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 5 / 6; grid-row: 1 / 3"><div class="four"></div></a>
  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 1 / 3; grid-row: 3"><div class="five"></div></a>
  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 3 / 4; grid-row: 3"><div class="six"></div></a>
  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 4 / 5; grid-row: 2 / 4"><div class="seven"></div></a>
  <a href="<?= url_to('Product::display', '1') ?>" style="grid-column: 5 / 6; grid-row: 3"><div class="eight"></div></a>
 </div>
</div>
<?php include 'footer.php';?>
</div>
</body>
</html>
