import jump from 'jump.js';

const getStuckHeaderHeight = () => {
    if (window.matchMedia('(min-width: 1024px)').matches) {
        return 80;
    }

    return 70;
};

const scrollTo = ($target, additionalOffset = 0, callback) => {
    const headerOffset = getStuckHeaderHeight() * -1;
    const offset = headerOffset - additionalOffset;

    jump($target, { duration: 500, offset, callback });
};

export default scrollTo;
