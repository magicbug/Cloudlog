/*!
 * @energiency/chartjs-plugin-piechart-outlabels v1.3.1
 * http://www.chartjs.org
 * (c) 2017-2022 @energiency/chartjs-plugin-piechart-outlabels contributors
 * Released under the MIT license
 */
(function (global, factory) {
typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory(require('chart.js'), require('chart.js/helpers')) :
typeof define === 'function' && define.amd ? define(['chart.js', 'chart.js/helpers'], factory) :
(global = typeof globalThis !== 'undefined' ? globalThis : global || self, global.ChartPieChartOutlabels = factory(global.Chart, global.Chart.helpers));
})(this, (function (chart_js, helpers) { 'use strict';

/**
 * @module Options
 */

var customDefaults = {

  PLUGIN_KEY: '$outlabels',

  /**
	 * The color used to draw the background of the label rect.
	 * @member {String|Array|Function|null}
	 * @default null (adaptive background)
	 */
  backgroundColor: function(context) {
    return context.dataset.backgroundColor;
  },

  /**
	 * The color used to draw the border of the label rect.
	 * @member {String|Array|Function|null}
	 * @default null (adaptive border color)
	 */
  borderColor: function(context) {
    return context.dataset.backgroundColor;
  },

  /**
	 * The color used to draw the line between label and arc of the chart.
	 * @member {String|Array|Function|null}
	 * @default null (adaptive line color)
	 */
  lineColor: function(context) {
    return context.dataset.backgroundColor;
  },

  /**
	 * The border radius used to add rounded corners to the label rect.
	 * @member {Number|Array|Function}
	 * @default 0 (not rounded)
	 */
  borderRadius: 0,

  /**
	 * The border width of the surrounding frame.
	 * @member {Number|Array|Function}
	 * @default 0 (no border)
	 */
  borderWidth: 0,

  /**
	 * The width (thickness) of the line between label and chart arc.
	 * @member {Number|Array|Function}
	 * @default 2
	 */
  lineWidth: 2,

  /**
	 * The color used to draw the label text.
	 * @member {String|Array|Function}
	 * @default white
	 */
  color: 'white',

  /**
	 * Whether to display labels global (boolean) or per data (function)
	 * @member {Boolean|Array|Function}
	 * @default true
	 */
  display: true,

  /**
	 * The font options used to draw the label text.
	 * @member {Object|Array|Function}
	 * @prop {Boolean} font.family - defaults to Chart.defaults.global.defaultFontFamily
	 * @prop {Boolean} font.size - defaults to Chart.defaults.global.defaultFontSize
	 * @prop {Boolean} font.style - defaults to Chart.defaults.global.defaultFontStyle
	 * @prop {Boolean} font.weight - defaults to 'normal'
	 * @prop {Boolean} font.maxSize - defaults to undefined (unlimited)
	 * @prop {Boolean} font.minSize - defaults to undefined (unlimited)
	 * @prop {Boolean} font.resizable - defaults to true
	 * @default Chart.defaults.global.defaultFont.*
	 */
  font: {
    family: undefined,
    size: undefined,
    style: undefined,
    weight: null,
    maxSize: null,
    minSize: null,
    resizable: true,
  },

  /**
	 * The line height (in pixel) to use for multi-lines labels.
	 * @member {Number|Array|Function|undefined}
	 * @default 1.2
	 */
  lineHeight: 1.2,


  /**
	 * The padding (in pixels) to apply between the text and the surrounding frame.
	 * @member {Number|Object|Array|Function}
	 * @prop {Number} padding.top - Space above the text.
	 * @prop {Number} padding.right - Space on the right of the text.
	 * @prop {Number} padding.bottom - Space below the text.
	 * @prop {Number} padding.left - Space on the left of the text.
	 * @default 4 (all values)
	 */
  padding: {
    top: 2,
    right: 2,
    bottom: 2,
    left: 2
  },

  /**
	 * Text alignment for multi-lines labels ('left'|'right'|'start'|'center'|'end').
	 * @member {String|Array|Function}
	 * @default 'center'
	 */
  textAlign: 'center',

  /**
	 * The radius of distance where the label will be drawn
	 * @member {Number|Array|Function|undefined}
	 * @default 30
	 */
  stretch: 12,

  /**
	 * The length of the horizontal part of line between label and chart arc.
	 * @member {Number}
	 * @default 30
	 */
  horizontalStrechPad: 12,

  /**
	 * The text of the label.
	 * @member {String}
	 * @default '%l %p' (label name and value percentage)
	 */
  text: '%l %p',

  /**
	 * The max level of zoom (out) for pie/doughnut chart in percent.
	 * @member {Number}
	 * @default 50 (%)
	 */
  maxZoomOutPercentage: 50,

  /**
	 * The count of numbers after the point separator for float values of percent property
	 * @member {Number}
	 * @default 1
	 */
  percentPrecision: 1,

  /**
	 * The count of numbers after the point separator for float values of value property
	 * @member {Number}
	 * @default 3
	 */
  valuePrecision: 3
};

var positioners = {
  center: function(arc, stretch) {
    var angle = (arc.startAngle + arc.endAngle) / 2;
    var cosA = Math.cos(angle);
    var sinA = Math.sin(angle);
    var d = arc.outerRadius;

    var stretchedD = d + stretch;
    return {
      x: arc.x + cosA * stretchedD,
      y: arc.y + sinA * stretchedD,
      d: stretchedD,
      arc: arc,
      anchor: {
        x: arc.x + cosA * d,
        y: arc.y + sinA * d,
      },
      copy: {
        x: arc.x + cosA * stretchedD,
        y: arc.y + sinA * stretchedD
      }
    };
  },

  moveFromAnchor: function(center, dist) {
    var arc = center.arc;
    var d = center.d;
    var angle = (arc.startAngle + arc.endAngle) / 2;
    var cosA = Math.cos(angle);
    var sinA = Math.sin(angle);

    d += dist;

    return {
      x: arc.x + cosA * d,
      y: arc.y + sinA * d,
      d: d,
      arc: arc,
      anchor: center.anchor,
      copy: {
        x: arc.x + cosA * d,
        y: arc.y + sinA * d
      }
    };
  }
};

function toFontString(font) {
  if (!font || helpers.isNullOrUndef(font.size) || helpers.isNullOrUndef(font.family)) {
    return null;
  }

  return (font.style ? font.style + ' ' : '')
    + (font.weight ? font.weight + ' ' : '')
    + font.size + 'px '
    + font.family;
}

function textSize(ctx, lines, font) {
  var items = [].concat(lines);
  var ilen = items.length;
  var prev = ctx.font;
  var width = 0;
  var i;

  ctx.font = font.string;

  for (i = 0; i < ilen; ++i) {
    width = Math.max(ctx.measureText(items[i]).width, width);
  }

  ctx.font = prev;

  return {
    height: ilen * font.lineHeight,
    width: width
  };
}

function adaptTextSizeToHeight(height, minimum, maximum) {
  var size = (height / 100) * 2.5;
  if (minimum && size < minimum) {
    return minimum;
  }
  if (maximum && size > maximum) {
    return maximum;
  }
  return size;
}

function parseFont(value, height) {
  var size = helpers.valueOrDefault(value.size, chart_js.Chart.defaults.defaultFontSize);

  if (value.resizable) {
    size = adaptTextSizeToHeight(height, value.minSize, value.maxSize);
  }

  var font = {
    family: helpers.valueOrDefault(value.family, chart_js.Chart.defaults.defaultFontFamily),
    lineHeight: helpers.toLineHeight(value.lineHeight, size),
    size: size,
    style: helpers.valueOrDefault(value.style, chart_js.Chart.defaults.defaultFontStyle),
    weight: helpers.valueOrDefault(value.weight, null),
    string: ''
  };

  font.string = toFontString(font);
  return font;
}

var PLUGIN_KEY$1 = customDefaults.PLUGIN_KEY;

var classes = {
  OutLabel: function(chart, index, ctx, config, context) {
    // Check whether the label should be displayed
    if (!helpers.resolve([config.display, true], context, index)) {
      throw new Error('Label display property is set to false.');
    }

    // Init text
    var value = context.dataset.data[index];
    var label = context.labels[index];
    var text = helpers.resolve([config.text, customDefaults.text], context, index);
    /* Replace label marker */
    text = text.replace(/%l/gi, label);

    /* Replace value marker with possible precision value */
    (text.match(/%v\.?(\d*)/gi) || []).map(function(val) {
      var prec = val.replace(/%v\./gi, '');
      if (prec.length) {
        return +prec;
      }
      return config.valuePrecision || customDefaults.valuePrecision;
    }).forEach(function(val) {
      text = text.replace(/%v\.?(\d*)/i, value.toFixed(val));
    });

    /* Replace percent marker with possible precision value */
    (text.match(/%p\.?(\d*)/gi) || []).map(function(val) {
      var prec = val.replace(/%p\./gi, '');
      if (prec.length) {
        return +prec;
      }
      return config.percentPrecision || customDefaults.percentPrecision;
    }).forEach(function(val) {
      text = text.replace(/%p\.?(\d*)/i, (context.percent * 100).toFixed(val) + '%');
    });

    // Count lines
    var lines = text.match(/[^\r\n]+/g) || [];

    // Remove unnecessary spaces
    for (var i = 0; i < lines.length; ++i) {
      lines[i] = lines[i].trim();
    }

    /* ===================== CONSTRUCTOR ==================== */
    this.init = function(text, lines) {
      // If everything ok -> begin initializing
      this.encodedText = config.text;
      this.text = text;
      this.lines = lines;
      this.label = label;
      this.value = value;
      this.ctx = ctx;

      // Init style
      this.style = {
        backgroundColor: helpers.resolve([config.backgroundColor, customDefaults.backgroundColor, 'black'], context, index),
        borderColor: helpers.resolve([config.borderColor, customDefaults.borderColor, 'black'], context, index),
        borderRadius: helpers.resolve([config.borderRadius, 0], context, index),
        borderWidth: helpers.resolve([config.borderWidth, 0], context, index),
        lineWidth: helpers.resolve([config.lineWidth, 2], context, index),
        lineColor: helpers.resolve([config.lineColor, customDefaults.lineColor, 'black'], context, index),
        color: helpers.resolve([config.color, 'white'], context, index),
        font: parseFont(helpers.resolve([config.font, {resizable: true}]), ctx.canvas.style.height.slice(0, -2)),
        padding: helpers.toPadding(helpers.resolve([config.padding, 0], context, index)),
        textAlign: helpers.resolve([config.textAlign, 'left'], context, index),
      };

      this.stretch = helpers.resolve([config.stretch, customDefaults.stretch], context, index);
      this.horizontalStrechPad = helpers.resolve([config.horizontalStrechPad, customDefaults.horizontalStrechPad], context, index);
      this.size = textSize(ctx, this.lines, this.style.font);

      this.offsetStep = this.size.width / 20;
      this.offset = {
        x: 0,
        y: 0
      };
      this.predictedOffset = this.offset;

      /*var angle = -((el._model.startAngle + el._model.endAngle) / 2) / (Math.PI);
			var val = Math.abs(angle - Math.trunc(angle));

			if (val > 0.45 && val < 0.55) {
				this.predictedOffset.x = 0;
			} else if (angle <= 0.45 && angle >= -0.45) {
				this.predictedOffset.x = this.size.width / 2;
			} else if (angle >= -1.45 && angle <= -0.55) {
				this.predictedOffset.x = -this.size.width / 2;
			}*/
    };

    this.init(text, lines);

    /* COMPUTING RECTS PART */
    this.computeLabelRect = function() {
      var width = this.textRect.width + 2 * this.style.borderWidth + this.style.padding.left + this.style.padding.right;
      var height = this.textRect.height + 2 * this.style.borderWidth + this.style.padding.top + this.style.padding.bottom;

      var x = this.textRect.x - this.style.borderWidth;
      var y = this.textRect.y - this.style.borderWidth;

      return {
        x: x,
        y: y,
        width: width,
        height: height,
        isLeft: this.textRect.isLeft,
        isTop: this.textRect.isTop
      };
    };

    this.computeTextRect = function() {
      const isLeft = this.center.x - this.center.anchor.x < 0;
      const isTop = this.center.y - this.center.anchor.y < 0;
      const shift = isLeft ? -(this.horizontalStrechPad + this.size.width) : this.horizontalStrechPad;
      return {
        x: this.center.x - this.style.padding.left + shift,
        y: this.center.y - (this.size.height / 2),
        width: this.size.width,
        height: this.size.height,
        isLeft,
        isTop
      };
    };

    this.roundedRect = function (ctx, x, y, width, height, radius) {
      var TL, TR, BR, BL;
    
      // Minimum horizontal value: half of the width
      var mH = r => Math.min(r, width / 2);
      // Minimum vertical value: half of the width
      var mV = r => Math.min(r, height / 2);
    
      if (radius instanceof Array) {
        // [TL, TR, BR, BL]
        TL = radius[0];
        TR = radius[1];
        BR = radius[2];
        BL = radius[3];
      } else {
        TL = TR = BR = BL = radius;
      }
    
      ctx.beginPath();
    
      ctx.moveTo(x + mH(TL), y);
      ctx.lineTo(x + width - mH(TR), y);
      ctx.quadraticCurveTo(x + width, y, x + width, y + mV(TR));
      ctx.lineTo(x + width, y + height - mV(BR));
      ctx.quadraticCurveTo(x + width, y + height, x + width - mH(BR), y + height);
      ctx.lineTo(x + mH(BL), y + height);
      ctx.quadraticCurveTo(x, y + height, x, y + height - mV(BL));
      ctx.lineTo(x, y + mV(TL));
      ctx.quadraticCurveTo(x, y, x + mH(TL), y);
    
      ctx.closePath();
    }

    this.getPoints = function() {
      return [
        {
          x: this.labelRect.x,
          y: this.labelRect.y
        },
        {
          x: this.labelRect.x + this.labelRect.width,
          y: this.labelRect.y
        },
        {
          x: this.labelRect.x + this.labelRect.width,
          y: this.labelRect.y + this.labelRect.height
        },
        {
          x: this.labelRect.x,
          y: this.labelRect.y + this.labelRect.height
        }
      ];
    };

    this.containsPoint = function(point) {
      let offset = 5;

      return	this.labelRect.x - offset <= point.x && point.x <= this.labelRect.x + this.labelRect.width + offset
							&&
						this.labelRect.y - offset <= point.y && point.y <= this.labelRect.y + this.labelRect.height + offset;
    };


    /* ======================= DRAWING ======================= */
    // Draw label text
    this.drawText = function() {
      var align = this.style.textAlign;
      var font = this.style.font;
      var lh = font.lineHeight;
      var color = this.style.color;
      var ilen = this.lines.length;
      var x, y, idx;

      if (!ilen || !color) {
        return;
      }

      x = this.textRect.x;
      y = this.textRect.y + lh / 2;

      if (align === 'center') {
        x += this.textRect.width / 2;
      } else if (align === 'end' || align === 'right') {
        x += this.textRect.width;
      }

      this.ctx.font = this.style.font.string;
      this.ctx.fillStyle = color;
      this.ctx.textAlign = align;
      this.ctx.textBaseline = 'middle';

      for (idx = 0; idx < ilen; ++idx) {
        this.ctx.fillText(
          this.lines[idx],
          Math.round(x) + this.style.padding.left,
          Math.round(y),
          Math.round(this.textRect.width)
        );
        y += lh;
      }
    };

    this.ccw = function(A, B, C) {
      return (C.y - A.y) * (B.x - A.x) > (B.y - A.y) * (C.x - A.x);
    };

    this.intersects = function(A, B, C, D) {
      return this.ccw(A, C, D) !== this.ccw(B, C, D) && this.ccw(A, B, C) !== this.ccw(A, B, D);
    };

    // Draw label box
		this.drawLabel = function() {
			ctx.beginPath();
			this.roundedRect(
				this.ctx,
				Math.round(this.labelRect.x),
				Math.round(this.labelRect.y),
				Math.round(this.labelRect.width),
				Math.round(this.labelRect.height),
				this.style.borderRadius
			);
			this.ctx.closePath();

			if (this.style.backgroundColor) {
				this.ctx.fillStyle = this.style.backgroundColor || 'black';
				this.ctx.fill();
			}

			if (this.style.borderColor && this.style.borderWidth) {
				this.ctx.strokeStyle = this.style.borderColor;
				this.ctx.lineWidth = this.style.borderWidth;
				this.ctx.lineJoin = 'miter';
				this.ctx.stroke();
			}
		};

    this.drawLine = function() {
      if (!this.lines.length) {
        return;
      }
      this.ctx.save();

      this.ctx.strokeStyle = this.style.lineColor;
      this.ctx.lineWidth = this.style.lineWidth;
      this.ctx.lineJoin = 'miter';
      this.ctx.beginPath();
      this.ctx.moveTo(this.center.anchor.x, this.center.anchor.y);
      this.ctx.lineTo(this.center.copy.x, this.center.copy.y);
      this.ctx.stroke();

      this.ctx.beginPath();
      this.ctx.moveTo(this.center.copy.x, this.center.copy.y);
      const xOffset = this.textRect.width + this.style.padding.width;
      const intersect = this.intersects(this.textRect, {
        x: this.textRect.x + this.textRect.width,
        y: this.textRect.y + this.textRect.height,
      }, this.center.copy, {
        x: this.textRect.x,
        y: this.textRect.y + this.textRect.height / 2
      });
      this.ctx.lineTo(this.textRect.x + (intersect ? xOffset : 0), this.textRect.y + this.textRect.height / 2);
      this.ctx.stroke();
      this.ctx.restore();
    };

    this.draw = function() {
      if (chart.getDataVisibility(index)) {
        this.drawLabel();
        this.drawText();
        this.drawLine();
      }
    };




    // eslint-disable-next-line max-statements
    this.update = function(view, elements, max) {
      this.center = positioners.center(view, this.stretch);
      this.moveLabelToOffset();

			this.center.x += this.offset.x;
			this.center.y += this.offset.y;

      var valid = false;

      while (!valid) {
        this.textRect = this.computeTextRect();
        this.labelRect = this.computeLabelRect();

        var rectPoints = this.getPoints();

        valid = true;

        for (var e = 0; e < max; ++e) {
          var element = elements[e][PLUGIN_KEY$1];
          if (!element || !chart.getDataVisibility(index)) {
            continue;
          }

          var elPoints = element.getPoints();

          for (var p = 0; p < rectPoints.length; ++p) {
            if (element.containsPoint(rectPoints[p])) {
              valid = false;
              break;
            }

            if (this.containsPoint(elPoints[p])) {
              valid = false;
              break;
            }
          }
        }

        if (!valid) {
          this.center = positioners.moveFromAnchor(this.center, 1);
          this.center.x += this.offset.x;
					this.center.y += this.offset.y;
        }
      }
    };

    this.moveLabelToOffset = function() {
      if (this.predictedOffset.x <= 0 && this.offset.x > this.predictedOffset.x) {
        this.offset.x -= this.offsetStep;
        if (this.offset.x <= this.predictedOffset.x) {
          this.offset.x = this.predictedOffset.x;
        }
      } else if (this.predictedOffset.x >= 0 && this.offset.x < this.predictedOffset.x) {
        this.offset.x += this.offsetStep;
        if (this.offset.x >= this.predictedOffset.x) {
          this.offset.x = this.predictedOffset.x;
        }
      }
    };

  }
};



chart_js.defaults.plugins.outlabels = customDefaults;


var PLUGIN_KEY = customDefaults.PLUGIN_KEY;

function configure(dataset, options) {
  var override = dataset.outlabels;
  var config = {};

  if (override === false) {
    return null;
  }
  if (override === true) {
    override = {};
  }

  return Object.assign({}, config, options, override);

}

var plugin = {
  id: 'outlabels',
  resize: function(chart) {
    chart.sizeChanged = true;
  },
  afterUpdate: (chart) => {
    const ctrl = chart._metasets[0].controller;
    var meta = ctrl.getMeta();

    var elements = meta.data || [];
    const rect = {
      x1: Infinity,
      x2: 0,
      y1: Infinity,
      y2: 0
    };
    elements.forEach((el, index) => {
      const outlabelPlugin = el[PLUGIN_KEY];
      if (!outlabelPlugin) {
        return;
      }

      outlabelPlugin.update(el, elements, index);
      const x = outlabelPlugin.labelRect.x + (!outlabelPlugin.labelRect.isLeft ? 0 : outlabelPlugin.labelRect.width);
      const y = outlabelPlugin.labelRect.y + (outlabelPlugin.labelRect.isTop ? 0 : outlabelPlugin.labelRect.height);
      if (x < rect.x1) {
        rect.x1 = x;
      }
      if (x > rect.x2) {
        rect.x2 = x;
      }
      if (y < rect.y1) {
        rect.y1 = y;
      }
      if (y > rect.y2) {
        rect.y2 = y;
      }
    });

    var max = chart.options.maxZoomOutPercentage || customDefaults.maxZoomOutPercentage;
    const maxDeltas = [
      chart.chartArea.left - rect.x1,
      chart.chartArea.top - rect.y1,
      rect.x2 - chart.chartArea.right,
      rect.y2 - chart.chartArea.bottom
    ];
    const diff = Math.max(...maxDeltas.filter(x => x > 0), 0);
    const percent = diff * 100 / ctrl.outerRadius;
    ctrl.outerRadius -= percent < max ? diff : max * 100 / ctrl.outerRadius;
    ctrl.innerRadius = ctrl.outerRadius / 2;

    ctrl.updateElements(meta.data, 0, meta.data.length, 'resize');
  },
  afterDatasetUpdate: function(chart, args, options) {
    var labels = chart.config.data.labels;
    var dataset = chart.data.datasets[args.index];
    var config = configure(dataset, options);
    var display = config && config.display;
    var elements = args.meta.data || [];
    var ctx = chart.ctx;
    var el, label, percent, newLabel, context, i;
    ctx.save();

    for (i = 0; i < elements.length; ++i) {
      el = elements[i];
      label = el[PLUGIN_KEY];
      percent = dataset.data[i] / args.meta.total;
      newLabel = null;

      if (display && el && !el.hidden) {
        try {
          context = {
            chart: chart,
            dataIndex: i,
            dataset: dataset,
            labels: labels,
            datasetIndex: args.index,
            percent: percent
          };
          newLabel = new classes.OutLabel(chart, i, ctx, config, context);
        } catch (e) {
          newLabel = null;
        }
      }

      if (
        label && newLabel && !chart.sizeChanged &&
    (label.label === newLabel.label) && (label.encodedText === newLabel.encodedText)
      ) {
        newLabel.offset = label.offset;
      }
      el[PLUGIN_KEY] = newLabel;
    }

    ctx.restore();
    chart.sizeChanged = false;
  },
  afterDatasetDraw: function(chart, args) {
    var elements = args.meta.data || [];
    var ctx = chart.ctx;

    elements.forEach((el, index) => {
      const outlabelPlugin = el[PLUGIN_KEY];
      if (!outlabelPlugin) {
        return;
      }
      outlabelPlugin.update(el, elements, index);
      outlabelPlugin.draw(ctx);
    });
  },
};

return plugin;

}));
