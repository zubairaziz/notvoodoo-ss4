# SilverStripe 4 Starter Kit
Just the basics to get you started for a locally developed site. Running with Yarn and Webpack. This README is meant to be instructional on how to get going with this starter kit, not a tutorial on how to use yarn or webpack.  It also presumes you know how to configure MAMP for your new project.

## Setup

1. Download this repo (DON'T CLONE).  You'll be using the download as your new project
2. Place the unzipped folder where you'll be serving your site and rename it appropriately (domain name etc - `trainor.com`, for example)
3. In the folder, copy `.env.example` to a new file `.env`, and update the database and mailtrap credentials
4. Import the database that is in the `_db/` folder.  DELETE this folder once done.
5. Rename the `starter` folder in themes to something related to the project (`trainor`, for example)
6. In the composer.json file, update the `expose` settings to point to the correct theme folder - `themes/YOUR THEME NAME/dist` and `themes/YOUR THEME NAME/images`
7. Update the theme name in the SilverStripe `app/_config/theme.yml` file (line 7)
8. Create a new git repo in this folder

You're now ready to start developing.

## Development

 1. Run `composer install` in the root of the folder
 2. Change in to the theme directory `cd themes/YOUR THEME NAME`
 3. Run `yarn install` to install dependencies

We now have 3 different webpack config files. 
 1. webpack.dev.js - specific for the development environment
 2. webpack.prod.js - specific for production environment 
 3. webpack.common.js contains code that is shared for both webpack.dev and webpack.prod

In webpack.dev.js be sure to set the proxy within the browserSync plugin to whatever your local environment is named.
When this is set, you can run `yarn run dev` to get webpack to start watching.
Using the current setting in the webpack.dev.js file, the browser will automatically refresh once it a file changed. This could be an update to a .scss file, .js file, or a new image could have been added and compressed. You can turn this setting off by adding the following to the webpack.dev.js file:
`{`
   ` // prevent BrowserSync from reloading the page`
    `reload: false`
`}`

Your webpack.dev.js file should look like this:

`new browserSync(`
    `{`
        `host: 'localhost',`
        `port: 3000,`
        `proxy: 'http://starter.test',`
        `notify: false,`
    `}`
`),`
`{`
    ` // prevent BrowserSync from reloading the page`
    `reload: false`
`}`

On the initial `yarn run dev` command it may take a minute to fire up your local browser. This will increase in time relative to the ammount of images you have contained in your images folder. All of these are compiled upon the `yarn dev` command.

Webpack is simply watching for file changes, not running a separate webpack dev server.

Your bundled css and js files will be placed in the `dist` folder in your theme, and are already referenced in the main `Page.ss` template

## Theme Notes

### Images

1. As you need images in your theme, place them in your `theme/YOUR THEME NAME/images` folder
2. New images that are added to the image directory fire the watch command and automatically get compressed.

### SCSS

1. All sass files should be placed in the `src/styles` folder. Everything is run thru the `main.scss` file
2. The `base` folder should contain elements that will be used throughout the site (global styles etc)
3. The `components` folder should contain elements that will be reused throughout the site (form element styles etc)
4. The `sections` folder should contain styles separated by page (home, landing, contact etc)
5. The `_settings.scss` should contain your settings, variables etc (`$main-color: #551A8B`)

### Foundation

1. Install: `yarn add foundation-sites`
2. Copy `node_modules/foundation-sites/scss/settings/_settings.scss` to your `src/styles` folder
3. Add `includePaths: ['node_modules/foundation-sites/scss']` to your `webpack.config.js` file's `sass-loader` options

## JS

 1. Use `yarn add dependency --dev` to add your dependencies.

## SIlverStripe

1. The `mysite` folder is now named `app`
2. The code folder has folders for each type of class you'd develop (model, page, controller etc)

### Silverstripe Admin Icons

Currently there is a bug in the latest version of SilverStripe, so do the following to change the icons in the Admin.
You can target the element that is setting the font-icon, which is typically set using the `content: '';` of a pseudo element. Simply target this element in your css file, located here `app/client/admin/css/cms.css`, and set a new background image. In the example below, we're not using a font-icon, we just override their 'content' and set a background image. From there you may need to add some more styles to get it positioned the way you want it.

Example:
`.font-icon-contactformadmin:before {`   
    `content: '';`  
    `background-color: transparent;`  
    `background-image: url('../../../images/contact-icon.png');`  
    `background-size: contain;`  
    `background-repeat: no-repeat;`  
    `display: block;`  
    `height: 1.25rem;`  
    `margin-left: -.2rem;`  
    `margin-top: .3rem;`  
    `width: 1.5rem;`  
`}`

This css file is also where you can change the admin logo to reflect the client's logo. Here's an example of how we did this for another project.

`.cms-sitename {`  
  `background-image: url('../../../images/logo.png');`  
  `background-size: cover;`  
  `background-repeat: no-repeat;`  
  `background-color: white;`  
  `background-position: center;`  
`}`  
`.collapsed .cms-menu__header .cms-sitename {`  
  `background-image: url('../../../images/logo-sm.png');`  
`}`