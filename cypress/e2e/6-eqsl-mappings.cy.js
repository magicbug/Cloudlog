describe("eQSL mapping management", () => {
	beforeEach(() => {
		cy.login();
	});

	it("shows the mappings management page", () => {
		cy.visit("/index.php/eqsl/mappings");
		cy.contains("eQSL Mappings").should("be.visible");
		cy.contains("Configured Mappings").should("be.visible");
		cy.get('form[action*="eqsl/save_mapping"]').should("exist");
	});

	it("shows mappings tab in import and export pages", () => {
		cy.visit("/index.php/eqsl/import");
		cy.get('a[href*="eqsl/mappings"]').should("exist");

		cy.visit("/index.php/eqsl/export");
		cy.get('a[href*="eqsl/mappings"]').should("exist");
	});
});
