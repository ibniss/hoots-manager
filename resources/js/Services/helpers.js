/**
 * Convert a string to sentence case.
 *
 * @param {String} str
 */
export const sentenceCase = str =>
    str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();

/**
 * Get a chunk of the data array for a given page.
 *
 * @param {Array} data
 * @param {Number} pageNumber
 * @param {Number} perPage
 * @returns {{ from: Number, to: Number, forPage: Array}}
 */
export const paginate = (data, pageNumber, perPage) => {
    const start = (pageNumber - 1) * perPage;
    const end = pageNumber * perPage;

    return {
        from: start + 1,
        to: end,
        pages: Math.ceil(data.length / perPage),
        forPage: data.slice(start, end),
    };
};
