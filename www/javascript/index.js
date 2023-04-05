// Get access to the canvas element
const canvas = document.querySelector('canvas');

// This returns a drawing context, in this case to draw in 2d 
const c = canvas.getContext('2d');
c.globalCompositeOperation = 'lighter';

// Helper Functions
function createImage(imgSrc, w, h) {
    const image = new Image(w, h);
    image.src = imgSrc;
    return image;
}


function collisionCheck(obj1, obj2){

	return (obj1.x + obj1.w >= obj2.x
		&& 	obj1.x 			<= obj2.x + obj2.w
		&& 	obj1.y + obj1.w >= obj2.y
		&& 	obj1.y 			<= obj2.y + obj2.h);
}


function drawText(text, x, y){
	c.fillStyle = 'black';
	c.font = game.font;
	c.fillText(text, x, y)
}


function playAudio(audio, volume, loop){
	audio.currentTime = 0;
	audio.volume = volume;
	audio.loop = loop;
	audio.play();
}


function getObject(name){
	let ret = null;

	game.objects.forEach((obj) => {
		
		if(obj.constructor.name === name)
			ret = obj;
	});
	return ret;
}


function uploadScore(value){
	const form = document.getElementById('form-input');
	const score = document.getElementById('score');
	score.value = value;
	form.submit();
}


const game = {

	points: 0,
	gravity: 0.5,
	objects: [],

	cam: {
		x: 0,
		y: 0,
		w: c.canvas.clientWidth,
		h: c.canvas.clientHeight
	},
	
	world: {
		w: 0,
		h: 0
	},

	keys: {
		right: 	{id: 68, pressed: false},
		left: 	{id: 65, pressed: false},
		up: 	{id: 87, pressed: false},
		down: 	{id: 83, pressed: false}
	},

	sfx: {
		coin: new Audio('sfx/coinsfx.mp3'),
		death: new Audio('sfx/death.mp3'),
		music: new Audio('sfx/backgroundmusic.mp3')
	},
	
	img: {
		coin: 		createImage('images/coin.png', 100, 100),
		block:	 	createImage('images/dirtblock.png', 100, 100),
		platform:	createImage('images/dirt.png', 500, 100),
		background: createImage('images/background.jpg'),
		flag:		createImage('images/flag.png', 100, 100)
	},

	font: "48px serif"
};


class GenericObject {


    constructor(x, y, image, w, h) {
        this.position = {x: x, y: y};
		this.velocity = {x: 0, y: 0};
		this.static = true;
		this.solid = true;
		this.alive = true;

		if(image === undefined || image === null){
			this.image = null;
			this.width = w;
			this.height = h;
		}else{
			this.image = image;
		
			if(w === undefined)
				this.width = image.width;
			else
				this.width = w;

			if(h === undefined)
				this.height = image.height;
			else
				this.height = h;
		}
    }
	

	update(){

		// Do physics if not static
		if(this.static === false){
			this.velocity.y += game.gravity;

			if(this.collides("Platform", {x: 0, y: this.velocity.y}) === false){
				this.onGround = false;
				this.position.y += this.velocity.y;

			}else{
				this.onGround = true;
				this.velocity.y = 0;
			}

			if(this.collides("Platform", {x: this.velocity.x, y: 0}) === false){
				this.position.x += this.velocity.x;
			}else{
				this.velocity.x = 0;
			}
		}
	}


    draw(){
		let x = this.position.x - game.cam.x;
		let y = this.position.y - game.cam.y;

		if (this.image !== null){
			c.drawImage(
				this.image, 
				x, 
				y, 
				this.width, 
				this.height
			);

		}else{
			c.fillStyle = 'red';
			c.fillRect(
				x, 
				y, 
				this.width, 
				this.height
			);
		}
	}

	
	destroy(){
		this.alive = false;
	}


	collides(objName, transition){

		if(transition === undefined)
			transition = {x: 0, y: 0};

		for(let i = 0; i < game.objects.length; i += 1){
			let obj = game.objects[i];
			
			if (obj.constructor.name === objName && obj !== this){
				let pos1 = {
					x: this.position.x + transition.x,
					y: this.position.y + transition.y,
					w: this.width,
					h: this.height
				};
				let pos2 = {
					x: obj.position.x + obj.velocity.x,
					y: obj.position.y + obj.velocity.y,
					w: obj.width,
					h: obj.height
				};

				if(collisionCheck(pos1, pos2) === true)
					return true;		
			}
		}
		return false;
	}
} 


class Player extends GenericObject {


    constructor(x, y) {
		super(x, y, null, 30, 30);
		this.static = false;
    }


    update() {
		super.update();

		if (game.keys.right.pressed) {
			this.velocity.x = 5;

		}else if (game.keys.left.pressed) {
			this.velocity.x = -5;
		
		}else{
			this.velocity.x = 0;
		}

		if(game.keys.up.pressed && this.onGround === true)
			this.velocity.y = -15;

		// Lose condition
		if (this.position.y > game.world.h) {
			playAudio(game.sfx.death, 1.0, false);
			init();
		}
    }
}


class Platform extends GenericObject {


    constructor(x, y, image, w, h) {
        super(x, y, image, w, h);
    }
}


class Coin extends GenericObject {


    constructor(x, y) {
        super(x, y, game.img.coin);
		this.solid = false;
    }


	update(){

		if(this.collides("Player")){
			playAudio(game.sfx.coin, 1.0, false);
			game.points += 100;
			this.destroy();
		}
	}
}


class Flag extends GenericObject {


    constructor(x, y) {
        super(x, y, game.img.flag);
		this.solid = false;
    }


	update(){
		// Win Condition
		if (this.collides('Player'))
			uploadScore(game.points);
	}
}


class Camera extends GenericObject{


	constructor(){ 
		super(0, 0, null, 0, 0);
		this.solid = false;

		this.cam = game.cam;
		this.velocity = {x: 0, y: 0};
	}
	

	draw(){ /* Draw nothing */ }
	

	update(){
		let player = getObject('Player');	

		if(player === null)
			return;

		let targetX = player.position.x + player.velocity.x * 75 - this.cam.w/2;

		this.velocity.x = (targetX - this.cam.x)/50;
		this.cam.x += this.velocity.x;

		let targetY = player.position.y - this.cam.h/2;

		this.velocity.y = (targetY - this.cam.y)/50;
		this.cam.y += this.velocity.y;


		// Clamp Camera to bounds
		if(this.cam.x < 0)
			this.cam.x = 0;

		if(this.cam.x > game.world.w - this.cam.w)
			this.cam.x = game.world.w - this.cam.w;

		if(this.cam.y > game.world.h - this.cam.h)
			this.cam.y = game.world.h - this.cam.h;
	}
}


// Reinitializes the player and map in case of DEATH
function init() {
	
	game.objects = [];
    game.points = 0;

	// Camera
	game.objects.push(new Camera());

	// Load the level from image
	level = new Image();
	level.src = 'images/levels/level.png';

	level.addEventListener('load', () => {

		// Create temporary canvas' for the level loader
		let temp_canvas = document.createElement('canvas');
		let temp_ctx = temp_canvas.getContext('2d');

		temp_ctx.width = level.width;
		temp_ctx.height = level.height;
		temp_ctx.drawImage(level, 0, 0);

		// Check the pixels vs threshold to choose type
		for(let x = 0; x < level.width; x ++){
			for(let y = 0; y < level.height; y ++){

				// Image recognition threshold
				let thresh = 128;
				let pixel = temp_ctx.getImageData(x, y, 1, 1);

				// Alpha is visible
				if(pixel.data[3] > 0){

					// Player 
					if(pixel.data[0] > thresh && pixel.data[1] < thresh && pixel.data[2] < thresh)
						game.objects.push(new Player(x * 100, y * 100));

					// Flag
					else if(pixel.data[0] < thresh && pixel.data[1] > thresh && pixel.data[2] > thresh)
						game.objects.push(new Flag(x * 100, y * 100));

					// Platform Block
					else if(pixel.data[0] < thresh && pixel.data[1] < thresh && pixel.data[2] < thresh)
						game.objects.push(new Platform(x * 100, y * 100, game.img.platform, 100, 100));

					// Coins
					else if(pixel.data[0] > thresh && pixel.data[1] > thresh && pixel.data[2] < thresh)
						game.objects.push(new Coin(x * 100, y * 100));
				}
			}
		}

		// Cleanup temporary canvas
		temp_canvas.remove();

		// Find world limits
		game.objects.forEach((obj) => {

			if(obj.position.x + obj.width > game.world.w)
				game.world.w = obj.position.x + obj.width;

			if(obj.position.y + obj.height > game.world.h)
				game.world.h = obj.position.y + obj.height;
		});
	});
}


function animate() {
    requestAnimationFrame(animate);
    c.clearRect(0, 0, canvas.width, canvas.height);

	// Let objects do their function loops
	game.objects.forEach((obj) => {
		obj.draw();
		obj.update();		
	});

	// Remove non-alive objects
	for(let i = 0; i < game.objects.length; i += 1){
		let obj = game.objects[i];

		if(obj.alive === false){
			game.objects.splice(i, 1);
			i -= 1;
		}
	}

    drawText(game.points, 800, 50);
}


// Initialize Game
init();
animate();
playAudio(game.sfx.music, 0.1, true);


// Event Listeners
addEventListener('keydown', (event) => {

	Object.entries(game.keys).forEach(([key, value]) => {

		if(value.id == event.keyCode)
			value.pressed = true;
	});
})


addEventListener('keyup', (event) => {

	Object.entries(game.keys).forEach(([key, value]) => {

		if(value.id == event.keyCode)
			value.pressed = false;
	});
})
