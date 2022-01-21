function frequencyToBand(frequency) {
	result = parseInt(frequency);
				
	if(result >= 14000000 && result <= 14400000) {
		return '20m';
	}
	else if(result >= 18000000 && result <= 19000000) {
		return '17m';
	}
	else if(result >= 1810000 && result <= 2000000) {
		return '160m';
	}
	else if(result >= 3000000 && result <= 4000000) {
		return '80m';
	}
	else if(result >= 5250000 && result <= 5450000) {
		return '60m';
	}
	else if(result >= 7000000 && result <= 7500000) {
		return '40m';
	}
	else if(result >= 10000000 && result <= 11000000) {
		return '30m';
	}
	else if(result >= 21000000 && result <= 21600000) {
		return '15m';
	}
	else if(result >= 24000000 && result <= 25000000) {
		return '12m';
	}
	else if(result >= 28000000 && result <= 30000000) {
		return '10m';
	}
	else if(result >= 50000000 && result <= 56000000) {
		return '6m';
	}
	else if(result >= 144000000 && result <= 148000000) {
		return '2m';
	}
	else if(result >= 219000000 && result <= 225000000) {
		return '1.25m';
	}
	else if(result >= 430000000 && result <= 440000000) {
		return '70cm';
	}
	else if(result >= 902000000 && result <= 928000000) {
		return '33cm';
	}
	else if(result >= 1200000000 && result <= 1600000000) {
		return '23cm';
	}
	else if(result >= 2300800000 && result <= 2890800000) {
		return '13cm';
	}
	else if(result >= 3300000000 && result <= 3450000000) {
		return '9cm';
	}
	else if(result >= 5650000000 && result <= 5700000000) {
		return '6cm';
	}
	else if(result >= 10125000000 && result <= 10525000000) {
		return '3cm';
	}
}

function LatLng2Loc(y, x, num) {
	if (x<-180) {x=x+360;}
	if (x>180) {x=x-360;}
	var yqth, yi, yk, ydiv, yres, ylp, y;
    var ycalc = new Array(0,0,0);
    var yn    = new Array(0,0,0,0,0,0,0);

    var ydiv_arr=new Array(10, 1, 1/24, 1/240, 1/240/24);
    ycalc[0] = (x + 180)/2;
    ycalc[1] =  y +  90;

    for (yi = 0; yi < 2; yi++) {
	for (yk = 0; yk < 5; yk++) {
	    ydiv = ydiv_arr[yk];
	    yres = ycalc[yi] / ydiv;
	    ycalc[yi] = yres;
	    if (ycalc[yi] > 0) ylp = Math.floor(yres); else ylp = Math.ceil(yres);
	    ycalc[yi] = (ycalc[yi] - ylp) * ydiv;
	    yn[2*yk + yi] = ylp;
	}
    }

    var qthloc="";
    if (num >= 2) qthloc+=String.fromCharCode(yn[0] + 0x41) + String.fromCharCode(yn[1] + 0x41);
    if (num >= 4) qthloc+=String.fromCharCode(yn[2] + 0x30) + String.fromCharCode(yn[3] + 0x30);
    if (num >= 6) qthloc+=String.fromCharCode(yn[4] + 0x41) + String.fromCharCode(yn[5] + 0x41);
    if (num >= 8) qthloc+=' ' + String.fromCharCode(yn[6] + 0x30) + String.fromCharCode(yn[7] + 0x30);
    if (num >= 10) qthloc+=String.fromCharCode(yn[8] + 0x61) + String.fromCharCode(yn[9] + 0x61);
	return qthloc;
	
}