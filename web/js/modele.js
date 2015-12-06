var Score = {
  colors : [0, 0, 0], // red, yellow, violet
  pentagon : [0, 0, 0, 0, 0],
  missed : 0,
  total : 0
};

function Game(){
  this.orange = [9,8,7,6,5,4,3,2,1];
  this.jaune = [3,1,4,1,5,1,6,1,7];
  this.violet = [1,2,3,4,5,6,7,8,9];
};

var p = Game.prototype;

/*p.calculateScore = function(){
  colors[0] = 0; colors[1] = 0; colors[2] = 0;
  for(var i=0; i<9; i++){
    colors[0] = red[i]; colors[1] = yellow[i]; colors[2] = violet[i];
  }
  if (red[0] !== 0 && yellow[1] !== 0) pentagon[0] = violet[2];
  if (yellow[2] !== 0 && violet[3] !== 0) pentagon[1] = red[1];
  if (yellow[5] !== 0 && violet[6] !== 0) pentagon[2] = red[4];
  if (red[5] !== 0 && violet[7] !== 0) pentagon[3] = yellow[6];
  if (red[6] !== 0 && yellow[7] !== 0) pentagon[4] = violet[8];
  total = colors[0] + colors[1] +  colors[2];
  total += pentagon[0] + pentagon[1] + pentagon[2] + pentagon[3] + pentagon[4];
  total -= missed * 5;
};*/

p.getCase = function(color, position){
  switch(color){
  case 0: 
    return this.orange[position];
    break;
  case 1:
    return this.jaune[position];
    break;
  case 2:
    return this.violet[position];
    break;
  }
};

p.chooseCase = function(color, position){
  
};

p.chooseDices = function(red, yellow, violet){
  
};