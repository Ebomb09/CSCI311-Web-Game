// get access to the canvas element
const canvas = document.querySelector('canvas')
// this returns a drawing context, in this case to draw in 2d 
const c = canvas.getContext('2d')
console.log(c)

const gravity = 0.5

class Player {
    constructor() {
        this.position = {
            x: 100,
            y: 100
        }
        this.velocity = {
            x: 0,
            y: 1
        }
        this.width = 30
        this.height = 30
    }

    draw() {
        // fills the a red square in the canvas
        c.fillStyle = 'red'
        // the square uses the position object to place itself on the canvas
        // then uses the width and height to dertime the width and height of the square
        c.fillRect(this.position.x,this.position.y, this.width, this.height)
    }

    update() {
        this.draw()
        this.position.y += this.velocity.y
        this.position.x += this.velocity.x
        if (this.position.y + this.height + this.velocity.y <= canvas.height) {
            this.velocity.y += gravity
        } 
    }
}

class Platform {
    constructor({x, y, image}) {
        this.position = {
            x: x,
            y: y
        }
        this.image = image
        this.width = image.width
        this.height = image.height
    }

    draw() {
        c.drawImage(this.image,this.position.x,this.position.y)
        //c.fillStyle = 'blue' 
        //c.fillRect(this.position.x, this.position.y, this.width, this.height)
    }
}

class Coin {
    constructor({x, y, image}) {
        this.position = {
            x: x,
            y: y
        }

        this.image = image
        this.width = image.width
        this.height = image.height
    }

    draw() {
        c.drawImage(this.image,this.position.x,this.position.y)
    }
}

class GenericObject {
    constructor({x, y, image}) {
        this.position = {
            x: x,
            y: y
        }
        this.image = image
        this.width = image.width
        this.height = image.height
    }

    draw() {
        c.drawImage(this.image,this.position.x,this.position.y)
    }
} 

function createImage(imgSrc, w, h) {
    const image = new Image(w, h)
    image.src = imgSrc
    return image
}
//SFX
var coinsfx = new Audio('sfx/coinsfx.mp3')
// Game objects
let player = new Player()
//const platform = new Platform()
let platformImg = createImage('images/dirt.png', 500, 100)
let coinImg = createImage('images/coin.png', 100,100)
let platforms = []
// Point variables
let points = 0
c.font = "48px serif"
let point

const keys = {
    right: {
        pressed: false
    },
    left: {
        pressed: false
    }
}
// background objects
let genericObjects = [new GenericObject({x: 0, y: 0, image: createImage('images/background.jpg')})]

let scrollOffset = 0
// reinitializes the player and map in case of DEATH
function init() {

    // Game objects
    player = new Player()
    //const platform = new Platform()
    platformImg = createImage('images/dirt.png', 500, 100)
    blockplat = createImage('images/dirtblock.png', 100, 100)
    coinImg = createImage('images/coin.png', 100,100)
    
    platforms = [
        new Platform({x: 500, y: 600, image: platformImg}), 
        new Platform({x: 0, y: 600, image: platformImg}),
        new Platform({x: 500 * 2 + 200, y: 600, image: platformImg}),
        new Platform({x: 1700 + 200, y: 500, image: blockplat}),
        new Platform({x: 2200, y: 400, image: blockplat}),
        new Platform({x: 2500, y: 500, image: blockplat}),
        new Platform({x: 2800, y: 600, image: platformImg}),
    ]
    points = 0
    // background objects
    genericObjects = [new GenericObject({x: 0, y: 0, image: createImage('images/background.jpg')})]
    coins = [new Coin({x: 500, y: 400, image: coinImg}), new Coin({x: 700, y: 400, image: coinImg})]
    scrollOffset = 0

}
function animate() {
    requestAnimationFrame(animate)
    // This clears the canvas
    c.clearRect(0, 0, canvas.width, canvas.height)
    // This draws the back on to the canvas
    // Since this is a loop, the update function will keep getting
    // Thus the position and volocity are updated to mimic gravity

    genericObjects.forEach(objectimg => {
        objectimg.draw()
    })
    platforms.forEach(platform => {
        platform.draw()
    })
    coins.forEach(coin => {
        console.log(coin)
        coin.draw()
    })
    point = c.fillText(points, 950, 50)
    
    player.update()
    if (keys.right.pressed &&
        player.position.x < 600) {
        player.velocity.x = 5
    } else if (keys.left.pressed && player.position.x > 70) {
        player.velocity.x = -5
    }
    else {
        player.velocity.x = 0

        if (keys.right.pressed) {
            coins.forEach(coin => {
                coin.position.x -= 5
            })
            platforms.forEach(platform => {
                scrollOffset += 5
                platform.position.x -= 5
            })
            
        } else if (keys.left.pressed) {
            coins.forEach(coin => {
                coin.position.x += 5
            })
            platforms.forEach(platform => {
                scrollOffset -= 5
                platform.position.x += 5
            })
            
        }
    }

    console.log(scrollOffset)
    platforms.forEach(platform => {
        if (player.position.y + player.height <= platform.position.y && 
            player.position.y + player.height + player.velocity.y >= platform.position.y &&
            player.position.x + player.width >= platform.position.x &&
            player.position.x <= platform.position.x + platform.width) {
            player.velocity.y = 0
        }
    })

    coins.forEach(coin => {
        if (player.position.x + player.width >= coin.position.x
            && player.position.x <= coin.position.x + coin.width
            && player.position.y + player.height >= coin.position.y
            && player.position.y <= coin.position.y + coin.height) {
            points += 1
            console.log(points)
            point = c.fillText(points, 950, 50)
            coinsfx.play()
            coin.position.y = 1000
        }
    })
        
    // win condition
    if (scrollOffset > 2000) {
        const scoreform = document.getElementById("scoresform")
	    	scoreform.submit()
    }
    // lose condition
    if (player.position.y > canvas.height) {
        init()
    }
}


init()
animate()

// event listeners
// movement
addEventListener('keydown', ({keyCode}) => {
    switch (keyCode) {
        case 65:
            console.log('left')
            keys.left.pressed = true
            break
        case 83:
            console.log('down')
            break
        case 68:
            console.log('right')
            keys.right.pressed = true
            break
        case 87:
            if (player.velocity.y <= 0 && player.velocity.y >= 0) {
               console.log('up')
               player.velocity.y -= 15 
            }
            break
    }
    console.log(keys.right.pressed)
})

addEventListener('keyup', ({keyCode}) => {
    switch (keyCode) {
        case 65:
            console.log('left')
            keys.left.pressed = false
            break
        case 83:
            console.log('down')
            break
        case 68:
            console.log('right')
            keys.right.pressed = false
            break
        case 87:
            console.log('up')
            break
    }
    console.log(keys.right.pressed)
})