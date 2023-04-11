# Mongoose Web Game
Web-based platforming game with a scoreboard. Written in PHP, Javascript and utilizing MySQL. 
The game involve's the player scoring points while navigating through a level within a certain time limit.

## Installation
1. Clone the repository
2. Create a MySQL `conf/db.info` configuration file
```
1. HOST		- MySQL Server to connect to
2. USER		- Login User 
3. PASSWORD	- Login Password
4. DATABASE	- Name of database being used
```
3. Run installer script
	* Generates required db header file and sets up db tables
```bash
./install.sh
```
4. Create symlink to `www` folder
```bash
ln -s www/ <apache host folder>
```

## Usage
Navigate to where your apache host folder.
