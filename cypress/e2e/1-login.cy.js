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
});
