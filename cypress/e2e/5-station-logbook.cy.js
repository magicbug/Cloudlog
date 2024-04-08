describe("Create station logbook", () => {
	beforeEach(() => {
		cy.login();
	});

	it("should load an empty list of station locations", () => {
		cy.visit("/index.php/logbooks");

		// Check that the table is not present
		cy.get("#station_logbooks_table").should("not.exist");
	});

	it("should have a button to create a new station location", () => {
		cy.visit("/index.php/logbooks");

		// Check that the button is present
		cy.get("a").contains("Create Station Logbook").should("exist").click();

		cy.url().should("include", "/logbooks/create");
	});

	it("should create a new station location", () => {
		cy.visit("/index.php/logbooks/create");

		// Define the station location name
		const stationLogbookName = "Home QTH";

		// Type the station location name into the input field
		cy.get('input[name="stationLogbook_Name"]').type(stationLogbookName);

		// Click the save button
		cy.get('button[type="submit"]')
			.contains("Create Station Logbook")
			.click();

		// Check if the station location was created successfully
		cy.url().should("include", "/logbooks");

		// // Check if the station location is present in the table
		cy.get("#station_logbooks_table")
			.contains(stationLogbookName)
			.should("exist");
	});
});
