describe("QRZCALL.EU integration", () => {
	beforeEach(() => {
		cy.login();
	});

	it("should show the QRZCALL.EU PAT field on the station create page", () => {
		cy.visit("/index.php/station/create");

		// The Personal Access Token text input should be present
		cy.get('input[name="qrzcallapikey"]')
			.should("exist")
			.and("be.visible")
			.and("have.attr", "placeholder", "pat_xxxxx");

		// The real-time upload select should be present with Yes/No options
		cy.get('select[name="qrzcallrealtime"]')
			.should("exist")
			.find("option")
			.should("have.length", 2);
	});

	it("should accept a Personal Access Token in the QRZCALL.EU field", () => {
		cy.visit("/index.php/station/create");

		const testPat = "pat_cypresstest1234567890";

		cy.get('input[name="qrzcallapikey"]')
			.scrollIntoView()
			.type(testPat, { force: true })
			.should("have.value", testPat);

		// Real-time upload defaults to "No"
		cy.get('select[name="qrzcallrealtime"]')
			.find("option:selected")
			.should("have.value", "0");

		// And can be switched to "Yes" (force: the QRZCALL.EU card can sit
		// under the station form's sticky action bar in the test viewport)
		cy.get('select[name="qrzcallrealtime"]').select("1", { force: true });
		cy.get('select[name="qrzcallrealtime"]')
			.find("option:selected")
			.should("have.value", "1");
	});
});
