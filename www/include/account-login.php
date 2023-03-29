<form class="account" method="POST">
	<h3> Login </h3>

	<div class="text-input">
		<label for="username">Username</label>
		<input id="username" name="username" type="text" placeholder="Type your username..." required>
	</div>

	<div class="text-input">
		<label for="password">Password</label>
		<input id="password" name="password" type="password" placeholder="Type your password..." required>
	</div>

	<input name="type" type="hidden" value="login">
	<input type="submit" value="Login">

</form>


<form class="account" method="POST">
	<h3> Create an Account </h3>

	<div class="text-input">
		<label for="username">Username</label>
		<input id="username" name="username" type="text" placeholder="Type your username..." required>
	</div>

	<div class="text-input">
		<label for="password">Password</label>
		<input id="password" name="password" type="password" placeholder="Type your password..." required>
	</div>

	<input name="type" type="hidden" value="create">
	<input type="submit" value="Create Account">
</form>
