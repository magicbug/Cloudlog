// Test suite for new account alerts within the application
describe("New account alerts", () => {
	// Before each test, perform login operation. Assumes cy.login() is a custom command.
	beforeEach(() => {
		cy.login();
	});

	// Alert messages expected to be seen by brand new accounts
	const locationsAlertMessage =
		"You have no station locations. Go here to create it!";
	const logbookAlertMessage =
		"You have no station logbook. Go here to create it!";
	const activeStationAlertMessage =
		"Attention: you need to set an active station location.";
	const noQSOAlertMessage =
		"You have made no QSOs today; time to turn on the radio!";

	// Test to verify the locations alert message and its link
	it(`should show a "${locationsAlertMessage}" alert with a valid link to create it`, () => {
		// Verify alert visibility and class for urgency
		cy.get("body")
			.contains(locationsAlertMessage)
			.should("be.visible")
			.and("have.class", "alert-danger");

		// Validate the hyperlink's destination within the alert
		cy.contains(locationsAlertMessage).within(() => {
			cy.get("a")
				.contains("here")
				.should("have.attr", "href")
				.and("equal", "http://localhost/index.php/station");
		});
	});

	// Test navigation to the station creation page via the alert link
	it("should navigate to the station creation page after clicking the link in the alert", () => {
		// Trigger click on the link within the alert message
		cy.contains(locationsAlertMessage).within(() => {
			cy.get("a").contains("here").click();
		});

		// Assert the correct page has been loaded by checking the URL
		cy.url().should("include", "/station");
	});

	// Test to verify the logbook alert message and its link
	it(`should show a "${logbookAlertMessage}" alert with a valid link to create it`, () => {
		// Verify alert visibility and class for urgency
		cy.get("body")
			.contains(logbookAlertMessage)
			.should("be.visible")
			.and("have.class", "alert-danger");

		// Validate the hyperlink's destination within the alert
		cy.contains(logbookAlertMessage).within(() => {
			cy.get("a")
				.contains("here")
				.should("have.attr", "href")
				.and("equal", "http://localhost/index.php/logbooks");
		});
	});

	// Test navigation to the logbook creation page via the alert link
	it("should navigate to the logbook creation page after clicking the link in the alert", () => {
		// Trigger click on the link within the alert message
		cy.contains(logbookAlertMessage).within(() => {
			cy.get("a").contains("here").click();
		});

		// Assert the correct page has been loaded by checking the URL
		cy.url().should("include", "/logbooks");
	});

	// Test to verify the active station alert is properly displayed
	it(`should display an "${activeStationAlertMessage}" alert`, () => {
		// Verify alert visibility and class for urgency
		cy.get("body")
			.contains(activeStationAlertMessage)
			.should("be.visible")
			.and("have.class", "alert-danger");
	});

	// Test to verify the no QSO alert is properly displayed
	it(`should display a "${noQSOAlertMessage}" alert`, () => {
		// Verify alert visibility and class for importance
		cy.get("body")
			.contains(noQSOAlertMessage)
			.should("be.visible")
			.and("have.class", "alert-warning");
	});
});
