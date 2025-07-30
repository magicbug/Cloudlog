describe('Contest Logging', () => {
    beforeEach(() => {
        // Navigate to contest logging page
        // Note: This test assumes the application is already set up and accessible
        cy.visit('/index.php/contesting?manual=1');
        
        // Wait for page to load and set exchange type to Serialgridsquare
        cy.get('#exchangetype').select('Serialgridsquare');
    });

    it('should allow 6-character locator input in gridsquare field', () => {
        // Test that the locator field accepts 6-character locators like JO42JA
        cy.get('#exch_gridsquare_r').should('be.visible');
        cy.get('#exch_gridsquare_r').type('JO42JA');
        cy.get('#exch_gridsquare_r').should('have.value', 'JO42JA');
        
        // Test that it also accepts 8-character locators
        cy.get('#exch_gridsquare_r').clear();
        cy.get('#exch_gridsquare_r').type('JO42JA67');
        cy.get('#exch_gridsquare_r').should('have.value', 'JO42JA67');
    });

    it('should support proper tab navigation through contest fields for Serialgridsquare exchange', () => {
        // Start from callsign field
        cy.get('#callsign').focus();
        
        // Tab through fields and verify the order
        cy.get('#callsign').tab();
        cy.focused().should('have.id', 'rst_sent');
        
        cy.focused().tab();
        cy.focused().should('have.id', 'exch_serial_s');
        
        cy.focused().tab();
        cy.focused().should('have.id', 'rst_rcvd');
        
        cy.focused().tab();
        cy.focused().should('have.id', 'exch_serial_r');
        
        cy.focused().tab();
        cy.focused().should('have.id', 'exch_gridsquare_r');
    });

    it('should show correct fields for Serialgridsquare exchange type', () => {
        // Verify that the correct fields are visible for Serialgridsquare exchange
        cy.get('.serials').should('be.visible');
        cy.get('.serialr').should('be.visible');
        cy.get('.gridsquarer').should('be.visible');
        
        // Verify that other exchange type fields are hidden
        cy.get('.exchanges').should('not.be.visible');
        cy.get('.exchanger').should('not.be.visible');
    });
});