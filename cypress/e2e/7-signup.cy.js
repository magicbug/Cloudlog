describe("Public signup", () => {
	let username;
	let callsign;
	const password = "CypressTest123!";

	before(() => {
		const id = Date.now();
		username = `cysignup${id}`;
		callsign = `ZZ${String(id).slice(-6)}`;
	});

	after(() => {
		cy.visit("/index.php/user/logout");
		cy.login();

		cy.visit("/index.php/options/registration");
		cy.get("#openRegistration").select("Disabled");
		cy.get('input[type="submit"]').click();

		cy.visit("/index.php/user");
		cy.get("body").then(($body) => {
			if ($body.text().includes(username)) {
				cy.contains("a", username)
					.invoke("attr", "href")
					.then((href) => {
						const userId = href.split("/").pop();
						cy.visit(`/index.php/user/delete/${userId}`);
						cy.get('input[type="submit"]').click();
					});
			}
		});
	});

	it("redirects to login when open registration is disabled", () => {
		cy.visit("/index.php/user/signup");
		cy.url().should("include", "/login");
		cy.contains("Registration is currently disabled.").should("be.visible");
	});

	it("creates an account when open registration is enabled", () => {
		cy.login();
		cy.visit("/index.php/options/registration");
		cy.get("#openRegistration").select("Enabled");
		cy.get('input[type="submit"]').click();
		cy.contains("Registration settings have been saved successfully.").should(
			"be.visible"
		);

		cy.visit("/index.php/user/logout");
		cy.visit("/index.php/user/login");
		cy.contains("Don't have an account? Sign up").should("be.visible").click();
		cy.url().should("include", "/signup");

		cy.get('input[name="user_name"]').type(username);
		cy.get('input[name="user_email"]').type(`${username}@example.com`);
		cy.get('input[name="user_password"]').type(password);
		cy.get('input[name="user_password_confirm"]').type(password);
		cy.get('input[name="user_firstname"]').type("Cypress");
		cy.get('input[name="user_lastname"]').type("Signup");
		cy.get('input[name="user_callsign"]').type(callsign);
		cy.get('input[name="user_locator"]').type("IO87JP");
		cy.get('button[type="submit"]').click();

		cy.url().should("include", "/login");
		cy.contains("Account created. You can now log in.").should("be.visible");

		cy.get('input[name="user_name"]').type(username);
		cy.get('input[name="user_password"]').type(password);
		cy.get('button[type="submit"]').click();
		cy.url().should("include", "/dashboard");
		cy.contains("Logout");
	});
});
