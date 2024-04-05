Cypress.Commands.add("login", () => {
	const username = "m0abc";
	const password = "demo";
	cy.visit("/index.php/user/login");
	cy.get('input[name="user_name"]').type(username);
	cy.get('input[name="user_password"]').type(password);
	cy.get('button[type="submit"]').click();
});
