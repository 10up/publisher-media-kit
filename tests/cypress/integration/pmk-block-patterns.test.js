describe('Check if Media Kit Block Pattern is available for use', () => {
	it('Can insert the block pattern', () => {
		cy.visitAdminPage('post-new.php');
		cy.closeWelcomeGuide();
		cy.getBlockEditor()
			.find('#post-title-0, h1.editor-post-title__input')
			.first()
			.click({ force: true })
			.type('Test Block Pattern');
		cy.get(
			'.edit-post-header-toolbar__inserter-toggle, .editor-document-tools__inserter-toggle',
		).click();
		cy.get(
			'.components-tab-panel__tabs button, .block-editor-inserter__tabs button, .block-editor-tabbed-sidebar__tablist button',
		)
			.contains('Patterns')
			.click();

		// (add version) If dropdown is available. (After WP 5.?)
		cy.get('body').then(($body) => {
			if ($body.find('.components-select-control__input').length > 0) {
				cy.get('.components-select-control__input').select('publisher-media-kit', {
					force: true,
				});
			} else {
				// Category tabs load asynchronously, so retry until the
				// "Publisher Media Kit" tab actually renders before clicking it.
				cy.contains('[role="tab"]', 'Publisher Media Kit', { timeout: 10000 }).click();
			}
		});

		// Wait for patterns to load, then verify cover pattern exists
		cy.get('[aria-label="Publisher Media Kit - Cover"]', { timeout: 10000 }).should('exist');
	});
});
