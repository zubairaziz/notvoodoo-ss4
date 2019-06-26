# NotVoodoo

## Development

Run `composer install` and `yarn install` in the root directory to install dependencies.

Run `yarn start` in the root directory to startup the development server.

## Deployment

Run `composer install` and `yarn install` in the root directory to install dependencies.

Run `yarn run build`.

## Crontab

The following tasks need to be added to the server's crontab file

`* * * * * [project-path...]/vendor/bin/sake dev/tasks/SendSubscriptionNotificationTask`

`* * * * * [project-path...]/vendor/bin/sake dev/tasks/UpdateHomePageOverlayTask`
