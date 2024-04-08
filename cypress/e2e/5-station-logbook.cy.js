describe("Create station logbook", () => {
	beforeEach(() => {
		cy.login();
	});

	it("should load an empty list of station locations", () => {
		// Navigate to the logbooks page
		cy.visit("/index.php/logbooks");

		// Check that the table is not present
		cy.get("#station_logbooks_table").should("not.exist");
	});

	it("should have a button to create a new station location", () => {
		// Navigate to the logbooks page
		cy.visit("/index.php/logbooks");

		// Check that the button is present
		cy.get("a").contains("Create Station Logbook").should("exist").click();

		cy.url().should("include", "/logbooks/create");
	});

	it("should create a new station location", () => {
		// Navigate to the create logbook page
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

	// it("should set as active station logbook when button clicked", () => {
	// 	// Navigate to the logbooks page
	// 	cy.visit("/index.php/logbooks");

	// 	// Check that the button is present
	// 	cy.get("a").contains("Set as Active Logbook").should("exist").click();

	// 	// Check if the station was set to active
	// 	cy.get("body")
	// 		.contains("Active Logbook")
	// 		.should("be.visible")
	// 		.and("have.class", "badge text-bg-success");
	// });

	// it("should link to a station location from the edit logbook page", () => {
	// 	// Navigate to the logbooks page
	// 	cy.visit("/index.php/logbooks");

	// 	// Click the edit button
	// 	cy.get("i.fas.fa-edit").should("exist").click();

	// 	// Ensure that the edit link navigates to the correct page
	// 	cy.url().should("include", "/logbooks/edit");

	// 	// Scroll to the bottom of the page
	// 	cy.scrollTo("bottom");

	// 	// Click the link location button
	// 	cy.get("button").contains("Link Location").should("exist").click();

	// 	// Make sure that our table now shows the linked station location
	// 	cy.get("#station_logbooks_linked_table")
	// 		.contains("Test Station Location")
	// 		.should("exist");
	// });
});
