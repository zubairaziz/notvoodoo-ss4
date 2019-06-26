const Selectors = function(selectors = {}) {
    return Object.assign(
        {
            asId(selectorKey) {
                return `#${this[selectorKey]}`;
            },
            asClass(selectorKey) {
                return `.${this[selectorKey]}`;
            },
            asString(selectorKey) {
                return selectorKey.replace(/^(#|\.)/, '');
            },
        },
        selectors
    );
};

export default Selectors;
