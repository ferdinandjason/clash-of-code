class Game{
	constructor(id,map,star,user){
		var canvas = document.getElementById("game");
		canvas.width = 640;
		canvas.height = 640;
		this.ctx = canvas.getContext("2d");
		this.id = id;
		this.user = user;

		var backgroundImage = new Image();
		backgroundImage.src = "../../public/images/tile.png";
		backgroundImage.onload = function(){
			console.log("image is fully loaded");
		};

		var coinImage = new Image();
		coinImage.src = "../../public/images/coin.png";
		coinImage.onload = function(){
			console.log("coin is fully loaded");
		};

		var player = new Image();
		player.src = "../../public/images/idle_1.png";
		player.onload = function(){
			console.log("rabbit is fully loaded");
		}

		var grounds = map;
		var coins = star;

		this.map = {};
		this.map.image = backgroundImage;
		this.map.canvas = this.ctx;
		this.map.tileSize = 64;
		this.map.rowCount = 10;
		this.map.colCount = 10;
		this.map.imageNum = 3;
		this.map.ground = grounds;

		this.sprite_coin = {};
		this.sprite_coin.image = coinImage;
		this.sprite_coin.canvas = this.ctx;
		this.sprite_coin.width = 1000;
		this.sprite_coin.height = 100;
		this.sprite_coin.indexFrame = 3;
		this.sprite_coin.maxFrame = 10;
		this.sprite_coin.loop = true;
		this.sprite_coin.scaleFactor = 0.3;

		this.player = {};
		this.player.image = player;
		this.player.canvas = this.ctx;
		this.player.width = 150;
		this.player.height = 210;
		this.player.indexFrame = 1;
		this.player.maxFrame = 1;
		this.player.loop = false;
		this.player.scaleFactor = 0.4;


		this.background = new Map(this.map);
		this.stars = [];
		for(var r = 0; r < this.map.rowCount; r++){
			for(var c = 0; c < this.map.colCount; c++){
				if(coins[r][c]==1){
					var x = c * this.map.tileSize + (this.map.tileSize/2 - (this.sprite_coin.width/this.sprite_coin.maxFrame*this.sprite_coin.scaleFactor)/2);
					var y = r * this.map.tileSize + (this.map.tileSize/2 - (this.sprite_coin.width/this.sprite_coin.maxFrame*this.sprite_coin.scaleFactor)/2);
					this.stars.push(new Sprite(this.sprite_coin,x,y,this.map.tileSize));
				}
			}
		}
		this.pemain = new Player(this.player,0+(this.map.tileSize/2 - (this.player.width*this.player.scaleFactor/2)),canvas.height - (this.player.height*this.player.scaleFactor) - this.map.tileSize/2 + 10,grounds,this.map.tileSize);

		this.fps = 40;
	}

	render(){
		this.background.render()
		this.pemain.render();
		for(var i = 0; i < this.stars.length ; i++){
			this.stars[i].render();
		}
	}

	update(){
		this.pemain.update();
		for(var i = 0; i < this.stars.length ; i++){
			this.stars[i].update();
		}
	}

	loop(){
		var that = this;
		setTimeout(function(){
			that.check_star();
			that.render();
			that.update();
            that.check_menang();
			requestAnimationFrame(that.loop.bind(that));
		},1000/that.fps);
	}

	run(){
		var that = this;
		setTimeout(function(){
			requestAnimationFrame(that.loop.bind(that));
		},1000/that.fps);
	}

	Pemain(){
		return this.pemain;
	}

	check_menang(){
		var x = this.pemain.x;
		var y = this.pemain.y;
		var g = this.pemain.grounds;
		if(g[y][x] == 1){
            $.ajax({
                url:'/coc/game/room/clear',
                type:'POST',
                data:{
                    star:this.pemain.stars,
                    step:step,
                    room_id:this.id,
                    user_id:this.user,
                }
            });
			alert('Kamu selesai dalam '+step+'step dan mendapatkan '+this.pemain.stars+' exp');
			location.href = '/coc/'
		}
	}

	check_star(){
		var x = this.pemain.x * this.map.tileSize + (this.map.tileSize/2 - (this.sprite_coin.width/this.sprite_coin.maxFrame*this.sprite_coin.scaleFactor)/2);
		var y = this.pemain.y * this.map.tileSize + (this.map.tileSize/2 - (this.sprite_coin.width/this.sprite_coin.maxFrame*this.sprite_coin.scaleFactor)/2);

		var index = -1;
		for(var i = 0; i < this.stars.length; i++){
			if(x == this.stars[i].dx && y == this.stars[i].dy){
				index = i;
				break;
			}
		}
		if(index != -1){
			// dapat bintang
			this.pemain.stars += 1;
			this.stars.splice(index,1);
		}
	}
}

class Map{
	constructor(options){
		this.image = options.image;
		this.canvas = options.canvas;
		this.tileSize = options.tileSize;
		this.rowCount = options.rowCount;
		this.colCount = options.colCount;
		this.imageNum = options.imageNum;
		this.ground = options.ground;
	}

	render(){
		for(var r = 0; r < this.rowCount; r++){
			for(var c = 0; c < this.colCount; c++){
				var tileType = this.ground[r][c];
				var tileRow = (tileType / this.imageNum) | 0;
				var tileCol = (tileType % this.imageNum) | 0;
				this.canvas.drawImage(
					this.image,
					(tileCol*this.tileSize),
					(tileRow*this.tileSize),
					this.tileSize,
					this.tileSize,
					(c*this.tileSize),
					(r*this.tileSize),
					this.tileSize,
					this.tileSize);
			}
		}
	}	
}

class Sprite{
	constructor(options,x,y,tileSize,p_index=0){
		this.canvas = options.canvas;
		this.width = options.width;
		this.height = options.height;
		this.image = options.image;

		this.frameIndex = p_index;
		this.index =  p_index;
		this.indexFrame = options.indexFrame || 0; 
		this.maxFrame = options.maxFrame || 1;

		this.scaleFactor = options.scaleFactor;

		this.loop = options.loop;

		this.dx = x;
		this.dy = y;

		this.tileSize = tileSize;
	}

	render(){
		this.canvas.drawImage(
			this.image,
			this.frameIndex * this.width / this.maxFrame,
			 0,
			this.width / this.maxFrame,
			this.height,
			this.dx,
			this.dy,
			this.width / this.maxFrame * this.scaleFactor,
			this.height * this.scaleFactor);
	}

	update(){
		this.index += 1;
		if(this.index > this.indexFrame){
			this.index = 0;

			if(this.frameIndex < this.maxFrame -1){
				this.frameIndex+= 1;
			} else if(this.loop){
				this.frameIndex = 0;
			}
		}
	}

	update_pos(x,y){
		this.dx += x*(this.tileSize);
		this.dy += y*(this.tileSize);
		
	}
}

class Player{
	constructor(options,initial_x,initial_y,grounds,tileSize){
		this.sprite = new Sprite(options,initial_x,initial_y,tileSize,1);
		this.position = 0;
		this.x = 0;
		this.y = 9;
		this.grounds = grounds;
		this.stars = 0;
	}

	handle(direction){
		if(direction === "lurus"){
			console.log(this.position);
			if(this.position == 0){this.update_pos(1,0);}  // ke kanan
			if(this.position == 1){this.update_pos(0,-1);}  // ke atas
			if(this.position == 2){this.update_pos(-1,0);} // ke kiri
			if(this.position == 3){this.update_pos(0,1);} // ke bawah
		}
		if(direction === "kiri"){
			this.position += 1;
			this.sprite.frameIndex -=1;
			this.sprite.index -=1;
			if(this.position < 0){
				this.position += 4;
			}
			if(this.sprite.frameIndex<0){
				this.sprite.frameIndex +=4;
			}
			if(this.sprite.index<0){
				this.sprite.index +=4;
			}
			this.sprite.index %=4;
			this.sprite.frameIndex %= 4;
			this.position %= 4;
		}
		if(direction === "kanan"){
			this.position -= 1;
			this.sprite.frameIndex +=1;
			this.sprite.index +=1;
			if(this.position < 0){
				this.position += 4;
			}
			if(this.sprite.frameIndex<0){
				this.sprite.frameIndex +=4;
			}
			if(this.sprite.index<0){
				this.sprite.index +=4;
			}
			this.sprite.index %=4;
			this.sprite.frameIndex %= 4;
			this.position %= 4;
		}
	}

	update_pos(dx,dy){
		if((this.x + dx < 10) && (this.y + dy < 10)){
			if(this.grounds[this.y+dy][this.x+dx] == 0 || this.grounds[this.y+dy][this.x+dx] == 1){
				// this.sprite.frameIndex += 1;
				// this.sprite.index += 1;
				// this.sprite.frameIndex %= 4;
				// this.sprite.index %= 4;
				this.sprite.update_pos(dx,dy);
				this.x+=dx;
				this.y+=dy;
				console.log(this.x);
				console.log(this.y);
			}
		}
	}

	update(){
		this.sprite.update();
	}

	render(){
		this.sprite.render();
	}
}