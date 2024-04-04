describe("Post QSO Input Form", () => {
	beforeEach(() => {
		cy.login();
	});

	it("Submits a QSO", () => {
		cy.visit("index.php/qso?manual=1");

		cy.get('select[name="mode"]').select("USB");
		cy.get('select[name="band"]').select("20m");
		cy.get('#qso_input input[name="callsign"]').first().type("KS3CKC");

		// Submit the QSO
		cy.get("#qso_input").submit();

		// Check if the QSO was added to the log
		cy.visit("index.php/dashboard");

		cy.get("table > tbody > tr:first").within(() => {
			cy.get("td").eq(2).should("contain", "KS3CKC");
			cy.get("td").eq(3).should("contain", "USB");
			cy.get("td").eq(6).should("contain", "20m");
		});
	});

	it("Delete a QSO", () => {
		cy.visit("index.php/dashboard");

		// Click the link in the first row of the table to open the modal
		cy.get("table > tbody > tr:first").within(() => {
			cy.get("a").first().click();
		});

		// Click the "Edit QSO" button
		cy.get("a").contains("Edit QSO").should("be.visible").click();

		// Click the delete button
		cy.get("a")
			.contains("Delete QSO")
			.scrollIntoView()
			.should("be.visible")
			.click();

		// Click the confirm delete button
		cy.get("button").contains("OK").should("be.visible").click();
	});
});
