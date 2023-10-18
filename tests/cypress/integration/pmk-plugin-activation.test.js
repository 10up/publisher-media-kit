describe( 'Admin can login and make sure plugin is activated', () => {
    it( 'Can activate plugin if it is deactivated', () => {
        cy.
            visitAdminPage( 'plugins.php' )
            .get( '#deactivate-publisher-media-kit' ).click()
            .get( '#activate-publisher-media-kit' ).click()
            .get( '#deactivate-publisher-media-kit' ).should( 'be.visible' );
    } );
} );