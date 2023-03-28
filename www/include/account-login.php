<div class="login">
	<h1> Login </h1>

	<form method="POST">
		<div class="usrtxt">
			<label for="username">Username</label>
			<input name="username" type="text" required><br>
			<label for="password">Password</label>
			<input name="password" type="password" required><br>
		</div>		
		<div class="button">
			<input name="type" type="hidden" value="login">
			<input type="submit" value="Login">
		</div>
	</form>
</div>

<div class="create-account">
	<h1> Create an Account </h1>

	<form method="POST">
		<div class="usrtxt">
			<label for="username">Username</label>
			<input name="username" type="text" required><br>
			<label for="password">Password</label>
			<input name="password" type="password" required><br>
		</div>		
		<div class="button">
			<input name="type" type="hidden" value="create">
			<input type="submit" value="Create Account">
		</div>
	</form>
</div>
