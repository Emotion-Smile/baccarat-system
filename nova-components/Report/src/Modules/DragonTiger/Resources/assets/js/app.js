import routes from './routes';

Nova.booting((Vue, router, store) => {
    router.addRoutes(routes);
});