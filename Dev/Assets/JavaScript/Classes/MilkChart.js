// Copyright 2006 Google Inc.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

/*
---
description: Graphing/chart tool for mootools 1.2

license: Apache License, Version 2.0

authors:
- Brett Dixon

requires: 
  core/1.2.5:Core

provides: [MilkChart.Column, MilkChart.Bar, MilkChart.Line, MilkChart.Scatter, MilkChart.Pie]

Modified by: Virtuosi Media Inc.
Notes: Updated to Mootools 1.4, added local data function, added points option for line graph

...
*/

Array.prototype.sum = function(){
	for(var i=0,sum=0;i<this.length;sum+=this[i++]);
	return sum;
}

// Add our namespace
var MilkChart = {};
//MilkChart.Colors = ['#4f81bd', '#c0504d', '#9bbb59', '#8064a2', '#4198af', '#db843d'];
MilkChart.Colors = ['#3D943D', '#07A', '#FA1', '#FF5A5A', '#66C', '#4198af', '#db843d'];
// Simple Point class
var Point = new Class({
    initialize: function(x,y) {
        this.x = x || 0;
        this.y = y || 0;
    }
});



MilkChart.Base = new Class({
    Implements: [Options,Events],
    options: {
        width: 480,
        height: 290,
		colors: MilkChart.Colors,
        padding: 12,
        font: "Verdana",
        fontColor: "#000000",
        fontSize: 10,
        background: "#FFFFFF",
        chartLineColor: "#878787",
        chartLineWeight: 1,
        border: true,
        borderWeight: 1,
        borderColor: "#878787",
        titleSize: 18,
        titleFont: "Verdana",
        titleColor: "#000000",
        showRowNames: true,
        showValues: true,
        showKey: true,
		useZero: true,
		copy: false,
		rowPrefix: "",
		ignoreFirstColumn: false
    },
    initialize: function(el, options) {
        this.setOptions(options);
        this.element = document.id(el);
        this.width = this.options.width;
        this.height = this.options.height;
        this.container = (this.element.get('tag') == "table") ? new Element('div').inject(this.element.getParent()) : this.element;
		this.container.setStyles({width:this.width, height:this.height, display: 'inline-block'})
        this._canvas = new Element('canvas', {width:this.options.width,height:this.options.height,id:el+'Canvas'}).inject(this.container);
        this.ctx = this._canvas.getContext('2d');
        this.data = {
        	title: this.element.title,
        	colNames: [],
        	rowNames: [],
        	rows: []
        }
		// Hacky, oh the shame!
        this.minY = (this.options.useZero) ? 0 : 10000000000;
        this.maxY = 0;
        this.bounds = [new Point(), new Point(this.width, this.height)];
        this.chartWidth = 0;
        this.chartHeight = 0;
        this.keyPadding = this.width * .2;
        this.rowPadding = this.height * .1;
        this.rowPaddingDimension = 'width';

		this.shapes = [];
		// This could be done in a list, but an object is more readable
        Object.each(MilkChart.Shapes, function(shape) {
            this.shapes.push(shape);
        }.bind(this));		
    },
    prepareCanvas: function() {
		if (!this.options.copy && this.element.get('tag') == "table") {
			this.element.setStyle('display', 'none');
		}
        
        // Fill our canvas' bg color
        this.ctx.fillStyle = this.options.background;
        this.ctx.fillRect(0, 0, this.width, this.height);
        
        this.ctx.font = this.options.fontSize + "px " + this.options.font;
        
        if (this.options.border) {
			this.ctx.lineWeight = this.options.borderWeight;
            this.ctx.strokeRect(0.5,0.5,this.width-1, this.height-1);
        }
        
        if (this.options.showValues) {
            // Accomodate the width of the values column
            this.bounds[0].x += this.getValueColumnWidth();
        }
        
        this.bounds[0].x += this.options.padding;
        this.bounds[0].y += this.options.padding;
        this.bounds[1].x -= this.options.padding*2;
        this.bounds[1].y -= this.options.padding*2;
        
        if (this.options.showKey) {
            // Apply key padding
            var longestName = "";
            this.data.colNames.each(function(col) {
            	if (col.length > longestName.length) {
	            	longestName = String(col);
	            }
            });
            var colorSquareWidth = 14
            this.keyPadding = this.ctx.measureText(longestName).width + colorSquareWidth;
            this.bounds[1].x -= this.keyPadding;
            // Set key bounds
            var chartKeyPadding = 1.02;
            this.keyBounds = [
                new Point(this.bounds[1].x * chartKeyPadding, this.bounds[0].y),
                new Point(this.keyPadding, this.bounds[1].y)
            ];
        }
        
        if (this.data.title) {
            titleHeight = this.bounds[0].y + this.height * .1;
            this.bounds[0].y = titleHeight;
            this.titleBounds = [new Point(this.bounds[0].x, 0), new Point(this.bounds[1].x, titleHeight)];
            this.drawTitle();
        }
        if (this.options.showRowNames) {
			this.ctx.font = this.options.fontSize + "px " + this.options.font;
			this.getRowPadding();
			this.bounds[1].y -= this.rowPadding;
		}
		else {
			this.rowPadding = 0;
		}
        
        this.chartWidth = (this.options.keyPosition == 'bottom') ? this.bounds[1].x : this.bounds[1].x - this.bounds[0].x;
        this.chartHeight = this.bounds[1].y - this.bounds[0].y;
        this.colors = this.__getColors(this.options.colors);
    },
    getValueColumnWidth: function() {
        return this.ctx.measureText(String(this.maxY)).width;
    },
    getRowPadding: function() {
    	this.rowPadding = (this.ctx.measureText(this.longestRowName).width > ((this.bounds[1].x - this.bounds[0].x) / this.data.rows.length)) ? this.ctx.measureText(this.longestRowName).width : this.height * 0.1;
    },
    drawTitle: function() {
		var titleHeightRatio = 1.25;
        var titleHeight = this.options.titleSize * titleHeightRatio;
        this.ctx.textAlign = 'left';
        this.ctx.font = this.options.titleSize + "px " + this.options.titleFont;
        this.ctx.fillStyle = this.options.titleColor;
        this.ctx.fillText(this.data.title, 0, titleHeight, this.chartWidth);
        //this.ctx.fillText(this.data.title, this.bounds[0].x + (this.bounds[1].x - this.bounds[0].x)/2, titleHeight, this.chartWidth);
    },
    drawAxes: function() {
        /**********************************
         * Draws X & Y axes
         *********************************/
        this.ctx.beginPath();
        this.ctx.strokeStyle = this.options.chartLineColor;
        // The +.5 is to put lines between pixels so they draw sharply
        this.ctx.moveTo(this.bounds[0].x+.5, this.bounds[0].y+.5);
        this.ctx.lineTo(this.bounds[0].x+.5, this.bounds[1].y+.5);
        this.ctx.moveTo(this.bounds[0].x+.5, this.bounds[1].y+.5);
        this.ctx.lineTo(this.bounds[1].x+.5, this.bounds[1].y+.5);
        this.ctx.stroke();
    },
    drawValueLines: function() {
        /**********************************
         * Draws horizontal value lines
         *
         * Finds the line values based on Array dist, and sets the numbers of lines
         * to use.  This formula is similar how excel handles it.  Not sure if there
         * is a cleaner way of creating this type of list.
         *
         * Next it draws in the lines and the values.  Also sets the ratio to apply
         * to the values in the table.
         *********************************/

		var dist = [1, 2, 5, 10, 20, 50, 100, 150, 500, 1000, 1500, 2000, 2500, 5000, 10000];
		var maxLines = 9;
		var i = 0;
		this.chartLines = 1;
		var delta = Math.floor((this.maxY - this.minY));
		while (Math.floor((delta / dist[i])) > maxLines) {
			i++;
		}
		this.chartLines = Math.floor((delta / dist[i])) + 2;
		var mult = dist[i];
		var negativeScale = (this.minY < 0) ? (mult + this.minY) : 0;

		// Set the bounds ratio
		this.ratio = (this.chartHeight) / ((this.chartLines - 1) * mult);

		this.ctx.font = this.options.fontSize + "px " + this.options.font;
		this.ctx.textAlign = "right";
		this.ctx.fillStyle = this.options.fontColor;

		var boundsHeight = this.bounds[1].y - this.bounds[0].y;
		var lineHeight = Math.floor(boundsHeight / (this.chartLines - 1));

		for (i = 0; i < this.chartLines; i++) {
			this.ctx.fillStyle = this.options.fontColor;
			var lineY = this.bounds[1].y - (i * lineHeight);

			var lineValue = (this.chartLines * mult) - ((this.chartLines - i) * mult) + this.minY - negativeScale;
			this.ctx.beginPath();
			// Correct values for crisp lines
			lineY += .5;
			this.ctx.moveTo(this.bounds[0].x - 4, lineY);
			if (this.options.showValues) {
				var offsetX = 8;
				var offsetY = 3
				this.ctx.fillText(String(lineValue), this.bounds[0].x - offsetX, lineY + offsetY);
			}
			if (this.options.keyPosition == 'bottom'){
				this.ctx.lineTo(this.options.width - this.options.padding, lineY);
			} else {
				this.ctx.lineTo(this.bounds[1].x, lineY);
			}
			this.ctx.stroke();
			}
    },
    swapAxes: function() {
    	var row = this.data.rowNames.slice(0);
    	var col = this.data.colNames.slice(0);
    	this.data.rowNames = col;
    	this.data.colNames = row;
    	var newRows = [];
    	this.data.rows.each(function(row, idx) {
    		row.each(function(cell, index) {
    			if (!newRows[index]) {
    				newRows[index] = [];
    			}
    			newRows[index][idx] = cell;
    		})
    	});
    	this.data.rows = newRows;
    	
    	this.setData(this.data);
    	this.render();
    },
    setData: function(data) {
    	this.bounds = [new Point(), new Point(this.width, this.height)];
    	this.colors = this.__getColors(this.options.colors);
    	this.minY = (this.options.useZero) ? 0 : 10000000000;
        this.maxY = 0;
        data.rows.each(function(row) {
        	var rowMax = Math.max.apply(Math, row);
        	var rowMin = Math.min.apply(Math, row);
        	this.maxY = (rowMax > this.maxY) ? rowMax : this.maxY;
        	this.minY = (rowMin < this.minY) ? rowMin : this.minY;
        }, this);
		var longestRowName = "";
		data.rowNames.each(function(row) {
			if (this.ctx.measureText(row).width > this.ctx.measureText(longestRowName).width) {
            	longestRowName = String(row);
            }
		}, this);
		this.longestRowName = longestRowName;

		this.data = data
    },
    loadLocal:function(data){
    	var json = (typeof(input)=='string') ? JSON.decode(data) : data;
    	this.setData(json);
		this.render();
    },   
    load: function(options) {
    	var self = this;
    	options = options || {};
    	var reqOptions = {
    		method: 'get',
    		onSuccess: function(res) {
    			self.setData(res);
    			self.render();
    		}
    	};
    	var merged = $merge(options, reqOptions);
    	var req = new Request.JSON(merged);
    	req.send();
    	
    	return req;
    },
    getData: function() {
        /**********************************
         * This function should be overridden for each new graph type as the data
         * is represented different for the different graphs.
         *********************************/

		return null;
    },
    
    draw: function() {
        // Abstract
        /**********************************
         * This function should be overridden for each new graph type as the data
         * is represented different for the different graphs.
         *********************************/

		return null;
    },
    
    drawKey: function() {
        // Abstract
        /**********************************
         * This function should be overridden for each new graph type.  The keys are
         * similar but the icons that represent the columns are not.
         *********************************/

		return null;
    },
	__getColors: function(clr) {
		/**********************************
		 * This accepts a single color to be a monochromatic gradient
		 * that will go from the given color to white, two colors as
		 * a gradient between the two, or use the default colors.
		 * 
		 * Keyword args may be implemented for convenience.
		 * i.e. "blue", "orange", etc.
		 */

		var colors = [];
		if (clr.length == 1 || clr.length == 2) {
			var min = new Color(clr[0]);
			// We either use the second color to get a gradient or use white mixed with 20% of the first color
			var max = (clr.length == 2) ? new Color(clr[1]) : new Color("#ffffff").mix(clr[0], 20);
			var delta = [(max[0] - min[0]) / this.data.colNames.length,(max[1] - min[1]) / this.data.colNames.length,(max[2] - min[2]) / this.data.colNames.length];
			var startColor = min;

			for (i=0;i<this.data.colNames.length;i++) {
				colors.push(startColor.rgbToHex());
				for (j=0;j<delta.length;j++) {
					startColor[j] += parseInt(delta[j]);
				}
			}
		}
		else {
			//Use default, but make sure we have enough!
			var mix = 0;
			var colorArray = clr.slice(0);
			while (colors.length != this.data.colNames.length) {
				if (colorArray.length == 0) {
					colorArray = clr.slice(0);
					mix += 20;
				}
				newColor = new Color(colorArray.shift()).mix("#ffffff", mix);
				colors.push(newColor.rgbToHex());

			}
		}

		return colors;
	}
});

MilkChart.Column = new Class({
    /**********************************
    * Column
    *
    * The Column graph type has the following options:
    * TODO
    *********************************/
    Extends: MilkChart.Base,
    options: {
        columnBorder: false,
		columnBorderWeight: 2,
		columnBorderColor: '#ffffff'
    },
    initialize: function(el, options) {
        this.parent(el, options);
        // Parse the data from the table
        if (this.element.get('tag') == "table") {
        	this.getData();
        	this.render();
        }
    },
    render: function() {
    	this.ctx.save();
    	// Sets up bounds for the graph, key, and other paddings
        this.prepareCanvas();
		// Set row width
        this.rowWidth = this.chartWidth / this.data.rows.length;//Math.round(this.chartWidth / this.data.rows.length);
        // Draws the X and Y axes lines
        this.drawAxes();
        // Draws the value lines
        this.drawValueLines();
        // Main function to draw the graph
        this.draw();
        // Draws the key for the graph
        if (this.options.showKey) this.drawKey();
        this.ctx.restore();
    },
    getData: function() {
        // Set the column headers
        this.element.getElement('thead').getChildren()[0].getChildren().each(function(item) {
           this.data.colNames.push(item.get('html'));
        }.bind(this));
        // If the footer will be used, get the row names from there
        // otherwise, get them when processing the rows
        var longestRowName = "";
        if (this.element.getElement('tfoot')) {
            this.element.getElement('tfoot').getChildren()[0].getChildren().each(function(item) {
            	var name = item.get('html');
                this.data.rowNames.push(name);
                if (this.ctx.measureText(name).width > this.ctx.measureText(longestRowName).width) {
                	longestRowName = String(name);
                }
            }.bind(this));
        }
        // Get data from rows
        this.element.getElement('tbody').getChildren().each(function(row) {
            var dataRow = [];
            row.getChildren().each(function(node) {
                val = Number(node.get('html'));
                if (!typeOf(val)) {
                    val = node.get('html').toFloat();
                }
                dataRow.push(val);
                if (val > this.maxY) this.maxY = val;
                if (val < this.minY) this.minY = val;
            }.bind(this));
            this.data.rows.push(dataRow);
            
        }.bind(this));
        // Get the first element as row name
        if (!this.element.getElement('tfoot')) {
            for (i=1;i<=this.data.rows.length;i++) {
            	var name = this.options.rowPrefix + i;
                this.data.rowNames.push(name);
                if (this.ctx.measureText(name).width > longestRowName.length) {
                	longestRowName = String(name);
                }
            }
        }
        this.longestRowName = longestRowName;
    },
    draw: function() {
        /*************************************
         * Draws the graph
         ************************************/
        var y = (this.minY >= 0) ? this.bounds[1].y : this.bounds[1].y - Math.floor((this.chartHeight/(this.chartLines-1)));
        var origin = new Point(this.bounds[0].x, y);
        var rowPadding = Math.floor(this.rowWidth * .16);
        var colWidth = Math.ceil((this.rowWidth - (rowPadding*2)) / this.data.rows[0].length);
        // Should we rotate row names?
        var rotateRowNames = (this.ctx.measureText(this.longestRowName).width > this.rowWidth);
        
        this.data.rows.each(function(row, idx) {
            var rowOrigin = new Point(origin.x, origin.y);
            this.ctx.fillStyle = this.options.fontColor;
            this.ctx.textAlign = "center"
			if (this.options.showRowNames) {
				var rowText = MilkChart.escape(this.data.rowNames[idx]);
				if (rotateRowNames) {
					this.ctx.save();
					this.ctx.textAlign = "right";
					this.ctx.translate(rowOrigin.x+(this.rowWidth/2) + this.options.fontSize, this.bounds[1].y + 4);
					this.ctx.rotate(-1.57079633);
					if (this.data.rows.length * this.options.fontSize > this.chartWidth) {
						if (idx % 8 == 1) {
							this.ctx.fillText(rowText, 0, 0);
						}
					}
					else {
						this.ctx.fillText(rowText, 0, 0);
					}
					this.ctx.restore();
				}
				else {
					this.ctx.fillText(rowText, rowOrigin.x+(this.rowWidth/2),this.bounds[1].y+(this.rowPadding/2));
				}
			}
            
            row.each(function(value, colorIndex) {
                this.ctx.beginPath();
                this.ctx.fillStyle = this.colors[colorIndex % this.colors.length];
                var colHeight = Math.ceil(value*this.ratio) - 1; // 1 pixel for value line
				//console.log(colHeight);
				this.ctx.fillStyle = this.colors[colorIndex];
                this.ctx.fillRect(rowOrigin.x+rowPadding, rowOrigin.y-colHeight, colWidth, colHeight);
				if (this.options.columnBorder) {
					this.ctx.strokeStyle = this.options.columnBorderColor;
					this.ctx.lineWidth = this.options.columnBorderWeight;
					this.ctx.strokeRect(rowOrigin.x+rowPadding, rowOrigin.y-colHeight, colWidth, colHeight);
				}

                rowOrigin.x += colWidth;
            }.bind(this));
            origin.x += this.rowWidth;
        }.bind(this))
    },
    drawKey: function() {
        // Add a margin to the column names
        var textMarginLeft = 14;
        var charHeightRatio = 0.06;
        var keyNameHeight = Math.ceil(this.height * charHeightRatio);
        var keyHeight = this.data.colNames.length * keyNameHeight;
        var keyOrigin = (this.height - keyHeight) / 2;
        var keySquareWidth = 10;
        
        this.data.colNames.each(function(item, idx) {
            this.ctx.fillStyle = this.options.fontColor;
            this.ctx.textAlign = "left";
			item = MilkChart.escape(item)
            this.ctx.fillText(item, this.keyBounds[0].x + textMarginLeft, keyOrigin+8);
            this.ctx.fillStyle = this.colors[idx % this.colors.length];
            this.ctx.fillRect(Math.ceil(this.keyBounds[0].x),Math.ceil(keyOrigin),keySquareWidth,keySquareWidth);
            
            keyOrigin += keyNameHeight;
        }, this);
    }
});

MilkChart.Bar = new Class({
    Extends: MilkChart.Column,
    options: {
        
    },
    initialize: function(el, options) {        
        this.parent(el, options);
        // Set the valueColumnWidth
        //this.valueColumnWidth = this.ctx.measureText(String(this.maxY)).width;
    },
    getValueColumnWidth: function() {
    	var valueColumnPadding = 14;
        return this.ctx.measureText(this.longestRowName).width + valueColumnPadding;
    },
    getRowPadding: function() {
    	this.rowPadding = this.height * 0.1;
    },
    drawValueLines: function() {
        /**********************************
         * Draws horizontal value lines
         *********************************/
		var dist = [1, 2, 5, 10, 20, 50, 100, 150, 500, 1000, 1500, 2000, 2500, 5000, 10000];
		var maxLines = 9;
		var i = 0;
		this.chartLines = 1;
		delta = Math.floor((this.maxY - this.minY));
		while (Math.floor((delta / dist[i])) > maxLines) {
			i++;
		}
		this.chartLines = Math.floor((delta / dist[i])) + 2;
		var mult = dist[i];
		var negativeScale = (this.minY < 0) ? (mult + this.minY) : 0;

		// Set the bounds ratio
		this.ratio = (this.chartWidth) / ((this.chartLines - 1) * mult);

        this.ctx.font = this.options.fontSize + "px " + this.options.font;
        this.ctx.textAlign = "center";
        this.ctx.fillStyle = this.options.fontColor;
        var boundsHeight = this.bounds[1].y - this.bounds[0].y;
        var lineHeight = Math.ceil(this.chartWidth / (this.chartLines - 1));
        for (i=0;i<this.chartLines;i++) {
            this.ctx.fillStyle = this.options.fontColor;
            var lineX = this.bounds[0].x + (i * lineHeight);
            var lineValue = (this.chartLines * mult) - ((this.chartLines-i) * mult) + this.minY;
            this.ctx.beginPath();
            // Correct values for crisp lines
            var lineX = Math.round(lineX) + .5;
            this.ctx.moveTo(lineX, this.bounds[0].y);
            this.ctx.fillText(MilkChart.escape(lineValue), lineX, this.bounds[1].y + 14);
            this.ctx.lineTo(lineX, this.bounds[1].y + 4);
            this.ctx.stroke();
        }
    },
    draw: function() {
        /*************************************
         * Draws the graph
         ************************************/
        var origin = new Point(this.bounds[0].x, this.bounds[1].y);
        this.colHeight = Math.round(this.chartHeight / this.data.rows.length);
        var padding = .16;
        var rowPadding = Math.ceil(this.colHeight * padding);
        var colWidth = Math.ceil((this.colHeight - (rowPadding*2)) / this.data.rows[0].length);
        var rowNameID = 0;
        
        this.data.rows.each(function(row, idx) {
            var rowOrigin = new Point(origin.x, origin.y);
            var colorID = 0;
            this.ctx.fillStyle = this.options.fontColor;
            this.ctx.textAlign = "center";
            var textWidth = Math.ceil(this.ctx.measureText(this.data.rowNames[rowNameID]).width);
            if (this.options.showRowNames) {
				var rowText = MilkChart.escape(this.data.rowNames[rowNameID]);
				if (this.data.rows.length * this.options.fontSize > this.chartWidth) {
					if (idx % 8 == 1) {
						this.ctx.fillText(MilkChart.escape(this.data.rowNames[rowNameID]), rowOrigin.x-((colWidth+textWidth)/2),rowOrigin.y-(this.rowPadding/2));
					}
				}
				else {
					this.ctx.fillText(MilkChart.escape(this.data.rowNames[rowNameID]), rowOrigin.x-((colWidth+textWidth)/2),rowOrigin.y-(this.rowPadding/2));
				}
			}
            
            row.each(function(value) {
                this.ctx.beginPath();
                this.ctx.fillStyle = this.colors[colorID];
                var colHeight = Math.ceil(value*this.ratio);
                this.ctx.fillRect(rowOrigin.x, rowOrigin.y-rowPadding, colHeight, colWidth);
                rowOrigin.y -= colWidth;
                colorID++;
            }.bind(this));
            origin.y -= this.colHeight;
            rowNameID++;
        }.bind(this))
    }
});

MilkChart.Line = new Class({
    /**********************************
    * Line
    *
    * The Column graph type has the following options:
    * - showTicks: Display tick marks at every point on the line
    * - showLines: Display the lines
    * - lineWeight: The thickness of the lines
    *********************************/
    Extends: MilkChart.Column,
    options: {
        showTicks: false,
        showLines: false,
        showPoints: true,
        showKey:true,
        keyPosition: 'right',
        tickSize: 10,
        lineWeight: 3
    },
  
    load: function(options) {
    	var self = this;
    	options = options || {};
    	var reqOptions = {
    		noCache: true,
    		onSuccess: function(data) {
    			var newRows = [];
		    	data.rows.each(function(row, idx) {
		    		row.each(function(cell, index) {
		    			if (!newRows[index]) {
		    				newRows[index] = [];
		    			}
		    			newRows[index][idx] = cell;
		    		});
		    	});
		    	data.rows = newRows;
    			self.setData(data);
    			self.render();
    		},
    		onError: function() {
    			
    		}
    	};
    	var merged = $merge(options, reqOptions);
    	var req = new Request.JSON(merged);
    	req.send();
    },
    render: function() {
    	this.ctx.save();
    	// Sets up bounds for the graph, key, and other paddings
        this.prepareCanvas();
		// Set row width
        this.rowWidth = this.chartWidth / this.data.rows[0].length;
        // Draws the X and Y axes lines
        this.drawAxes();
        // Draws the value lines
        this.drawValueLines();
        // Main function to draw the graph
        this.draw();
        // Draws the key for the graph
        if (this.options.showKey) this.drawKey();
        this.ctx.restore();
    },
    
    getData: function() {
        // Line and Scatter graphs use colums instead of rows to define objects
        this.data.rows = [];
        // Set the column headers
        this.element.getElement('thead').getChildren()[0].getChildren().each(function(item) {
           this.data.colNames.push(item.get('html'));
           this.data.rows.push([]);
        }.bind(this));
        var longestRowName = "";
        // If the table has a footer, use this for row names
        if (this.element.getElement('tfoot')) {
            this.element.getElement('tfoot').getChildren()[0].getChildren().each(function(item) {
                var name = item.get('html');
                this.data.rowNames.push(name);
                if (this.ctx.measureText(name).width > longestRowName.length) {
                	longestRowName = String(name);
                }
            }.bind(this));
        }
        
        // Get data from rows
        this.element.getElement('tbody').getChildren().each(function(row) {
            row.getChildren().each(function(node, index) {
                val = Number(node.get('html'));
                if (!typeOf(val)) {
                    var val = node.get('html').toFloat();
                }
                this.data.rows[index].push(val)
                if (val > this.maxY) this.maxY = val;
                if (val < this.minY) this.minY = val;
            }.bind(this));
            
        }.bind(this));

		// Get the first element as row name
        if (!this.element.getElement('tfoot')) {
            for (i=1;i<=this.element.getElement('tbody').getChildren().length;i++) {
                var name = this.options.rowPrefix + i;
                this.data.rowNames.push(name);
                if (this.ctx.measureText(name).width > longestRowName.length) {
                	longestRowName = String(name);
                }
            }
        }
        this.longestRowName = longestRowName;
    },
    draw: function() {
        /*************************************
         * Draws the graph
         ************************************/
        var origin = new Point(this.bounds[0].x, this.bounds[1].y);
        var rowCenter = this.rowWidth / 2;
        var rowNameID = 0;
        var colorID = 0;
        var y = (this.minY >= 0) ? this.bounds[1].y + (this.minY * this.ratio) : this.bounds[1].y - Math.floor((this.chartHeight/(this.chartLines-1)));
        
        var shapeIndex = 0;
        this.data.rows.each(function(row, index) {
            if (this.options.showLines) {
                var rowOrigin = new Point(origin.x, origin.y);
                var lineOrigin = this.bounds[0].x + rowCenter;
                this.ctx.lineWidth = this.options.lineWeight;
                this.ctx.beginPath();
                this.ctx.strokeStyle = this.colors[index];
                this.ctx.moveTo(rowOrigin.x+rowCenter, y - (row[0] * this.ratio));
                         
                row.each(function(value) {
                	var pointCenter = rowOrigin.x + rowCenter;
                    var point = new Point(pointCenter, y - (value * this.ratio));
                    this.ctx.lineTo(point.x, point.y);
                    rowOrigin.x += this.rowWidth;
                }.bind(this));                	

                this.ctx.stroke();
            }      

            if (this.options.showPoints){
            	var rowOrigin = new Point(origin.x, origin.y);
            	row.each(function(value) {
                	var pointCenter = rowOrigin.x + rowCenter;
                    var point = new Point(pointCenter, y - (value * this.ratio));
                    rowOrigin.x += this.rowWidth;
                    
                	this.ctx.fillStyle = this.colors[index];
                    this.ctx.beginPath();
                    this.ctx.arc(point.x, point.y, 4, 0, Math.PI*2, true);
                    this.ctx.closePath();
                    this.ctx.fill();                                         
                }.bind(this));            	
            }
                       
            if (this.options.showTicks) {
                var rowOrigin = new Point(origin.x, origin.y);
                var lineOrigin = this.bounds[0].x + rowCenter;
                shapeIndex = (shapeIndex > MilkChart.Shapes.getLength() - 1) ? 0 : shapeIndex;
                var shape = this.shapes[shapeIndex];
                row.each(function(value) {
                    var pointCenter = rowOrigin.x + rowCenter;
                    var point = new Point(pointCenter, y - (value * this.ratio));
                    shape(this.ctx, point.x, point.y, this.options.tickSize, this.colors[index]);
                    
                    rowOrigin.x += this.rowWidth;
                    
                }.bind(this));
                shapeIndex++;
            }
            
            colorID++;
            rowNameID++;
        }.bind(this))
        this.__drawRowLabels();
    },
    drawKey: function() {
    	var keyNameHeight = Math.ceil(this.height * .05);
        var keyHeight = this.data.colNames.length * keyNameHeight;
        var keyOrigin = (this.height - keyHeight) / 2;
        var shapeIndex = 0;
        var totalWidth = 0;
        
        this.data.colNames.each(function(item, index) {
            this.ctx.fillStyle = this.options.fontColor;
            this.ctx.textAlign = "left";
            if (this.options.keyPosition == 'bottom'){
            	this.ctx.fillText(MilkChart.escape(item), totalWidth + 30, this.height - 30);
            } else {
            	this.ctx.fillText(MilkChart.escape(item), this.keyBounds[0].x + 30, keyOrigin+5);
            }      
            this.ctx.fillStyle = this.colors[index % this.colors.length];
            this.ctx.strokeStyle = this.colors[index % this.colors.length];
            this.ctx.lineWidth = 3;
            
            this.ctx.beginPath();
            if (this.options.keyPosition == 'bottom'){
	            this.ctx.moveTo(totalWidth + 0, this.height - 34);
	            this.ctx.lineTo(totalWidth + 20, this.height - 34);
            	totalWidth += 35+(item.length*7);
            } else {
                this.ctx.moveTo(this.keyBounds[0].x, keyOrigin+.5);
                this.ctx.lineTo(this.keyBounds[0].x + 20, keyOrigin+.5);            	
            }
            this.ctx.closePath();
            this.ctx.stroke();

            
            if (this.options.showTicks) {
            	shapeIndex = (shapeIndex > MilkChart.Shapes.getLength() - 1) ? 0 : shapeIndex;
                var shape = this.shapes[shapeIndex];
                shape(this.ctx, this.keyBounds[0].x + 10, keyOrigin, 10, this.colors[index % this.colors.length]);
                shapeIndex++;
            }
            
            keyOrigin += keyNameHeight;
        }.bind(this));
    },
    __drawRowLabels: function() {
        var origin = new Point(this.bounds[0].x, this.bounds[1].y);
        var rowCenter = this.rowWidth / 2;
        // Should we rotate row names?
        var rotateRowNames = (this.ctx.measureText(this.longestRowName).width > this.rowWidth);
        
        this.ctx.fillStyle = this.options.fontColor;
        this.ctx.lineWidth = 1;
        this.ctx.textAlign = "center"
        
        this.data.rowNames.each(function(item, idx) {
            // Draw row labels
            var rowText = MilkChart.escape(this.data.rowNames[idx]);
			if (rotateRowNames) {
				this.ctx.save();
				this.ctx.textAlign = "right";
				this.ctx.translate(origin.x+(this.rowWidth/2) + this.options.fontSize, this.bounds[1].y + 4);
				this.ctx.rotate(-1.57079633);
				if (this.data.rowNames.length * this.options.fontSize > this.chartWidth) {
					if (idx % 8 == 1) {
						this.ctx.fillText(rowText, 0, 0);
					}
				}
				else {
					this.ctx.fillText(rowText, 0, 0);
				}
				this.ctx.restore();
			}
			else {
				this.ctx.fillText(rowText, origin.x+(this.rowWidth/2),this.bounds[1].y+(this.rowPadding/2));
			}
            origin.x += this.rowWidth;
        }.bind(this))
    }   
});

MilkChart.Scatter = new Class({
    /**********************************
    * Scatter
    *
    * The Scatter graph type has the following options:
    * - showTicks: Display tick marks at every point on the line
    * - showLines: Display the lines
    * - lineWeight: The thickness of the lines
    *********************************/
    Extends: MilkChart.Line,
    options: {
        showTicks: true,
        showLines: false
    },
    initialize: function(el, options) {
        this.parent(el, options);
    }
});

MilkChart.Pie = new Class({
    /**********************************
    * Pie
    *
    * The Pie chart type has the following options:
    * - stroke: Display an outline around the chart and each slice
    * - strokeWidth: Width of the outline
    * - strokeColor: Color of the outline
    * - shadow (bool): Display shadow under chart
    *********************************/
    Extends: MilkChart.Base,
    options: {
        stroke: true,
        strokeWeight: 3,
        strokeColor: "#ffffff",
        chartTextColor: "#000000",
        shadow: false,
        chartLineWeight: 2,
        pieBorder: false
    },
    initialize: function(el, options) {      
        this.parent(el, options);
        if (this.element.get('tag') == "table") {
        	this.rowCount = this.element.getElement('thead').getChildren()[0].getChildren().length;
			this.colors = this.__getColors(this.options.colors);
			this.options.showRowNames = false;
	        // Parse the data from the table
	        this.getData();
	        this.render();
        }
    },
    swapAxes: function() {
    	return true;
    },
    render: function() {
    	this.ctx.save();
    	// Sets up bounds for the graph, key, and other paddings
        this.prepareCanvas();

        this.radius = (this.chartHeight / 2);
        // Draws the key for the graph
        if (this.options.showKey) this.drawKey();
        // Main function to draw the graph
        this.draw();
        this.ctx.restore();
    },
    setData: function(data) {
    	//this.parent(data);
    	this.bounds = [new Point(), new Point(this.width, this.height)];
    	this.colors = this.__getColors(this.options.colors);
    	data.rowNames = data.colNames;
    	
		var longestRowName = "";
		data.rowNames.each(function(row) {
			if (this.ctx.measureText(row).width > this.ctx.measureText(longestRowName).width) {
            	longestRowName = String(row);
            }
		}, this);
		this.longestRowName = longestRowName;
		var newRows = [];
    	var pieTotal = 0;
    	data.rows.each(function(val) {
    		pieTotal += val[0];
    	});
    	data.rows.each(function(val, idx) {
    		var row = [val[0], (val[0]/pieTotal) * 360];
    		newRows.push(row);
    	}, this);
    	this.pieTotal = pieTotal;
    	data.rows = newRows;
    	this.data = data;
    },
    getData: function() {
		this.element.getElement('thead').getChildren()[0].getChildren().each(function(item) {
		   this.data.rowNames.push(item.get('html'));
		   this.data.colNames.push(item.get('html'));
        }.bind(this));

        var pieTotal = 0;
        
        // Get data from rows
		this.element.getElement('tbody').getChildren()[0].getChildren().each(function(node) {
            var dataRow = [];
            var val = node.get('html').toInt();
            dataRow.push(val);
            pieTotal += val;
            this.data.rows.push(dataRow);
            
        }.bind(this));
        
        this.data.rows.each(function(item) {
            item.push((item[0]/pieTotal) * 360);
        });
        this.pieTotal = pieTotal;
    },
    draw: function() {
        var arcStart = 0;
		var center = new Point((this.bounds[1].x / 2) + this.options.padding, (this.bounds[1].y / 2) + this.options.padding);
        if (this.options.shadow) {
            var radgrad = this.ctx.createRadialGradient(center.x, center.y, this.radius, center.x*1.03, center.y*1.03, this.radius*1.05);
            radgrad.addColorStop(0.5, '#000000');
            radgrad.addColorStop(0.75, '#000000');
            radgrad.addColorStop(1, 'rgba(0,0,0,0)');
            this.ctx.fillStyle = radgrad;
            this.ctx.fillRect(this.bounds[0].x,this.bounds[0].y,this.width,this.height);
        }
        this.data.rows.each(function(item, index) {
            this.ctx.fillStyle = this.colors[index % this.colors.length];
            this.ctx.beginPath();
            this.ctx.arc(center.x, center.y, this.radius, (Math.PI/180)*arcStart, (Math.PI/180)*(item[1]+arcStart), false);
            this.ctx.lineTo(center.x, center.y);
            this.ctx.closePath();
            this.ctx.fill();
            if (this.options.stroke) {
                this.ctx.strokeStyle = this.options.strokeColor;
                this.ctx.lineWidth = this.options.strokeWeight;
                this.ctx.lineJoin = 'round';
                this.ctx.beginPath();
                this.ctx.arc(center.x, center.y, this.radius, (Math.PI/180)*arcStart, (Math.PI/180)*(item[1]+arcStart), false);
                this.ctx.lineTo(center.x, center.y);
                this.ctx.closePath();
                this.ctx.stroke();
            }
            if (this.options.showValues) {
				this.ctx.font = 'bold ' + this.options.fontSize + "px " + this.options.font;
                this.ctx.fillStyle = this.options.chartTextColor;
				this.ctx.textAlign = "center";
                var start = (Math.PI/180) * (arcStart);
                var end = (Math.PI/180) * (item[1] + arcStart);
                var centerAngle = start + ((end - start) / 2);
                var percent = Math.round((item[0]/this.pieTotal)*100);
                var centerDist = (percent < 5) ? .90 : 1.75;
                var x = this.radius * Math.cos(centerAngle) / centerDist;
                var y = this.radius * Math.sin(centerAngle) / centerDist;
                this.ctx.fillText(percent + "%", center.x + x, center.y + y);
            }
            arcStart += item[1];
        }.bind(this));
        if (this.options.pieBorder) {
            this.ctx.lineWidth = this.options.chartLineWeight
            this.ctx.strokeStyle = this.options.chartLineColor;
            this.ctx.beginPath();
            this.ctx.arc(center.x, center.y, this.radius-1, 0, Math.PI*2);
            this.ctx.stroke();
        }
    },
    drawKey: function() {
        var keyNameHeight = Math.ceil(this.height * .06);
        var keyHeight = this.data.rowNames.length * keyNameHeight;
		var keyHeight = (keyHeight > this.height) ? this.height * .9 : keyHeight;
        var keyOrigin = (this.height - keyHeight) / 2;
        
        this.ctx.font = 'bold ' + this.options.fontSize + "px " + this.options.font;
        
        this.data.rowNames.each(function(item, index) {
            this.ctx.fillStyle = this.options.fontColor;
            this.ctx.textAlign = "left";
            this.ctx.fillText(MilkChart.escape(item), this.keyBounds[0].x + 14, keyOrigin+8);
            this.ctx.fillStyle = this.colors[index % this.colors.length];
            this.ctx.fillRect(Math.ceil(this.keyBounds[0].x),Math.ceil(keyOrigin),10,10);
            
            keyOrigin += keyNameHeight;
        }.bind(this))
    }
});


MilkChart.Doughnut = new Class({
    /**********************************
    * Pie
    *
    * The Pie chart type has the following options:
    * - stroke: Display an outline around the chart and each slice
    * - strokeWidth: Width of the outline
    * - strokeColor: Color of the outline
    * - shadow (bool): Display shadow under chart
    *********************************/
    Extends: MilkChart.Base,
    options: {
        stroke: true,
        strokeWeight: 1,
        strokeColor: "#ffffff",
        chartTextColor: "#000000",
        shadow: false,
        chartLineWeight: 2,
        pieBorder: false
    },
    initialize: function(el, options) {      
        this.parent(el, options);
        if (this.element.get('tag') == "table") {
        	
	        // Parse the data from the table
	        this.getData();

	        this.rowCount = this.element.getElements('th').length;
			this.colors = this.__getColors(this.options.colors);
			this.options.showRowNames = false;
	        this.render();
        }
    },
    swapAxes1: function() {
    	return true;
    },
    render: function() {
    	this.ctx.save();
    	// Sets up bounds for the graph, key, and other paddings
        this.prepareCanvas();

        this.radius = (this.chartHeight / 2);
        // Draws the key for the graph
        if (this.options.showKey) this.drawKey();
        // Main function to draw the graph
        this.draw();
        this.ctx.restore();
    },
    setData: function(data) {
    	//this.parent(data);
    	this.bounds = [new Point(), new Point(this.width, this.height)];
    	this.colors = this.__getColors(this.options.colors);
    	
		var longestRowName = "";
		data.colNames.each(function(row) {
			if (this.ctx.measureText(row).width > this.ctx.measureText(longestRowName).width) {
            	longestRowName = String(row);
            }
		}, this);
		this.longestRowName = longestRowName;
		var newRows = [];

		data.totals = [];
		data.rows.each(function(row, idx) {
			var pieTotal = 0;
			row.each(function(cell) {
				pieTotal += (typeof(cell) == 'number') ? cell : cell[0];
			});
			var dataRow = [];
			row.each(function(cell) {
				var val = (typeof(cell) == 'number') ? cell : cell[0];
				dataRow.push([val, (val / pieTotal) * 360]);
			});
			newRows.push(dataRow);
			data.totals.push(pieTotal);
		});

    	data.rows = newRows;
    	this.data = data;
    },
    getData: function() {
		this.element.getElements('th').each(function(item) {
		   this.data.colNames.push(item.get('html'));
        }.bind(this));
        
        var rows = this.element.getElements('tbody tr');
        if (this.element.tfoot) {
        	this.element.getElement('tfoot').getChildren().getChildren().each(function(item) {
			   this.data.rowNames.push(item.get('html'));
	        }.bind(this));
        }
        else {
        	rows.each(function(row, idx) {
        		this.data.rowNames.push(this.options.rowPrefix + idx);
        	}, this);
        }
        this.data.totals = [];
		rows.each(function(row, idx) {
			var pieTotal = 0;
			row.getElements('td').each(function(cell) {
				pieTotal += cell.get('html').toInt();
			});
			var dataRow = [];
			row.getElements('td').each(function(cell) {
				dataRow.push([cell.get('html').toInt(), (cell.get('html').toInt() / pieTotal) * 360]);
			});
			this.data.rows.push(dataRow);
			this.data.totals.push(pieTotal);
		}, this);
    },
    draw: function() {
    	var arcStart = 0;
		var center = new Point((this.bounds[1].x / 2) + this.options.padding, (this.bounds[1].y / 2) + this.options.padding);
		var radiusDelta = (this.radius / 2) / this.data.rows.length;
		var radius = Number(this.radius);
    	this.data.rows.each(function(row, idx) {
    		row.each(function(cell, index) {
				this.ctx.fillStyle = this.colors[index % this.colors.length];
	            this.ctx.beginPath();
	            this.ctx.arc(center.x, center.y, radius, (Math.PI / 180) * arcStart, (Math.PI / 180) * (cell[1] + arcStart), false);
	            this.ctx.lineTo(center.x, center.y);
	            this.ctx.closePath();
	            this.ctx.fill();

	            if (this.options.stroke) {
	                this.ctx.strokeStyle = this.options.strokeColor;
	                this.ctx.lineWidth = this.options.strokeWeight;
	                this.ctx.beginPath();
	                this.ctx.arc(center.x, center.y, radius, (Math.PI / 180) * arcStart, (Math.PI / 180) * (cell[1] + arcStart), false);
	                this.ctx.lineTo(center.x, center.y);
	                this.ctx.closePath();
	                this.ctx.stroke();
	            }
	           arcStart += cell[1];
    		}, this);
    		radius -= radiusDelta;
    		arcStart = 0;
    	}, this);
    	
    	this.ctx.beginPath();
    	this.ctx.fillStyle = "#ffffff";
        this.ctx.arc(center.x, center.y, this.radius / 2, 0, Math.PI*2);
        this.ctx.fill();
    },
    drawKey: function() {
        var keyNameHeight = Math.ceil(this.height * .06);
        var keyHeight = this.data.rowNames.length * keyNameHeight;
		var keyHeight = (keyHeight > this.height) ? this.height * .9 : keyHeight;
        var keyOrigin = (this.height - keyHeight) / 2;
        
        this.ctx.font = this.options.fontSize + "px " + this.options.font;
        
        this.data.colNames.each(function(item, index) {
            this.ctx.fillStyle = this.options.fontColor;
            this.ctx.textAlign = "left";
            this.ctx.fillText(MilkChart.escape(item), this.keyBounds[0].x + 14, keyOrigin+8);
            this.ctx.fillStyle = this.colors[index % this.colors.length];
            this.ctx.fillRect(Math.ceil(this.keyBounds[0].x),Math.ceil(keyOrigin),10,10);
            
            keyOrigin += keyNameHeight;
        }.bind(this))
    }
});


// Shapes for tick marks
MilkChart.Shapes = new Object({
	/*********************************************
	 * This object is here for easy reference. Feel
	 * free to add any additional shapes here.
	 ********************************************/
    square: function(ctx, x, y, size, color) {
        ctx.fillStyle = color;
        ctx.fillRect(x-(size/2), y-(size/2), size, size);
    },
    circle: function(ctx, x, y, size, color) {
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.arc(x, y, size/2, 0, (Math.PI/180)*360, true);
        ctx.closePath();
        ctx.fill();
    },
    triangle: function(ctx, x,y,size,color) {
        ctx.fillStyle = color;
        ctx.beginPath();
        x -= size/2;
        y -= size/2;
        lr = new Point(x+size, y+size);
        ctx.moveTo(x, lr.y);
        ctx.lineTo(x + (size/2), y);
        ctx.lineTo(lr.x, lr.y);
        ctx.closePath();
        ctx.fill();
    },
    cross: function(ctx,x,y,size,color) {
        x -= size/2;
        y -= size/2;
        ctx.strokeStyle = color;
        ctx.lineWidth = size / 2;
        ctx.beginPath();
        ctx.moveTo(x,y);
        ctx.lineTo(x+size, y+size);
        ctx.moveTo(x,y+size);
        ctx.lineTo(x+size,y);
        ctx.closePath();
        ctx.stroke();
    },
    diamond: function(ctx,x,y,size,color) {
        x -= size/2;
        y -= size/2;
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.moveTo(x+(size/2), y);
        ctx.lineTo(x+size,y+(size/2));
        ctx.lineTo(x+(size/2),y+size);
        ctx.lineTo(x, y+(size/2));
        ctx.closePath();
        ctx.fill();
    },
    pipe: function(ctx,x,y,size,color) {
    	//x -= size/2;
        y -= size/2;
        ctx.strokeStyle = color;
        ctx.lineWidth = size / 2;
        ctx.beginPath();
        ctx.moveTo(x,y);
        ctx.lineTo(x, y+size);
        ctx.stroke();
    }
})
MilkChart.escape = function(str) {
	str = String(str);
	var patterns = [
		[/\&amp;/g,'&'],
		[/\&lt;/g,'<'],
		[/\&gt;/g,'>']
	]
	patterns.each(function(item) {
		str = str.replace(item[0], item[1]);
	})

	return str
}