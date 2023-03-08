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
        } else {
            this.velocity.y = 0
        }
    }
}

class Platform {
    constructor({x, y}) {
        this.position = {
            x: x,
            y: y
        }

        this.width = 200
        this.height = 20
    }

    draw() {
        c.fillStyle = 'blue' 
        c.fillRect(this.position.x, this.position.y, this.width, this.height)
    }
}

// Game objects
const player = new Player()
//const platform = new Platform()
const platforms = [new Platform({x: 500, y: 500}), new Platform({x: 100, y: 400})]
const keys = {
    right: {
        pressed: false
    },
    left: {
        pressed: false
    }
}

let scrollOffset = 0

function animate() {
    if (scrollOffset > 2000) {
	    const scoreform = document.getElementById("scoresform")
	    var score = document.getElementById("form_score")
	    score.value = scrollOffset
	    var user = document.getElementById("form_user")
	    user.value = 1	// Test user
	    scoreform.submit()
    }
    requestAnimationFrame(animate)
    // This clears the canvas
    c.clearRect(0, 0, canvas.width, canvas.height)
    // This draws the back on to the canvas
    // Since this is a loop, the update function will keep getting
    // Thus the position and volocity are updated to mimic gravity
    player.update()
    platforms.forEach(platform => {
        platform.draw()
    })
    if (keys.right.pressed &&
        player.position.x < 800) {
        player.velocity.x = 5
    } else if (keys.left.pressed && player.position.x > 70) {
        player.velocity.x = -5
    }
    else {
        player.velocity.x = 0

        if (keys.right.pressed) {
            platforms.forEach(platform => {
		scrollOffset += 5
                platform.position.x -= 5
            })
            
        } else if (keys.left.pressed) {
            platforms.forEach(platform => {
		scrollOffset -= 5
                platform.position.x += 5
            })
            
        }
    }
    platforms.forEach(platform => {
        if (player.position.y + player.height <= platform.position.y && 
            player.position.y + player.height + player.velocity.y >= platform.position.y &&
            player.position.x + player.width >= platform.position.x &&
            player.position.x <= platform.position.x + platform.width) {
            player.velocity.y = 0
        }
    })
    /*
    if (scrollOffset > 2000) {
	    const scoreform = document.getElementById("scoresform")
	    scoreform.submit()
    }
    */
    console.log(scrollOffset)
}

animate()
function wincheck() {

	if (scrollOffset > 2000) {
    		const scoreform = document.getElementById("scoresform")
	    	scoreform.submit()
	}
}

wincheck()
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
            console.log('up')
            player.velocity.y -= 15
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
