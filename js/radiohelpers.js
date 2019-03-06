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
	else if(result >= 430000000 && result <= 440000000) {
		return '70cm';
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