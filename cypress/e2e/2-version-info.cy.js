describe("Version Info Modal", () => {
	beforeEach(() => {
		cy.login();
	});

	it("should open after login", () => {
		// Make sure the dialog is enabled
		cy.request({
			method: "POST",
			url: "http://localhost/index.php/user_options/enableVersionDialog",
		}).wait(1000);
		cy.get(".modal-title").contains("Version Info").should("be.visible");
	});

	it("should close after clicking 'Close' button", () => {
		// Make sure the dialog is enabled
		cy.request({
			method: "POST",
			url: "http://localhost/index.php/user_options/enableVersionDialog",
		}).wait(1000);
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

	it("should close after clicking 'Don't show again' button", () => {
		// Make sure the dialog is enabled
		cy.request({
			method: "POST",
			url: "http://localhost/index.php/user_options/enableVersionDialog",
		}).wait(1000);

		// check if the modal is visible
		cy.get(".modal-title").contains("Version Info").should("be.visible");
		// click the "Don't show again" button
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

	it("should not show the version info dialog after click 'Dont show again' button", () => {
		// check if the modal is not visible
		cy.get(".modal-title").should("not.exist");
	});
});
