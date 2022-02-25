describe( 'Check if Media Kit Block Pattern is available for use', () => {
    it( 'Can insert the block pattern', () => {
        cy.visitAdminPage( 'post-new.php' );
        cy.get( 'button[aria-label="Close dialog"]' ).click();
        cy.get( '#post-title-0, h1.editor-post-title__input' ).click().type( 'Test Block Pattern' );
        cy.get( '.edit-post-header-toolbar__inserter-toggle' ).click();
        cy.get( '.components-tab-panel__tabs button:nth-child(2)' ).click();

        //(add version) If dropdown is available. (After WP 5.?)
        cy.get( 'body' ).then( ( $body ) => {
            if ( $body.find('.components-select-control__input' ).length > 0 ) {
                cy.get( '.components-select-control__input' ).select('publisher-media-kit', { force: true });
            }
        });

        cy.get( '[aria-label="Publisher Media Kit - Cover"] .block-editor-block-preview__container' ).click({ force: true });
        cy.get('.edit-post-header-toolbar__inserter-toggle').click();
        cy.get( '[data-type="core/heading"]' ).should( 'contain.text', 'Media Kit' );
    } );
} );