// ***********************************************************
// This example support/e2e.js is processed and
// loaded automatically before your test files.
//
// This is a great place to put global configuration and
// behavior that modifies Cypress.
//
// You can change the location of this file or turn off
// automatically serving support files with the
// 'supportFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/configuration
// ***********************************************************

// Import commands.js using ES2015 syntax:
import './commands'

// Handle uncaught exceptions from the application
Cypress.on('uncaught:exception', (err, runnable) => {
	// Ignore specific errors that don't affect the test functionality
	if (err.message.includes('Cannot read properties of null')) {
		return false;
	}
	if (err.message.includes('convertMarkdownToHTML')) {
		return false;
	}
	// Return true to fail the test for other uncaught exceptions
	return true;
});

// Alternatively you can use CommonJS syntax:
// require('./commands')