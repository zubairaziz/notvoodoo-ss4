// Styles
import 'styles/app';

// Polyfills
import objectFitImages from 'object-fit-images';

document.addEventListener('DOMContentLoaded', () => {
    objectFitImages();
});

/*
 * Script loader
 *
 * Imports and runs all scripts in the `./scripts` directory
 * Scripts must have a default export with `can` and `run` functions
 *
 *    export default {
 *      can: () => boolean,
 *      run: function to run if `can` is true
 *    }
 */
document.addEventListener('DOMContentLoaded', () => {
    (r => {
        r.keys()
            .reduce((modules, script) => {
                const module = r(script).default;
                module && modules.push(module);
                return modules;
            }, [])
            .map(
                module => (module.can === true || module.can()) && module.run()
            );
    })(require.context('scripts', true, /^(?!.*(common)).*\.js$/));
});
