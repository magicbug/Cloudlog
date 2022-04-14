// HamGridSquare.js
// Copyright 2014 Paul Brewer KI6CQ
// License:  MIT License http://opensource.org/licenses/MIT
//
// Javascript routines to convert from lat-lon to Maidenhead Grid Squares
// typically used in Ham Radio Satellite operations and VHF Contests
//
// Inspired in part by K6WRU Walter Underwood's python answer
// http://ham.stackexchange.com/a/244
// to this stack overflow question:
// How Can One Convert From Lat/Long to Grid Square
// http://ham.stackexchange.com/questions/221/how-can-one-convert-from-lat-long-to-grid-square
//

latLonToGridSquare = function(param1,param2){
  var lat=-100.0;
  var lon=0.0;
  var adjLat,adjLon,GLat,GLon,nLat,nLon,gLat,gLon,rLat,rLon;
  var U = 'ABCDEFGHIJKLMNOPQRSTUVWX'
  var L = U.toLowerCase();
  // support Chris Veness 2002-2012 LatLon library and
  // other objects with lat/lon properties
  // properties could be numbers, or strings
  function toNum(x){
    if (typeof(x) === 'number') return x;
    if (typeof(x) === 'string') return parseFloat(x);
    // dont call a function property here because of binding issue
    throw "HamGridSquare -- toNum -- can not convert input: "+x;
  }
  if (typeof(param1)==='object'){
    if (param1.length === 2){
      lat = toNum(param1[0]);
      lon = toNum(param1[1]);
    } else if (('lat' in param1) && ('lon' in param1)){
      lat = (typeof(param1.lat)==='function')? toNum(param1.lat()): toNum(param1.lat);
      lon = (typeof(param1.lon)==='function')? toNum(param1.lon()): toNum(param1.lon);
    } else if (('latitude' in param1) && ('longitude' in param1)){
      lat = (typeof(param1.latitude)==='function')? toNum(param1.latitude()): toNum(param1.latitude);
      lon = (typeof(param1.longitude)==='function')? toNum(param1.longitude()): toNum(param1.longitude);
    } else {
      throw "HamGridSquare -- can not convert object -- "+param1;
    }
  } else {
    lat = toNum(param1);
    lon = toNum(param2);
  }
  if (isNaN(lat)) throw "lat is NaN";
  if (isNaN(lon)) throw "lon is NaN";
  if (Math.abs(lat) === 90.0) throw "grid squares invalid at N/S poles";
  if (Math.abs(lat) > 90) throw "invalid latitude: "+lat;
  if (Math.abs(lon) > 180) throw "invalid longitude: "+lon;
  adjLat = lat + 90;
  adjLon = lon + 180;
  GLat = U[Math.trunc(adjLat/10)];
  GLon = U[Math.trunc(adjLon/20)];
  nLat = ''+Math.trunc(adjLat % 10);
  nLon = ''+Math.trunc((adjLon/2) % 10);
  rLat = (adjLat - Math.trunc(adjLat)) * 60;
  rLon = (adjLon - 2*Math.trunc(adjLon/2)) *60;
  gLat = L[Math.trunc(rLat/2.5)];
  gLon = L[Math.trunc(rLon/5)];
  return GLon+GLat+nLon+nLat+gLon+gLat;
}

gridSquareToLatLon = function(grid, obj){
  var returnLatLonConstructor = (typeof(LatLon)==='function');
  var returnObj = (typeof(obj)==='object');
  var lat=0.0,lon=0.0,aNum="a".charCodeAt(0),numA="A".charCodeAt(0);
  function lat4(g){
    return 10*(g.charCodeAt(1)-numA)+parseInt(g.charAt(3))-90;
  }
  function lon4(g){
    return 20*(g.charCodeAt(0)-numA)+2*parseInt(g.charAt(2))-180;
  }
  if ((grid.length!=4) && (grid.length!=6)) throw "gridSquareToLatLon: grid must be 4 or 6 chars: "+grid;
  if (/^[A-X][A-X][0-9][0-9]$/.test(grid)){
    lat = lat4(grid)+0.5;
    lon = lon4(grid)+1;
  } else if (/^[A-X][A-X][0-9][0-9][a-x][a-x]$/.test(grid)){
    lat = lat4(grid)+(1.0/60.0)*2.5*(grid.charCodeAt(5)-aNum+0.5);
    lon = lon4(grid)+(1.0/60.0)*5*(grid.charCodeAt(4)-aNum+0.5);
  } else throw "gridSquareToLatLon: invalid grid: "+grid;
  if (returnLatLonConstructor) return new LatLon(lat,lon);
  if (returnObj){
    obj.lat = lat;
    obj.lon = lon;
    return obj;
  }
  return [lat,lon];
};

testGridSquare = function(){
  // First four test examples are from "Conversion Between Geodetic and Grid Locator Systems",
  // by Edmund T. Tyson N5JTY QST January 1989
  // original test data in Python / citations by Walter Underwood K6WRU
  // last test and coding into Javascript from Python by Paul Brewer KI6CQ
  var testData = [
    ['Munich', [48.14666,11.60833], 'JN58td'],
    ['Montevideo', [[-34.91,-56.21166]], 'GF15vc'],
    ['Washington, DC', [{lat:38.92,lon:-77.065}], 'FM18lw'],
    ['Wellington', [{latitude:-41.28333,longitude:174.745}], 'RE78ir'],
    ['Newington, CT (W1AW)', [41.714775,-72.727260], 'FN31pr'],
    ['Palo Alto (K6WRU)', [[37.413708,-122.1073236]], 'CM87wj'],
    ['Chattanooga (KI6CQ/4)', [{lat:function(){ return "35.0542"; }, 
                              lon: function(){ return "-85.1142"}}], "EM75kb"]
  ];
  var i=0,l=testData.length,result='',result2,result3,thisPassed=0,totalPassed=0;
  for(i=0;i<l;++i){
    result = latLonToGridSquare.apply({}, testData[i][1]);
    result2 = gridSquareToLatLon(result);
    result3 = latLonToGridSquare(result2);
    thisPassed = (result===testData[i][2]) && (result3===testData[i][2]);
    console.log("test "+i+": "+testData[i][0]+" "+JSON.stringify(testData[i][1])+
                " result = "+result+" result2 = "+result2+" result3 = "+result3+" expected= "+testData[i][2]+
                " passed = "+thisPassed);
    totalPassed += thisPassed;
  }
  console.log(totalPassed+" of "+l+" test passed");
  return totalPassed===l;
};

HamGridSquare = {
  toLatLon: gridSquareToLatLon,
  fromLatLon: latLonToGridSquare,
  test: testGridSquare
};

