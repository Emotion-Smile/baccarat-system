describe('Trader user test', () => {

	beforeEach(() => {

		cy.refreshDatabase().seed();
		//cy.seed('UserSeeder');
		cy.login({ name: 'company_seeder' });
		// cy.create('App\\Models\\User', 3, {
		// 	type: 'trader'
		// });

		// cy.php(`
		// 	App\\Models\\User::count()
		// `).then(count => {
		// 	cy.log('The count of posts ' + count);
		// });
	});

	it('Can create new Trader', () => {

		const name = 'first trader test';
		const phone = '070356569';
		const environment = 'test';

		cy
			.visit('/cbs-admin');

		cy
			.get('a[dusk=traders-resource-link]')
			.click();

		cy
			.get('a[dusk=create-button]')
			.click();

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
			.contains('Trader Details: ' + name);

		cy
			.get('a[dusk=traders-resource-link]')
			.click();

		cy
			.get('tr')
			.should('contain', name)
			.and('contain', phone)
			.and('contain', environment)

		cy.php(`
			App\\Models\\User::where('name', '${name}')->first()
		`).then(user => {
			expect(user.type).equal('trader');
			expect(user.super_senior).equal(0);
			expect(user.senior).equal(0);
			expect(user.master_agent).equal(0);
			expect(user.agent).equal(0);
			expect(user.condition).to.be.null;
		});
	});

	it('Cannot create user if name already exist', () => {

		cy.visit('/cbs-admin');

		cy.get('a[dusk=traders-resource-link]').click();

		cy.get('a[dusk=create-button]').click();

		cy
			.get('input[dusk=name]')
			.type('root')
			.should('have.value', 'root');

		cy
			.get('input[dusk=password]')
			.type('password')
			.should('have.value', 'password');

		cy
			.get('button[dusk=create-button]')
			.click();

		cy
			.get('.card')
			.should('contain', 'has already been taken.');

	})

	it('Can update trader', () => {

		cy.seed('UserTraderSeeder');

		cy.php(`
			App\\Models\\User::where('type','trader')->orderByDesc('id')->first()
		`).then(trader => {

			const name = 'first trader test';
			const phone = '070356569';
			const environment = 'test';

			cy.log(trader);

			cy.visit('/cbs-admin');

			cy.get('a[dusk=traders-resource-link]').click();

			cy.get(`a[dusk=${trader.id}-edit-button]`).click();

			cy.get('input[dusk=name]').should('have.value', trader.name);
			cy.get('input[dusk=name]').clear();


			//perform change

			// cy
			// 	.get('select[dusk="environment"]')
			// 	.select(environment)
			// 	.should('have.value', 1);

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
				.get('button[dusk=update-button]')
				.click();

			cy
				.contains('Trader Details: ' + name);

			cy
				.get('a[dusk=traders-resource-link]')
				.click();

			cy
				.get('tr')
				.should('contain', name)
				.and('contain', phone)
				.and('contain', environment)
		});

	});

	//it('Can delete trader');

});
