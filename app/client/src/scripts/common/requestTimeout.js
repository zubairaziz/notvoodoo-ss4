const requestTimeout = (fn, delay) => {
    const start = new Date().getTime();
    const handle = new Object();

    const loop = () => {
        const current = new Date().getTime();
        const delta = current - start;

        delta >= delay
            ? fn.call()
            : (handle.value = window.requestAnimationFrame(loop));
    };

    handle.value = window.requestAnimationFrame(loop);

    return handle;
};

export default requestTimeout;
