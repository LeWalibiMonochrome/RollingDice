var rollroll = rollroll || {};

(function(rollroll){
	var Dice = function($div,$i,$clr) {
		  this.div = document.getElementById($div);
		  this.i = $i;
		  this.val = 0;
		  this.selected = false;

		  $clr = $clr || '#FFFFFF';
		  this.div.style.background = $clr;

		  var s = '<table>';
		  for(var i=0; i<3; i++) {
			  s += '<tr>';
			  for(var j=0; j<3; j++) {
				  s += '<td id="dice'+this.i+'_'+i+'_'+j+'"></td>';
			  }
			  s += '</tr>';
		  }
		  this.div.innerHTML = s+'</table>';
		  this.updateSelection();
	  };
	  var p = Dice.prototype;
	  p.select = function() {
		  this.selected = !this.selected;
		  this.updateSelection();
	  }
	  p.updateSelection = function() {
		  this.div.className = "dice" + (this.selected ? " diceSelected" : "");
	  }
	  p.setCase = function($i,$j,$v) {
		  document.getElementById('dice'+this.i+'_'+$i+'_'+$j).innerHTML = ($v?'<span class="diceBall"> </span>':'');
	  };
	  p.resetCases = function() {
		  	for(var i=0;i<3;i++) {
			  for(var j=0;j<3;j++) {
				  this.setCase(i,j,false);
			  }
		  }
	  }
	  p.setCases = function() {
	  		for(var i=0,j=arguments.length;i<j;i+=2) {
	  			this.setCase(arguments[i],arguments[i+1],true);
	  		}
	  };
	  p.set = function($i) {
		  if(!$i) {
			  while(!$i || $i == this.val) {
				  $i = 1+Math.floor(Math.random()*6);
			  }
		  }
		  this.val = $i;
		  this.resetCases();
		  switch($i) {
			  case 1: this.setCases(1,1); break;
			  case 2: this.setCases(0,0,2,2); break;
			  case 3: this.setCases(0,0,1,1,2,2); break;
			  case 4: this.setCases(0,0,2,0,0,2,2,2); break;
			  case 5: this.setCases(0,0,2,0,0,2,2,2,1,1); break;
			  case 6: this.setCases(0,0,1,0,2,0,0,2,1,2,2,2); break;
			  default: break;
		  }
	};
	rollroll.Dice = Dice;
})(rollroll);