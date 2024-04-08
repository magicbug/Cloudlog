describe("Create station location", () => {
	beforeEach(() => {
		cy.login();
	});

	it("should load an empty list of station locations", () => {
		cy.visit("/index.php/station");

		// Check that the table is not present
		cy.get("#station_locations_table").should("not.exist");
	});

	it("should have a button to create a new station location", () => {
		cy.visit("/index.php/station");

		// Check that the button is present
		cy.get("a")
			.contains("Create a Station Location")
			.should("exist")
			.click();

		cy.url().should("include", "/station/create");
	});

	it("should create a new station location", () => {
		cy.visit("/index.php/station/create");

		// Define the station location name
		const stationLocationName = "Test Station Location";
		const stationCallsign = "2M0SQL";
		const stationPower = "100";
		const stationDXCC = "United States Of America - K";
		const stationCity = "Olathe";
		const stationState = "Kansas";
		const stationCounty = "Johnson";
		const stationGridsquare = "EM28";
		const stationCQ = "4";
		const stationITU = "7";

		// Type the station location name into the input field
		cy.get('input[name="station_profile_name"]').type(stationLocationName);
		cy.get('input[name="station_callsign"]').type(stationCallsign);
		cy.get('input[name="station_power"]').type(stationPower);
		cy.get('select[name="dxcc"]').select(stationDXCC);
		cy.get('input[name="city"]').type(stationCity);
		cy.get('select[name="station_state"]').select(stationState);
		cy.get("#stationCntyInput-selectized")
			.type(stationCounty, { force: true }) // force typing if the element isn't initially visible
			.get(".selectize-dropdown-content > div") // get the dropdown content
			.contains(stationCounty) // find the option containing the county name
			.click(); // click to select the option
		cy.get('input[name="gridsquare"]').type(stationGridsquare);
		cy.get('select[name="station_cq"]').select(stationCQ);
		cy.get('select[name="station_itu"]').select(stationITU);

		// Click the save button
		cy.get('button[type="submit"]')
			.contains("Create Station Location")
			.click();

		// Check if the station location was created successfully
		cy.url().should("include", "/station");

		// // Check if the station location is present in the table
		cy.get("#station_locations_table")
			.contains(stationLocationName)
			.should("exist");
	});
});
