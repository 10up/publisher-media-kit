describe( 'Check if Media Kit Block Pattern is available for use', () => {
    it( 'Can insert the block pattern', () => {
        cy.visitAdminPage( 'post-new.php' );
        cy.get( 'button[aria-label="Close dialog"]' ).click();
        cy.get( '#post-title-0' ).click().type( 'Test Block Pattern' );
        cy.get( '.edit-post-header-toolbar__inserter-toggle' ).click();
        cy.get( '.components-tab-panel__tabs button:nth-child(2)' ).click();
        cy.get( '.components-select-control__input' ).select('publisher-media-kit', { force: true });
        cy.get( '[aria-label="Publisher Media Kit - Cover"]' ).click();
        cy.get('.edit-post-header-toolbar__inserter-toggle').click();
        cy.get( '[aria-label="Block: Heading"]' ).should( 'contain.text', 'Media Kit' );
    } );
} );