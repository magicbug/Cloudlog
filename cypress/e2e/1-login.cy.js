describe("Login Test", () => {
	it("Should log in successfully", () => {
		// Define the username and password
		const username = "m0abc";
		const password = "demo";

		// Visit the login page
		cy.visit("/index.php/user/login");

		// Type the username and password into the input fields
		cy.get('input[name="user_name"]').type(username);
		cy.get('input[name="user_password"]').type(password);

		// Click the login button
		cy.get('button[type="submit"]').click();

		// Check if the login was successful
		// This could be checking/ for a URL change, looking for a log out button, etc.
		cy.url().should("include", "/dashboard");
		cy.contains("Logout");
	});

	it("Should display an error message on failed login", () => {
		// Define the username and password
		const username = "m0abc";
		const password = "wrongpassword";

		// Visit the login page
		cy.visit("/index.php/user/login");

		// Type the username and password into the input fields
		cy.get('input[name="user_name"]').type(username);
		cy.get('input[name="user_password"]').type(password);

		// Click the login button
		cy.get('button[type="submit"]').click();

		// Check if the login was successful
		// This could be checking/ for a URL change, looking for a log out button, etc.
		cy.url().should("include", "/login");

		cy.get("body")
			.contains("Incorrect username or password!")
			.should("be.visible")
			.and("have.class", "alert-danger");
	});

	it("Should display an error message on empty fields", () => {
		// Visit the login page
		cy.visit("/index.php/user/login");

		// Click the login button
		cy.get('button[type="submit"]').click();

		// Check if the login was successful
		// This could be checking/ for a URL change, looking for a log out button, etc.
		cy.url().should("include", "/login");

		cy.get("body")
			.contains(`The "Username" field is required.`)
			.should("be.visible")
			.parent()
			.and("have.class", "alert-danger");

		cy.get("body")
			.contains(`The "Password" field is required.`)
			.should("be.visible")
			.parent()
			.and("have.class", "alert-danger");
	});

	it("Should display and open the forgot password page", () => {
		// Visit the login page
		cy.visit("/index.php/user/login");

		// Click the "Forgot Password?" link
		cy.get("a")
			.contains("Forgot your password?")
			.should("be.visible")
			.click();

		// Check if the correct page has been loaded by checking the URL
		cy.url().should("include", "/forgot_password");
	});
});
