// Test suite for new account notifications within the application
describe("New account notifications", () => {
	// Before each test, perform login operation. Assumes cy.login() is a custom command.
	beforeEach(() => {
		cy.login();
	});

	// Notification messages expected to be seen by new accounts
	const locationsNotificationMessage =
		"You have no station locations. Go here to create it!";
	const logbookNotificationMessage =
		"You have no station logbook. Go here to create it!";
	const activeStationNotificationMessage =
		"Attention: you need to set an active station location.";
	const noQSONotificationMessage =
		"You have made no QSOs today; time to turn on the radio!";

	// Test to verify the locations notification message and its link
	it(`should show a "${locationsNotificationMessage}" notification with a valid link to create it`, () => {
		// Verify notification visibility and class for urgency
		cy.get("body")
			.contains(locationsNotificationMessage)
			.should("be.visible")
			.and("have.class", "alert-danger");

		// Validate the hyperlink's destination within the notification
		cy.contains(locationsNotificationMessage).within(() => {
			cy.get("a")
				.contains("here")
				.should("have.attr", "href")
				.and("equal", "http://localhost/index.php/station");
		});
	});

	// Test navigation to the station creation page via the notification link
	it("should navigate to the station creation page after clicking the link in the notification", () => {
		// Trigger click on the link within the notification message
		cy.contains(locationsNotificationMessage).within(() => {
			cy.get("a").contains("here").click();
		});

		// Assert the correct page has been loaded by checking the URL
		cy.url().should("include", "/station");
	});

	// Test to verify the logbook notification message and its link
	it(`should show a "${logbookNotificationMessage}" notification with a valid link to create it`, () => {
		// Verify notification visibility and class for urgency
		cy.get("body")
			.contains(logbookNotificationMessage)
			.should("be.visible")
			.and("have.class", "alert-danger");

		// Validate the hyperlink's destination within the notification
		cy.contains(logbookNotificationMessage).within(() => {
			cy.get("a")
				.contains("here")
				.should("have.attr", "href")
				.and("equal", "http://localhost/index.php/logbooks");
		});
	});

	// Test navigation to the logbook creation page via the notification link
	it("should navigate to the logbook creation page after clicking the link in the notification", () => {
		// Trigger click on the link within the notification message
		cy.contains(logbookNotificationMessage).within(() => {
			cy.get("a").contains("here").click();
		});

		// Assert the correct page has been loaded by checking the URL
		cy.url().should("include", "/logbooks");
	});

	// Test to verify the active station notification is properly displayed
	it(`should display an "${activeStationNotificationMessage}" notification`, () => {
		// Verify notification visibility and class for urgency
		cy.get("body")
			.contains(activeStationNotificationMessage)
			.should("be.visible")
			.and("have.class", "alert-danger");
	});

	// Test to verify the no QSO notification is properly displayed
	it(`should display a "${noQSONotificationMessage}" notification`, () => {
		// Verify notification visibility and class for importance
		cy.get("body")
			.contains(noQSONotificationMessage)
			.should("be.visible")
			.and("have.class", "alert-warning");
	});
});
