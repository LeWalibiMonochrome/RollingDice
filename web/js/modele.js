var rollroll = rollroll || {};

(function(rollroll){
  function Game(orange, jaune, violet, missed){
    this.orange = orange || [0,0,0,0,0,0,0,0,0];
    this.jaune = jaune || [0,0,0,0,0,0,0,0,0];
    this.violet = violet || [0,0,0,0,0,0,0,0,0];
    this.missed = missed || 0;
    this.colorsScore = [0, 0, 0]; // orange, jaune, violet
    this.pentagonScore = [0, 0, 0, 0, 0];
    this.totalScore = 0;
    this.isRolling = false;
  };
  
  var p = Game.prototype;

  p.refreshScore = function(){
    for(var i=0; i<missed; i++)
      document.getElementById("m"+i).innerHTML = "<span>X</span>";
    this.calculateScore();
    for(var i=0; i <3 /*cookies*/; i++)
      document.getElementById("sc"+i).innerHTML = "<span>"+this.prettify(this.colorsScore[i])+"</span>";
    for(var i=0; i<5; i++)
      document.getElementById("sp"+i).innerHTML = "<span>"+this.prettify(this.pentagonScore[i])+"</span>";
    document.getElementById("sm").innerHTML = "<span>"+this.prettify(this.missed * 5)+"</span>";    
    document.getElementById("st").innerHTML = "<span>"+this.prettify(this.totalScore)+"</span>";
  }

  p.calculateScore = function(){
    this.colorsScore[0] = 0; this.colorsScore[1] = 0; this.colorsScore[2] = 0;
    for(var i=0; i<5; i++){
      this.pentagonScore[i] = 0;
    }

    var max=0, nbr=0;
    for(var i=0; i<9; i++){
      if (this.orange[i] != 0){
	max = Math.max(max, this.orange[i]);
        nbr++;
      }
    }
    if (nbr == 9)  this.colorsScore[0] = max;
    else  this.colorsScore[0] = nbr;

    max=0; nbr=0;
    for(var i=0; i<9; i++){
      if (this.jaune[i] != 0){
	max = Math.max(max, this.jaune[i]);
        nbr++;
      }
    }
    if (nbr == 9)  this.colorsScore[1] = max;
    else  this.colorsScore[1] = nbr;

    max=0; nbr=0;
    for(var i=0; i<9; i++){
      if (this.violet[i] != 0){
	max = Math.max(max, this.violet[i]);
        nbr++;
      }
    }
    if (nbr == 9)  this.colorsScore[2] = max;
    else  this.colorsScore[2] = nbr;

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
      return this.prettify(this.orange[position]);
    case 1:
      return this.prettify(this.jaune[position]);
    case 2:
      return this.prettify(this.violet[position]);
    }
  };
  
  p.setCase = function(color, position, valeur){
    switch(color){
    case 0:
      this.orange[position] = valeur;
      document.getElementById("o"+position).innerHTML = "<span>"+this.orange[position]+"</span>";
      break;
    case 1:
      this.jaune[position] = valeur;
      document.getElementById("j"+position).innerHTML = "<span>"+this.jaune[position]+"</span>";
      break;
    case 2:
      this.violet[position] = valeur;
      document.getElementById("v"+position).innerHTML = "<span>"+this.violet[position]+"</span>";
      break;
    }
    this.refreshScore();
  };  

  p.addMiss = function(){
    this.missed++;
  }
  
  p.prettify = function(value){
    if (value == 0) 
      return "&nbsp;"
    return value;
  };

  rollroll.Game = Game;
})(rollroll);
