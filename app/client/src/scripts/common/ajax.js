import axios from 'axios';

export default axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});
