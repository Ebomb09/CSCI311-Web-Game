<div class="login">
	<h1> Login </h1>

	<form method="POST">

		<label for="username">Username</label>
		<input name="username" type="text" required>

		<label for="password">Password</label>
		<input name="password" type="password" required>

		<input name="type" type="hidden" value="login">

		<input type="submit" value="Login">
	</form>
</div>

<div class="create_account">
	<p> Don't have an account? </p>
	<h2> Create an Account </h2>

	<form method="POST">
		<label for="username">Username</label>
		<input name="username" type="text" required>

		<label for="password">Password</label>
		<input name="password" type="password" required>

		<input name="type" type="hidden" value="create">

		<input type="submit" value="Create Account">
	</form>
</div>
