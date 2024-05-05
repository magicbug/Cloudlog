const { defineConfig } = require("cypress");

module.exports = defineConfig({
  projectId: 'gm8wco',
	e2e: {
		baseUrl: "http://localhost/",
		setupNodeEvents(on, config) {
			// implement node event listeners here
		},
	},
});
