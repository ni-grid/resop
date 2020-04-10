import '../css/app.scss';
import '../css/availability-form.scss';
import '../css/planning.scss';
import '../css/availability-table.scss';

const $ = require('jquery');
require('bootstrap');
require('bootstrap-select');
require('daterangepicker');

$.fn.selectpicker.Constructor.DEFAULTS.noneSelectedText = '-';
$.fn.selectpicker.Constructor.DEFAULTS.noneResultsText = 'Aucun résultat pour {0}';
$.fn.selectpicker.Constructor.DEFAULTS.selectAllText = 'Tout sélectionner';
$.fn.selectpicker.Constructor.DEFAULTS.deselectAllText = 'Tout déselectionner';
$.fn.selectpicker.Constructor.DEFAULTS.doneButtonText = 'Fermer';
$.fn.selectpicker.Constructor.DEFAULTS.mobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);

var browserUpdate = require('browser-update');
browserUpdate({ required: { e: -2, f: -2, o: -2, s: -2, c: -2 }, insecure: true, unsupported: true, api: 2020.04 });
