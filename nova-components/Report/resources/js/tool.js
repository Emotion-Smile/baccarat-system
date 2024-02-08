import routes from './routes';
import component from './components';

Nova.booting((Vue, router, store) => {
  router.addRoutes(routes);
  component.addComponents(Vue);
});
