jQuery.fn.shortkeys = jQuery.fn.keys = function (obj, settings) {		
	var el = this;
	this.settings = jQuery.extend({
			split: "+",
			moreKeys: {}			
		}, settings || {});	
	this.wackyKeys = { '.': 190, ',': 188, ';': 59,	'Space': 32	};	
	this.formElements  = new Array("input", "select", "textarea", "button");
	this.keys = new Array();	
	this.onFormElement = false;
	this.keysDown = new Array();
	this.init = function (obj) {
		for(x in this.wackyKeys) {
			this.wackyKeys[x.toUpperCase()] = this.wackyKeys[x];
		}
		for(x in obj) {
			this.keys.push(x.split(this.settings.split));
		}
		for(i in this.keys) {
			var quickArr = new Array();
			for(j in this.keys[i]) {
				quickArr.push(this.convertToNumbers(this.keys[i][j].toUpperCase()));
			}
			quickArr.sort();
			this.keys[i] = quickArr;
		}
	};	
	this.convertToNumbers = function (inp) {
		if (this.wackyKeys[inp] != undefined) {
			return this.wackyKeys[inp];
		}
		return inp.toUpperCase().charCodeAt(0);
	};	
	this.keyAdd = function(keyCode) {
		this.keysDown.push(keyCode);
		this.keysDown.sort();
	};
	this.keyRemove = function (keyCode) {
		for(i in this.keysDown) {
			if(this.keysDown[i] == keyCode) {
				this.keysDown.splice(i,1);
			}
		};	
		this.keysDown.sort();	
	};		
	this.keyTest = function (i) {
		if (this.keys[i].length != this.keysDown.length) return false;
		for(j in this.keys[i]) {
			if(this.keys[i][j] != this.keysDown[j]) {
				return false;
			}
		}	
		return true;
	};
	this.keyRemoveAll = function () {
		this.keysDown = new Array();	
	};
	this.focused = function (bool) {
		this.onFormElement = bool;
	}	
	$(document).keydown(function(e) {
		el.keyAdd(e.keyCode);
		var i = 0;
		for(x in obj) {
			if(el.keyTest(i) && !el.onFormElement) {
				obj[x]();
				return false;
				break;	
			}			
			i++;
		};	
	});	
	$(document).keyup(function (e) {
		el.keyRemove(e.keyCode);
	});	
	for(x in this.formElements) {
		$(this.formElements[x]).focus( function () {
			el.focused(true);
		});
		$(this.formElements[x]).blur( function () {
			el.focused(false);
		});
	}	
	$(document).focus( function () {
		el.keyRemoveAll();
	});
	
	this.init(obj);
	jQuery.extend(this.wackyKeys, this.settings.moreKeys);

	return this;
}