var Game = {
  red : [],
  yellow : [],
  violet : [],
  missed,
  score : new Score
};

var Score = {
  colors : [0, 0, 0] // red, yellow, violet
  pentagon : [0, 0, 0, 0, 0],
  missed : 0
  total : 0
}

Game.calculateScore = function(){
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
};

Game.sendSelectedDice = function(){
  
};

Game.sendSelectedBox = function(){

};

game = new Game();
