describe( 'Check if Media Kit page is created on plugin activation', () => {
    before( () => {
        cy
            .visitAdminPage( 'edit.php?post_type=page' )
            .get( '#post-search-input' )
            .clear()
            .type( 'Media Kit' )
            .get( '#search-submit' ).click();
    } );

    it( 'Activate Media Kit Plugin', () => {
        cy.get( 'body' ).then( ( $body ) => {
            if ( $body.find('[aria-label="Move “Media Kit” to the Trash"]' ).length > 0 ) {
                cy.get( '[aria-label="Move “Media Kit” to the Trash"]' ).click({ force: true });
            }
        });

        cy.
            visitAdminPage( 'plugins.php' )
            .get( '#deactivate-publisher-media-kit' ).click()
            .get( '#activate-publisher-media-kit' ).click()
            .get( '#deactivate-publisher-media-kit' ).should( 'be.visible' );

        cy
            .get( '.updated.notice a' )
            .invoke('attr', 'href')
            .then(href => {
                cy
                    .request( href )
                    .its('status')
                    .should('eq', 200);

            });
    } );
} );