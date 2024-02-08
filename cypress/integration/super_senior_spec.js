describe('Super senior user test', () => {

    beforeEach(() => {

        cy.refreshDatabase().seed();
        cy.login({ name: 'company_seeder' });
    });

	it('Can create new super senior', () => {

		const name = 'first super senior';
		const phone = '070356569';
		const environment = 'test';

		cy
			.visit('/cbs-admin/resources/super-seniors/new');

		cy
			.get('select[dusk="environment"]')
			.select(environment)
			.should('have.value', 1);

		cy
			.get('input[dusk=name]')
			.type(name)
			.should('have.value', name);

		cy
			.get('input[dusk=password]')
			.type('password')
			.should('have.value', 'password');

		cy
			.get('input[dusk=phone]')
			.type(phone)
			.should('have.value', phone);

		cy
			.get('button[dusk=create-button]')
			.click();

		cy
			.contains('Super Senior Details: ' + name);

	})
})
