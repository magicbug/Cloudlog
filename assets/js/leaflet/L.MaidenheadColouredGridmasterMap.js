/*
 * L.Maidenhead displays a Maidenhead Locator of lines on the map.
 */

L.Maidenhead = L.LayerGroup.extend({


	options: {
		// Line and label color
		color: 'rgba(255, 0, 0, 0.4)',

		// Redraw on move or moveend
		redraw: 'move'
	},

	initialize: function (options) {
		L.LayerGroup.prototype.initialize.call(this);
		L.Util.setOptions(this, options);
	},

	onAdd: function (map) {
		this._map = map;
		var grid = this.redraw();
		this._map.on('viewreset '+ this.options.redraw, function () {
			grid.redraw();
		});

		this.eachLayer(map.addLayer, map);
	},

	onRemove: function (map) {
		// remove layer listeners and elements
		map.off('viewreset '+ this.options.redraw, this.map);
		this.eachLayer(this.removeLayer, this);
	},

	redraw: function () {
		var d3 = new Array(20, 10, 10, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
		var lat_cor = new Array(0, 8, 8, 8, 2.5, 2.2, 6, 8, 8, 8, 8, 8, 8); // Used for gridsquare text offset
		var bounds = map.getBounds();
		var zoom = map.getZoom();
		var unit = d3[zoom];
		var lcor = lat_cor[zoom];
		var w = bounds.getWest();
		var e = bounds.getEast();
		var n = bounds.getNorth();
		var s = bounds.getSouth();
		var c = 0.2;
		if (n > 85) n = 85;
		if (s < -85) s = -85;
		var left = Math.floor(w/(unit*2))*(unit*2);
		var right = Math.ceil(e/(unit*2))*(unit*2);
		var top = Math.ceil(n/unit)*unit;
		var bottom = Math.floor(s/unit)*unit;
		this.eachLayer(this.removeLayer, this);
		const grids_unworked = ['EN29', 'CN78', 'CN88', 'CN98', 'DN08', 'DN18', 'DN28', 'DN38', 'DN48', 'DN58', 'DN68', 'DN78', 'DN88', 'DN98',
			'EN08', 'EN18', 'EN28', 'EN38', 'EN48', 'EN58', 'CN77', 'CN87', 'CN97', 'DN07', 'DN17', 'DN27', 'DN37', 'DN47', 'DN57', 'DN67',
			'DN77', 'DN87', 'DN97', 'EN07', 'EN17', 'EN27', 'EN37', 'EN47', 'EN57', 'EN67', 'FN57', 'FN67', 'CN76', 'CN86', 'CN96', 'DN06',
			'DN16', 'DN26', 'DN36', 'DN46', 'DN56', 'DN66', 'DN76', 'DN86', 'DN96', 'EN06', 'EN16', 'EN26', 'EN36', 'EN46', 'EN56', 'EN66',
			'EN76', 'EN86', 'FN46', 'FN56', 'FN66', 'CN75', 'CN85', 'CN95', 'DN05', 'DN15', 'DN25', 'DN35', 'DN45', 'DN55', 'DN65', 'DN75',
			'DN85', 'DN95', 'EN05', 'EN15', 'EN25', 'EN35', 'EN45', 'EN55', 'EN65', 'EN75', 'EN85', 'FN25', 'FN35', 'FN45', 'FN55', 'FN65',
			'CN74', 'CN84', 'CN94', 'DN04', 'DN14', 'DN24', 'DN34', 'DN44', 'DN54', 'DN64', 'DN74', 'DN84', 'DN94', 'EN04', 'EN14', 'EN24',
			'EN34', 'EN44', 'EN54', 'EN64', 'EN74', 'EN84', 'FN14', 'FN24', 'FN34', 'FN44', 'FN54', 'FN64', 'CN73', 'CN83', 'CN93', 'DN03',
			'DN13', 'DN23', 'DN33', 'DN43', 'DN53', 'DN63', 'DN73', 'DN83', 'DN93', 'EN03', 'EN13', 'EN23', 'EN33', 'EN43', 'EN53', 'EN63',
			'EN73', 'EN83', 'FN03', 'FN13', 'FN23', 'FN33', 'FN43', 'FN53', 'CN72', 'CN82', 'CN92', 'DN02', 'DN12', 'DN22', 'DN32', 'DN42',
			'DN52', 'DN62', 'DN72', 'DN82', 'DN92', 'EN02', 'EN12', 'EN22', 'EN32', 'EN42', 'EN52', 'EN62', 'EN72', 'EN82', 'EN92', 'FN02',
			'FN12', 'FN22', 'FN32', 'FN42', 'CN71', 'CN81', 'CN91', 'DN01', 'DN11', 'DN21', 'DN31', 'DN41', 'DN51', 'DN61', 'DN71', 'DN81',
			'DN91', 'EN01', 'EN11', 'EN21', 'EN31', 'EN41', 'EN51', 'EN61', 'EN71', 'EN81', 'EN91', 'FN01', 'FN11', 'FN21', 'FN31', 'FN41',
			'FN51', 'CN70', 'CN80', 'CN90', 'DN00', 'DN10', 'DN20', 'DN30', 'DN40', 'DN50', 'DN60', 'DN70', 'DN80', 'DN90', 'EN00', 'EN10',
			'EN20', 'EN30', 'EN40', 'EN50', 'EN60', 'EN70', 'EN80', 'EN90', 'FN00', 'FN10', 'FN20', 'FN30', 'CM79', 'CM89', 'CM99', 'DM09',
			'DM19', 'DM29', 'DM39', 'DM49', 'DM59', 'DM69', 'DM79', 'DM89', 'DM99', 'EM09', 'EM19', 'EM29', 'EM39', 'EM49', 'EM59', 'EM69',
			'EM79', 'EM89', 'EM99', 'FM09', 'FM19', 'FM29', 'CM88', 'CM98', 'DM08', 'DM18', 'DM28', 'DM38', 'DM48', 'DM58', 'DM68', 'DM78',
			'DM88', 'DM98', 'EM08', 'EM18', 'EM28', 'EM38', 'EM48', 'EM58', 'EM68', 'EM78', 'EM88', 'EM98', 'FM08', 'FM18', 'FM28', 'CM87',
			'CM97', 'DM07', 'DM17', 'DM27', 'DM37', 'DM47', 'DM57', 'DM67', 'DM77', 'DM87', 'DM97', 'EM07', 'EM17', 'EM27', 'EM37', 'EM47',
			'EM57', 'EM67', 'EM77', 'EM87', 'EM97', 'FM07', 'FM17', 'FM27', 'CM86', 'CM96', 'DM06', 'DM16', 'DM26', 'DM36', 'DM46', 'DM56',
			'DM66', 'DM76', 'DM86', 'DM96', 'EM06', 'EM16', 'EM26', 'EM36', 'EM46', 'EM56', 'EM66', 'EM76', 'EM86', 'EM96', 'FM06', 'FM16',
			'FM26', 'CM95', 'DM05', 'DM15', 'DM25', 'DM35', 'DM45', 'DM55', 'DM65', 'DM75', 'DM85', 'DM95', 'EM05', 'EM15', 'EM25', 'EM35',
			'EM45', 'EM55', 'EM65', 'EM75', 'EM85', 'EM95', 'FM05', 'FM15', 'FM25', 'CM94', 'DM04', 'DM14', 'DM24', 'DM34', 'DM44', 'DM54',
			'DM64', 'DM74', 'DM84', 'DM94', 'EM04', 'EM14', 'EM24', 'EM34', 'EM44', 'EM54', 'EM64', 'EM74', 'EM84', 'EM94', 'FM04', 'FM14',
			'CM93', 'DM03', 'DM13', 'DM23', 'DM33', 'DM43', 'DM53', 'DM63', 'DM73', 'DM83', 'DM93', 'EM03', 'EM13', 'EM23', 'EM33', 'EM43',
			'EM53', 'EM63', 'EM73', 'EM83', 'EM93', 'FM03', 'FM13', 'DM02', 'DM12', 'DM22', 'DM32', 'DM42', 'DM52', 'DM62', 'DM72', 'DM82',
			'DM92', 'EM02', 'EM12', 'EM22', 'EM32', 'EM42', 'EM52', 'EM62', 'EM72', 'EM82', 'EM92', 'FM02', 'DM31', 'DM41', 'DM51', 'DM61',
			'DM71', 'DM81', 'DM91', 'EM01', 'EM11', 'EM21', 'EM31', 'EM41', 'EM51', 'EM61', 'EM71', 'EM81', 'EM91', 'DM70', 'DM80', 'DM90',
			'EM00', 'EM10', 'EM20', 'EM30', 'EM40', 'EM50', 'EM60', 'EM70', 'EM80', 'EM90', 'DL79', 'DL89', 'DL99', 'EL09', 'EL19', 'EL29',
			'EL39', 'EL49', 'EL59', 'EL79', 'EL89', 'EL99', 'DL88', 'DL98', 'EL08', 'EL18', 'EL28', 'EL58', 'EL88', 'EL98', 'EL07', 'EL17',
			'EL87', 'EL97', 'EL06', 'EL16', 'EL86', 'EL96', 'EL15', 'EL95', 'EL84', 'EL94'
		];

		for (var lon = left; lon < right; lon += (unit*2)) {
			if (lon > -180 || lon < 180) {
				for (var lat = bottom; lat < top; lat += unit) {
					var bounds = [[lat,lon],[lat+unit,lon+(unit*2)]];
					var locator = this._getLocator(lon,lat);
	
					if(grid_four.includes(locator)) {
	
						if(grid_four_lotw.includes(locator)) {
							var rectConfirmed = L.rectangle(bounds, {className: 'grid-rectangle grid-confirmed', color: 'rgba(144,238,144, 0.6)', weight: 1, fillOpacity: 1, fill:true, interactive: false});
							this.addLayer(rectConfirmed);
						} else if (grid_four_paper.includes(locator)) {
							var rectPaper = L.rectangle(bounds, {className: 'grid-rectangle grid-confirmed', color: 'rgba(0,176,240, 0.6)', weight: 1, fillOpacity: 1, fill:true, interactive: false});
							this.addLayer(rectPaper);
						} else {
							var rectWorked = L.rectangle(bounds, {className: 'grid-rectangle grid-worked', color: 'rgba(255,215,87, 0.6)', weight: 1, fillOpacity: 1, fill:true, interactive: false})
							this.addLayer(rectWorked);
						}
						// Controls text on grid on various zoom levels
						this.addLayer(this._getLabel(lon+unit-(unit/lcor),lat+(unit/2)+(unit/lcor*c)));
					} else if (grids_unworked.includes(locator)) {
						var rect = L.rectangle(bounds, {className: 'grid-rectangle grid-unworked', color: 'rgba(0,0,0, 0.3)', weight: 1, fillOpacity: 0.15, fill:true, interactive: false})
						this.addLayer(rect);
						this.addLayer(this._getLabel(lon+unit-(unit/lcor),lat+(unit/2)+(unit/lcor*c)));
					}
				}
			}
		}
		// Added this to print fields and field name, while still showing worked/confirmed gridsquares
		unit = 10;
		var left = Math.floor(w / (unit * 2)) * (unit * 2);
		var right = Math.ceil(e / (unit * 2)) * (unit * 2);
		var top = Math.ceil(n / unit) * unit;
		var bottom = Math.floor(s / unit) * unit;
		for (var lon = left; lon < right; lon += (unit * 2)) {
			for (var lat = bottom; lat < top; lat += unit) {
				var bounds = [[lat, lon], [lat + unit, lon + (unit * 2)]];

				this.addLayer(L.rectangle(bounds, {
					className: 'grid-rectangle',
					color: this.options.color,
					weight: 1,
					fill: false,
					interactive: false
				}));
			}
		}
		return this;
	},

	_getLabel: function(lon,lat) {
	  var title_size = new Array(0, 10, 14, 16, 8.5, 13, 14, 16, 24, 36, 36, 64, 128); // Controls text size on labels
		var zoom = map.getZoom();
		var size = title_size[zoom]+'px';
		var title = '';
		var locator = this._getLocator(lon,lat);
		title = '<span class="grid-text" style="cursor: default;"><font style="color:'+this.options.color+'; font-size:'+size+'; font-weight: 900; ">' + locator + '</font></span>';
		var myIcon = L.divIcon({className: 'my-div-icon', html: title});
		var marker = L.marker([lat,lon], {icon: myIcon}, clickable=false);
		if (typeof gridsquaremap !== 'undefined' && gridsquaremap == true) {
			marker.on('click', function(event) {
				spawnGridsquareModal(locator);
			});
		}
		return marker;
	},

	_getLocator: function(lon,lat) {
	  var ydiv_arr=new Array(10, 1, 1/24, 1/240, 1/240/24);
	  var d1 = "ABCDEFGHIJKLMNOPQR".split("");
	  var d4 = new Array(0, 1, 1, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5);
      var locator = "";
      var x = lon;
      var y = lat;
      var precision = d4[map.getZoom()];
      while (x < -180) {x += 360;}
      while (x > 180) {x -=360;}
      x = x + 180;
      y = y + 90;
      locator = locator + d1[Math.floor(x/20)] + d1[Math.floor(y/10)];
      for (var i=0; i<4; i=i+1) {
		if (precision > i+1) {
        rlon = x%(ydiv_arr[i]*2);
        rlat = y%(ydiv_arr[i]);
			if ((i%2)==0) {
				locator += Math.floor(rlon/(ydiv_arr[i+1]*2)) +""+ Math.floor(rlat/(ydiv_arr[i+1]));
			}
		}
	  }
      return locator;
	},

});

L.maidenhead = function (options) {
	return new L.Maidenhead(options);
};
