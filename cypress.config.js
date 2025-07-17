const { defineConfig } = require("cypress");

module.exports = defineConfig({
  projectId: 'gm8wco',
	e2e: {
		baseUrl: "http://localhost/",
		defaultCommandTimeout: 60000, // Increase default timeout to 60 seconds
		requestTimeout: 60000, // Increase request timeout to 60 seconds
		setupNodeEvents(on, config) {
			// implement node event listeners here
		},
	},
});
