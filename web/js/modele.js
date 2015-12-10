var rollroll = rollroll || {};

(function(rollroll){
  function Game(){
    this.orange = [9,8,0,0,5,4,0,2,1];
    this.jaune = [3,1,4,1,5,1,6,1,7];
    this.violet = [1,2,3,4,5,6,7,8,9];
    this.missed = 0;
    this.colorsScore = [0, 0, 0]; // orange, jaune, violet
    this.pentagonScore = [0, 0, 0, 0, 0];
    this.totalScore = 0;
    this.isRolling = false;
  };
  
  var p = Game.prototype;

  p.calculateScore = function(){
    this.colorsScore[0] = 0; this.colorsScore[1] = 0; this.colorsScore[2] = 0;
    for(var i=0; i<5; i++){
      this.pentagonScore[i] = 0;
    }
    for(var i=0; i<9; i++){
      this.colorsScore[0] = this.orange[i]; this.colorsScore[1] = this.jaune[i]; this.colorsScore[2] = this.violet[i];
    }
    if (this.orange[0] !== 0 && this.jaune[1] !== 0) this.pentagonScore[0] = this.violet[2];
    if (this.jaune[2] !== 0 && this.violet[3] !== 0) this.pentagonScore[1] = this.orange[1];
    if (this.jaune[5] !== 0 && this.violet[6] !== 0) this.pentagonScore[2] = this.orange[4];
    if (this.orange[5] !== 0 && this.violet[7] !== 0) this.pentagonScore[3] = this.jaune[6];
    if (this.orange[6] !== 0 && this.jaune[7] !== 0) this.pentagonScore[4] = this.violet[8];
    
    this.totalScore = this.colorsScore[0] + this.colorsScore[1] +  this.colorsScore[2];
    this.totalScore += this.pentagonScore[0] + this.pentagonScore[1] + this.pentagonScore[2] + this.pentagonScore[3] + this.pentagonScore[4];
    this.totalScore -= this.missed * 5;
};
  
  p.getCase = function(color, position){
  switch(color){
  case 0:
    if (this.orange[position] === 0) 
      return "&nbsp;"
    return this.orange[position];
  case 1:
    if (this.jaune[position] === 0) 
      return "&nbsp;"
    return this.jaune[position];
  case 2:
    if (this.violet[position] === 0) 
      return "&nbsp;"
    return this.violet[position];
  }
  };  
})(rollroll);
