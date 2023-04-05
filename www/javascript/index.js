// Get access to the canvas element
const canvas = document.querySelector('canvas');

// This returns a drawing context, in this case to draw in 2d 
const c = canvas.getContext('2d');


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
	c.font = game.font;
	c.fillText(text, x, y)
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
	
	keys: {
		right: 	{id: 68, pressed: false},
		left: 	{id: 65, pressed: false},
		up: 	{id: 87, pressed: false},
		down: 	{id: 83, pressed: false}
	},

	sfx: {
		coin: new Audio('sfx/coinsfx.mp3')
	},
	
	img: {
		coin: 		createImage('images/coin.png', 100,100),
		block:	 	createImage('images/dirtblock.png', 100, 100),
		platform:	createImage('images/dirt.png', 500, 100),
		background: createImage('images/background.jpg')
	},

	font: "48px serif"
};


class GenericObject {


    constructor(x, y, image, w, h) {
        this.position = {x: x, y: y};
		this.velocity = {x: 0, y: 0};
		this.static = true;
		this.solid = true;

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

			if (this.position.y + this.height + this.velocity.y <= canvas.height)
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
		let x = this.position.x - (game.cam.x - game.cam.w/2);
		let y = this.position.y;

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
		
		for(let i = 0; i < game.objects.length; i ++){
			let obj = game.objects[i];
			
			if(obj === this)
				game.objects.splice(i, 1);	
		}
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

		// lose condition
		if (this.position.y > canvas.height) {
			init();
		}
    }
}


class Platform extends GenericObject {


    constructor(x, y, image) {
        super(x, y, image);
    }
}


class Coin extends GenericObject {


    constructor(x, y) {
        super(x, y, game.img.coin);
		this.solid = false;
    }

	update(){


		if(this.collides("Player")){
			game.sfx.coin.currentTime = 0;	
			game.sfx.coin.play();
			game.points += 1;
			this.destroy();
		}
	}
}


class Camera{

	constructor(){ 
		this.cam = game.cam;
		this.velocity = {x: 0, y: 0};
	}
	
	draw(){}
	
	update(){
		let player = getObject('Player');	

		if(player === null)
			return;

		this.velocity.x = (player.position.x - this.cam.x)/50;
		this.cam.x += this.velocity.x;
	}
}

// reinitializes the player and map in case of DEATH
function init() {
	
	game.objects = [];
    game.points = 0;

	// Camera
	game.objects.push(new Camera());

    // Players
    game.objects.push(new Player(100, 100))
    
	// Platforms
	game.objects.push(
        new Platform(500, 600, game.img.platform), 
        new Platform(0, 600, game.img.platform),
        new Platform(500 * 2 + 200, 600, game.img.platform),
        new Platform(1700 + 200, 500, game.img.dirt),
        new Platform(2200, 400, game.img.dirt),
        new Platform(2500, 500, game.img.dirt),
        new Platform(2800, 600, game.img.dirt)
	);

	// Coins
	game.objects.push(
		new Coin(500, 400), 
		new Coin(700, 400)
	);
}


function animate() {
    requestAnimationFrame(animate);
    c.clearRect(0, 0, canvas.width, canvas.height);

	game.objects.forEach((obj) => {
		obj.draw();
		obj.update();		
	});

    point = drawText(game.points, 950, 50);

    // win condition
    if (game.cam.x > 1500)
		uploadScore(game.points);
}


// Initialize Game
init();
animate();


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
