describe("Version Info Modal", () => {
	beforeEach(() => {
		cy.login();
		cy.request(
			"POST",
			"http://localhost/index.php/user_options/enableVersionDialog"
		).wait(1000);
	});

	it("should open after login", () => {
		// check if the modal is visible
		cy.get(".modal-title").contains("Version Info").should("be.visible");
	});

	it("should close after clicking 'Close' button", () => {
		// check if the modal is visible
		cy.get(".modal-title").contains("Version Info").should("be.visible");
		// click the 'Close' button
		cy.get("button")
			.contains("Close")
			.should("be.visible")
			.wait(500)
			.click();

		// check if the modal is not visible
		cy.get(".modal-title")
			.contains("Version Info")
			.should("not.be.visible");
	});

	it("should not show again after clicking 'Don't show again' button", () => {
		// check if the modal is visible
		cy.get(".modal-title").contains("Version Info").should("be.visible");
		// click the 'Close' button
		cy.get("button")
			.contains("Don't show again")
			.should("be.visible")
			.wait(500)
			.click();

		// check if the modal is not visible
		cy.get(".modal-title")
			.contains("Version Info")
			.should("not.be.visible");
	});
});
